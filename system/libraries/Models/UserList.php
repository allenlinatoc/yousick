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
 * Description of UserList
 *
 * @author alinatoc
 */
class UserList extends \ModelCollection
{

    public function __construct($fetch = true)
    {
        parent::__construct('user', $fetch);
    }

    public function Create()
    {
        foreach ($this as $user)
        {
            if ($user instanceof User && !$user->Exists())
            {
                $user->SaveAll();
            }
        }
    }

}
