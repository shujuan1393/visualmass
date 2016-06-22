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
        header("Location: homepage.php#menu1");
    } 
} else if (isset($_GET['banner'])) { 
    unset($_SESSION['addHomepageBannerError']);
    $target_dir = "../uploads/banner/";
    $random_digit=md5(uniqid(rand(), true));
    
    if (empty($_FILES['image']['name']) && empty($_POST['oldImage'])) { 
        unset($_SESSION['updateHomepageError']);
        unset($_SESSION['addHomepageSuccess']);
        unset($_SESSION['addHomepageError']);
        unset($_SESSION['updateHomepageSuccess']);
        unset($_SESSION['addHomepageBannerSuccess']);
        $_SESSION['addHomepageBannerError'] = "No file selected";
        header("Location: homepage.php");
    } else if (!empty($_FILES['image']['name'])) {
        $new_file = 'homepage_'.$random_digit.basename($_FILES["image"]["name"]);
        $target_file = $target_dir . $new_file;
        $uploadOk = 1;

        $imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
        
        // Check if file already exists
        if (file_exists($target_file)) {
            unset($_SESSION['addHomepageBannerSuccess']);
            $_SESSION['addHomepageBannerError'] = "Sorry, file already exists.";
            header('Location: homepage.php');
        }
        // Allow certain file formats
        if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
        && $imageFileType != "gif" && $imageFileType != "mp3" && $imageFileType != "mp4" && $imageFileType != "wma" ) {
            unset($_SESSION['addHomepageBannerSuccess']);
            $_SESSION['addHomepageBannerError'] = "Sorry, only JPG, JPEG, PNG, GIF, MP3, MP4 & WMA files are allowed.";
            header('Location: homepage.php');
        }

        // Check file size
        if ($_FILES["image"]["size"] > 5000000) {
            unset($_SESSION['addHomepageBannerSuccess']);
            $_SESSION['addHomepageBannerError'] = "Sorry, uploads cannot be greater than 5MB.";
            header('Location: homepage.php');
        }
        
        if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
            unset($_POST['addHomepageBannerError']);
        } else { 
            unset($_SESSION['addHomepageBannerSuccess']);
            $_SESSION['addHomepageBannerError'] = "Sorry, there was an error uploading your file.";
            header('Location: homepage.php');   
        }
    } else {
        unset($_SESSION['addHomepageBannerError']);
        $target_file = $_POST['oldImage'];
    }
    
    if(!isset($_SESSION['addHomepageBannerError'])) {
        $check = "Select * from homepage where type='banner';";
        $cresult = mysqli_query($link, $check);

        if (!mysqli_query($link, $check)) {
            echo "Error description: ". mysqli_error($link);
            exit();
        } else {
            $crow = mysqli_fetch_assoc($cresult);

            if ($cresult -> num_rows != 0) {
                $faqBanner = "UPDATE homepage SET html='$target_file' where type='banner'";
            } else {
                $faqBanner = "INSERT INTO homepage (type, html) VALUES "
                        . "('banner', '$target_file');";
            }
            if (!empty($faqBanner)) {
                unset($_SESSION['updateHomepageSuccess']);
                unset($_SESSION['addHomepageSuccess']);
                unset($_SESSION['addHomepageError']);
                unset($_SESSION['updateHomepageError']);
                mysqli_query($link, $faqBanner);
                unset($_SESSION['addHomepageBannerError']);
                $_SESSION['addHomepageBannerSuccess'] = "Banner updated successfully";
                header("Location: homepage.php");
            }
        }
    }
    
} else if (isset($_POST['submit'])) {
    
    $buttonno = $_POST['buttonno'];
    
    for ($i = 1; $i <= $buttonno; $i++) {
        $linki = "type".$i;
        $linkposi = "linkpos".$i;
        $buttoni = "buttontext".$i;
        if (!empty($_POST[$buttoni]) && (empty($_POST[$linkposi]) || empty($_POST[$linki]))) {
            unset($_SESSION['addHomepageSuccess']);
            unset($_SESSION['updateHomepageError']);
            unset($_SESSION['updateHomepageSuccess']);
            unset($_SESSION['uploadHomepageError']);
            $_SESSION['addHomepageError'] = "Button text and Link position required";
            if (!empty($_POST['editid'])) {
                header("Location: homepage.php?id=".$_POST['editid']."#menu1");
            } else {
                header("Location: homepage.php#menu1");
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
            header("Location: homepage.php?id=".$_POST['editid']."#menu1");
        } else {
            header("Location: homepage.php#menu1");
        }
    } else if (empty($_POST['oldImage']) && empty($_FILES['image']['name'])) { 
        unset($_SESSION['addHomepageSuccess']);
        unset($_SESSION['updateHomepageError']);
        unset($_SESSION['updateHomepageSuccess']);
        unset($_SESSION['uploadHomepageError']);
        $_SESSION['addHomepageError'] = "No image selected";
        if (!empty($_POST['editid'])) {
            header("Location: homepage.php?id=".$_POST['editid']."#menu1");
        } else {
            header("Location: homepage.php#menu1");
        }
    } else if (!empty($_POST['html']) && empty($_POST['htmlpos'])) { 
        unset($_SESSION['addHomepageSuccess']);
        unset($_SESSION['updateHomepageError']);
        unset($_SESSION['updateHomepageSuccess']);
        unset($_SESSION['uploadHomepageError']);
        $_SESSION['addHomepageError'] = "Content position required";
        if (!empty($_POST['editid'])) {
            header("Location: homepage.php?id=".$_POST['editid']."#menu1");
        } else {
            header("Location: homepage.php#menu1");
        }
    } else if (!empty($_FILES['image']['name']) && empty($_POST['imagepos'])) { 
        unset($_SESSION['addHomepageSuccess']);
        unset($_SESSION['updateHomepageError']);
        unset($_SESSION['updateHomepageSuccess']);
        unset($_SESSION['uploadHomepageError']);
        $_SESSION['addHomepageError'] = "Image position required";
        if (!empty($_POST['editid'])) {
            header("Location: homepage.php?id=".$_POST['editid']."#menu1");
        } else {
            header("Location: homepage.php#menu1");
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
            $linki = "type".$i;
            $linkposi = "linkpos".$i;
            $buttoni = "buttontext".$i;
            
            $selLink = $_POST[$linki];
            if (strcmp($selLink, "products") === 0)  {
                $secondLink = "productstype".$i;
                $pagelink .= $selLink.".php?".$_POST[$secondLink];
            } else if (strcmp($selLink, "product") === 0) {
                $secondLink = "productItem".$i;
                $pagelink .= $selLink.".php?id=".$_POST[$secondLink];
            } else if (strcmp($selLink, "ourstory") === 0) {
                $secondLink = "ourstorytype".$i;
                $pagelink .= $selLink.".php?type=".$_POST[$secondLink];
            } else if (strcmp($selLink, "page") === 0) {
                $secondLink = "pageItem".$i;
                $pagelink .= $selLink.".php?id=".$_POST[$secondLink];
            }
            
//            $pagelink .= $_POST[$linki];
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
                        . "fieldorder='$fieldorder', type='section' where id = '$editid';";
                
                if (mysqli_query($link, $updateHomepageSql)) {
                    unset($_SESSION['addHomepageSuccess']);
                    unset($_SESSION['addHomepageError']);
                    unset($_SESSION['updateHomepageError']);
                    unset($_SESSION['editUploadHomepageError']);
                    $_SESSION['updateHomepageSuccess'] = "Record updated successfully";
                    header("Location: homepage.php#menu1");
                } else {
                    echo "Error updating record: " . mysqli_error($link);
                }
            } else {
                $advSql = "INSERT INTO homepage (title, image, imagepos, buttontext, "
                        . "link, linkpos, status, html, htmlpos, fieldorder, type) "
                    . "VALUES ('$title','$image', '$imagepos', '$buttonlink',"
                        . " '$pagelink', '$linkpos', '$status', '$html', '$htmlpos', "
                        . "'$fieldorder', 'section');";

                mysqli_query($link, $advSql);
                unset($_SESSION['uploadHomepagepageError']);
                $_SESSION['addHomepagepageSuccess'] = "Advertisement successfully added";
                header('Location: homepage.php#menu1');
            }
        }
    }
}

