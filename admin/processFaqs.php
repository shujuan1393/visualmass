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
    
    $updateDiscSql = "UPDATE faq SET title='$edittitle', html='$edithtml', "
            . "type='section' where id = '$editid';";

    if (mysqli_query($link, $updateDiscSql)) {
        unset($_SESSION['addFaqSuccess']);
        unset($_SESSION['addFaqError']);
        unset($_SESSION['updateFaqError']);
        unset($_SESSION['addFaqBannerError']);
        unset($_SESSION['addFaqBannerSuccess']);
        $_SESSION['updateFaqSuccess'] = "Record updated successfully";
        header("Location: faq.php");
    } else {
        echo "Error updating record: " . mysqli_error($link);
    }
} else if (isset($_GET['delete'])) {
    $deletesql = "DELETE FROM faq where id ='". $_GET['id']."'";
    if (mysqli_query($link, $deletesql)) {
        unset($_SESSION['updateFaqError']);
        unset($_SESSION['addFaqSuccess']);
        unset($_SESSION['addFaqError']);
        unset($_SESSION['addFaqBannerError']);
        unset($_SESSION['addFaqBannerSuccess']);
        $_SESSION['updateFaqSuccess'] = "Record deleted successfully";
        header("Location: faq.php");
    } 
} else if (isset($_POST['submit'])) {
    if(empty($_POST['title']) || empty($_POST['html']) ) {
        unset($_SESSION['addFaqSuccess']);
        unset($_SESSION['updateFaqError']);
        unset($_SESSION['updateFaqSuccess']);
        unset($_SESSION['addFaqBannerError']);
        unset($_SESSION['addFaqBannerSuccess']);
        $_SESSION['addFaqError'] = "Empty field(s)";
        header('Location: faq.php');
    } else {
        unset($_SESSION['addFaqError']);
        unset($_SESSION['updateFaqError']);
        unset($_SESSION['updateFaqSuccess']);
        unset($_SESSION['addFaqBannerError']);
        unset($_SESSION['addFaqBannerSuccess']);
        
        $title = $_POST['title'];
        $html = htmlentities($_POST['html']);


        $faqSql = "INSERT INTO faq (title, html, type) VALUES "
                . "('$title', '$html', 'section');";

        mysqli_query($link, $faqSql);
        $_SESSION['addFaqSuccess'] = "FAQ section successfully added";
        header('Location: faq.php');
    }
}

