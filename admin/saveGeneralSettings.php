<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
require_once '../config/db.php';


if(isset($_POST['submit'])) {
    $_SESSION['primary'] = $_POST['primary'];
    $_SESSION['email'] = $_POST['email'];
    $_SESSION['currency'] = $_POST['currency'];
    $_SESSION['timezone'] = $_POST['timezone'];
    
    if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
        unset($_SESSION['updateGenSetSuccess']);
        $_SESSION['gensetError'] = "Invalid email";
        header('Location: generalSettings.php');
    } else {
        unset($_SESSION['primary']);
        unset($_SESSION['email']);
        unset($_SESSION['currency']);
        unset($_SESSION['timezone']);
        
        unset($_SESSION['gensetError']);
        $store = $_POST['primary'];
        $email = $_POST['email'];
        $curr = $_POST['currency'];
        $time = $_POST['timezone'];

        $val = "primary=".$store."&";
        $val .= "email=".$email."&";
        $val .= "curr=".$curr."&";
        $val .= "timezone=".$time;

        $checkSql = "Select * from settings where type='general'";
        $result = mysqli_query($link, $checkSql);
        
        if (!mysqli_query($link,$checkSql)) {
            echo("Error description: " . mysqli_error($link));
        } else {
            $sql = "";
            if ($result->num_rows == 0) {
                $sql = "INSERT INTO settings (type, value) VALUES"
                        . "('general','$val')";
            } else {
                $sql = "Update settings set value='".$val."' where type='general'";
            }
            if (mysqli_query($link, $sql)) {
                $_SESSION['updateGenSetSuccess'] = "Changes saved successfully";
                header("Location: generalSettings.php");
            } else {
                echo "Error updating record: " . mysqli_error($link);
            }

        }
    }
}
?>
