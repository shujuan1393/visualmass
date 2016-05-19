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
        unset($_SESSION['updateMainError']);
        unset($_SESSION['addMainSuccess']);
        unset($_SESSION['addMainError']);
        $_SESSION['updateMainSuccess'] = "Record deleted successfully";
        header("Location: mainstory.php");
    } 
} else if (isset($_GET['banner'])) { 
    unset($_SESSION['addMainBannerError']);
    $target_dir = "../uploads/banner/";
    $random_digit=md5(uniqid(rand(), true));
    
    if (empty($_FILES['image']['name']) && empty($_POST['oldImage'])) { 
        unset($_SESSION['updateMainError']);
        unset($_SESSION['addMainSuccess']);
        unset($_SESSION['addMainError']);
        unset($_SESSION['updateMainSuccess']);
        unset($_SESSION['addMainBannerSuccess']);
        $_SESSION['addMainBannerError'] = "No file selected";
        header("Location: mainstory.php");
    } else if (!empty($_FILES['image']['name'])) {
        $new_file = 'main_'.$random_digit.basename($_FILES["image"]["name"]);
        $target_file = $target_dir . $new_file;
        $uploadOk = 1;

        $imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
        
        // Check if file already exists
        if (file_exists($target_file)) {
            unset($_SESSION['addMainBannerSuccess']);
            $_SESSION['addMainBannerError'] = "Sorry, file already exists.";
            header('Location: mainstory.php');
        }
        // Allow certain file formats
        if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
        && $imageFileType != "gif" && $imageFileType != "mp3" && $imageFileType != "mp4" && $imageFileType != "wma" ) {
            unset($_SESSION['addMainBannerSuccess']);
            $_SESSION['addMainBannerError'] = "Sorry, only JPG, JPEG, PNG, GIF, MP3, MP4 & WMA files are allowed.";
            header('Location: mainstory.php');
        }

        if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
            unset($_POST['addMainBannerError']);
        } else { 
            unset($_SESSION['addMainBannerSuccess']);
            $_SESSION['addMainBannerError'] = "Sorry, there was an error uploading your file.";
            header('Location: mainstory.php');   
        }
    } else {
        unset($_SESSION['addMainBannerError']);
        $target_file = $_POST['oldImage'];
    }
    
    if(!isset($_SESSION['addMainBannerError'])) {
        $check = "Select * from ourstory where page='main' and type='banner';";
        $cresult = mysqli_query($link, $check);

        if (!mysqli_query($link, $check)) {
            echo "Error description: ". mysqli_error($link);
            exit();
        } else {
            $crow = mysqli_fetch_assoc($cresult);

            if ($cresult -> num_rows != 0) {
                $faqBanner = "UPDATE ourstory SET html='$target_file' where page='main' and type='banner'";
            } else {
                $faqBanner = "INSERT INTO ourstory (title, html, type, page) VALUES "
                        . "('banner', '$target_file', 'banner', 'main');";
            }
            if (!empty($faqBanner)) {
                unset($_SESSION['updateMainSuccess']);
                unset($_SESSION['addMainSuccess']);
                unset($_SESSION['addMainError']);
                unset($_SESSION['updateMainError']);
                mysqli_query($link, $faqBanner);
                unset($_SESSION['addMainBannerError']);
                $_SESSION['addMainBannerSuccess'] = "Banner updated successfully";
                header("Location: mainstory.php");
            }
        }
    }
    
} else if (isset($_POST['submit'])) {
    if(empty($_POST['title']) || empty($_POST['html'])) {
        unset($_SESSION['addMainSuccess']);
        unset($_SESSION['updateMainError']);
        unset($_SESSION['updateMainSuccess']);
        $_SESSION['addMainError'] = "Empty field(s)";
        header('Location: mainstory.php');
    } else { 
        unset($_SESSION['addMainError']);
        unset($_SESSION['updateMainError']);
        unset($_SESSION['updateMainSuccess']);
        
        $title = $_POST['title'];
        $html = htmlentities($_POST['html']);
        $status = $_POST['status'];
        $order = $_POST['order'];
        
        if (!empty($_POST['editid'])) {
            $editid = $_POST['editid'];

            $updateDiscSql = "UPDATE ourstory SET title='$title', html='$html',"
                    . " status='$status', fieldorder='$order', page='main', type='section' where id = '$editid';";

            if (mysqli_query($link, $updateDiscSql)) {
                unset($_SESSION['addMainSuccess']);
                unset($_SESSION['addMainError']);
                unset($_SESSION['updateMainError']);
                $_SESSION['updateMainSuccess'] = "Record updated successfully";
                header("Location: mainstory.php");
            } else {
                echo "Error updating record: " . mysqli_error($link);
            }
        } else {
            $blogSql = "INSERT INTO ourstory (title, html, status, page, type, fieldorder) "
                    . "VALUES ('$title','$html', '$status', 'main', 'section', '$order');";

            mysqli_query($link, $blogSql);
            $_SESSION['addMainSuccess'] = "Section successfully added";
            header('Location: mainstory.php');
        }
    }
}