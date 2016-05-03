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
    $edittitle = $_POST['edittitle'];
    $edithtml = htmlentities($_POST['edithtml']);
    
    $updateDiscSql = "UPDATE terms SET title='$edittitle', html='$edithtml' "
            . "where id = '$editid';";

    if (mysqli_query($link, $updateDiscSql)) {
        unset($_SESSION['addTermSuccess']);
        unset($_SESSION['addTermError']);
        unset($_SESSION['updateTermError']);
        $_SESSION['updateTermSuccess'] = "Record updated successfully";
        header("Location: terms.php");
    } else {
        echo "Error updating record: " . mysqli_error($link);
    }
} else if (isset($_GET['delete'])) {
    $deletesql = "DELETE FROM terms where id ='". $_GET['id']."'";
    if (mysqli_query($link, $deletesql)) {
        unset($_SESSION['addTermSuccess']);
        unset($_SESSION['addTermError']);
        unset($_SESSION['updateTermError']);
        $_SESSION['updateTermSuccess'] = "Record deleted successfully";
        header("Location: terms.php");
    } 
} else if (isset($_POST['submit'])) {
    if(empty($_POST['title']) || empty($_POST['html']) ) {
        unset($_SESSION['updateTermSuccess']);
        unset($_SESSION['addTermSuccess']);
        unset($_SESSION['updateTermError']);
        $_SESSION['addTermError'] = "Empty field(s)";
        header('Location: terms.php');
    } else {
        unset($_SESSION['updateTermSuccess']);
        unset($_SESSION['addTermError']);
        unset($_SESSION['updateTermError']);
        
        $title = $_POST['title'];
        $html = htmlentities($_POST['html']);
        
        $faqSql = "INSERT INTO terms (title, html) VALUES "
                . "('$title', '$html');";

        mysqli_query($link, $faqSql);
        $_SESSION['addTermSuccess'] = "Terms section successfully added";
        header('Location: terms.php');
    }
}

