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
    
    $buttonno = $_POST['buttonno'];
    
    for ($i = 1; $i <= $buttonno; $i++) {
        $linki = "type".$i;
        $linkposi = "linkpos".$i;
        $buttoni = "buttontext".$i;
        if (!empty($_POST[$linki]) && (empty($_POST[$linkposi]) 
                || empty($_POST[$buttoni]))) { 
            unset($_SESSION['addAdvSuccess']);
            unset($_SESSION['updateAdvError']);
            unset($_SESSION['updateAdvSuccess']);
            unset($_SESSION['uploadAdvError']);
            $_SESSION['addAdvError'] = "Button text and Link position required";
            if (!empty($_POST['editid'])) {
                header("Location: advertisements.php?id=".$_POST['editid']);
            } else {
                header('Location: advertisements.php');
            }
        } 
    }
    
    if(empty($_POST['title']) || empty($_POST['status']) || 
            empty($_POST['visibility'])) {
        unset($_SESSION['addAdvSuccess']);
        unset($_SESSION['updateAdvError']);
        unset($_SESSION['updateAdvSuccess']);
        unset($_SESSION['uploadAdvError']);
        $_SESSION['addAdvError'] = "Empty field(s)";
        header('Location: advertisements.php');
    } else if (empty($_POST['oldImage']) && empty($_FILES['image']['name'])) { 
        unset($_SESSION['addAdvSuccess']);
        unset($_SESSION['updateAdvError']);
        unset($_SESSION['updateAdvSuccess']);
        unset($_SESSION['uploadAdvError']);
        $_SESSION['addAdvError'] = "No image selected";
        header('Location: advertisements.php');
    } else if (!empty($_POST['visibility']) && empty($_POST['minheight'])) { 
        unset($_SESSION['addAdvSuccess']);
        unset($_SESSION['updateAdvError']);
        unset($_SESSION['updateAdvSuccess']);
        unset($_SESSION['uploadAdvError']);
        $_SESSION['addAdvError'] = "Min height required";
        header('Location: advertisements.php');
    } else if (!empty($_POST['html']) && empty($_POST['htmlpos'])) { 
        unset($_SESSION['addAdvSuccess']);
        unset($_SESSION['updateAdvError']);
        unset($_SESSION['updateAdvSuccess']);
        unset($_SESSION['uploadAdvError']);
        $_SESSION['addAdvError'] = "Content position required";
        header('Location: advertisements.php');
    } else if (!empty($_FILES['image']['name']) && empty($_POST['imagepos'])) { 
        unset($_SESSION['addAdvSuccess']);
        unset($_SESSION['updateAdvError']);
        unset($_SESSION['updateAdvSuccess']);
        unset($_SESSION['uploadAdvError']);
        $_SESSION['addAdvError'] = "Image position required";
        header('Location: advertisements.php');
    } else if (!isset($_SESSION['addAdvError'])){
        unset($_SESSION['addAdvError']);
        unset($_SESSION['updateAdvError']);
        unset($_SESSION['updateAdvSuccess']);
        unset($_SESSION['uploadAdvError']);
        
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
            }
            
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
        $expiry = $_POST['expiry'];
        $start = $_POST['date3'];
        $end = $_POST['date4'];
        $html = htmlentities($_POST['html']);
        $htmlpos = $_POST['htmlpos'];
        $visArr = $_POST['visibility'];
        $vis = "";

        for($i = 0; $i < count($visArr); $i++) {
            $vis .= $visArr[$i];

            if ($i+1 !== count($visArr)) {
                $vis.=",";
            }
        }
        
        $minheight = $_POST['minheight'];
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
                    unset($_SESSION['addAdvSuccess']);
                    $_SESSION['uploadAdvError'] = "File is not an image.";
                    if (!empty($_POST['editid'])) {
                        header('Location: advertisements.php?id='.$_POST['editid']);
                    } else {
                        header('Location: advertisements.php');
                    }
                }
            }
            // Check if file already exists
            if (file_exists($target_file)) {
                unset($_SESSION['addAdvSuccess']);
                $_SESSION['uploadAdvError'] = "Sorry, file already exists.";
                if (!empty($_POST['editid'])) {
                    header('Location: advertisements.php?id='.$_POST['editid']);
                } else {
                    header('Location: advertisements.php');
                }
            }
            // Check file size
            if ($_FILES["image"]["size"] > 5000000) {
                unset($_SESSION['addAdvSuccess']);
                $_SESSION['uploadAdvError'] = "Sorry, uploads cannot be greater than 5MB.";
                if (!empty($_POST['editid'])) {
                    header('Location: advertisements.php?id='.$_POST['editid']);
                } else {
                    header('Location: advertisements.php');
                }
            }
            // Allow certain file formats
            if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
            && $imageFileType != "gif" ) {
                unset($_SESSION['addAdvSuccess']);
                $_SESSION['uploadAdvError'] = "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
                if (!empty($_POST['editid'])) {
                    header('Location: advertisements.php?id='.$_POST['editid']);
                } else {
                    header('Location: advertisements.php');
                }
            }
            if (!isset($_SESSION['uploadAdvError'])) {
                if (!move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
                    unset($_SESSION['addAdvSuccess']);
                    $_SESSION['uploadAdvError'] = "Sorry, there was an error uploading your file.";
                    if (!empty($_POST['editid'])) {
                        header('Location: advertisements.php?id='.$_POST['editid']);
                    } else {
                        header('Location: advertisements.php');
                    }
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
                    . "end='$end', expiry='$expiry', visibility='$vis',"
                        . " minheight='$minheight', html='$html', htmlpos='$htmlpos',"
                        . "imagepos='$imagepos', linkpos='$linkpos', buttontext='$buttonlink' where id = '$editid';";
                
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
                $advSql = "INSERT INTO advertisements (title, image, imagepos, buttontext, "
                        . "link, linkpos, status, expiry, start, end, visibility, "
                        . "minheight, html, htmlpos) "
                    . "VALUES ('$title','$image', '$imagepos', '$buttonlink',"
                        . " '$pagelink', '$linkpos', '$status', '$expiry', "
                        . "'$start', '$end', '$vis', '$minheight', '$html', '$htmlpos');";

                mysqli_query($link, $advSql);
                unset($_SESSION['uploadAdvError']);
                $_SESSION['addAdvSuccess'] = "Advertisement successfully added";
                header('Location: advertisements.php');
            }
        }
    }
}

