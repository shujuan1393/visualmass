<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
require_once '../config/db.php';

if (isset($_GET['add'])) {
    if(empty($_POST['name'])) {
        unset($_SESSION['addBlogCatSuccess']);
        unset($_SESSION['updateBlogCatSuccess']);
        unset($_SESSION['updateBlogCatError']);
        
        unset($_SESSION['updateAuthorSuccess']);
        unset($_SESSION['addAuthorSuccess']);
        unset($_SESSION['addAuthorError']);
        unset($_SESSION['updateAuthorError']);
        
        $_SESSION['addBlogCatError'] = "Empty field(s)";
        echo "<script>window.history.back()</script>";
    } else {
        if (empty($_POST['editid'])) {
            $sql = "INSERT INTO categories (type, name) VALUES ('blog', '".$_POST['name']."');";
           
            mysqli_query($link, $sql);
            $_SESSION['addBlogCatSuccess'] = "Blog category successfully added";
        } else {
            $sql = "UPDATE categories set name='".$_POST['name']."' where id ='".$_POST['editid']."';";
            mysqli_query($link, $sql);
            $_SESSION['addBlogCatSuccess'] = "Record successfully updated";
        }
        unset($_SESSION['updateBlogCatSuccess']);
        unset($_SESSION['addBlogCatError']);
        unset($_SESSION['updateBlogCatError']);
        
        unset($_SESSION['updateAuthorSuccess']);
        unset($_SESSION['addAuthorError']);
        unset($_SESSION['addAuthorSuccess']);
        unset($_SESSION['updateAuthorError']);
        echo "<script>window.history.back()</script>";
    }
} else if (isset($_GET['delete']) && isset($_GET['id'])) { 
    $deletesql = "DELETE FROM categories where id ='". $_GET['id']."'";
    if (mysqli_query($link, $deletesql)) {
        unset($_SESSION['addBlogCatSuccess']);
        unset($_SESSION['addBlogCatError']);
        unset($_SESSION['updateBlogCatError']);
        
        unset($_SESSION['addAuthorSuccess']);
        unset($_SESSION['addAuthorError']);
        unset($_SESSION['updateAuthorError']);
        unset($_SESSION['updateAuthorSuccess']);
        $_SESSION['updateBlogCatSuccess'] = "Record deleted successfully";
        echo "<script>window.history.back()</script>";
    } 
} else if (isset($_GET['delete']) && isset($_GET['aid'])) { 
    $deletesql = "DELETE FROM authors where id ='". $_GET['aid']."'";
    if (mysqli_query($link, $deletesql)) {
        unset($_SESSION['addBlogCatSuccess']);
        unset($_SESSION['addBlogCatError']);
        unset($_SESSION['updateBlogCatError']);
        unset($_SESSION['updateBlogCatSuccess']);
        
        unset($_SESSION['addAuthorSuccess']);
        unset($_SESSION['addAuthorError']);
        unset($_SESSION['updateAuthorError']);
        $_SESSION['updateAuthorSuccess'] = "Record deleted successfully";
        echo "<script>window.history.back()</script>";
    } 
} else if (isset($_GET['update'])) {
    if(empty($_POST['firstname']) || empty($_POST['lastname'])
            || empty($_POST['email']) || empty($_POST['phone'])) {
        unset($_SESSION['addBlogCatSuccess']);
        unset($_SESSION['addBlogCatError']);
        unset($_SESSION['updateBlogCatSuccess']);
        unset($_SESSION['updateBlogCatError']);
        
        unset($_SESSION['updateAuthorSuccess']);
        unset($_SESSION['addAuthorSuccess']);
        unset($_SESSION['updateAuthorError']);
        $_SESSION['addAuthorError'] = "Empty field(s)";
        echo "<script>window.history.back()</script>";
    } else if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
        unset($_SESSION['addBlogCatSuccess']);
        unset($_SESSION['addBlogCatError']);
        unset($_SESSION['updateBlogCatSuccess']);
        unset($_SESSION['updateBlogCatError']);
        
        unset($_SESSION['updateAuthorSuccess']);
        unset($_SESSION['addAuthorSuccess']);
        unset($_SESSION['updateAuthorError']);
        $_SESSION['addAuthorError'] = "Invalid email";
        echo "<script>window.history.back()</script>";
    } else {
        $firstname = $_POST['firstname'];
        $lastname = $_POST['lastname'];
        $email = $_POST['email'];
        $phone = $_POST['phone'];
        $date = $_POST['date3'];
        
        if (empty($_POST['editid'])) {
            $authSql = "INSERT INTO authors (firstname, lastname, email, phone, datejoined) "
                    . "VALUES ('$firstname', '$lastname', '$email', '$phone', '$date');";
            mysqli_query($link, $authSql);
            $_SESSION['addAuthorSuccess'] = "Author successfully added";
        } else {
            $authSql = "UPDATE authors set firstname='$firstname', lastname='$lastname', "
                    . "email='$email', phone ='$phone'"
                    . ", datejoined='$date' where id ='".$_POST['editid']."';";
            mysqli_query($link, $authSql);
            $_SESSION['addAuthorSuccess'] = "Author successfully updated";
        }
        unset($_SESSION['updateBlogCatSuccess']);
        unset($_SESSION['addBlogCatError']);
        unset($_SESSION['addBlogCatSuccess']);
        unset($_SESSION['updateBlogCatError']);
        
        unset($_SESSION['updateAuthorSuccess']);
        unset($_SESSION['addAuthorError']);
        unset($_SESSION['updateAuthorError']);
        echo "<script>window.history.back()</script>";
    }
}

