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
 *
 *
 * [REST_META]
 *
 * username (string)
 * id (int)
 *
 */

header('Content-type: application/json');

if (!isset($_REQUEST['username']) && !isset($_REQUEST['id']))
{
    die(ModelResponse::InvalidRequest());
}

if (isset($_REQUEST['username']))
{
    $username = trim(strtolower($_REQUEST['username']));
    $sickleaveStat = new \Models\Counters\SickleaveStat($username);
}
else
{
    $id = intval($_REQUEST['id']);
    $sickleaveStat = new \Models\Counters\SickleaveStat($id);
}


$response = new ModelResponse(true, 'Success', $sickleaveStat);
die($response);