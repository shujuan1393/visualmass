<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
require_once '../config/db.php';
$target_dir = "../uploads/advertisements/";

if (isset($_GET['delete'])) {
    $deletesql = "DELETE FROM advertisements where id ='". $_GET['id']."'";
    if (mysqli_query($link, $deletesql)) {
        unset($_SESSION['updateAdvError']);
        unset($_SESSION['addAdvSuccess']);
        unset($_SESSION['addAdvError']);
        unset($_SESSION['uploadAdvError']);
        $_SESSION['updateAdvSuccess'] = "Record deleted successfully";
        header("Location: advertisements.php");
    } 
} else if (isset($_POST['submit'])) {
    if(empty($_POST['title']) || empty($_POST['status']) ||
            (empty($_POST['oldImage']) && empty($_FILES['image']['name']))) {
        unset($_SESSION['addAdvSuccess']);
        unset($_SESSION['updateAdvError']);
        unset($_SESSION['updateAdvSuccess']);
        unset($_SESSION['uploadAdvError']);
        $_SESSION['addAdvError'] = "Empty field(s)";
        header('Location: advertisements.php');
    } else {
        unset($_SESSION['addAdvError']);
        unset($_SESSION['updateAdvError']);
        unset($_SESSION['updateAdvSuccess']);
        unset($_SESSION['uploadAdvError']);
        
        $title = $_POST['title'];
        $pagelink = $_POST['link'];
        $status = $_POST['status'];
        $start = $_POST['date3'];
        $end = $_POST['date4'];
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
                    unset($_SESSION['addAdvSuccess']);
                    $_SESSION['uploadAdvError'] = "File is not an image.";
                    header('Location: advertisements.php');
                }
            }
            // Check if file already exists
            if (file_exists($target_file)) {
                unset($_SESSION['addAdvSuccess']);
                $_SESSION['uploadAdvError'] = "Sorry, file already exists.";
                header('Location: advertisements.php');
            }
            // Check file size
            if ($_FILES["image"]["size"] > 500000) {
                unset($_SESSION['addAdvSuccess']);
                $_SESSION['uploadAdvError'] = "Sorry, your file is too large.";
                header('Location: advertisements.php');
            }
            // Allow certain file formats
            if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
            && $imageFileType != "gif" ) {
                unset($_SESSION['addAdvSuccess']);
                $_SESSION['uploadAdvError'] = "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
                header('Location: advertisements.php');
            }
            if ($uploadOk === 1) {
                if (!move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
                    unset($_SESSION['addAdvSuccess']);
                    $_SESSION['uploadAdvError'] = "Sorry, there was an error uploading your file.";
                    header('Location: advertisements.php');   
                } else {
                    unset($_SESSION['uploadAdvError']);
                    $image .= $target_file;
                }
            }
        } else {
            if (!empty($_POST['oldImage'])) {
                $image = $_POST['oldImage'];
            }
        }
        
        if (!isset($_SESSION['uploadAdvError'])) {
            if (!empty($_POST['editid'])) {
                $editid = $_POST['editid'];
                
                $updateAdvSql = "UPDATE advertisements SET title='$title', link='$pagelink', "
                    . "image='$image', status='$status', start='$start', "
                    . "end='$end' where id = '$editid';";
                
                if (mysqli_query($link, $updateAdvSql)) {
                    unset($_SESSION['addAdvSuccess']);
                    unset($_SESSION['addAdvError']);
                    unset($_SESSION['updateAdvError']);
                    unset($_SESSION['editUploadAdvError']);
                    $_SESSION['updateAdvSuccess'] = "Record updated successfully";
                    header("Location: advertisements.php");
                } else {
                    echo "Error updating record: " . mysqli_error($link);
                }
            } else {
                $advSql = "INSERT INTO advertisements (title, image, link, status, start, end) "
                    . "VALUES ('$title','$image', '$pagelink', '$status', '$start', '$end');";

                mysqli_query($link, $advSql);
                unset($_SESSION['uploadAdvError']);
                $_SESSION['addAdvSuccess'] = "Advertisement successfully added";
                header('Location: advertisements.php');
            }
        }
    }
}

