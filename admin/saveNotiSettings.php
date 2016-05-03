<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
session_start();

require '../config/db.php';


if(isset($_POST['submit'])) {unset($_SESSION['gensetError']);
    $welcome = htmlentities($_POST['welcome']);
    $purchase = htmlentities($_POST['purchase']);
    $incomplete = htmlentities($_POST['incomplete']);
    $password = htmlentities($_POST['password']);
    $cancel = htmlentities($_POST['cancel']);
    $refund = htmlentities($_POST['refund']);
    $gift = htmlentities($_POST['gift']);
    $feedback = htmlentities($_POST['feedback']);
    $eyecheck = htmlentities($_POST['eyecheck']);
    
    $templates = "email=".$welcome.",".$purchase.",";
    $templates .= $incomplete.",".$password.",";
    $templates .= $cancel.",".$refund.",";
    $templates .= $gift.",".$feedback.",";
    $templates .= $eyecheck;
    
    $complete = htmlentities($_POST['complete']);
    $collect = htmlentities($_POST['collect']);
    $templates .= "#sms=".$complete.",".$collect;
    
    $checkSql = "Select * from settings where type='notifications'";
    $result = mysqli_query($link, $checkSql);

    if (!mysqli_query($link,$checkSql)) {
        echo("Error description: " . mysqli_error($link));
    } else {
        $sql = "";
        if ($result->num_rows == 0) {
            $sql = "INSERT INTO settings (type, value) VALUES"
                    . "('notifications','$templates')";
        } else {
            $sql = "Update settings set value='".$templates."' where type='notifications'";
        }
        if (mysqli_query($link, $sql)) {
            $_SESSION['updateNotiSetSuccess'] = "Changes saved successfully";
            header("Location: notificationSettings.php");
        } else {
            echo "Error updating record: " . mysqli_error($link);
        }

    }
}
?>
