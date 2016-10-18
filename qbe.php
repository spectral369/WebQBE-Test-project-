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
if (filter_input(INPUT_POST, 'servername') !== NULL AND filter_input(INPUT_POST, 'username') !== NULL AND
        filter_input(INPUT_POST, 'password') !== NULL AND filter_input(INPUT_POST, 'serverport') !== NULL) {
    $servern = filter_input(INPUT_POST, 'servername');
    $username = filter_input(INPUT_POST, 'username');
    $password = filter_input(INPUT_POST, 'password');
    $serverp = filter_input(INPUT_POST, 'serverport');
}


$cookie_name = "serverna";
$cookie_port = "port";
$cookie_user = "user";
if (filter_input(INPUT_COOKIE, $cookie_name) === NULL OR filter_input(INPUT_COOKIE, $cookie_name) != $servern) {
    setcookie($cookie_name, $servern, time() + (86400 * 5), "/");
}
if (filter_input(INPUT_COOKIE, $cookie_port) === NULL OR filter_input(INPUT_COOKIE, $cookie_port) != $serverp) {
    setcookie($cookie_port, $serverp, time() + (86400 * 5), "/");
}
if (filter_input(INPUT_COOKIE, $cookie_user) === NULL OR filter_input(INPUT_COOKIE, $cookie_user) != $username) {
    setcookie($cookie_user, $username, time() + (86400 * 5), "/");
}
include("DB/DB.php");

DB::setUP($username, $password, $servern, $serverp);
$conn = DB::connect();

$dbs = $conn->query('show databases;');
?>
<!DOCTYPE html> 
<html>
    <head>
        <script src="js/jquery-1.12.4.min.js"></script>
        <link rel="stylesheet" href="css/jquery-ui.css">
        <script src="js/jquery-ui.min.js"></script>
        <script src="js/pdfmake.min.js"></script>
        <script src="js/vfs_fonts.js"></script>
        <script type="text/javascript" src="js/customJS.js"></script>
        <link rel="stylesheet" type="text/css" href="css/style.css" media="screen" />	
        <title>QBE Web</title>
    </head>
    <body>
        <div id="work">
            <!-- wating for response-->
            <div id="loading" style="display:none;">
                Loading Please Wait....
                <img src="img/loader.gif" alt="Loading" />
            </div>
            <div id="dbs1">
                <form id="bazele">
                    <select id="dbs" name="users" >
                        <!--aici era un </div> -->

                        <option value="remove" selected disabled>Chose a Database</option>
                        <?php
                        foreach ($dbs as $db => $t) {
                            echo "<option value='$t[0]'>$t[0]</option>";
                        }
                        ?>
                    </select>
                    <input type="text" class="querytxt" id="queryString">
                    <input type="button" id="query" value="Query" onclick="both();" disabled>
                    <input type="button" id="toxml" value="Export to XML" disabled>
                    <input type="button" id="topdf" value="Export to PDF" disabled>
                </form>
                <div id="dialog-message" title="Select PDF options"  hidden>

                    <div style="margin-left: 23px;">
                        <p> Please Select page size:
                            <select id="pdf-page-size">
                                <option value="A3">A3</option>
                                <option value="A4" selected>A4</option>
                                <option value="A5">A5</option>
                                <option value="letter">letter</option>
                                <option value="legal">legal</option>
                                <option value="folio">folio</option>
                                <option value="executive">executive</option>
                            </select>
                            <br />
                            Please select page orientation:
                            <select id="orientation">
                                <option value="landscape" selected>landscape</option>
                                <option value="portrait">portrait</option>
                            </select>  
                            <br />
                            Please enter a description:
                            <input type="text" size="28" placeholder="description" id="description" /><br />
                        </p></div>
                </div>



                <div id="tabele">
                    <form id="tabelele">
                        <select id="table" name="tables" size="23" disabled>
                            <!--<option value="_blank"></option>-->
                        </select>
                    </form>
                </div>
                <!--tabel query-->
                <div id="tab">
                    <table id="queryTab" >



                    </table>
                </div>


                <div id="coloane">
                    <form id="coloanele">
                        <select id="col2" size="23" multiple disabled>
                            <!--<option value="_blank">blank_field</option>-->
                        </select>
                    </form> 
                </div> 
            </div>
        </div>
    </body>
</html>


