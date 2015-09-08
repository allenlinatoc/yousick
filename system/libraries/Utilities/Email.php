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
                            . 'To view this sick-leave, click the URL below: <br>'
                            . '<a href="{{url}}">{{url}}</a><br>'
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
                                'url' => BASE_URL . 'admin/view-sickleave?id=' . $sickleave->GetRecordID()
                            ]);

                // Send mail

                $mail->send();
            }
        }
    }


    static public function NotifyPeopleFromSickleave(\Models\Sickleave $sickleave)
    {
        $mail = self::Instance();

        $userAuthor = $sickleave->getAuthor();
        $userFor = $sickleave->getFor();

        $recipients = [ $userAuthor->getEmail() ];

        if ($userAuthor->id != $userFor->id )
            array_push($recipients, $userFor->getEmail());

        foreach ($recipients as $recipient)
        {
            $mail->clearAllRecipients();

            $user = $recipient == $userAuthor->getEmail() ? $userAuthor : $userFor;

            if ($user instanceof \Models\User); // intellisense

            $mail->addAddress($recipient, $user->getName());
            $mail->Subject = sprintf('[READ] Sick-leave for %s has been read', $sickleave->getFor()->getName());
            $mail->Body =
                    String::Format(
                            '<font face="arial" size="8px">'
                            . 'Hello {{name}},<br>'
                            . '<br>'
                            . '<br>'
                            . '<h2>Sick-leave for {{for_name}} has been read!</h2>'
                            . '<br>'
                            . '<b>For: </b>{{for_name}}<br>'
                            . '<b>Author: </b>{{author_name}}<br>'
                            . '<b>Target date: </b>{{date}}<br>'
                            . '<b>Span: </b>{{span}} day/s<br>'
                            . '<br>'
                            . '<h2>Reason</h2>'
                            . '<h3><i>"{{reason}}"</i></h3><br>'
                            . 'To view this sick-leave, click the URL below: <br>'
                            . '<a href="{{url}}">{{url}}</a><br>'
                            . '<br>'
                            . 'Thank you!<br>'
                            . '<i>OpeniT YouSick system</i>'
                            . '</font>',
                            [
                                'name' => $user->getName(),
                                'for_name' => $sickleave->getFor()->getName(),
                                'author_name' => $sickleave->getAuthor()->getName(),
                                'date' => $sickleave->getDate(),
                                'span' => $sickleave->getSpan(),
                                'reason' => $sickleave->getReason(),
                                'url' => BASE_URL . 'user/view-sickleave?id=' . $sickleave->GetRecordID()
                            ]);

            $mail->send();
        }
    }

}
?>