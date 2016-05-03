<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
$link = mysqli_connect('localhost', 'visualmass', 'ilovevisualmass');
if (!$link) {
    die('Could not connect: ' . mysql_error());
}
//echo 'Connected successfully';

mysqli_select_db($link, "visual_mass");

