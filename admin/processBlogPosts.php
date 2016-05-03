<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
session_start();
require '../config/db.php';
$target_dir = "../uploads/blog/";

if (isset($_GET['edit'])) {  
    $random_digit=md5(uniqid(rand(), true));
    $new_file = $random_digit.basename($_FILES["editimage"]["name"]);
    $edit_target_file = $target_dir . $new_file;
    $uploadOk = 1;
    $edit_imageFileType = pathinfo($edit_target_file,PATHINFO_EXTENSION);

    // Check if image file is a actual image or fake image
    if(isset($_POST["submit"]) && !empty($_FILES['editimage']['name'])) {
        $check = getimagesize($_FILES['editimage']["tmp_name"]);
        if($check !== false) {
            $uploadOk = 1;
        } else {
            $_SESSION['editUploadBlogError'] = "File is not an image.";
            header('Location: editBlogPost.php?id='.$_POST['editid']);
        }
    }
    // Check if file already exists
    if (file_exists($edit_target_file)) {
        $_SESSION['editUploadBlogError'] = "Sorry, file already exists.";
        header('Location: editBlogPost.php?id='.$_POST['editid']);
    }
    // Check file size
    if ($_FILES['editimage']["size"] > 500000) {
        $_SESSION['editUploadBlogError'] = "Sorry, your file is too large.";
        header('Location: editBlogPost.php?id='.$_POST['editid']);
    }
    // Allow certain file formats
    if($edit_imageFileType != "jpg" && $edit_imageFileType != "png" && $edit_imageFileType != "jpeg"
    && $edit_imageFileType != "gif" ) {
        $_SESSION['editUploadBlogError'] = "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
        header('Location: editBlogPost.php?id='.$_POST['editid']);
    }
    
    $editid = $_POST['editid'];
    $edittitle = $_POST['edittitle'];
    $editexcerpt = htmlentities($_POST['editexcerpt']);
    $edithtml = htmlentities($_POST['edithtml']);
    $editvisibility = $_POST['editvisibility'];
    $editdate = $_POST['date3'];
    $edittagArr = $_POST['edittags'];
    $edittags = "";
    
//    for($i = 0; $i < count($edittagArr); $i++) {
//        $edittags .= $edittagArr[$i];
//
//        if ($i+1 !== count($edittagArr)) {
//            $edittags.=",";
//        }
//    }
    if (empty($_FILES['editimage']['name'])) {
        $edit_target_file = $_POST['oldImage'];
    } else {        
        if (!move_uploaded_file($_FILES['editimage']["tmp_name"], $edit_target_file)) {
            $_SESSION['editUploadBlogError'] = "Sorry, there was an error uploading your file.";
            header('Location: editBlogPost.php?id='.$_POST['editid']);
        } 
    }
    
    $updateDiscSql = "UPDATE blog SET title='$edittitle', excerpt='$editexcerpt', "
            . "html='$edithtml', visibility='$editvisibility', dateposted='$editdate', "
            . "tags='$edittags', image='$edit_target_file' where id = '$editid';";

    if (mysqli_query($link, $updateDiscSql)) {
        unset($_SESSION['addBlogSuccess']);
        unset($_SESSION['addBlogError']);
        unset($_SESSION['updateBlogError']);
        $_SESSION['updateBlogSuccess'] = "Record updated successfully";
        header("Location: blog.php");
    } else {
        echo "Error updating record: " . mysqli_error($link);
    }
} else if (isset($_GET['delete'])) {
    $deletesql = "DELETE FROM blog where id ='". $_GET['id']."'";
    if (mysqli_query($link, $deletesql)) {
        unset($_SESSION['updateBlogError']);
        unset($_SESSION['addBlogSuccess']);
        unset($_SESSION['addBlogError']);
        $_SESSION['updateBlogSuccess'] = "Record deleted successfully";
        header("Location: blog.php");
    } 
} else if (isset($_POST['submit'])) {
    if(empty($_POST['title']) || empty($_POST['html']) ) {
        unset($_SESSION['addBlogSuccess']);
        unset($_SESSION['updateBlogError']);
        unset($_SESSION['updateBlogSuccess']);
        $_SESSION['addBlogError'] = "Empty field(s)";
        header('Location: blog.php');
    } else {
        unset($_SESSION['addBlogError']);
        unset($_SESSION['updateBlogError']);
        unset($_SESSION['updateBlogSuccess']);
        
        $image = "";
        $title = $_POST['title'];
        $excerpt = htmlentities($_POST['excerpt']);
        $html = htmlentities($_POST['html']);
        $visibility = $_POST['visibility'];
        $date = $_POST['date3'];
        $author;
        
        $getAuthor = "Select * from staff where email='".$_SESSION['loggedUserEmail']."';";
        
        if (!mysqli_query($link, $getAuthor)) {
            $_SESSION['addBlogError'] = mysqli_error($link);
            header('Location: blog.php');
        } else {
            $result = mysqli_query($link, $getAuthor);
            $row = mysqli_fetch_assoc($result);
            $author = $row['firstname']." ".$row['lastname'];
        }
        
        $tagArr = $_POST['tags'];
        $tags = "";

//        for($i = 0; $i < count($tagArr); $i++) {
//            $tags .= $tagArr[$i];
//
//            if ($i+1 !== count($tagArr)) {
//                $tags.=",";
//            }
//        }
        
        if (!empty($_FILES['image']['name'])) {
            $random_digit=md5(uniqid(rand(), true));
            $new_file = $random_digit.basename($_FILES["image"]["name"]);
            $target_file = $target_dir . $new_file;
            $uploadOk = 1;
            
            $imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);

            // Check if image file is a actual image or fake image
            if(isset($_POST["submit"])) {
                $check = getimagesize($_FILES["image"]["tmp_name"]);
                if($check !== false) {
                    $uploadOk = 1;
                } else {
                    unset($_SESSION['addBlogSuccess']);
                    $_SESSION['uploadBlogError'] = "File is not an image.";
                    header('Location: blog.php');
                }
            }
            // Check if file already exists
            if (file_exists($target_file)) {
                unset($_SESSION['addBlogSuccess']);
                $_SESSION['uploadBlogError'] = "Sorry, file already exists.";
                header('Location: blog.php');
            }
            // Check file size
            if ($_FILES["image"]["size"] > 500000) {
                unset($_SESSION['addBlogSuccess']);
                $_SESSION['uploadBlogError'] = "Sorry, your file is too large.";
                header('Location: blog.php');
            }
            // Allow certain file formats
            if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
            && $imageFileType != "gif" ) {
                unset($_SESSION['addBlogSuccess']);
                $_SESSION['uploadBlogError'] = "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
                header('Location: blog.php');
            }
            if ($uploadOk === 1) {
                if (!move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
                    unset($_SESSION['addBlogSuccess']);
                    $_SESSION['uploadBlogError'] = "Sorry, there was an error uploading your file.";
                    header('Location: blog.php');   
                } else {
                    unset($_SESSION['uploadBlogError']);
                    $image .= $target_file;
                }
            }
        } 
        
        if (!isset($_SESSION['uploadBlogError'])) {

            $blogSql = "INSERT INTO blog (title, excerpt, image, html, visibility, "
                    . "author, dateposted, tags) VALUES ('$title','$excerpt', '$image', '$html', "
                    . "'$visibility', '$author', '$date', '$tags');";
            
            mysqli_query($link, $blogSql);
            $_SESSION['addBlogSuccess'] = "Blog entry successfully added";
            header('Location: blog.php');
        }
    }
}

