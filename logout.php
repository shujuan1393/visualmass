<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
require_once 'config/db.php';

unset($_SESSION['loggedUser']);
if (isset($_SESSION['loggedUserEmail']) && isset($_SESSION['userType'])) {
    date_default_timezone_set("Asia/Singapore");
    $now = date("Y-m-d h:i:sa");
    $updateSql = "UPDATE staff set lastlogout='$now' where email='". $_SESSION['loggedUserEmail']."';";
    
    mysqli_query($link, $updateSql);
    session_destroy();
    setcookie("user", "", time() - 3600);
    header("Location: admin/login.php");  
} else {
    session_destroy();
    setcookie("user", "", time() - 3600);
    header("Location: index.php");
}
?>

