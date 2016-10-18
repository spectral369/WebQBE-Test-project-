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
header('Content-Type: application/json; charset=utf-8');
include "../../DB/DB.php";
$conn = DB::connect();
$t1 = $conn->query(filter_input(INPUT_GET, 'qs'));
$arr1 = array();
$i = 0;

foreach ($t1 as $db => $tr) {
    $arr1[$i] = $tr;
    $i++;
}
echo json_encode($arr1);
?>
