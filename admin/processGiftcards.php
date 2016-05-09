<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
require_once '../config/db.php';

if (isset($_GET['delete'])) {
    $deletesql = "DELETE FROM giftcards where id ='". $_GET['id']."'";
    if (mysqli_query($link, $deletesql)) {
        unset($_SESSION['addGiftSuccess']);
        unset($_SESSION['addGiftError']);
        unset($_SESSION['updateGiftError']);
        $_SESSION['updateGiftSuccess'] = "Record deleted successfully";
        header("Location: giftcards.php");
    } 
} else if (isset($_POST['submit'])) {
    if(empty($_POST['name']) || empty($_POST['desc']) || empty($_POST['status']) 
            || (strcmp($_POST['customise'], "yes")===0 && empty($_POST['amount']) )) {
        unset($_SESSION['addGiftSuccess']);
        unset($_SESSION['updateGiftSuccess']);
        unset($_SESSION['updateGiftError']);
        $_SESSION['addGiftError'] = "Empty field(s)";
        header('Location: giftcards.php');
    } else {
        unset($_SESSION['addGiftError']);
        unset($_SESSION['updateGiftSuccess']);
        unset($_SESSION['updateGiftError']);
        
        $name = $_POST['name'];
        $desc = $_POST['desc'];
        $amount = $_POST['amount'];
        $status = $_POST['status'];
        $customise = $_POST['customise'];
        
        if (!empty($_POST['editid'])) {  
            $editid = $_POST['editid'];
            
            $updateGiftSql = "UPDATE giftcards SET name='$name', "
                    . "description='$desc', amount='$amount', "
                    . "customise='$customise', status='$status' where id = '$editid';";
            if (mysqli_query($link, $updateGiftSql)) {
                unset($_SESSION['addGiftSuccess']);
                unset($_SESSION['addGiftError']);
                unset($_SESSION['updateGiftError']);
                $_SESSION['updateGiftSuccess'] = "Record updated successfully";
                header("Location: giftcards.php");
            } else {
                echo "Error updating record: " . mysqli_error($link);
            }
        } else {
            $giftSql = "INSERT INTO giftcards (name, description, amount, customise, status)"
                . " VALUES ('$name', '$desc', '$amount', '$customise', '$status');";
            
            mysqli_query($link, $giftSql);
            $_SESSION['addGiftSuccess'] = "Gift card successfully added";
            header('Location: giftcards.php');
        }
    }
}

