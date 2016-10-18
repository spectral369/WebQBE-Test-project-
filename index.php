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
$cookie_name = "serverna";
$cookie_port = "port";
$cookie_user = "user";
if (filter_input(INPUT_COOKIE, $cookie_name) !== NULL) {
    $defaultServerName = filter_input(INPUT_COOKIE, $cookie_name);
}
if (filter_input(INPUT_COOKIE, $cookie_user) !== NULL) {
    $defaultServerUser = filter_input(INPUT_COOKIE, $cookie_user);
}
if (filter_input(INPUT_COOKIE, $cookie_port) !== NULL) {
    $defaultServerPort = filter_input(INPUT_COOKIE, $cookie_port);
}
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
    <head>
        <meta http-equiv="Content-Type" content=
              "text/html; charset=utf-8" />

        <title>PHP QBE</title>
        <style type="text/css">
            #container {
                margin: 0 auto;
                width: 875px;
                text-align: center;
            }
            #container h1{
                display:block;
                text-align:center; 
            }
            #username,#password,#servername,#serverport{
                display: block;
                color:black;
                margin: 0 auto;
                position: relative;
            }
        </style>
    </head>

    <body>
        <div id="container">
            <h1>PHP QBE</h1>
            <hr />

            <div id="login">
                <form action="qbe.php" method="post" id="loginform">
                    <br />

                    <div id="center">
                        <div id="servernamefield">
                            <label for="servername">Server Name</label><input type=
                                                                              "text" id="servername" name="servername" placeholder=
                                                                              "Server address" required="" oninvalid=
                                                                              "this.setCustomValidity('Server address required')"
                                                                              oninput="setCustomValidity('')" value=
                                                                              "<?php echo $defaultServerName; ?>" /><br />
                        </div>

                        <div id="userdiv">
                            <label for="username">Username</label><input type=
                                                                         "text" id="username" name="username" value=
                                                                         "<?php echo $defaultServerUser; ?>" placeholder=
                                                                         "DB username" required="" oninvalid=
                                                                         "this.setCustomValidity('Username required')" oninput=
                                                                         "setCustomValidity('')" /><br />
                        </div>

                        <div id="passdiv">
                            <label for="password">Password</label><input type=
                                                                         "password" name="password" id="password" placeholder=
                                                                         "Enter your password" required="" oninvalid=
                                                                         "this.setCustomValidity('Password required')" oninput=
                                                                         "setCustomValidity('')" /><br />
                        </div>

                        <div id="serverportfield">
                            <label for="serverport">Server Port</label><input type="number"
                                                                              id="serverport" name="serverport" value="<?php echo $defaultServerPort; ?>"
                                                                              placeholder="DB port" required="" oninvalid=
                                                                              "this.setCustomValidity('DB port required')" oninput=
                                                                              "setCustomValidity('')" /><br />
                        </div><input type="submit" value="Login" id="sub" />
                    </div>
                </form>
            </div>
        </div>
    </body>
</html>
