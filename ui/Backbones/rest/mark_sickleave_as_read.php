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
 * id (int) -- ID of sickleave to be marked as read
 *
 */

header('Content-type: application/json');

// Check request

if (!\Utilities\Requests::HasRequest([ 'id']))
{
    die(ModelResponse::InvalidRequest());
}

// Check session

if (!\Utilities\Session::IsLoggedIn())
{
    die(ModelResponse::NoSession());
}

// Check if admin
$sessData = \Utilities\Session::Initialize();

if ($sessData instanceof Models\SessionData)
{
    if (!$sessData->getUser()->isAdmin())
        die(new ModelResponse(false, 'Sorry, but you are not authorized to do so'));
}

// Otherwise proceed

$sickLeave = Models\Sickleave::Find($_REQUEST['id'], 'sickleave');

if ($sickLeave instanceof Models\Sickleave)
    ; // intellisense only

if ($sickLeave->notifstatus == 'UNREAD' || (is_string($sickLeave->read_on) && strlen($sickLeave->read_on) == 0))
{
    // This is not yet read
    $db = \DB::Instance();
    $pdo = $db->pdo;

    if ($pdo instanceof PDO)
        ; // intellisense

    $stmt = $pdo->prepare('CALL mark_sickleave_as_read(:sickleave_id, :read_by)');
    $stmt->bindParam(':sickleave_id', $sickLeave->id);
    $stmt->bindParam(':read_by', $sessData->getUser()->id);

    if ($stmt->execute())
    {
        $sickLeave = Models\Sickleave::Find($sickLeave->GetRecordID(), 'sickleave');


        // Notify via email
        $isNotificationEnabled = Utilities\Config::get('email_notification', CONFIG_PATH . 'application.ini');
        if ($isNotificationEnabled == 1)
        {
            try
            {
                Utilities\Email::NotifyPeopleFromSickleave($sickLeave);
            }
            catch (phpmailerException $ex)
            {
                $sickLeave->SetState(new ModelResponse(false, 'Failure on notifying people involved in this sick-leave: ' . $ex->getMessage()));
            }
        }

        die(new ModelResponse(true, 'Sick leave has been successfully marked as READ', $sickLeave));
    }
    else
    {
        die(new ModelResponse(false, 'Failed to mark sickleave as read'));
    }
}

die(new ModelResponse(true, 'Sick leave is already marked as READ', $sickLeave));
