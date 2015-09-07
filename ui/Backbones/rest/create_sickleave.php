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
 * author_id (int)
 * for_id (int)
 * date (mysql date)
 * span (decimal)
 * reason (string)
 *
 */

header('Content-type: application/json');

$expectations = [
    'author_id',
    'for_id',
    'date',
    'span',
    'reason'
];

if (!\Utilities\Requests::HasRequest($expectations))
{
    die(ModelResponse::InvalidRequest());
}

$raw_data = [];
foreach ($_REQUEST as $key => $value)
{
    if (in_array($key, $expectations))
        $raw_data[$key] = $value;
}

$newSickleave = new \Models\Sickleave();
$newSickleave->Absorb($raw_data);

if (!$newSickleave->SaveAll())
    die(ModelResponse::DataSaveFailed());


// Otherwise, success

die(new ModelResponse(true, "Sick leave entry has been successfully added!", $newSickleave));