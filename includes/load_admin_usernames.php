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

function get_admins()
{
    $user_file_contents = file_get_contents(CONFIG_PATH . 'users');
    $users = array_map('rtrim', explode("\n", $user_file_contents));

    $keys = array_keys($users);
}

function is_admin($username)
{
    $user_file_contents = file_get_contents(CONFIG_PATH . 'users');
    $users = array_map('rtrim', explode("\n", $user_file_contents));

    $keys = array_keys($users);

    foreach ($keys as $key)
    {
        $uname = $users[$key];

        // Bypass empty
        if (strlen($uname) == 0)
            continue;

        if ($uname{strlen($uname) - 1} != '*')
            continue;

        if (strcasecmp(rtrim($uname, '*'), $username) === 0)
            return true;
    }

    return false;
}