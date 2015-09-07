<?php

class Model
{

    static public $TABLE = null;

    protected $state = null;
    protected $authorizedSession;
    protected $propertyMap = null;
    public $id = null;


    public function __construct($tablename)
    {
        self::$TABLE = $tablename;
    }

    public function SetRecordID($id)
    {
        $this->id = $id;
    }

    /**
     * @property ID
     */
    public function GetRecordID()
    {
        return $this->id;
    }

    public function __toString()
    {
        return $this->Serialize();
    }

    protected function toArray()
    {
        $arr = array();
        $classReflection = new ReflectionClass($this);
        $methods = $classReflection->getMethods();
        foreach ($methods as $m)
        {
            $doc = $m->getDocComment();
            preg_match_all('#@property(.*?)\n#s', $doc, $prop);
            preg_match_all('#@complex(.*?)\n#s', $doc, $complex);
            if ($prop[1])
            {
                $method = $m->getName();
                $val    = $this->$method();

                if (is_array($val))
                {
                    if (!\Utilities\InputUtils::isEmpty($val))
                    {
                        foreach ($val as $v)
                        {
                            if ($v instanceof Model)
                            {
                                $arr[trim($prop[1][0])][] = $v->toArray();
                            }
                            else
                            {
                                $arr[trim($prop[1][0])][] = $v;
                            }
                        }
                    }
                }
                else
                {
                    if ($val instanceof Model)
                    {
                        $arr[trim($prop[1][0])] = (!\Utilities\InputUtils::isEmpty($complex[1]) ? $val->toArray() : $val->__toString());
                    }
                    else
                    {
                        if (isset($val))
                        {
                            $arr[trim($prop[1][0])] = $val;
                        }
                    }
                }
            }
        }
        return $arr;
    }

    public function Absorb(array $array)
    {
        foreach ($array as $key => $value)
        {
            $this->{$key} = $value;
        }
    }


    public function Delete()
    {
        if (!$this->Exists())
            return;

        $db = \DB::Instance();

        $db->delete($this->GetModelName(), [
            'id' => $this->id
        ]);
    }

    public function Exists()
    {
        return $this->GetRecordID() != null;
    }


    public function GetAuthorizedSession()
    {
        return $this->authorizedSession;
    }


    /**
     * Get the name of this model
     *
     * @return string
     */
    public function GetModelName()
    {
        $matches = array();
        preg_match('/[A-Za-z0-9_]+$/i', get_class($this), $matches);
        return strtolower($matches[0]);
    }


    /**
     * Get the assoc-array of Properties and its corresponding values
     *
     * @param array $excludeFields (Optional) Array of fields/columns to be excluded
     *
     * @return array
     */
    public function GetPropertyValues(array $excludeFields = null)
    {
        $output = json_decode($this->Serialize(), true);

        if (!is_array($excludeFields))
        {
            $excludeFields = [ 'has_state' ];
        }

        if (is_array($excludeFields) && sizeof($excludeFields) > 0)
        {
            $keys = array_keys($output);
            foreach ($keys as $key)
            {

                foreach ($excludeFields as $field)
                {
                    if (strcasecmp($key, $field) == 0)
                        unset($output[$key]);
                }
            }
        }

        return $output;
    }

    public function GetDBReadyValues(array $excludeFields = null)
    {
        $propertyValues = $this->GetPropertyValues($excludeFields);
        $propMap = $this->GetPropertyMap();

        foreach ($propertyValues as $prop => $value)
        {
            if ($propMap !== null && $propMap->IsMapped($prop))
            {
                $reflection = new ReflectionObject($value);
                if ($reflection->isSubclassOf('\Model'))
                {
                    $propertyValues[$prop] = $value->GetRecordID();
                    continue;
                }
            }

            $propertyValues[$prop] = $value;
        }

        return $propertyValues;
    }

    /**
     * Get the ComplexPropertyMap of this model
     *
     * @return \ComplexPropertyMap
     */
    public function GetPropertyMap()
    {
        return $this->propertyMap;
    }

    /**
     * @property state
     * @complex
     * @return ModelResponse
     */
    public function GetState()
    {
        return $this->state;
    }

    /**
     * Check if this has property map
     *
     * @return boolean
     */
    public function HasPropertyMap()
    {
        return $this->propertyMap != null;
    }


    /**
     * Check if this has state/ModelResponse inside
     *
     * @return boolean
     * @property has_state
     */
    public function HasState()
    {
        // JSONization doesn't apply to ModelResponse objects

        $reflection = new ReflectionObject($this);
        if (strtolower(trim($reflection->getName(), '\\')) == 'modelresponse')
            return null;

        return $this->GetState() !== null;
    }


    /**
     * Save (auto-detec, e.g. insert or update) data in this model. Returns boolean if operation is successful.
     *
     * @param array $excludeFields (Optional) An array of model fields to be excluded from saving
     *
     * @return boolean
     */
    public function SaveAll(array $excludeFields = null)
    {
        $db = \DB::Instance();
        $db->pdo->beginTransaction();

        $properties = $this->GetDBReadyValues($excludeFields);

        // Check if this exists
        if ($this->Exists())
        {
            $db->update($this->GetModelName(), $properties, [
                'id' => $this->id
            ]);
        }
        else
        {

            // Create data
            $insertResult = $db->insert($this->GetModelName(), [
                $properties
            ]);

            // Get the ID
            $this->id = $db->pdo->lastInsertId();

            if ($this->id == 0)
                $this->SetState(ModelResponse::DataSaveFailed());

        }

        $errorInfo = $db->pdo->errorInfo();
        $errorCode = intval($errorInfo[0]);
        if ($errorCode != 0)
        {
            $this->SetState(new ModelResponse(false, sprintf('[%s] %s', $errorCode, $errorInfo[1])));
        }

        // Fetch-back
        $data = $db->select($this->GetModelName(), '*', [
            'id' => $this->id
        ]);

        // If has result, absorb it
        if (sizeof($data) > 0)
            $this->Absorb($data[0]);

        // Commit transaction
        $db->pdo->commit();

        return true;
    }

    public function Serialize()
    {
        return json_encode($this->toArray());
    }

    public function SetPropertyMap(ComplexPropertyMap $propertyMap)
    {
        $this->propertyMap = $propertyMap;
    }

    public function SetState(ModelResponse $state)
    {
        $this->state = $state;
    }

    public function RequestBind(array $vars, $tag = '')
    {
        $r       = new ReflectionClass($this);
        $setters = array();
        $methods = $r->getMethods();
        //get all setters with databind
        foreach ($methods as $m)
        {
            $x    = strpos($m->getName(), "set") + 1;
            $bind = new MethodAnnotation($m, "requestBind");
            if ($x == 1 && $bind->exists())
            {
                //get setter parameter type;
                $param      = new ReflectionParameter(array(get_class($this), $m->getName()), 0);
                $type       = $param->getClass();
                $methodName = $m->getName();
                $val        = null;
                if (array_key_exists($tag . $bind->getValue(), $vars))
                {
                    $val = $vars[$tag . $bind->getValue()];
                }
                if ($type)
                {
                    $typeName = $type->getName();
                    if ($type->isSubclassOf("Model"))
                    {
                        $val = new $typeName();
                        $val->RequestBind($vars, $r->getName());
                    }
                    else
                    {
                        if ($type->isSubclassOf("tlc\datatypes\DataType"))
                        {
                            try
                            {
                                $val = new $typeName($val, true);
                            }
                            catch (Exception $e)
                            {
                                throw new Exception($e->getMessage() . '. Field ' . $bind->getValue());
                            }
                        }
                    }
                }
                $this->$methodName($val);
            }
        }
    }





    /**
     * Create an instance of this Model from an ID (optionally, also table name). Returns NULL if none found
     *
     * @param int $id
     * @param string $tableName The name of table to derive from
     *
     * @return mixed
     */
    static public function Find($id, $tableName)
    {
        $db = \DB::Instance();

        $rows = $db->select($tableName, '*', [
            'id' => $id
        ]);

        $modelClass = sprintf('\Models\%s', ucfirst(strtolower($tableName)));

        $model = new $modelClass();

        if ($rows === false)
        {
            $model->SetState(ModelResponse::NoData());
        }
        else
        {
            if (sizeof($rows) == 0)
            {
                $model->SetState(ModelResponse::NoData());
                return $model;
            }
            else
            {
                $rows = $rows[0];
            }

            $keys = array_keys($rows);

            foreach ($keys as $key)
            {
                $model->{strtolower($key)} = $rows[strtolower($key)];
            }
        }

        return $model;
    }

}
?>