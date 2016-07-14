<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

require_once '../config/db.php';

if (isset($_GET['update'])) {
    $buttonno = $_POST['buttonno'];
    
    for ($i = 1; $i <= $buttonno; $i++) {
        $linki = "type".$i;
        $linkposi = "linkpos".$i;
        $buttoni = "buttontext".$i;
        if (!empty($_POST[$buttoni]) && (empty($_POST[$linkposi]) || empty($_POST[$linki]))) {
            unset($_SESSION['addPageSectionSuccess']);
            unset($_SESSION['updatePageSectionError']);
            unset($_SESSION['updatePageSectionSuccess']);
            unset($_SESSION['uploadPageSectionError']);
            $_SESSION['addPageSectionError'] = "Button text and Link position required";
            if (!empty($_POST['editid'])) {
                header("Location: pages.php?id=".$_POST['editid']."#menu1");
            } else {
                header('Location: pages.php#menu1');
            }
        } 
    }
    $status = $_POST['status2'];
    
    if (strcmp($status, "inactive") === 0 && (empty($_POST['date5']) || empty($_POST['scheduledtime2']))) {
        unset($_SESSION['addPageSectionSuccess']);
        unset($_SESSION['updatePageSectionError']);
        unset($_SESSION['updatePageSectionSuccess']);
        unset($_SESSION['uploadPageSectionError']);
        $_SESSION['addPageSectionError'] = "Date/time not selected for scheduled page release";
        if (!empty($_POST['editid'])) {
            header("Location: pages.php?id=".$_POST['editid']."#menu1");
        } else {
            header('Location: pages.php#menu1');
        }
    } else if(empty($_POST['title'])) {
        unset($_SESSION['addPageSectionSuccess']);
        unset($_SESSION['updatePageSectionError']);
        unset($_SESSION['updatePageSectionSuccess']);
        unset($_SESSION['uploadPageSectionError']);
        $_SESSION['addPageSectionError'] = "Empty field(s)";
        if (!empty($_POST['editid'])) {
            header("Location: pages.php?id=".$_POST['editid']."#menu1");
        } else {
            header('Location: pages.php#menu1');
        }
    } else if (empty($_POST['oldImage']) && empty($_FILES['image']['name'])) { 
        unset($_SESSION['addPageSectionSuccess']);
        unset($_SESSION['updatePageSectionError']);
        unset($_SESSION['updatePageSectionSuccess']);
        unset($_SESSION['uploadPageSectionError']);
        $_SESSION['addPageSectionError'] = "No image selected";
        if (!empty($_POST['editid'])) {
            header("Location: pages.php?id=".$_POST['editid']."#menu1");
        } else {
            header('Location: pages.php#menu1');
        }
    } else if (!empty($_POST['html']) && empty($_POST['htmlpos'])) { 
        unset($_SESSION['addPageSectionSuccess']);
        unset($_SESSION['updatePageSectionError']);
        unset($_SESSION['updatePageSectionSuccess']);
        unset($_SESSION['uploadPageSectionError']);
        $_SESSION['addPageSectionError'] = "Content position required";
        if (!empty($_POST['editid'])) {
            header("Location: pages.php?id=".$_POST['editid']."#menu1");
        } else {
            header('Location: pages.php#menu1');
        }
    } else if (!empty($_FILES['image']['name']) && empty($_POST['imagepos'])) { 
        unset($_SESSION['addPageSectionSuccess']);
        unset($_SESSION['updatePageSectionError']);
        unset($_SESSION['updatePageSectionSuccess']);
        unset($_SESSION['uploadPageSectionError']);
        $_SESSION['addPageSectionError'] = "Image position required";
        if (!empty($_POST['editid'])) {
            header("Location: pages.php?id=".$_POST['editid']."#menu1");
        } else {
            header('Location: pages.php#menu1');
        }
    } else if (!isset($_SESSION['addPageSectionError'])){
        unset($_SESSION['addPageSectionError']);
        unset($_SESSION['updatePageSectionError']);
        unset($_SESSION['updatePageSectionSuccess']);
        unset($_SESSION['uploadPageSectionError']);
        
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
        $pageid = $_POST['pageid'];
        $html = htmlentities($_POST['html']);
        $htmlpos = $_POST['htmlpos'];
        $fieldorder = $_POST['order'];
        $imagepos = $_POST['imagepos'];
        
        $scheduledate = $_POST['date5'];
        $scheduletime = $_POST['scheduledtime2'];
        $schedule = date('Y-m-d G:i:s', strtotime($scheduledate." ".$scheduletime));
        
        $image = "";
        $target_dir = "../uploads/pages/";
        
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
                    unset($_SESSION['addPageSectionSuccess']);
                    $_SESSION['uploadPageSectionError'] = "File is not an image.";
                    if (!empty($_POST['editid'])) {
                        header("Location: pages.php?id=".$_POST['editid']."#menu1");
                    } else {
                        header('Location: pages.php#menu1');
                    }
                }
            }
            // Check if file already exists
            if (file_exists($target_file)) {
                unset($_SESSION['addPageSectionSuccess']);
                $_SESSION['uploadPageSectionError'] = "Sorry, file already exists.";
                if (!empty($_POST['editid'])) {
                    header("Location: pages.php?id=".$_POST['editid']."#menu1");
                } else {
                    header('Location: pages.php#menu1');
                }
            }
            // Check file size
            if ($_FILES["image"]["size"] > 500000) {
                unset($_SESSION['addPageSectionSuccess']);
                $_SESSION['uploadPageSectionError'] = "Sorry, your file is too large.";
                if (!empty($_POST['editid'])) {
                    header("Location: pages.php?id=".$_POST['editid']."#menu1");
                } else {
                    header('Location: pages.php#menu1');
                }
            }
            // Allow certain file formats
            if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
            && $imageFileType != "gif" ) {
                unset($_SESSION['addPageSectionSuccess']);
                $_SESSION['uploadPageSectionError'] = "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
                if (!empty($_POST['editid'])) {
                    header("Location: pages.php?id=".$_POST['editid']."#menu1");
                } else {
                    header('Location: pages.php#menu1');
                }
            }
            if ($uploadOk === 1) {
                if (!move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
                    unset($_SESSION['addPageSectionSuccess']);
                    $_SESSION['uploadPageSectionError'] = "Sorry, there was an error uploading your file.";
                    if (!empty($_POST['editid'])) {
                        header("Location: pages.php?id=".$_POST['editid']."#menu1");
                    } else {
                        header('Location: pages.php#menu1');
                    }  
                } else {
                    unset($_SESSION['uploadPageSectionError']);
                    $image .= $target_file;
                }
            }
        } else {
            if (!empty($_POST['oldImage'])) {
                $image = $_POST['oldImage'];
            }
        }
        
        if (!isset($_SESSION['uploadPageSectionError'])) {
            if (!empty($_POST['editid'])) {
                $editid = $_POST['editid'];
                
                $updatePageSectionSql = "UPDATE pages SET title='$title', link='$pagelink', "
                    . "image='$image', status='$status', pageid='$pageid', html='$html', htmlpos='$htmlpos',"
                        . "imagepos='$imagepos', linkpos='$linkpos', scheduled='$schedule', buttontext='$buttonlink', "
                        . "fieldorder='$fieldorder', type='section' where id = '$editid';";
                
                if (mysqli_query($link, $updatePageSectionSql)) {
                    unset($_SESSION['addPageSectionSuccess']);
                    unset($_SESSION['addPageSectionError']);
                    unset($_SESSION['updatePageSectionError']);
                    unset($_SESSION['editUploadPageSectionError']);
                    $_SESSION['updatePageSectionSuccess'] = "Record updated successfully";
                    header("Location: pages.php#menu1");
                } else {
                    echo "Error updating record: " . mysqli_error($link);
                }
            } else {
                $advSql = "INSERT INTO pages (title, image, imagepos, buttontext, scheduled"
                        . "link, linkpos, status, html, htmlpos, fieldorder, type, pageid) "
                    . "VALUES ('$title','$image', '$imagepos', '$buttonlink', '$schedule',"
                        . " '$pagelink', '$linkpos', '$status', '$html', '$htmlpos', "
                        . "'$fieldorder', 'section', '$pageid');";

                mysqli_query($link, $advSql);
                unset($_SESSION['uploadPageSectionError']);
                $_SESSION['addPageSectionSuccess'] = "Section successfully added";
                header('Location: pages.php#menu1');
            }
        }
    }
}
