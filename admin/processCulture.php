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
        unset($_SESSION['updateCultError']);
        unset($_SESSION['addCultSuccess']);
        unset($_SESSION['addCultError']);
        $_SESSION['updateCultSuccess'] = "Record deleted successfully";
        header("Location: culturestory.php#menu1");
    } 
} else if (isset($_GET['banner'])) { 
    unset($_SESSION['addCultBannerError']);
    $target_dir = "../uploads/banner/";
    $random_digit=md5(uniqid(rand(), true));
    
    if (empty($_FILES['image']['name']) && empty($_POST['oldImage'])) { 
        unset($_SESSION['updateCultError']);
        unset($_SESSION['addCultSuccess']);
        unset($_SESSION['addCultError']);
        unset($_SESSION['updateCultSuccess']);
        unset($_SESSION['addCultBannerSuccess']);
        $_SESSION['addCultBannerError'] = "No file selected";
        header("Location: culturestory.php");
    } else if (!empty($_FILES['image']['name'])) {
        $new_file = 'culture_'.$random_digit.basename($_FILES["image"]["name"]);
        $target_file = $target_dir . $new_file;
        $uploadOk = 1;

        $imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
        
        // Check if file already exists
        if (file_exists($target_file)) {
            unset($_SESSION['addCultBannerSuccess']);
            $_SESSION['addCultBannerError'] = "Sorry, file already exists.";
            header('Location: culturestory.php');
        }
        // Allow certain file formats
        if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
        && $imageFileType != "gif" && $imageFileType != "mp3" && $imageFileType != "mp4" && $imageFileType != "wma" ) {
            unset($_SESSION['addCultBannerSuccess']);
            $_SESSION['addCultBannerError'] = "Sorry, only JPG, JPEG, PNG, GIF, MP3, MP4 & WMA files are allowed.";
            header('Location: culturestory.php');
        }
        
        // Check file size
        if ($_FILES["image"]["size"] > 5000000) {
            unset($_SESSION['addCultBannerSuccess']);
            $_SESSION['addCultBannerError'] = "Sorry, uploads cannot be greater than 5MB.";
            header('Location: culturestory.php');
        }

        if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
            unset($_POST['addCultBannerError']);
        } else { 
            unset($_SESSION['addCultBannerSuccess']);
            $_SESSION['addCultBannerError'] = "Sorry, there was an error uploading your file.";
            header('Location: culturestory.php');   
        }
    } else {
        unset($_SESSION['addCultBannerError']);
        $target_file = $_POST['oldImage'];
    }
    
    if(!isset($_SESSION['addCultBannerError'])) {
        $check = "Select * from ourstory where page='culture' and type='banner';";
        $cresult = mysqli_query($link, $check);

        if (!mysqli_query($link, $check)) {
            echo "Error description: ". mysqli_error($link);
            exit();
        } else {
            $crow = mysqli_fetch_assoc($cresult);

            if ($cresult -> num_rows != 0) {
                $faqBanner = "UPDATE ourstory SET html='$target_file' where page='culture' and type='banner'";
            } else {
                $faqBanner = "INSERT INTO ourstory (title, html, type, page) VALUES "
                        . "('banner', '$target_file', 'banner', 'culture');";
            }
            if (!empty($faqBanner)) {
                unset($_SESSION['updateCultSuccess']);
                unset($_SESSION['addCultSuccess']);
                unset($_SESSION['addCultError']);
                unset($_SESSION['updateCultError']);
                mysqli_query($link, $faqBanner);
                unset($_SESSION['addCultBannerError']);
                $_SESSION['addCultBannerSuccess'] = "Banner updated successfully";
                header("Location: culturestory.php");
            }
        }
    }
    
} else if (isset($_POST['submit'])) {
    $_SESSION['title'] = $_POST['title'];
    $_SESSION['html'] = $_POST['html'];
    $_SESSION['status'] = $_POST['status'];
    $_SESSION['order'] = $_POST['order'];
    
    if(empty($_POST['title']) || empty($_POST['html'])) {
        unset($_SESSION['addCultSuccess']);
        unset($_SESSION['updateCultError']);
        unset($_SESSION['updateCultSuccess']);
        $_SESSION['addCultError'] = "Empty field(s)";
        if (!empty($_POST['editid'])) {
            header("Location: culturestory.php?id=".$_POST['editid']."#menu1");
        } else {
            header("Location: culturestory.php#menu1");
        }
    } else { 
        unset($_SESSION['title']);
        unset($_SESSION['html']);
        unset($_SESSION['status']);
        unset($_SESSION['order']);
        
        unset($_SESSION['addCultError']);
        unset($_SESSION['updateCultError']);
        unset($_SESSION['updateCultSuccess']);
        
        $title = $_POST['title'];
        $html = htmlentities($_POST['html']);
        $status = $_POST['status'];
        $order = $_POST['order'];
        
        if (!empty($_POST['editid'])) {
            $editid = $_POST['editid'];

            $updateDiscSql = "UPDATE ourstory SET title='$title', html='$html',"
                    . " status='$status', fieldorder='$order', page='culture', type='section' where id = '$editid';";

            if (mysqli_query($link, $updateDiscSql)) {
                unset($_SESSION['addCultSuccess']);
                unset($_SESSION['addCultError']);
                unset($_SESSION['updateCultError']);
                $_SESSION['updateCultSuccess'] = "Record updated successfully";
                header("Location: culturestory.php#menu1");
            } else {
                echo "Error updating record: " . mysqli_error($link);
            }
        } else {
            $blogSql = "INSERT INTO ourstory (title, html, status, page, type, fieldorder) "
                    . "VALUES ('$title','$html', '$status', 'culture', 'section', '$order');";

            mysqli_query($link, $blogSql);
            $_SESSION['addCultSuccess'] = "Section successfully added";
            header('Location: culturestory.php#menu1');
        }
    }
}

