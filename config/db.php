<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

session_start();
$curUrl = $_SERVER['REQUEST_URI'];
$urlArr = explode("/", $curUrl);

if (in_array("admin", $urlArr)) {
    if (!isset($_SESSION['loggedUserEmail'])) {
        header("Location: login.php");
    }
}

$link = mysqli_connect('localhost', 'visualmass', 'ilovevisualmass');
if (!$link) {
    die('Could not connect: ' . mysql_error());
}
//echo 'Connected successfully';

mysqli_select_db($link, "visual_mass");

