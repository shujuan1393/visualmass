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

$link = mysqli_connect('localhost', 'visualmass', 'ilovevisualmass');
if (!$link) {
    die('Could not connect: ' . mysql_error());
}
//echo 'Connected successfully';

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

require_once('braintree/lib/Braintree.php');

Braintree_Configuration::environment('sandbox');
Braintree_Configuration::merchantId('t6f3x7thfrp85fxr');
Braintree_Configuration::publicKey('zwffr27gfdksxmxz');
Braintree_Configuration::privateKey('f4d205166ddd37027a37a5fed3cdbba5');
