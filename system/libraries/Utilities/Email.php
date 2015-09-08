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
            $adminUser = \Models\User::FindUsername($admin);
            if ($adminUser instanceof \Models\User)
            {
                $emailAddress = $adminUser->getEmail();

                $mail->clearAllRecipients();

                // Send email to this person
                $mail->addAddress($emailAddress, $adminUser->getName());

                $mail->Subject = sprintf('New Sick-leave for %s', $userFor->getName());
                $mail->Body =
                    String::Format(
                            '<font face="arial" size="8px">'
                            . 'Hello {{admin_name}},<br>'
                            . '<br>'
                            . '<br>'
                            . '<h2>A sick-leave has been filed for {{for_name}}</h2>'
                            . '<br>'
                            . '<b>For: </b>{{for_name}}<br>'
                            . '<b>Author: </b>{{author_name}}<br>'
                            . '<b>Target date: </b>{{date}}<br>'
                            . '<b>Span: </b>{{span}} day/s<br>'
                            . '<br>'
                            . '<h2>Reason</h2>'
                            . '<h3><i>"{{reason}}"</i></h3><br>'
                            . 'To read this sick-leave, click the URL below: <br>'
                            . '<a href="">{{url}}</a><br>'
                            . '<br>'
                            . 'Thank you!<br>'
                            . '<i>OpeniT YouSick system</i>'
                            . '</font>',
                            [
                                'admin_name' => $adminUser->getName(),
                                'for_name' => $userFor->getName(),
                                'author_name' => $userAuthor->getName(),
                                'date' => $sickleave->getDate(),
                                'span' => $sickleave->getSpan(),
                                'reason' => $sickleave->getReason(),
                                'url' => BASE_URL . 'sickleave?id=' . $sickleave->GetRecordID()
                            ]);

                // Send mail

                $mail->send();
            }
        }
    }

}
?>