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
     * @property pangalan
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


    public function Save()
    {
        parent::SaveAll([ 'created_on' ]);
    }

}
