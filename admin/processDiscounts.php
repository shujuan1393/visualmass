<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
session_start();
require '../config/db.php';

if (isset($_GET['edit'])) {  
    $editid = $_POST['editid'];
    $editcode = $_POST['editcode'];
    $editname = $_POST['editname'];
    $editlimit = $_POST['editlimit'];
    $editrecurrence = $_POST['editrecurrence'];
    $editstatus = $_POST['editstatus'];
    $editstart = $_POST['date3'];
    $editend = $_POST['date4'];
    $editusageArr = $_POST['editusage'];
    $editusage = "";
    
    for($i = 0; $i < count($editusageArr); $i++) {
        $editusage .= $editusageArr[$i];

        if ($i+1 !== count($editusageArr)) {
            $editusage.=",";
        }
    }
    
    $updateDiscSql = "UPDATE discounts SET code='$editcode', name='$editname', "
            . "disclimit='$editlimit', recurrence='$editrecurrence', "
            . "discusage='$editusage', status='$editstatus', start='$editstart', "
            . "end='$editend' where id = '$editid';";

    if (mysqli_query($link, $updateDiscSql)) {
        unset($_SESSION['addDiscSuccess']);
        unset($_SESSION['addDiscError']);
        unset($_SESSION['updateDiscError']);
        $_SESSION['updateDiscSuccess'] = "Record updated successfully";
        header("Location: discounts.php");
    } else {
        echo "Error updating record: " . mysqli_error($link);
    }
} else if (isset($_GET['delete'])) {
    $deletesql = "DELETE FROM discounts where id ='". $_GET['id']."'";
    if (mysqli_query($link, $deletesql)) {
        unset($_SESSION['updateDiscError']);
        unset($_SESSION['addDiscSuccess']);
        unset($_SESSION['addDiscError']);
        $_SESSION['updateDiscSuccess'] = "Record deleted successfully";
        header("Location: discounts.php");
    } 
} else if (isset($_POST['submit'])) {
    if(empty($_POST['limit']) 
            || empty($_POST['recurrence']) || empty($_POST['status']) 
            || empty($_POST['usage']) || empty($_POST['code']) ) {
        unset($_SESSION['addDiscSuccess']);
        unset($_SESSION['updateDiscError']);
        unset($_SESSION['updateDiscSuccess']);
        $_SESSION['randomString'] = $_POST['code'];
        $_SESSION['addDiscError'] = "Empty field(s)";
        header('Location: discounts.php');
    } else {
        unset($_SESSION['addDiscError']);
        unset($_SESSION['updateDiscError']);
        unset($_SESSION['updateDiscSuccess']);
        
        $code = $_POST['code'];
        $name = $_POST['name'];
        $limit = $_POST['limit'];
        $recurrence = $_POST['recurrence'];
        $status = $_POST['status'];
        $start = $_POST['date3'];
        $end = $_POST['date4'];
        $usageArr = $_POST['usage'];
        $usage = "";

        for($i = 0; $i < count($usageArr); $i++) {
            $usage .= $usageArr[$i];

            if ($i+1 !== count($usageArr)) {
                $usage.=",";
            }
        }

        $discSql = "INSERT INTO discounts (code, name, disclimit, recurrence, discusage, "
                . "status, start, end) VALUES ('$code','$name', '$limit', '$recurrence', "
                . "'$usage', '$status', '$start', '$end');";

        mysqli_query($link, $discSql);
        $_SESSION['addDiscSuccess'] = "Discount successfully added";
        header('Location: discounts.php');
    }
}

