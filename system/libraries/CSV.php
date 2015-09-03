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

/**
 * CSV parser for this app
 *
 * @author alinatoc
 */
class CSV
{

    const COMMA_ESCAPE_STRING = '{{comma}}';


    /**
     * Parse a CSV file
     *
     * @param string    $path   The path to CSV file
     * @param array     $expectedFields An array of expected fields, case-insensitive
     *
     * @throws \Exceptions\FileNotFoundException
     */
    static public function Parse($path)
    {
        // Check if path exists
        if (!file_exists($path))
            throw new \Exceptions\FileNotFoundException($path);

        // Note, COMMAS are escaped: \,
        $contents = self::EscapeCSV(file_get_contents($path));
        $result = [];

        $csvArray = explode(PHP_EOL, $contents);

        $result_header = explode(',', $csvArray[0]);

        for ($index = 1; $index < sizeof($csvArray); $index++)
        {
            $split_row = explode(",", $csvArray[$index]);
            $row = [];

            $indexSplit_row = 0;
            foreach ($result_header as $header)
            {
                $row[$header] = $split_row[$indexSplit_row];

                $row[$header] = str_replace(self::COMMA_ESCAPE_STRING, ",", $row[$header]);

                // Remove surrounding Quote
                if ($row[$header]{0} == '"' && $row[$header]{strlen($row[$header]) - 1} == '"')
                    $row[$header] = trim(trim($row[$header], '"'), "'");

                $indexSplit_row++;
            }

            array_push($result, $row);
        }

        return $result;

    }


    /**
     * Escape a CSV content with commas
     *
     * @param string    $contents The CSV contents to be escaped
     *
     * @return string
     */
    static public function EscapeCSV($contents)
    {
        $result = $contents;

        $quotes = [ '"', "'" ];

        $is_quoted = false;

        for ($index = 0; $index < strlen($result); $index++)
        {
            if (array_search($result{$index}, $quotes) !== false)
            {
                $is_quoted = !$is_quoted;
                continue;
            }

            if ($is_quoted && $result{$index} == ',')
            {
                $result = substr_replace ($result, self::COMMA_ESCAPE_STRING, $index, 1);
                $index+=2;
            }
        }

        return $result;
    }


//    /**
//     * Unescape a CSV content with escaped commas inside quoted contents
//     *
//     * @param string    $contents The CSV contents to be unescaped
//     *
//     * @return string
//     */
//    static public function UnescapeCSV($contents)
//    {
//        $result = $contents;
//
//        $quotes = [ '"', "'" ];
//
//        $is_quoted = false;
//
//        for ($index = 0; $index < strlen($result); $index++)
//        {
//            if (array_search($result{$index}, $quotes) !== false)
//            {
//                $is_quoted = !$is_quoted;
//                continue;
//            }
//
//            if ($is_quoted && $result{$index} == "," && $index > 0 && $result{$index - 1} == '\\')
//            {
//                $result = substr_replace ($result, "", $index - 1, 1);
//            }
//        }
//
//        return $result;
//    }


    static public function UnescapeCSV($contents)
    {
        return str_replace(self::COMMA_ESCAPE_STRING, ",", $contents);
    }


}
