<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

session_start();

if (!isset($_COOKIE['user'])) {
    header("Location: login.php");
    exit();
} 

if(auto_logout("user_time")) {
    session_unset();
    session_destroy();
    setcookie("user", "", time() - 3600);
    header("Location: login.php");          
    exit;
}  

function auto_logout($field) {
    if (isset($_SESSION[$field])) {
        $t = time();
        $t0 = $_SESSION[$field];
        $diff = $t - $t0;
        if ($diff > 900 || !isset($t0)) {          
            return true;
        }
        else {
            $_SESSION[$field] = time();
        }
    } else {
        header("Location: login.php");
    }
}
$link = mysqli_connect('localhost', 'visualmass', 'ilovevisualmass');
if (!$link) {
    die('Could not connect: ' . mysql_error());
}
//echo 'Connected successfully';

mysqli_select_db($link, "visual_mass");

