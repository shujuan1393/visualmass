<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

ob_start();
session_start();
$curUrl = $_SERVER['REQUEST_URI'];
$urlArr = explode("/", $curUrl);

if (in_array("admin", $urlArr)) {
    if (!isset($_SESSION['loggedUserEmail'])) {
        header("Location: login.php");
    }
}

//$link = mysqli_connect('localhost', 'visualma_admin', 'P@ssw0rd!23');
$link = mysqli_connect('localhost', 'visualmass', 'ilovevisualmass');

if (!$link) {
    die('Could not connect: ' . mysql_error());
}
//echo 'Connected successfully';

//mysqli_select_db($link, "visualma_visualmass");
mysqli_select_db($link, "visual_mass");

function GetCartId() {
// This function will generate an encrypted string and
// will set it as a cookie using set_cookie. This will
// also be used as the cookieId field in the cart table
    if(isset($_COOKIE["cartId"])) {
        return $_COOKIE["cartId"];
    } else {
        // There is no cookie set. We will set the cookie
        // and return the value of the users session ID
        setcookie("cartId", session_id(), time() + ((3600 * 24) * 30));
        return session_id();
    }
}

//archive discounts
$disc = "Select * from discounts";
$discres = mysqli_query($link, $disc);

if (!mysqli_query($link, $disc)) {
    die(mysqli_error($link));
} else {
    if ($discres -> num_rows > 0) {
        while($row = mysqli_fetch_assoc($discres)) {
            $date = date('Y-m-d');
            $today = date('Y-m-d', strtotime($date));
            //echo $paymentDate; // echos today! 
            $start = date('Y-m-d', strtotime($row['start']));
            $end = date('Y-m-d', strtotime($row['end']));

            //archive all expired discounts
            if ($today >= $end) {
                $sql = "INSERT INTO discountarchives (code, name, disclimit, recurrence, "
                        . "discusage, userlimit, start, end, disctype, disccondition) VALUES "
                        . "('".$row['code']."', '".$row['name']."', '".$row['disclimit']."', "
                        . "'".$row['recurrence']."', '".$row['discusage']."', '".$row['userlimit']."', "
                        . "'".$row['start']."', '".$row['end']."', '".$row['disctype']."', '".$row['disccondition']."');";
                mysqli_query($link, $sql);
                
                //remove from discounts table
                $remove = "DELETE FROM discounts where id='".$row['id']."';";
                mysqli_query($link, $remove);
            }
        }
    }
}
