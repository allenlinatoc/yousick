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

namespace Models;

/**
 * Description of SickleaveList
 *
 * @author alinatoc
 */
class SickleaveList extends \ModelCollection
{

    public function __construct($fetch = true)
    {
        parent::__construct('sickleave', $fetch);
    }

    static public function CreateFromUsername($username)
    {
        $pdo = \DB::Instance()->pdo;
        if ($pdo instanceof \PDO)
        {
            $stmt = $pdo->prepare("SELECT sickleave.* FROM sickleave JOIN user ON sickleave.for_id = user.id AND user.username = :username ORDER BY sickleave.date DESC");

            $stmt->execute([
                ':username' => $username
            ]);

            $rows = $stmt->fetchAll();

            $result = new SickleaveList(false);

            foreach ($rows as $row)
            {
                $sickleave = new Sickleave();
                $sickleave->Absorb($row);
                $result->add($sickleave);
            }

            return $result;
        }

        return false;
    }

}
