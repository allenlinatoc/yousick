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
 * [REST_META]
 *
 * username (string)
 *
 */

require_once ROOT_PATH . 'includes/load_admin_usernames.php';

$expectations = [ 'username' ];

if (!\Utilities\Requests::HasRequest($expectations))
{
    die(ModelResponse::InvalidRequest());
}

$is_admin = is_admin($_REQUEST['username']);
$user = null;

if ($is_admin)
{
    $user = \Models\User::FindUsername($_REQUEST['username']);
    die(new ModelResponse(true, 'Yes, an admin', $user));
}
else
{
    die(new ModelResponse(false, sprintf('User \"%s\" is not an admin', $_REQUEST['username'])));
}