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

header('Content-type: application/json');

require_once ROOT_PATH . 'includes/load_ad_usernames.php';

var_dump($_REQUEST);
var_dump($_POST);
die();

if (!isset($_REQUEST['username']))
{
    die(ModelResponse::InvalidRequest());
}

$username = trim($_REQUEST['username']);

$matches = array();
preg_match('/[A-Za-z0-9_\.]+/', $username, $matches);

if (sizeof($matches) > 0)
    $username = strtolower($matches[0]);


$exists = (in_array($username, $users));

echo new ModelResponse($exists, $exists ? 'Username exists' : 'User is not registered');