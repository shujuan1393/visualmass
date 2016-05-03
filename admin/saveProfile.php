<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
session_start();
require '../config/db.php';

$firstname = $_POST['firstname'];
$lastname = $_POST['lastname'];
$email = $_POST['email'];
$password = $_POST['password'];
$phone = $_POST['phone'];
$web = $_POST['web'];
$bio = htmlentities($_POST['biography']);
$pwd = md5($password);

$updateProfile = "UPDATE staff set firstname='$firstname', lastname='$lastname', email='$email',"
        . "password='$pwd', phone='$phone', website='$web', biography='$bio' where email='".$_SESSION['loggedUserEmail']."';";

if (!mysqli_query($link,$updateProfile)) {
//                echo("Error description: " . mysqli_error($link));
    $_SESSION['profileError'] = mysqli_error($link);
} else {
    mysqli_query($link, $updateProfile);
    $_SESSION['profileSuccess'] = "Changes saved successfully";
}

header("Location: profile.php");
