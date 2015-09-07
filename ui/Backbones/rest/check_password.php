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
 * password (string)
 *
 */

header('Content-type: application/json');

require_once ROOT_PATH . 'includes/load_ad_usernames.php';

// Check requests
if (!\Utilities\Requests::HasRequest([ 'username', 'password']))
{
    die(ModelResponse::InvalidRequest());
}

extract($_REQUEST, EXTR_SKIP);

$matches = array();
// Username sanitation
preg_match('/[A-Za-z0-9_\.]+/', $username, $matches);

if (sizeof($matches) > 0)
    $username = $matches[0];
// end of username sanitation


if (!has_account($username))
{
    die(new ModelResponse(false, 'User is not registered'));
}

// Otherwise, proceed with LDAP authentication

$ldapresource = ldap_connect("ldap://svg.openit.local", 389) or die("Unable to connect to ldap://svg.openit.local:389");
//$result = ldap_bind("svg.openit.local");

ob_start();
$bind = ldap_bind($ldapresource, sprintf("%s@svg.openit.local", $username), trim($password));
$buffer = ob_get_clean();

if ($bind == true)
{
    // Get user data
    $user = \Models\User::FindUsername($username);

    die(new ModelResponse(true, 'Authentication success', $user));
}
else
    die(new ModelResponse(false, 'Incorrect password'));