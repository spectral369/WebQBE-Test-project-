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
if (!isset($_SESSION)) {
    session_start();
}
require $_SERVER['DOCUMENT_ROOT'] . '/WebQBE/config.php';

//to be tested
/* try {
  include 'config.php';
  } finally  {
  include '../../config.php';
  } */
class DB {

    private static $conn;
    private static $usern;
    private static $passw;
    protected static $pieces;
    private static $servername;
    private static $serverport;
    public static $file;
    private static $myfile;
    private static $fname;

    private
            function __construct() {
        
    }

    public static
            function setUP($username, $password, $servern, $serverp) {
        global $usern;
        global $passw;
        global $servername;
        global $serverport;
        global $fname;
        $usern = $username;
        $passw = $password;
        $servername = $servern;
        $serverport = $serverp;
        
    }

    //now lets create our method for connecting to the database
    public static
            function connect() {
        global $usern;
        global $passw;
        global $servername;
        global $serverport;
        global $file;
        global $fname;
        if (!isset($_SESSION['key'])) {
            $key = DB::random_str(random_int(3, 35));
            $_SESSION['key'] = $key;
        } else {
            $key = $_SESSION['key'];
        }

        if (!isset($_SESSION['fname'])) {
            $_SESSION['fname'] = substr(str_shuffle(str_repeat("0123456789abcdefghijklmnopqrstuvwxyz", 5)), 0, 5);
             $fname = $_SESSION['fname'];
            
        } else {
            $fname = $_SESSION['fname'];
        }

        $file = realpath(US) . DIRECTORY_SEPARATOR . $fname;

        if (!file_exists($file) || filesize($file) < 1) {
            $myfile = fopen($file, "w+");
            //chmod($file, 0600);
            //test

            if (flock($myfile, LOCK_EX)) {
                $string = $passw;
                $iv = mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_CBC), MCRYPT_DEV_URANDOM);
                $encrypted = base64_encode($iv . mcrypt_encrypt(MCRYPT_RIJNDAEL_128, hash('sha256', $key, true), $string, MCRYPT_MODE_CBC, $iv));
                //test
                fwrite($myfile, "$usern,$encrypted,$servername,$serverport");
                fseek($myfile, 0);
                $str = fread($myfile, 1024);
                $pieces = explode(",", $str);
                $pieces[1] = $passw;
            }
        } else {
            $myfile1 = fopen($file, "r");

            if (flock($myfile1, LOCK_EX)) {
                fseek($myfile1, 0);
                $str = fread($myfile1, 1024);
                $pieces = explode(",", $str);
                //test
                $data = base64_decode($pieces[1]);
                $iv = substr($data, 0, mcrypt_get_iv_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_CBC));
                $decrypted = rtrim(mcrypt_decrypt(MCRYPT_RIJNDAEL_128, hash('sha256', $key, true), substr($data, mcrypt_get_iv_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_CBC)), MCRYPT_MODE_CBC, $iv), "\0");
                $pieces[1] = $decrypted;
                //fclose($myfile1);
            }
        }

        if (!empty(self::$conn)) {
            return self::$conn;
        } //end if
        try {
            $dbh = new PDO('mysql:host=' . $pieces[2] . ';port=' . $pieces[3] . ';', $pieces[0], $pieces[1], array(
                PDO::ATTR_PERSISTENT => true
            ));
            self::$conn = $dbh;
            return $dbh;
        } catch (PDOException $e) {
            print "Error! : " . $e->getMessage() . "<br/>";
            $f = @fopen($file, "r+");
            ftruncate($f, 0);
            fclose($f);
            header('Refresh: 5;' . $_SERVER['HTTP_REFERER']);
            die();
        }
    }

    static
            function random_str($length) {
        $keyspace = "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
        $str = '';
        $max = mb_strlen($keyspace, '8bit') - 1;

        for ($i = 0; $i < $length; ++$i) {
            $str .= $keyspace[random_int(0, $max)];
        }
        return $str;
    }

    static function unload() {
        global $file;
        $f = fopen($file, "r+");
        if ($f !== false) {
            ftruncate($f, 0);
            fclose($f);
            //unlink($f);//www-data needs delete permission !
        } else {
            die("error !");
        }
    }

}

//end class
?>
