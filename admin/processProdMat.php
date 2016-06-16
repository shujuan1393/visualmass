<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
require_once '../config/db.php';

if (isset($_GET['delete'])) {
    $deletesql = "DELETE FROM materials where id ='". $_GET['id']."'";
    if (mysqli_query($link, $deletesql)) {
        unset($_SESSION['addProdMatSuccess']);
        unset($_SESSION['updateProdMatError']);
        unset($_SESSION['addProdMatError']);
        $_SESSION['updateProdMatSuccess'] = "Record deleted successfully";
        header("Location: productSettings.php#prodMat");
    } 
} else {
    if(empty($_POST['name']) || empty($_POST['details'])) {
        unset($_SESSION['addProdMatSuccess']);
        unset($_SESSION['updateProdMatError']);
        unset($_SESSION['updateProdMatSuccess']);
        $_SESSION['addProdMatError'] = "Empty field(s)";
        if (!empty($_POST['editid'])) {
            header('Location: productSettings.php?type=material&id='.$_POST['editid'].'#prodMat');
        } else {
            header('Location: productSettings.php#prodMat');
        }
    } else {
        unset($_SESSION['addProdMatError']);
        unset($_SESSION['updateProdMatError']);
        unset($_SESSION['updateProdMatSuccess']);
        
        $name = $_POST['name'];
        $details = htmlentities($_POST['details']);
        
        //upload image
        $target_dir = "../uploads/materials/";
        
        if (!empty($_FILES['image']['name'])) {
            $random_digit=md5(uniqid(rand(), true));
            $new_file = $random_digit.basename($_FILES["image"]["name"]);
            $target_file = $target_dir . $new_file;
            $imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);

            // Check if image file is a actual image or fake image
//            $check = getimagesize($_FILES["image"]["tmp_name"]);
//            if($check !== false) {
////                echo "File is an image - " . $check["mime"] . ".";
//                $uploadOk = 1;
//            } else {
//                unset($_SESSION['addProdMatSuccess']);
//                $_SESSION['uploadProdMatError'] = "File is not an image.";
//                if (!empty($_POST['editid'])) {
//                    header('Location: locations.php?id='.$_POST['editid']);
//                } else {
//                    header('Location: locations.php');
//                }
//            }
//            
            // Check if file already exists
            if (file_exists($target_file)) {
                unset($_SESSION['addProdMatSuccess']);
                $_SESSION['uploadMatError'] = "Sorry, file already exists.";
                if (!empty($_POST['editid'])) {
                    header('Location: productSettings.php?type=material&id='.$_POST['editid'].'#prodMat');
                } else {
                    header('Location: productSettings.php#prodMat');
                }
            }
            // Check file size
            if ($_FILES["image"]["size"] > 5000000) {
                unset($_SESSION['addProdMatSuccess']);
                $_SESSION['uploadProdMatError'] = "Sorry, uploads cannot be greater than 5MB.";
                if (!empty($_POST['editid'])) {
                    header('Location: productSettings.php?type=material&id='.$_POST['editid'].'#prodMat');
                } else {
                    header('Location: productSettings.php#prodMat');
                }
            }
         
            // Allow certain file formats
            if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
            && $imageFileType != "gif" ) {
                unset($_SESSION['addProdMatSuccess']);
                $_SESSION['uploadProdMatError'] = "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
                if (!empty($_POST['editid'])) {
                    header('Location: productSettings.php?type=material&id='.$_POST['editid'].'#prodMat');
                } else {
                    header('Location: productSettings.php#prodMat');
                }
            }
            // Check if $uploadOk is set to 0 by an error
            if (!isset($_SESSION['uploadProdMatError'])) {
                if (!move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
                    unset($_SESSION['addProdMatSuccess']);
                    $_SESSION['uploadProdMatError'] = "Sorry, there was an error uploading your file.";
                    if (!empty($_POST['editid'])) {
                        header('Location: productSettings.php?type=material&id='.$_POST['editid'].'#prodMat');
                    } else {
                        header('Location: productSettings.php#prodMat');
                    }
                } else {
                    unset($_SESSION['uploadProdMatError']);
                    $image = $target_file;
                }
            }
        } else {
            if (!empty($_POST['oldImage'])) {
                $image = $_POST['oldImage'];
            }
        }
        
        if (!empty($_POST['editid'])) {
            $editid = $_POST['editid'];
            $updateSql = "UPDATE materials SET name ='" .$name. "', details='$details',"
                    . "image='$image' where id='". $editid. "'";

            if (mysqli_query($link, $updateSql)) {
                unset($_SESSION['addProdMatSuccess']);
                unset($_SESSION['updateProdMatError']);
                $_SESSION['updateProdMatSuccess'] = "Record updated successfully";
            } else {
                echo "Error updating record: " . mysqli_error($link);
            }
        } else {
            // output data of each row
            $sql = "INSERT INTO materials (details, name, image) "
                    . "VALUES ('$details','$name', '$image');";

            mysqli_query($link, $sql);

            $_SESSION['addProdMatSuccess'] = "Product Material successfully added";
        }
        header("Location: productSettings.php#prodMat");
    } 
}
