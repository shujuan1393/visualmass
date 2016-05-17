<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
require_once '../config/db.php';
if (empty($_POST['password']) || empty($_POST['repassword'])) {
    $_SESSION['profileError'] = "Empty field(s)";
    header("Location: profile.php");
} else if (strcmp($_POST['password'], $_POST['repassword']) !== 0) { 
    $_SESSION['profileError'] = "Passwords do not match";
    header("Location: profile.php");    
} else {
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $repassword = $_POST['repassword'];
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
}
