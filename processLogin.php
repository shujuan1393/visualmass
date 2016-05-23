<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

require_once 'config/db.php';

if(empty($_POST['email']) || empty($_POST['password'])) {
    $_SESSION['loginFormError'] = "Email/password field(s) empty";
    header('Location: login.php');
} else {
    unset($_SESSION['loginFormError']);
    $username = trim($_POST['email']);
    $password = trim($_POST['password']);
    $pwdmd5 = md5($password);

    $qry = "Select * from user ".
        " where email='$username' and password='$pwdmd5' ";

    $result = mysqli_query($link, $qry);

    if (!mysqli_query($link,$qry))
    {
        echo("Error description: " . mysqli_error($link));
    } else {
        if ($result->num_rows === 0) {
            $_SESSION['loginFormError'] = "Invalid email/password ";
            header('Location: login.php');
        } else {
            // output data of each row
            while ($row = mysqli_fetch_assoc($result)) {
                $type = $row['accountType'];
                if (strcmp($type, "customer") === 0) {
                    $_SESSION['user_time'] = time();
//                    setcookie("user", $row['email'], time() + (86400 * 30), "/"); // 86400 = 1 day
                    $_SESSION['loggedUser'] = $row['firstname'];
                    header('Location: index.php');
                } 
            }
        } 
    }
}
  
