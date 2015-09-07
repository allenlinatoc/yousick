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
 * Description of Sickleave
 *
 * @author alinatoc
 */
class Sickleave extends \Model
{

    public
            $author_id,
            $for_id,
            $date,
            $span,
            $reason,
            $remarks,
            $notifstatus,
            $read_by,
            $read_on,
            $created_on
    ;

    public function __construct()
    {

        parent::__construct('sickleave');
        $this->SetPropertyMap(new \ComplexPropertyMap([
            'author' => 'User',
            'for' => 'User',
            'read_by' => 'User'
        ]));

    }


    /**
     * @property author_id
     * @return \Models\User
     */
    public function getAuthor() { return $this->author_id; }

    /**
     * @property for_id
     * @return \Models\User
     */
    public function getFor() { return $this->for_id; }

    /**
     * @property date
     */
    public function getDate() { return $this->date; }

    /**
     * @property span
     * @return decimal
     */
    public function getSpan() { return $this->span; }

    /**
     * @property reason
     * @return string
     */
    public function getReason() { return $this->reason; }

    /**
     * @property remarks
     * @return string
     */
    public function getRemarks() { return $this->remarks; }

    /**
     * @property notifstatus
     * @return string
     */
    public function getNotifstatus() { return $this->notifstatus; }

    /**
     * @property read_by
     * @return \Models\User
     */
    public function getReadBy() { return $this->read_by; }

    /**
     * @property read_on
     * @return string
     */
    public function getReadOn() { return $this->read_on; }

    /**
     * @property created_on
     * @return string
     */
    public function getCreatedOn() { return $this->created_on; }

    public function Save()
    {
        parent::SaveAll();
    }

}
