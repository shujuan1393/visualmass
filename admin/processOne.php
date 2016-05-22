<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
require_once '../config/db.php';

if (isset($_GET['delete'])) {
    $deletesql = "DELETE FROM ourstory where id ='". $_GET['id']."'";
    if (mysqli_query($link, $deletesql)) {
        unset($_SESSION['updateOneError']);
        unset($_SESSION['addOneSuccess']);
        unset($_SESSION['addOneError']);
        $_SESSION['updateOneSuccess'] = "Record deleted successfully";
        header("Location: onestory.php");
    } 
} else if (isset($_GET['banner'])) { 
    unset($_SESSION['addOneBannerError']);
    $target_dir = "../uploads/banner/";
    $random_digit=md5(uniqid(rand(), true));
    
    if (empty($_FILES['image']['name']) && empty($_POST['oldImage'])) { 
        unset($_SESSION['updateOneError']);
        unset($_SESSION['addOneSuccess']);
        unset($_SESSION['addOneError']);
        unset($_SESSION['updateOneSuccess']);
        unset($_SESSION['addOneBannerSuccess']);
        $_SESSION['addOneBannerError'] = "No file selected";
        header("Location: onestory.php");
    } else if (!empty($_FILES['image']['name'])) {
        $new_file = 'one_'.$random_digit.basename($_FILES["image"]["name"]);
        $target_file = $target_dir . $new_file;
        $uploadOk = 1;

        $imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
        
        // Check if file already exists
        if (file_exists($target_file)) {
            unset($_SESSION['addOneBannerSuccess']);
            $_SESSION['addOneBannerError'] = "Sorry, file already exists.";
            header('Location: onestory.php');
        }
        // Allow certain file formats
        if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
        && $imageFileType != "gif" && $imageFileType != "mp3" && $imageFileType != "mp4" && $imageFileType != "wma" ) {
            unset($_SESSION['addOneBannerSuccess']);
            $_SESSION['addOneBannerError'] = "Sorry, only JPG, JPEG, PNG, GIF, MP3, MP4 & WMA files are allowed.";
            header('Location: onestory.php');
        }

        if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
            unset($_POST['addOneBannerError']);
        } else { 
            unset($_SESSION['addOneBannerSuccess']);
            $_SESSION['addOneBannerError'] = "Sorry, there was an error uploading your file.";
            header('Location: onestory.php');   
        }
    } else {
        unset($_SESSION['addOneBannerError']);
        $target_file = $_POST['oldImage'];
    }
    
    if(!isset($_SESSION['addOneBannerError'])) {
        $check = "Select * from ourstory where page='one' and type='banner';";
        $cresult = mysqli_query($link, $check);

        if (!mysqli_query($link, $check)) {
            echo "Error description: ". mysqli_error($link);
            exit();
        } else {
            $crow = mysqli_fetch_assoc($cresult);

            if ($cresult -> num_rows != 0) {
                $faqBanner = "UPDATE ourstory SET html='$target_file' where page='one' and type='banner'";
            } else {
                $faqBanner = "INSERT INTO ourstory (title, html, type, page) VALUES "
                        . "('banner', '$target_file', 'banner', 'one');";
            }
            if (!empty($faqBanner)) {
                unset($_SESSION['updateOneSuccess']);
                unset($_SESSION['addOneSuccess']);
                unset($_SESSION['addOneError']);
                unset($_SESSION['updateOneError']);
                mysqli_query($link, $faqBanner);
                unset($_SESSION['addOneBannerError']);
                $_SESSION['addOneBannerSuccess'] = "Banner updated successfully";
                header("Location: onestory.php");
            }
        }
    }
    
} else if (isset($_POST['submit'])) {
    if(empty($_POST['title']) || empty($_POST['html'])) {
        unset($_SESSION['addOneSuccess']);
        unset($_SESSION['updateOneError']);
        unset($_SESSION['updateOneSuccess']);
        $_SESSION['addOneError'] = "Empty field(s)";
        header('Location: onestory.php');
    } else { 
        unset($_SESSION['addOneError']);
        unset($_SESSION['updateOneError']);
        unset($_SESSION['updateOneSuccess']);
        
        $title = $_POST['title'];
        $html = htmlentities($_POST['html']);
        $status = $_POST['status'];
        $order = $_POST['order'];
        
        if (!empty($_POST['editid'])) {
            $editid = $_POST['editid'];

            $updateDiscSql = "UPDATE ourstory SET title='$title', html='$html',"
                    . " status='$status', fieldorder='$order', page='one', type='section' where id = '$editid';";

            if (mysqli_query($link, $updateDiscSql)) {
                unset($_SESSION['addOneSuccess']);
                unset($_SESSION['addOneError']);
                unset($_SESSION['updateOneError']);
                $_SESSION['updateOneSuccess'] = "Record updated successfully";
                header("Location: onestory.php");
            } else {
                echo "Error updating record: " . mysqli_error($link);
            }
        } else {
            $blogSql = "INSERT INTO ourstory (title, html, status, page, type, fieldorder) "
                    . "VALUES ('$title','$html', '$status', 'one', 'section', '$order');";

            mysqli_query($link, $blogSql);
            $_SESSION['addOneSuccess'] = "Section successfully added";
            header('Location: onestory.php');
        }
    }
}