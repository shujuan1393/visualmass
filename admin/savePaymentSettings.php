<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
require_once '../config/db.php';

$_SESSION['visibility'] = $_POST['visibility'];
$_SESSION['disclaimer'] = $_POST['disclaimer'];

if (empty($_POST['visibility']) || empty($_POST['disclaimer'])) { 
    unset($_SESSION['updatePaymentSetSuccess']);
    $_SESSION['updatePaymentSetError'] = "Empty field(s)";
    header("Location: paymentsSettings.php");
} else if(isset($_POST['submit'])) {
    unset($_SESSION['updatePaymentSetError']);
    $visibility = $_POST['visibility'];
    $disclaimer = html_entity_decode($_POST['disclaimer']);
    
    $val = "visibility=".$visibility."#";
    $val .= "disclaimer=".$disclaimer;
    
    $checkSql = "Select * from settings where type='payments'";
    $result = mysqli_query($link, $checkSql);
    
    if (!mysqli_query($link,$checkSql)) {
        echo("Error description: " . mysqli_error($link));
    } else {
        unset($_SESSION['visibility']);
        unset($_SESSION['disclaimer']);
        
        $sql = "";
        if ($result->num_rows == 0) {
            $sql = "INSERT INTO settings (type, value) VALUES"
                    . "('payments','$val')";
        } else {
            $sql = "Update settings set value='".$val."' where type='payments'";
        }
        if (mysqli_query($link, $sql)) {
            $_SESSION['updatePaymentSetSuccess'] = "Changes saved successfully";
            header("Location: paymentsSettings.php");
        } else {
            echo "Error updating record: " . mysqli_error($link);
        }

    }
}
?>
