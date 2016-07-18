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
    $_SESSION['name'] = $_POST['name'];
    $_SESSION['desc'] = $_POST['desc'];
    $_SESSION['status'] = $_POST['status'];
    $_SESSION['type'] = $_POST['type'];
    $_SESSION['customise'] = $_POST['customise'];
    $_SESSION['randomString'] = $_POST['code'];
    $_SESSION['amount'] = $_POST['amount'];
    
//    echo $_SESSION['name'];
//    echo "<br>";
//    exit();
    if(empty($_POST['name']) || empty($_POST['desc']) || empty($_POST['status']) ||
            empty($_POST['type']) || empty($_POST['code'])
            || (strcmp($_POST['customise'], "no")===0 && empty($_POST['amount']) )) {
        unset($_SESSION['addGiftSuccess']);
        unset($_SESSION['updateGiftSuccess']);
        unset($_SESSION['updateGiftError']);
        $_SESSION['addGiftError'] = "Empty field(s)";
        if (!empty($_POST['editid'])) {
            header('Location: giftcards.php?id='.$_POST['editid']);            
        } else {
            header('Location: giftcards.php');
        }
    } else {
        unset($_SESSION['name']);
        unset($_SESSION['desc']);
        unset($_SESSION['status']);
        unset($_SESSION['type']);
        unset($_SESSION['customise']);
        unset($_SESSION['amount']);
        unset($_SESSION['randomString']);
        
        unset($_SESSION['addGiftError']);
        unset($_SESSION['updateGiftSuccess']);
        unset($_SESSION['updateGiftError']);
        
        $name = $_POST['name'];
        $desc = $_POST['desc'];
        $amount = $_POST['amount'];
        $status = $_POST['status'];
        $customise = $_POST['customise'];
        $typeArr = $_POST['type'];
        $type = "";
        
        for($i = 0; $i < count($typeArr); $i++) {
            $type .= $typeArr[$i];

            if ($i+1 !== count($typeArr)) {
                $type.=",";
            }
        }
        
        $code = $_POST['code'];
        
        if (!empty($_POST['editid'])) {  
            $editid = $_POST['editid'];
            
            $updateGiftSql = "UPDATE giftcards SET code='$code', name='$name', "
                    . "type='$type', description='$desc', amount='$amount', "
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
            $giftSql = "INSERT INTO giftcards (code, name, type, description, "
                    . "amount, customise, status) VALUES ('$code', '$name', '$type', '$desc', "
                    . "'$amount', '$customise', '$status');";
            
            mysqli_query($link, $giftSql);
            $_SESSION['addGiftSuccess'] = "Gift card successfully added";
            header('Location: giftcards.php');
        }
    }
}

