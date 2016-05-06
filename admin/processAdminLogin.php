<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
require_once '../config/db.php';

if(empty($_POST['email']) || empty($_POST['password'])) {
    $_SESSION['adminLoginError'] = "Email/password field(s) empty";
    header('Location: login.php');
} else {
    unset($_SESSION['adminLoginError']);
    $username = trim($_POST['email']);
    $password = trim($_POST['password']);
    $pwdmd5 = md5($password);
        
    $qry = "Select * from staff ".
        " where email='$username' and password='$pwdmd5' ";
    $result = mysqli_query($link, $qry);
    if (!mysqli_query($link,$qry)) {
        echo("Error description: " . mysqli_error($link));
    } else {
        if ($result->num_rows === 0) {
            $_SESSION['adminLoginError'] = "Invalid email/password ";
            header('Location: login.php');
        } else {
            while ($row = mysqli_fetch_assoc($result)) {
                $type = $row['type'];
//                if (strcmp($type, "admin") === 0) {
                    $_SESSION['loggedUser'] = $row['firstname'];
                    $_SESSION['loggedUserEmail'] = $row['email'];
                    $_SESSION['userType'] = $row['type'];
                    $_SESSION['user_time'] = time();
                    setcookie("user", $row['email'], time() + (86400 * 30), "/"); // 86400 = 1 day
                    date_default_timezone_set("Asia/Singapore");
                    $now = date("Y-m-d h:i:sa");
                    $updateSql = "UPDATE staff set lastlogin='$now' where id=". $row['id'];
                    mysqli_query($link, $updateSql);
                    header('Location: admin.php');                      
//                } 
            }
        } 
    }
}
?>  
