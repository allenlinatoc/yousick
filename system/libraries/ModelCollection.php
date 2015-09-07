<?php

/*
 * Copyright (C) 2015 alinatoc
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

/**
 * Description of ModelsCollection
 *
 * @author alinatoc
 */
abstract class ModelCollection
        extends Model
        implements Iterator
{

    private $modelName;

    private $position = 0;
    public $list = [];

    public function __construct($modelName, $fetch = true)
    {
        if ($modelName == null)
            throw new Exception(sprintf('Argument \"%s\" is null', $modelName));

        $this->modelName = $modelName;

        if ($fetch)
        {
            // Query and fetch
            $db = \DB::Instance();
            $data = $db->select($this->modelName, '*');

            foreach ($data as $row)
            {
                $keys_in_row = array_keys($row);

                $modelClass = sprintf("\Models\%s", $this->modelName);

                $model = new $modelClass();

                foreach ($keys_in_row as $key)
                {
                    $key = strtolower($key);
                    $model->{$key} = $row[$key];
                }

                $this->add($model);
            }
        }

        reset($this->list);
    }

    /**
     * @property collection
     * @complex
     */
    public function GetData()
    {
        return $this->list;
    }

    /**
     * @property modelname
     */
    public function GetModelName()
    {
        return $this->modelName;
    }

    function add($item)
    {
        if ($item instanceof Model)
            $this->list[] = $item;
        else
            throw new Exception("<b>Not a Model object</b><br>" + var_dump($item));
    }

    function rewind()
    {
        reset($this->list);
    }

    function current()
    {
        return current($this->list);
    }

    function key()
    {
        return key($this->list);
    }

    function runs()
    {
        return next($this->list);
    }

    function next()
    {
        return next($this->list);
    }

    function previous()
    {
        return prev($this->list);
    }

    function remove(int $id)
    {
        $keys = array_keys($this->list);

        foreach ($keys as $key)
        {
            if ($this->list[$key]->id == $id)
            {
                $currentKey = $this->key();
                $this->next();
                unset($this->list[$currentKey]);
            }
        }
    }

    function removeAt(int $index)
    {
        if (isset($this->list[$index]))
        {
            unset($this->list[$index]);
        }
    }

    function valid()
    {
        return $this->key() !== null;
    }

    public function __toString()
    {
        return $this->Serialize();
    }

}
