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

namespace Models;


/**
 * Model class for User
 *
 * @author alinatoc
 */
class User extends \Model
{

    public
            $username,
            $name,
            $department,
            $email,
            $created_on
    ;


    public function __construct()
    {
        parent::__construct('users');
    }


    /**
     * @property username
     */
    public function getUsername() { return $this->username; }

    /**
     * @property name
     */
    public function getName() { return $this->name; }

    /**
     * @property department
     */
    public function getDepartment() { return $this->department; }

    /**
     * @property email
     */
    public function getEmail() { return $this->email; }

    /**
     * @property created_on
     */
    public function getCreatedOn() { return $this->created_on; }

    /**
     * @property is_admin
     */
    public function isAdmin()
    {
        if ($this->getUsername() !== null)
        {
            require_once ROOT_PATH . '/includes/load_admin_usernames.php';
            return is_admin($this->getUsername());
        }
        else
        {
            return null;
        }
    }


    public function Save()
    {
        parent::SaveAll([ 'is_admin' ]);
    }

    public function SaveAll(array $excludeFields = null)
    {
        return parent::SaveAll([ 'is_admin' ]);
    }


    /**
     *
     * @param string $username
     * @return User
     */
    static public function FindUsername($username)
    {
        $db = \DB::Instance();

        $rows = $db->select('user', [ 'id' ], [
            'username' => strtolower($username)
        ]);

        if (sizeof($rows) == 0)
            return false;

        $rows = $rows[0];

        return User::Find($rows['id'], 'user');
    }

}
