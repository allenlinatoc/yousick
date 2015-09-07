<?php

class MethodAnnotation
{

    private $method;
    private $name;
    private $value;

    public function __construct($method, $name)
    {
        $this->method = $method;
        $this->name   = $name;
    }

    public function getMethod()
    {
        return $this->method;
    }

    public function getValue()
    {
        return $this->value;
    }

    public function exists()
    {
        $doc = $this->method->getDocComment();
        preg_match_all('#@' . $this->name . '(.*?)\n#s', $doc, $bind);
        if ($bind[1])
        {
            $this->value = trim($bind[1][0]);
        }
        return $bind[1];
    }

}

?>