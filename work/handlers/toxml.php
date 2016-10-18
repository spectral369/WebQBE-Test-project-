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
header("Content-type: text/xml");
include "../../DB/DB.php";
$conn = DB::connect();
$result = $conn->query(filter_input(INPUT_GET, 'queryString'));
$coun = $result->rowCount();
$col_rows = $result->fetchAll();
$colSel = filter_input(INPUT_GET, 'colSelected2', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY);
$j = 0;
$xmlDom = new DOMDocument("1.0", "utf-8");
$xmlDom->preserveWhiteSpace = false;
$xmlDom->formatOutput = true;
$xmlDom->appendChild($xmlDom->createElement('QBEXMLResult'));
$xmlRoot = $xmlDom->documentElement;
/* other style
  while ( $row = $colSel[$j])
  {
  $xmlRowElementNode = $xmlDom->createElement('field'.$j.'');

  //$i=0;
  for($i=0;$i<$coun;$i++)
  {
  $xmlRowElement = $xmlDom->createElement($row);//id
  $xmlText = $xmlDom->createTextNode($col_rows[$i][$row]); ///id scris
  $xmlRowElement->appendChild($xmlText);

  $xmlRowElementNode->appendChild($xmlRowElement);
  }

  $xmlRoot->appendChild($xmlRowElementNode);
  $j++;
  }
 */
for ($k = 0; $k < $coun; $k++) {
    $xmlRowElementNode = $xmlDom->createElement('field' . $k . '');

    for ($i = 0; $i < count($colSel); $i++) {
        $row = $colSel[$i];
        $xmlRowElement = $xmlDom->createElement($row);
        $xmlText = $xmlDom->createTextNode($col_rows[$k][$colSel[$i]]);
        $xmlRowElement->appendChild($xmlText);
        $xmlRowElementNode->appendChild($xmlRowElement);
    }
    $xmlRoot->appendChild($xmlRowElementNode);
    $j++;
}
ob_clean();
echo $xmlDom->saveXML();
?>