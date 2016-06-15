<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
require_once '../config/db.php';
$target_dir = "../uploads/banner/";

if (isset($_GET['delete']) && isset($_GET['banner'])) {
    $deletesql = "DELETE FROM productbanner where id ='". $_GET['id']."'";
    if (mysqli_query($link, $deletesql)) {
        unset($_SESSION['updateProdDescBannerError']);
        unset($_SESSION['addProdDescBannerSuccess']);
        unset($_SESSION['addProdDescBannerError']);
        unset($_SESSION['uploadProdDescBannerError']);
        $_SESSION['updateProdDescBannerSuccess'] = "Record deleted successfully";
        header("Location: productdesc.php");
    } 
} else if (isset($_GET['banner'])) { 
    unset($_SESSION['addProdDescBannerError']);
    $target_dir = "../uploads/banner/";
    $random_digit=md5(uniqid(rand(), true));
    
    if (empty($_FILES['image']['name']) && empty($_POST['oldImage'])) { 
        unset($_SESSION['updateProdDescError']);
        unset($_SESSION['addProdDescSuccess']);
        unset($_SESSION['addProdDescError']);
        unset($_SESSION['updateProdDescSuccess']);
        unset($_SESSION['addProdDescBannerSuccess']);
        $_SESSION['addProdDescBannerError'] = "No file selected";
        header("Location: productdesc.php");
    } else if (!empty($_FILES['image']['name'])) {
        $new_file = 'product_'.$random_digit.basename($_FILES["image"]["name"]);
        $target_file = $target_dir . $new_file;
        $uploadOk = 1;

        $imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
        
        // Check if file already exists
        if (file_exists($target_file)) {
            unset($_SESSION['addProdDescBannerSuccess']);
            $_SESSION['addProdDescBannerError'] = "Sorry, file already exists.";
            header('Location: productdesc.php');
        }
        // Allow certain file formats
        if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
        && $imageFileType != "gif" && $imageFileType != "mp3" && $imageFileType != "mp4" && $imageFileType != "wma" ) {
            unset($_SESSION['addProdDescBannerSuccess']);
            $_SESSION['addProdDescBannerError'] = "Sorry, only JPG, JPEG, PNG, GIF, MP3, MP4 & WMA files are allowed.";
            header('Location: productdesc.php');
        }

        // Check file size
        if ($_FILES["image"]["size"] > 5000000) {
            unset($_SESSION['addProdDescBannerSuccess']);
            $_SESSION['addProdDescBannerError'] = "Sorry, uploads cannot be greater than 5MB.";
            header('Location: productdesc.php');
        }
        
        if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
            unset($_POST['addProdDescBannerError']);
        } else { 
            unset($_SESSION['addProdDescBannerSuccess']);
            $_SESSION['addProdDescBannerError'] = "Sorry, there was an error uploading your file.";
            header('Location: productdesc.php');   
        }
    } else {
        unset($_SESSION['addProdDescBannerError']);
        $target_file = $_POST['oldImage'];
    }
    
    if(!isset($_SESSION['addProdDescBannerError'])) {
        $type = $_POST['categories'];
        $check = "Select * from productbanner where gender='all' and categories='$type';";
        $cresult = mysqli_query($link, $check);

        if (!mysqli_query($link, $check)) {
            echo "Error description: ". mysqli_error($link);
            exit();
        } else {
            $crow = mysqli_fetch_assoc($cresult);

            if ($cresult -> num_rows != 0) {
                $faqBanner = "UPDATE productbanner SET image='$target_file' where categories='$type'";
            } else {
                $faqBanner = "INSERT INTO productbanner (image, categories, gender) VALUES "
                        . "('$target_file', '$type', 'all');";
            }
            if (!empty($faqBanner)) {
                unset($_SESSION['updateProdDescSuccess']);
                unset($_SESSION['addProdDescSuccess']);
                unset($_SESSION['addProdDescError']);
                unset($_SESSION['updateProdDescError']);
                mysqli_query($link, $faqBanner);
                unset($_SESSION['addProdDescBannerError']);
                $_SESSION['addProdDescBannerSuccess'] = "Banner updated successfully";
                header("Location: productdesc.php");
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
            unset($_SESSION['addProdDescSuccess']);
            unset($_SESSION['updateProdDescError']);
            unset($_SESSION['updateProdDescSuccess']);
            unset($_SESSION['uploadProdDescError']);
            $_SESSION['addProdDescError'] = "Button text and Link position required";
            if (!empty($_POST['editid'])) {
                header("Location: productdesc.php?id=".$_POST['editid']."#menu1");
            } else {
                header('Location: productdesc.php#menu1');
            }
        } 
    }
    
//    if (isset($_SESSION['addProdDescError'])) {
//        echo $_SESSION['addProdDescError'];
//    } else {
//        echo "Here";
//    }
//    exit();
    
    if(empty($_POST['title']) || empty($_POST['status'])) {
        unset($_SESSION['addProdDescSuccess']);
        unset($_SESSION['updateProdDescError']);
        unset($_SESSION['updateProdDescSuccess']);
        unset($_SESSION['uploadProdDescError']);
        $_SESSION['addProdDescError'] = "Empty field(s)";
        if (!empty($_POST['editid'])) {
            header("Location: productdesc.php?id=".$_POST['editid']."#menu1");
        } else {
            header('Location: productdesc.php#menu1');
        }
    } else if (empty($_POST['oldImage']) && empty($_FILES['image']['name'])) { 
        unset($_SESSION['addProdDescSuccess']);
        unset($_SESSION['updateProdDescError']);
        unset($_SESSION['updateProdDescSuccess']);
        unset($_SESSION['uploadProdDescError']);
        $_SESSION['addProdDescError'] = "No image selected";
        if (!empty($_POST['editid'])) {
            header("Location: productdesc.php?id=".$_POST['editid']."#menu1");
        } else {
            header('Location: productdesc.php#menu1');
        }
    } else if (!empty($_POST['html']) && empty($_POST['htmlpos'])) { 
        unset($_SESSION['addProdDescSuccess']);
        unset($_SESSION['updateProdDescError']);
        unset($_SESSION['updateProdDescSuccess']);
        unset($_SESSION['uploadProdDescError']);
        $_SESSION['addProdDescError'] = "Content position required";
        if (!empty($_POST['editid'])) {
            header("Location: productdesc.php?id=".$_POST['editid']."#menu1");
        } else {
            header('Location: productdesc.php#menu1');
        }
    } else if (!empty($_FILES['image']['name']) && empty($_POST['imagepos'])) { 
        unset($_SESSION['addProdDescSuccess']);
        unset($_SESSION['updateProdDescError']);
        unset($_SESSION['updateProdDescSuccess']);
        unset($_SESSION['uploadProdDescError']);
        $_SESSION['addProdDescError'] = "Image position required";
        if (!empty($_POST['editid'])) {
            header("Location: productdesc.php?id=".$_POST['editid']."#menu1");
        } else {
            header('Location: productdesc.php#menu1');
        }
    } else if (!isset($_SESSION['addProdDescError'])){
        unset($_SESSION['addProdDescError']);
        unset($_SESSION['updateProdDescError']);
        unset($_SESSION['updateProdDescSuccess']);
        unset($_SESSION['uploadProdDescError']);
        
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
        $type = $_POST['type'];
                
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
                    unset($_SESSION['addProdDescSuccess']);
                    $_SESSION['uploadProdDescError'] = "File is not an image.";
                    if (!empty($_POST['editid'])) {
                        header("Location: productdesc.php?id=".$_POST['editid']."#menu1");
                    } else {
                        header('Location: productdesc.php#menu1');
                    }
                }
            }
            // Check if file already exists
            if (file_exists($target_file)) {
                unset($_SESSION['addProdDescSuccess']);
                $_SESSION['uploadProdDescError'] = "Sorry, file already exists.";
                if (!empty($_POST['editid'])) {
                    header("Location: productdesc.php?id=".$_POST['editid']."#menu1");
                } else {
                    header('Location: productdesc.php#menu1');
                }
            }
            // Check file size
            if ($_FILES["image"]["size"] > 500000) {
                unset($_SESSION['addProdDescSuccess']);
                $_SESSION['uploadProdDescError'] = "Sorry, your file is too large.";
                if (!empty($_POST['editid'])) {
                    header("Location: productdesc.php?id=".$_POST['editid']."#menu1");
                } else {
                    header('Location: productdesc.php#menu1');
                }
            }
            // Allow certain file formats
            if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
            && $imageFileType != "gif" ) {
                unset($_SESSION['addProdDescSuccess']);
                $_SESSION['uploadProdDescError'] = "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
                if (!empty($_POST['editid'])) {
                    header("Location: productdesc.php?id=".$_POST['editid']."#menu1");
                } else {
                    header('Location: productdesc.php#menu1');
                }
            }
            if ($uploadOk === 1) {
                if (!move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
                    unset($_SESSION['addProdDescSuccess']);
                    $_SESSION['uploadProdDescError'] = "Sorry, there was an error uploading your file.";
                    if (!empty($_POST['editid'])) {
                        header("Location: productdesc.php?id=".$_POST['editid']."#menu1");
                    } else {
                        header('Location: productdesc.php#menu1');
                    } 
                } else {
                    unset($_SESSION['uploadProdDescError']);
                    $image .= $target_file;
                }
            }
        } else {
            if (!empty($_POST['oldImage'])) {
                $image = $_POST['oldImage'];
            }
        }
        
        if (!isset($_SESSION['uploadProdDescError'])) {
            if (!empty($_POST['editid'])) {
                $editid = $_POST['editid'];
                
                $updateProdDescSql = "UPDATE productdescription SET title='$title', link='$pagelink', "
                    . "image='$image', status='$status', html='$html', htmlpos='$htmlpos',"
                        . "imagepos='$imagepos', linkpos='$linkpos', buttontext='$buttonlink', "
                        . "fieldorder='$fieldorder', type='$type' where id = '$editid';";
                
                if (mysqli_query($link, $updateProdDescSql)) {
                    unset($_SESSION['addProdDescSuccess']);
                    unset($_SESSION['addProdDescError']);
                    unset($_SESSION['updateProdDescError']);
                    unset($_SESSION['editUploadProdDescError']);
                    $_SESSION['updateProdDescSuccess'] = "Record updated successfully";
                    header('Location: productdesc.php#menu1');
                } else {
                    echo "Error updating record: " . mysqli_error($link);
                }
            } else {
                $advSql = "INSERT INTO productdescription (title, image, imagepos, buttontext, "
                        . "link, linkpos, status, html, htmlpos, fieldorder, type) "
                    . "VALUES ('$title','$image', '$imagepos', '$buttonlink',"
                        . " '$pagelink', '$linkpos', '$status', '$html', '$htmlpos', "
                        . "'$fieldorder', '$type');";

                mysqli_query($link, $advSql);
                unset($_SESSION['uploadProdDescpageError']);
                $_SESSION['addProdDescpageSuccess'] = "Advertisement successfully added";
                header('Location: productdesc.php#menu1');
            }
        }
    }
}

