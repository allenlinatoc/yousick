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
 * Description of SessionData
 *
 * @author alinatoc
 */
class SessionData extends \Model
{

    public $user;
    public $session_id;

    public function __construct($username)
    {
        $this->user = $username != null ? User::FindUsername($username) : null;
        $this->sessionId = session_id();
    }

    /**
     * @property session_id
     */
    public function getSessionID() { return $this->session_id; }

    /**
     * @property user
     * @complex
     * @return User
     */
    public function getUser() { return $this->user; }

}
