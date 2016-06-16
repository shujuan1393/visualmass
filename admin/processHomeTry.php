<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
require_once '../config/db.php';
$target_dir = "../uploads/hometry/";

if (isset($_GET['delete'])) {
    $deletesql = "DELETE FROM hometry where id ='". $_GET['id']."'";
    if (mysqli_query($link, $deletesql)) {
        unset($_SESSION['updateHomeError']);
        unset($_SESSION['addHomeSuccess']);
        unset($_SESSION['addHomeError']);
        unset($_SESSION['uploadHomeError']);
        $_SESSION['updateHomeSuccess'] = "Record deleted successfully";
        header("Location: homeTry.php");
    } 
} else if (isset($_GET['banner'])) { 
    unset($_SESSION['addHomeBannerError']);
    $target_dir = "../uploads/banner/";
    $random_digit=md5(uniqid(rand(), true));
    
    if (empty($_FILES['image']['name']) && empty($_POST['oldImage'])) { 
        unset($_SESSION['updateHomeError']);
        unset($_SESSION['addHomeSuccess']);
        unset($_SESSION['addHomeError']);
        unset($_SESSION['updateHomeSuccess']);
        unset($_SESSION['addHomeBannerSuccess']);
        $_SESSION['addHomeBannerError'] = "No file selected";
        header("Location: homeTry.php");
    } else if (!empty($_FILES['image']['name'])) {
        $new_file = 'hometry_'.$random_digit.basename($_FILES["image"]["name"]);
        $target_file = $target_dir . $new_file;
        $uploadOk = 1;

        $imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
        
        // Check if file already exists
        if (file_exists($target_file)) {
            unset($_SESSION['addHomeBannerSuccess']);
            $_SESSION['addHomeBannerError'] = "Sorry, file already exists.";
            header('Location: homeTry.php');
        }
        // Allow certain file formats
        if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
        && $imageFileType != "gif" && $imageFileType != "mp3" && $imageFileType != "mp4" && $imageFileType != "wma" ) {
            unset($_SESSION['addHomeBannerSuccess']);
            $_SESSION['addHomeBannerError'] = "Sorry, only JPG, JPEG, PNG, GIF, MP3, MP4 & WMA files are allowed.";
            header('Location: homeTry.php');
        }

        // Check file size
        if ($_FILES["image"]["size"] > 5000000) {
            unset($_SESSION['addHomeBannerSuccess']);
            $_SESSION['addHomeBannerError'] = "Sorry, uploads cannot be greater than 5MB.";
            header('Location: homeTry.php');
        }
        
        if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
            unset($_POST['addHomeBannerError']);
        } else { 
            unset($_SESSION['addHomeBannerSuccess']);
            $_SESSION['addHomeBannerError'] = "Sorry, there was an error uploading your file.";
            header('Location: homeTry.php');   
        }
    } else {
        unset($_SESSION['addHomeBannerError']);
        $target_file = $_POST['oldImage'];
    }
    
    if(!isset($_SESSION['addHomeBannerError'])) {
        $check = "Select * from hometry where type='banner';";
        $cresult = mysqli_query($link, $check);

        if (!mysqli_query($link, $check)) {
            echo "Error description: ". mysqli_error($link);
            exit();
        } else {
            $crow = mysqli_fetch_assoc($cresult);

            if ($cresult -> num_rows != 0) {
                $faqBanner = "UPDATE hometry SET html='$target_file' where type='banner'";
            } else {
                $faqBanner = "INSERT INTO hometry (type, html) VALUES "
                        . "('banner', '$target_file');";
            }
            if (!empty($faqBanner)) {
                unset($_SESSION['updateHomeSuccess']);
                unset($_SESSION['addHomeSuccess']);
                unset($_SESSION['addHomeError']);
                unset($_SESSION['updateHomeError']);
                mysqli_query($link, $faqBanner);
                unset($_SESSION['addHomeBannerError']);
                $_SESSION['addHomeBannerSuccess'] = "Banner updated successfully";
                header("Location: homeTry.php");
            }
        }
    }
    
} else if (isset($_POST['submit'])) {
    
    $buttonno = $_POST['buttonno'];
    
    for ($i = 1; $i <= $buttonno; $i++) {
        $linki = "link".$i;
        $linkposi = "linkpos".$i;
        $buttoni = "buttontext".$i;
        if (!empty($_POST[$buttoni]) && (empty($_POST[$linkposi]) || empty($_POST[$linki]))) {
            unset($_SESSION['addHomeSuccess']);
            unset($_SESSION['updateHomeError']);
            unset($_SESSION['updateHomeSuccess']);
            unset($_SESSION['uploadHomeError']);
            $_SESSION['addHomeError'] = "Button text and Link position required";
            if (!empty($_POST['editid'])) {
                header("Location: homeTry.php?id=".$_POST['editid']);
            } else {
                header('Location: homeTry.php');
            }
        } 
    }
    
    if(empty($_POST['title']) || empty($_POST['status'])) {
        unset($_SESSION['addHomeSuccess']);
        unset($_SESSION['updateHomeError']);
        unset($_SESSION['updateHomeSuccess']);
        unset($_SESSION['uploadHomeError']);
        $_SESSION['addHomeError'] = "Empty field(s)";
        if (!empty($_POST['editid'])) {
            header("Location: homeTry.php?id=".$_POST['editid']);
        } else {
            header('Location: homeTry.php');
        }
    } else if (empty($_POST['oldImage']) && empty($_FILES['image']['name'])) { 
        unset($_SESSION['addHomeSuccess']);
        unset($_SESSION['updateHomeError']);
        unset($_SESSION['updateHomeSuccess']);
        unset($_SESSION['uploadHomeError']);
        $_SESSION['addHomeError'] = "No image selected";
        if (!empty($_POST['editid'])) {
            header("Location: homeTry.php?id=".$_POST['editid']);
        } else {
            header('Location: homeTry.php');
        }
    } else if (!empty($_POST['html']) && empty($_POST['htmlpos'])) { 
        unset($_SESSION['addHomeSuccess']);
        unset($_SESSION['updateHomeError']);
        unset($_SESSION['updateHomeSuccess']);
        unset($_SESSION['uploadHomeError']);
        $_SESSION['addHomeError'] = "Content position required";
        if (!empty($_POST['editid'])) {
            header("Location: homeTry.php?id=".$_POST['editid']);
        } else {
            header('Location: homeTry.php');
        }
    } else if (!empty($_FILES['image']['name']) && empty($_POST['imagepos'])) { 
        unset($_SESSION['addHomeSuccess']);
        unset($_SESSION['updateHomeError']);
        unset($_SESSION['updateHomeSuccess']);
        unset($_SESSION['uploadHomeError']);
        $_SESSION['addHomeError'] = "Image position required";
        if (!empty($_POST['editid'])) {
            header("Location: homeTry.php?id=".$_POST['editid']);
        } else {
            header('Location: homeTry.php');
        }
    } else if (!isset($_SESSION['addHomeError'])){
        unset($_SESSION['addHomeError']);
        unset($_SESSION['updateHomeError']);
        unset($_SESSION['updateHomeSuccess']);
        unset($_SESSION['uploadHomeError']);
        
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
                    unset($_SESSION['addHomeSuccess']);
                    $_SESSION['uploadHomeError'] = "File is not an image.";
                    header('Location: homeTry.php');
                }
            }
            // Check if file already exists
            if (file_exists($target_file)) {
                unset($_SESSION['addHomeSuccess']);
                $_SESSION['uploadHomeError'] = "Sorry, file already exists.";
                header('Location: homeTry.php');
            }
            // Check file size
            if ($_FILES["image"]["size"] > 500000) {
                unset($_SESSION['addHomeSuccess']);
                $_SESSION['uploadHomeError'] = "Sorry, your file is too large.";
                header('Location: homeTry.php');
            }
            // Allow certain file formats
            if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
            && $imageFileType != "gif" ) {
                unset($_SESSION['addHomeSuccess']);
                $_SESSION['uploadHomeError'] = "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
                header('Location: homeTry.php');
            }
            if ($uploadOk === 1) {
                if (!move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
                    unset($_SESSION['addHomeSuccess']);
                    $_SESSION['uploadHomeError'] = "Sorry, there was an error uploading your file.";
                    header('Location: homeTry.php');   
                } else {
                    unset($_SESSION['uploadHomeError']);
                    $image .= $target_file;
                }
            }
        } else {
            if (!empty($_POST['oldImage'])) {
                $image = $_POST['oldImage'];
            }
        }
        
        if (!isset($_SESSION['uploadHomeError'])) {
            if (!empty($_POST['editid'])) {
                $editid = $_POST['editid'];
                
                $updateHomeSql = "UPDATE hometry SET title='$title', link='$pagelink', "
                    . "image='$image', status='$status', html='$html', htmlpos='$htmlpos',"
                        . "imagepos='$imagepos', linkpos='$linkpos', buttontext='$buttonlink', "
                        . "fieldorder='$fieldorder', type='section' where id = '$editid';";
                
                if (mysqli_query($link, $updateHomeSql)) {
                    unset($_SESSION['addHomeSuccess']);
                    unset($_SESSION['addHomeError']);
                    unset($_SESSION['updateHomeError']);
                    unset($_SESSION['editUploadHomeError']);
                    $_SESSION['updateHomeSuccess'] = "Record updated successfully";
                    header("Location: homeTry.php");
                } else {
                    echo "Error updating record: " . mysqli_error($link);
                }
            } else {
                $advSql = "INSERT INTO hometry (title, image, imagepos, buttontext, "
                        . "link, linkpos, status, html, htmlpos, fieldorder, type) "
                    . "VALUES ('$title','$image', '$imagepos', '$buttonlink',"
                        . " '$pagelink', '$linkpos', '$status', '$html', '$htmlpos', "
                        . "'$fieldorder', 'section');";

                mysqli_query($link, $advSql);
                unset($_SESSION['uploadHomeError']);
                $_SESSION['addHomeSuccess'] = "Advertisement successfully added";
                header('Location: homeTry.php');
            }
        }
    }
}

