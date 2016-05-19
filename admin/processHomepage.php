<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
require_once '../config/db.php';
$target_dir = "../uploads/homepage/";

if (isset($_GET['delete'])) {
    $deletesql = "DELETE FROM homepage where id ='". $_GET['id']."'";
    if (mysqli_query($link, $deletesql)) {
        unset($_SESSION['updateHomepageError']);
        unset($_SESSION['addHomepageSuccess']);
        unset($_SESSION['addHomepageError']);
        unset($_SESSION['uploadHomepageError']);
        $_SESSION['updateHomepageSuccess'] = "Record deleted successfully";
        header("Location: homepage.php");
    } 
} else if (isset($_POST['submit'])) {
    
    $buttonno = $_POST['buttonno'];
    
    for ($i = 1; $i <= $buttonno; $i++) {
        $linki = "link".$i;
        $linkposi = "linkpos".$i;
        $buttoni = "buttontext".$i;
        if (!empty($_POST[$linki]) && (empty($_POST[$linkposi]) 
                || empty($_POST[$buttoni]))) { 
            unset($_SESSION['addHomepageSuccess']);
            unset($_SESSION['updateHomepageError']);
            unset($_SESSION['updateHomepageSuccess']);
            unset($_SESSION['uploadHomepageError']);
            $_SESSION['addHomepageError'] = "Button text and Link position required";
            if (!empty($_POST['editid'])) {
                header("Location: homepage.php?id=".$_POST['editid']);
            } else {
                header('Location: homepage.php');
            }
        } 
    }
    
    if(empty($_POST['title']) || empty($_POST['status'])) {
        unset($_SESSION['addHomepageSuccess']);
        unset($_SESSION['updateHomepageError']);
        unset($_SESSION['updateHomepageSuccess']);
        unset($_SESSION['uploadHomepageError']);
        $_SESSION['addHomepageError'] = "Empty field(s)";
        if (!empty($_POST['editid'])) {
            header("Location: homepage.php?id=".$_POST['editid']);
        } else {
            header('Location: homepage.php');
        }
    } else if (empty($_POST['oldImage']) && empty($_FILES['image']['name'])) { 
        unset($_SESSION['addHomepageSuccess']);
        unset($_SESSION['updateHomepageError']);
        unset($_SESSION['updateHomepageSuccess']);
        unset($_SESSION['uploadHomepageError']);
        $_SESSION['addHomepageError'] = "No image selected";
        if (!empty($_POST['editid'])) {
            header("Location: homepage.php?id=".$_POST['editid']);
        } else {
            header('Location: homepage.php');
        }
    } else if (!empty($_POST['html']) && empty($_POST['htmlpos'])) { 
        unset($_SESSION['addHomepageSuccess']);
        unset($_SESSION['updateHomepageError']);
        unset($_SESSION['updateHomepageSuccess']);
        unset($_SESSION['uploadHomepageError']);
        $_SESSION['addHomepageError'] = "Content position required";
        if (!empty($_POST['editid'])) {
            header("Location: homepage.php?id=".$_POST['editid']);
        } else {
            header('Location: homepage.php');
        }
    } else if (!empty($_FILES['image']['name']) && empty($_POST['imagepos'])) { 
        unset($_SESSION['addHomepageSuccess']);
        unset($_SESSION['updateHomepageError']);
        unset($_SESSION['updateHomepageSuccess']);
        unset($_SESSION['uploadHomepageError']);
        $_SESSION['addHomepageError'] = "Image position required";
        if (!empty($_POST['editid'])) {
            header("Location: homepage.php?id=".$_POST['editid']);
        } else {
            header('Location: homepage.php');
        }
    } else if (!isset($_SESSION['addHomepageError'])){
        unset($_SESSION['addHomepageError']);
        unset($_SESSION['updateHomepageError']);
        unset($_SESSION['updateHomepageSuccess']);
        unset($_SESSION['uploadHomepageError']);
        
        $pagelink = "";
        $linkpos = "";
        $buttonlink = "";
        
        for ($i = 1; $i <= $buttonno; $i++) {
            $linki = "link".$i;
            $linkposi = "linkpos".$i;
            $buttoni = "buttontext".$i;
            
            $pagelink .= $_POST[$linki];
            $linkpos .= $_POST[$linkposi];
            $buttonlink .= $_POST[$buttoni];
            
            if ($i+1 !== $buttonno+1) {
                $pagelink .= ",";
                $linkpos .= ",";
                $buttonlink .= ",";
            }
        }
        
        $title = $_POST['title'];
        $status = $_POST['status'];
        $html = htmlentities($_POST['html']);
        $htmlpos = $_POST['htmlpos'];
        $fieldorder = $_POST['order'];
        $imagepos = $_POST['imagepos'];
                
        $image = "";
        
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
                    unset($_SESSION['addHomepageSuccess']);
                    $_SESSION['uploadHomepageError'] = "File is not an image.";
                    header('Location: homepage.php');
                }
            }
            // Check if file already exists
            if (file_exists($target_file)) {
                unset($_SESSION['addHomepageSuccess']);
                $_SESSION['uploadHomepageError'] = "Sorry, file already exists.";
                header('Location: homepage.php');
            }
            // Check file size
            if ($_FILES["image"]["size"] > 500000) {
                unset($_SESSION['addHomepageSuccess']);
                $_SESSION['uploadHomepageError'] = "Sorry, your file is too large.";
                header('Location: homepage.php');
            }
            // Allow certain file formats
            if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
            && $imageFileType != "gif" ) {
                unset($_SESSION['addHomepageSuccess']);
                $_SESSION['uploadHomepageError'] = "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
                header('Location: homepage.php');
            }
            if ($uploadOk === 1) {
                if (!move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
                    unset($_SESSION['addHomepageSuccess']);
                    $_SESSION['uploadHomepageError'] = "Sorry, there was an error uploading your file.";
                    header('Location: homepage.php');   
                } else {
                    unset($_SESSION['uploadHomepageError']);
                    $image .= $target_file;
                }
            }
        } else {
            if (!empty($_POST['oldImage'])) {
                $image = $_POST['oldImage'];
            }
        }
        
        if (!isset($_SESSION['uploadHomepageError'])) {
            if (!empty($_POST['editid'])) {
                $editid = $_POST['editid'];
                
                $updateHomepageSql = "UPDATE homepage SET title='$title', link='$pagelink', "
                    . "image='$image', status='$status', html='$html', htmlpos='$htmlpos',"
                        . "imagepos='$imagepos', linkpos='$linkpos', buttontext='$buttonlink', "
                        . "fieldorder='$fieldorder' where id = '$editid';";
                
                if (mysqli_query($link, $updateHomepageSql)) {
                    unset($_SESSION['addHomepageSuccess']);
                    unset($_SESSION['addHomepageError']);
                    unset($_SESSION['updateHomepageError']);
                    unset($_SESSION['editUploadHomepageError']);
                    $_SESSION['updateHomepageSuccess'] = "Record updated successfully";
                    header("Location: homepage.php");
                } else {
                    echo "Error updating record: " . mysqli_error($link);
                }
            } else {
                $advSql = "INSERT INTO homepage (title, image, imagepos, buttontext, "
                        . "link, linkpos, status, html, htmlpos, fieldorder) "
                    . "VALUES ('$title','$image', '$imagepos', '$buttonlink',"
                        . " '$pagelink', '$linkpos', '$status', '$html', '$htmlpos', '$fieldorder');";

                mysqli_query($link, $advSql);
                unset($_SESSION['uploadHomepagepageError']);
                $_SESSION['addHomepagepageSuccess'] = "Advertisement successfully added";
                header('Location: homepage.php');
            }
        }
    }
}

