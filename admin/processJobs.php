<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

require_once '../config/db.php';

if (isset($_GET['delete'])) {
    $deletesql = "DELETE FROM jobs where id ='". $_GET['id']."'";
    
    if (mysqli_query($link, $deletesql)) {
        unset($_SESSION['updateJobError']);
        unset($_SESSION['addJobError']);
        $_SESSION['updateJobSuccess'] = "Record deleted successfully";
        header("Location: jobs.php");
    } 
} else if (isset($_POST['submit'])) {
    $_SESSION['title'] = $_POST['title'];
    $_SESSION['html'] = $_POST['html'];
    $_SESSION['type'] = $_POST['type'];
    $_SESSION['status'] = $_POST['status'];
    $_SESSION['featured'] = $_POST['featured'];
    $_SESSION['scheduledate'] = $_POST['date4'];
    $_SESSION['time'] = $_POST['scheduledtime'];
    
    if(empty($_POST['title']) || empty($_POST['html']) 
            || empty($_POST['type']) || empty($_POST['status'])) {
        unset($_SESSION['addJobSuccess']);
        unset($_SESSION['updateJobError']);
        unset($_SESSION['updateJobSuccess']);
        $_SESSION['addJobError'] = "Empty field(s)";
        if (!empty($_POST['editid'])) {
            header('Location: jobs.php?id='.$_POST['editid']);
        } else {
            header("Location: jobs.php");
        }
    } else {
        unset($_SESSION['title']);
        unset($_SESSION['html']);
        unset($_SESSION['type']);
        unset($_SESSION['status']);
        unset($_SESSION['featured']);
        unset($_SESSION['scheduledate']);
        unset($_SESSION['time']);
        
        unset($_SESSION["updateJobError"]);
        unset($_SESSION["updateJobSuccess"]);
        unset($_SESSION["addJobError"]);
        unset($_SESSION["addJobSuccess"]);
        
        $title = $_POST['title'];
        $html = htmlentities($_POST['html']);
        $type = $_POST['type'];
        $status = $_POST['status'];
        $featured = $_POST['featured'];
        $scheduledate = $_POST['date4'];
        $scheduletime = $_POST['scheduledtime'];
        $schedule = date('Y-m-d G:i:s', strtotime($scheduledate." ".$scheduletime));
        
        if (!empty($_POST['editid'])) {
            $editcode = $_POST['editid'];

            $editJobSql = "UPDATE jobs set title='$title', html='$html',"
                    . " status='$status', featured='$featured',scheduled='$schedule', type='$type' "
                    . "where id='$editcode'";

            mysqli_query($link, $editJobSql);

            $_SESSION['updateJobSuccess'] = "Job updated successfully";
                header('Location: jobs.php');
        } else {
            $productSql = "INSERT INTO jobs (title, html, status, featured, type, scheduled) "
                    . "VALUES('$title', '$html', '$status', '$featured', '$type', '$schedule')";

            mysqli_query($link, $productSql);

            unset($_SESSION["updateJobError"]);
            unset($_SESSION["updateJobSuccess"]);
            $_SESSION['addJobSuccess'] = "Job added successfully";
            header('Location: jobs.php');
        }
    }
    
}

