<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
require_once '../config/db.php';

if (isset($_GET['add'])) {
    $_SESSION['name'] = $_POST['name'];
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
        unset($_SESSION['name']);
        
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
        header("Location: blogSettings.php");
//        echo "<script>window.history.back()</script>";
    } 
} else if (isset($_GET['delete']) && isset($_GET['aid'])) { 
    $deletesql = "DELETE FROM staff where id ='". $_GET['aid']."'";
    if (mysqli_query($link, $deletesql)) {
        unset($_SESSION['addBlogCatSuccess']);
        unset($_SESSION['addBlogCatError']);
        unset($_SESSION['updateBlogCatError']);
        unset($_SESSION['updateBlogCatSuccess']);
        
        unset($_SESSION['addAuthorSuccess']);
        unset($_SESSION['addAuthorError']);
        unset($_SESSION['updateAuthorError']);
        $_SESSION['updateAuthorSuccess'] = "Record deleted successfully";
        header("Location: blogSettings.php#menu1");
//        echo "<script>window.history.back()</script>";
    } 
} else if (isset($_GET['update'])) {
    $_SESSION['firstname'] = $_POST['firstname'];
    $_SESSION['lastname'] = $_POST['lastname'];
    $_SESSION['email'] = $_POST['email'];
    $_SESSION['phone'] = $_POST['phone'];
    $_SESSION['date'] = $_POST['date3'];
    
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
        
        if (!empty($_POST['editid'])) {
            header("Location: blogSettings.php?id=".$_POST['editid']."#menu1");
        } else {
            header("Location: blogSettings.php#menu1");
        }
//        echo "<script>window.history.back()</script>";
    } else if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
        unset($_SESSION['addBlogCatSuccess']);
        unset($_SESSION['addBlogCatError']);
        unset($_SESSION['updateBlogCatSuccess']);
        unset($_SESSION['updateBlogCatError']);
        
        unset($_SESSION['updateAuthorSuccess']);
        unset($_SESSION['addAuthorSuccess']);
        unset($_SESSION['updateAuthorError']);
        $_SESSION['addAuthorError'] = "Invalid email";
        if (!empty($_POST['editid'])) {
            header("Location: blogSettings.php?id=".$_POST['editid']."#menu1");
        } else {
            header("Location: blogSettings.php#menu1");
        }
//        echo "<script>window.history.back()</script>";
    } else {
        unset($_SESSION['firstname']);
        unset($_SESSION['lastname']);
        unset($_SESSION['email']);
        unset($_SESSION['phone']);
        unset($_SESSION['date']);
        
        $firstname = $_POST['firstname'];
        $lastname = $_POST['lastname'];
        $email = $_POST['email'];
        $phone = $_POST['phone'];
        $date = $_POST['date3'];
        $authortype = "author";
        $pwd = md5('P@ssw0rd!23');
        
        if (empty($_POST['editid'])) {
            $authSql = "INSERT INTO staff (firstname, lastname, email, phone, type, password, datejoined) "
                    . "VALUES ('$firstname', '$lastname', '$email', '$phone', 
                            '$authortype', '$pwd', '$date');";
            mysqli_query($link, $authSql);
            $_SESSION['addAuthorSuccess'] = "Author successfully added";
        } else {
            $authSql = "UPDATE staff set firstname='$firstname', lastname='$lastname', "
                    . "email='$email', phone='$phone'"
                    . ", datejoined='$date', status='$status' where id ='".$_POST['editid']."';";
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
        
        header("Location: blogSettings.php#menu1");
        
//        echo "<script>window.history.back()</script>";
    }
}

