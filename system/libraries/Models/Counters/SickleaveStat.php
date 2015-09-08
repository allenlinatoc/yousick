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

namespace Models\Counters;

/**
 * Description of SickleaveStat
 *
 * @author alinatoc
 */
class SickleaveStat extends \Model
{

    public
            $used,
            $username;


    public function __construct($username_or_id)
    {
        parent::__construct('Counters\SickleaveStat');

        if (is_int($username_or_id))
            $this->username = \Models\User::Find($username_or_id, 'user')->getUsername();
        else
            $this->username = $username_or_id;

        // Count
        $pdo = \DB::Instance()->pdo;
        if ($pdo instanceof \PDO)
        {
            $stmt = $pdo->prepare("SELECT count "
                    . "FROM MonthlyIndividual "
                    . "WHERE username = :username AND `year` = year(localtime());");

            $stmt->bindParam(':username', $this->username);
            $stmt->execute();
            $rows = $stmt->fetchAll();


            if (sizeof($rows) == 0)
            {
                $this->used = 0;
            }
            else
            {
                $row = $rows[0];
                $this->used = $row['count'];
            }
        }
    }


    /**
     * @property used
     */
    public function getUsed() { return $this->used; }

    /**
     * @property username
     */
    public function getUsername() { return $this->username; }

    /**
     * @property remaining
     */
    public function getRemaining()
    {
        $remaining = 15 - $this->used;
        return $remaining > 0 ? $remaining : 0;
    }

}
