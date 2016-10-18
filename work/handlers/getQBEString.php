<?php

/*
 * Copyright (C) 2016 spectral369
 *
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation; either version 2
 * of the License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.
 */
session_start();
//header('Content-Type: application/json; charset=utf-8');
include "../../DB/DB.php";
$conn = DB::connect();

$queryString = "Select ";
$selCols;
foreach (filter_input(INPUT_GET, 'colSelected2', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY) as $col) {
    $selCols .= $col;
    $selCols .= ", ";
}
$search = ',';
$replace = ' ';

$selCols = strrev(implode(strrev($replace), explode(strrev($search), strrev($selCols), 2)));

$queryString .= $selCols;
$queryString .= " FROM ";
$queryString .= filter_input(INPUT_GET, 'db_selected');
$queryString .= '.';
$queryString .= filter_input(INPUT_GET, 'table_selected');
$queryString .= " WHERE ";



//test
$str = filter_input(INPUT_GET, 'querystr');
$tokens = preg_split('/[\s:]+/', $str);



if (preg_match('/:/', $str)) {

    $ad = " ";

    $r = filter_input(INPUT_GET, 'colSelected2', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY);
    $isGood = false;
    for ($i = 0; $i < count($r); $i = $i + 2) {
        $s = $r[$i];
        if (in_array($tokens[$i], $r)) {
            $isGood = true;
        } else {
            $queryString = "-1";
            $isGood = false;
        }
    }

    if ($isGood) {

        for ($h = 0; $h < count($tokens); $h = $h + 2) {
            $ad .= $tokens[$h];
            $ad .= "='";
            $ad .= $tokens[$h + 1];
            $ad .= "' ";
            $ad .= "OR ";
        }
        $ad = strrev(implode(strrev(";"), explode(strrev("OR"), strrev($ad), 2)));
        $queryString .= $ad;
    }
} else {
//test
    $s;
    foreach (filter_input(INPUT_GET, 'colSelected2', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY) as $col) {
        $s .= $col;
        $s .= " LIKE'%";
        $s .= filter_input(INPUT_GET, 'querystr');
        $s .= "%'";
        $s .= " OR ";
    }
    $sr = "OR";
    $rp = ";";
    $s = strrev(implode(strrev($rp), explode(strrev($sr), strrev($s), 2)));
    $queryString .= $s;
}
echo $queryString;
?>
