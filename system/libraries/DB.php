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
 * Static utility class containing the Medoo DB instance
 *
 * @author alinatoc
 */
class DB
{

    /**
     * @var array This contains the DB medoo options
     */
    static public $options = [
	// required
	'database_type' => 'mysql',
	'database_name' => 'yousickdb',
	'server' => '127.0.0.1:3306',
	'username' => 'yousick',
	'password' => 'yousick',
	'charset' => 'utf8',

	// optional
	'port' => 3310,
	// driver_option for connection, read more from http://www.php.net/manual/en/pdo.setattribute.php
	'option' => [
		PDO::ATTR_CASE => PDO::CASE_NATURAL
	]
    ];


    /**
     * Generate new instance of DB medoo
     *
     * @return medoo The medoo instance
     */
    static public function Instance()
    {
        $medoo = new medoo(self::$options);
        return $medoo;
    }

}
