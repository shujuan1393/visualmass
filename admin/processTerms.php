<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
require_once '../config/db.php';

if (isset($_GET['delete'])) {
    $deletesql = "DELETE FROM terms where id ='". $_GET['id']."'";
    if (mysqli_query($link, $deletesql)) {
        unset($_SESSION['addTermSuccess']);
        unset($_SESSION['addTermError']);
        unset($_SESSION['updateTermError']);
        $_SESSION['updateTermSuccess'] = "Record deleted successfully";
        header("Location: terms.php");
    } 
} else if (isset($_POST['submit'])) {
    $_SESSION['title'] = $_POST['title'];
    $_SESSION['html'] = $_POST['html'];
    $_SESSION['order'] = $_POST['order'];
    
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
        
        unset($_SESSION['title']);
        unset($_SESSION['html']);
        unset($_SESSION['order']);
        
        $title = $_POST['title'];
        $order = $_POST['order'];
        $html = htmlentities($_POST['html']);
        
        if (!empty($_POST['editid'])) {
            $editid = $_POST['editid'];

            $updateDiscSql = "UPDATE terms SET title='$title', html='$html', fieldorder='$order' "
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
        } else {
            $faqSql = "INSERT INTO terms (title, html, fieldorder) VALUES "
                    . "('$title', '$html', '$order');";
            
            mysqli_query($link, $faqSql);
            $_SESSION['addTermSuccess'] = "Terms section successfully added";
            header('Location: terms.php');
        }
    }
}

