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
include "../../DB/DB.php";
header('Content-Type: application/json; charset=utf-8');
$conn = DB::connect();
$queryString = "";
$queryString .= filter_input(INPUT_GET, 'db_selected');
$queryString .= '.';
$queryString .= filter_input(INPUT_GET, 'val2');
$t = $conn->query('SHOW COLUMNS FROM ' . $queryString . '');
$arr = array();
$i = 0;
foreach ($t as $db => $tr) {
    $arr[$i] = $tr;
    $i++;
}
echo json_encode($arr);
?>