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
        unset($_SESSION['updateDesError']);
        unset($_SESSION['addDesSuccess']);
        unset($_SESSION['addDesError']);
        $_SESSION['updateDesSuccess'] = "Record deleted successfully";
        header("Location: designstory.php");
    } 
} else if (isset($_GET['banner'])) { 
    unset($_SESSION['addDesBannerError']);
    $target_dir = "../uploads/banner/";
    $random_digit=md5(uniqid(rand(), true));
    
    if (empty($_FILES['image']['name']) && empty($_POST['oldImage'])) { 
        unset($_SESSION['updateDesError']);
        unset($_SESSION['addDesSuccess']);
        unset($_SESSION['addDesError']);
        unset($_SESSION['updateDesSuccess']);
        unset($_SESSION['addDesBannerSuccess']);
        $_SESSION['addDesBannerError'] = "No file selected";
        header("Location: designstory.php");
    } else if (!empty($_FILES['image']['name'])) {
        $new_file = 'design_'.$random_digit.basename($_FILES["image"]["name"]);
        $target_file = $target_dir . $new_file;
        $uploadOk = 1;

        $imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
        
        // Check if file already exists
        if (file_exists($target_file)) {
            unset($_SESSION['addDesBannerSuccess']);
            $_SESSION['addDesBannerError'] = "Sorry, file already exists.";
            header('Location: designstory.php');
        }
        
        // Allow certain file formats
        if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
        && $imageFileType != "gif" && $imageFileType != "mp3" && $imageFileType != "mp4" && $imageFileType != "wma" ) {
            unset($_SESSION['addDesBannerSuccess']);
            $_SESSION['addDesBannerError'] = "Sorry, only JPG, JPEG, PNG, GIF, MP3, MP4 & WMA files are allowed.";
            header('Location: designstory.php');
        }

        if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
            unset($_POST['addDesBannerError']);
        } else { 
            unset($_SESSION['addDesBannerSuccess']);
            $_SESSION['addDesBannerError'] = "Sorry, there was an error uploading your file.";
            header('Location: designstory.php');   
        }
    } else {
        unset($_SESSION['addDesBannerError']);
        $target_file = $_POST['oldImage'];
    }
    
    if(!isset($_SESSION['addDesBannerError'])) {
        $check = "Select * from ourstory where page='design' and type='banner';";
        $cresult = mysqli_query($link, $check);
        
        if (!mysqli_query($link, $check)) {
            echo "Error description: ". mysqli_error($link);
            exit();
        } else {
            $crow = mysqli_fetch_assoc($cresult);

            if ($cresult -> num_rows != 0) {
                $faqBanner = "UPDATE ourstory SET html='$target_file' where page='design' and type='banner'";
            } else {
                $faqBanner = "INSERT INTO ourstory (title, html, type, page) VALUES "
                        . "('banner', '$target_file', 'banner', 'design');";
            }
            if (!empty($faqBanner)) {
                unset($_SESSION['updateDesSuccess']);
                unset($_SESSION['addDesSuccess']);
                unset($_SESSION['addDesError']);
                unset($_SESSION['updateDesError']);
                mysqli_query($link, $faqBanner);
                unset($_SESSION['addDesBannerError']);
                $_SESSION['addDesBannerSuccess'] = "Banner updated successfully";
                header("Location: designstory.php");
            }
        }
    }
    
} else if (isset($_POST['submit'])) {
    if(empty($_POST['title']) || empty($_POST['html'])) {
        unset($_SESSION['addDesSuccess']);
        unset($_SESSION['updateDesError']);
        unset($_SESSION['updateDesSuccess']);
        $_SESSION['addDesError'] = "Empty field(s)";
        header('Location: designstory.php');
    } else { 
        unset($_SESSION['addDesError']);
        unset($_SESSION['updateDesError']);
        unset($_SESSION['updateDesSuccess']);
        
        $title = $_POST['title'];
        $html = htmlentities($_POST['html']);
        $status = $_POST['status'];
        $order = $_POST['order'];
        
        if (!empty($_POST['editid'])) {
            $editid = $_POST['editid'];

            $updateDiscSql = "UPDATE ourstory SET title='$title', html='$html',"
                    . " status='$status', fieldorder='$order', page='design', type='section' where id = '$editid';";

            if (mysqli_query($link, $updateDiscSql)) {
                unset($_SESSION['addDesSuccess']);
                unset($_SESSION['addDesError']);
                unset($_SESSION['updateDesError']);
                $_SESSION['updateDesSuccess'] = "Record updated successfully";
                header("Location: designstory.php");
            } else {
                echo "Error updating record: " . mysqli_error($link);
            }
        } else {
            $blogSql = "INSERT INTO ourstory (title, html, status, page, type, fieldorder) "
                    . "VALUES ('$title','$html', '$status', 'design', 'section', '$order');";

            mysqli_query($link, $blogSql);
            $_SESSION['addDesSuccess'] = "Section successfully added";
            header('Location: designstory.php');
        }
    }
}

