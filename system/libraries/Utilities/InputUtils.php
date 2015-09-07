<?php

namespace Utilities;

class InputUtils
{

    public static function isEmpty($var)
    {
        if (is_array($var))
        {
            return empty($var);
        }
        return !isset($var) || trim($var) == false;
    }

}

?>