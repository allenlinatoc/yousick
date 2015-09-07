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

namespace Utilities;

require_once ROOT_PATH . '/includes/phpmailer/PHPMailerAutoload.php';

/**
 * Description of Email
 *
 * @author alinatoc
 */
class Email
{

    /**
     *
     */
    static public function Instance()
    {
        $phpMailer = new \PHPMailer();
        $phpMailer->SMTPAuth = false;
        $phpMailer->SMTPDebug = 0;
        $phpMailer->Port = 25;
        $phpMailer->Host = "mail.openit.com";
        $phpMailer->From = 'system@openit.com';
        $phpMailer->FromName = 'OpeniT YouSick system';
        $phpMailer->isHTML(true);

        return $phpMailer;
    }


    static public function NotifyAdmins(\Models\Sickleave $sickleave)
    {
        $mail = self::Instance();

        // Get admins
        require_once ROOT_PATH . 'includes/load_admin_usernames.php';

        $admins = get_admins();

        // Initialize user data of people behind the sickleave entry
        $userAuthor = $sickleave->getAuthor();
        $userFor = $sickleave->getFor();

        foreach ($admins as $admin)
        {
            $user = \Models\User::FindUsername($admin);
            if ($user instanceof \Models\User)
            {
                $emailAddress = $user->getEmail();

                // Send email to this person
                $mail->addAddress($emailAddress, $user->getName());

                $mail->Subject = sprintf('New Sick-leave for %s', $userFor->getName());
                $mail->Body =
                        sprintf('<b>%s has filed a sick-leave ');
            }
        }
    }

}
