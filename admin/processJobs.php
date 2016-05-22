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
    if(empty($_POST['title']) || empty($_POST['html']) 
            || empty($_POST['type']) || empty($_POST['status'])) {
        unset($_SESSION['addJobSuccess']);
        unset($_SESSION['updateJobError']);
        unset($_SESSION['updateJobSuccess']);
        $_SESSION['addJobError'] = "Empty field(s)";
        header('Location: jobs.php');
    } else {
        unset($_SESSION["updateJobError"]);
        unset($_SESSION["updateJobSuccess"]);
        unset($_SESSION["addJobError"]);
        unset($_SESSION["addJobSuccess"]);
        
        $title = $_POST['title'];
        $html = htmlentities($_POST['html']);
        $type = $_POST['type'];
        $status = $_POST['status'];
        $featured = $_POST['featured'];
        
        if (!empty($_POST['editid'])) {
            $editcode = $_POST['editid'];

            $editJobSql = "UPDATE jobs set title='$title', html='$html',"
                    . " status='$status', featured='$featured', type='$type' "
                    . "where id='$editcode'";

            mysqli_query($link, $editJobSql);

            $_SESSION['updateJobSuccess'] = "Job updated successfully";
                header('Location: jobs.php');
        } else {
            $productSql = "INSERT INTO jobs (title, html, status, featured, type) "
                    . "VALUES('$title', '$html', '$status', '$featured', '$type')";

            mysqli_query($link, $productSql);

            unset($_SESSION["updateJobError"]);
            unset($_SESSION["updateJobSuccess"]);
            $_SESSION['addJobSuccess'] = "Job added successfully";
            header('Location: jobs.php');
        }
    }
    
}

