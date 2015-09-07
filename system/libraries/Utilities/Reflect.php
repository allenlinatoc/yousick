<?php

/*
 * Copyright (C) 2015 Allen
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

namespace Utilities;

/**
 * Description of Reflect
 *
 * @author Allen
 */
class Reflect
{

    /**
     * Get the properties in an object
     *
     * @param object $object
     * @param boolean $public_only
     *
     * @return array
     */
    static public function GetProperties($object, $public_only = true)
    {
        $result = array();

        foreach ($object as $prop => $value)
        {
            if ($public_only && !self::IsPropertyPublic($object, $prop))
                continue;

            $result[$prop] = $value;
        }

        return $result;
    }

    /**
     * Check if a property in an object is public
     *
     * @param object $object
     * @param string $propertyName
     *
     * @return boolean
     */
    static public function IsPropertyPublic($object, $propertyName)
    {

        $reflectionClass = new ReflectionClass($object);
        $properties = $reflectionClass->getProperties();

        foreach ($properties as $property)
        {
            if (strcmp($property->getName(), $propertyName) != 0)
                continue;

            if ($property instanceof \ReflectionProperty)
                return ($property->isPublic());
        }
    }

}
