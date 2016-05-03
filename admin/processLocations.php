<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
session_start();
require '../config/db.php';
$target_dir = "../uploads/locations/";

if (isset($_GET['edit']) && isset($_FILES['editimage'])) { 
    $random_digit=md5(uniqid(rand(), true));
    $new_file = $random_digit.basename($_FILES["editimage"]["name"]);
    $edit_target_file = $target_dir . $new_file;
    $uploadOk = 1;
    $edit_imageFileType = pathinfo($edit_target_file,PATHINFO_EXTENSION);

    // Check if image file is a actual image or fake image
    if(isset($_POST["submit"]) && !empty($_FILES['editimage']['name'])) {
        $check = getimagesize($_FILES['editimage']["tmp_name"]);
        if($check !== false) {
            $uploadOk = 1;
        } else {
            $_SESSION['editUploadLocError'] = "File is not an image.";
            header('Location: editLocation.php?id='.$_POST['editid']);
        }
    }
    // Check if file already exists
    if (file_exists($edit_target_file)) {
        $_SESSION['editUploadLocError'] = "Sorry, file already exists.";
        header('Location: editLocation.php?id='.$_POST['editid']);
    }
    // Check file size
    if ($_FILES['editimage']["size"] > 500000) {
        $_SESSION['editUploadLocError'] = "Sorry, your file is too large.";
        header('Location: editLocation.php?id='.$_POST['editid']);
    }
    // Allow certain file formats
    if($edit_imageFileType != "jpg" && $edit_imageFileType != "png" && $edit_imageFileType != "jpeg"
    && $edit_imageFileType != "gif" ) {
        $_SESSION['editUploadLocError'] = "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
        header('Location: editLocation.php?id='.$_POST['editid']);
    }
    $editid = $_POST['editid'];
    $editname = $_POST['editname'];
    $editaddress = $_POST['editaddress'];
    $editphone = $_POST['editphone'];
    $editapt = $_POST['editapt'];
    $editcity = $_POST['editcity'];
    $editzip = $_POST['editzip'];
    $editcountry = $_POST['editcountry'];
    $edittype = $_POST['edittype'];
    $editServiceArr = $_POST['editservices'];
    $editServices = "";

    for($i = 0; $i < count($editServiceArr); $i++) {
        $editServices .= $editServiceArr[$i];

        if ($i+1 !== count($editServiceArr)) {
            $editServices.=",";
        }
    }
        if (empty($_FILES['editimage']['name'])) {
            $edit_target_file = $_POST['oldImage'];
        } else {        
            if (!move_uploaded_file($_FILES['editimage']["tmp_name"], $edit_target_file)) {
                $_SESSION['editUploadLocError'] = "Sorry, there was an error uploading your file.";
                header('Location: editLocation.php?id='.$_POST['editid']);
            } 
        }
        
        $updateSql = "UPDATE locations SET name='". $editname. "', "
            . "address ='" .$editaddress. "', phone ='".$editphone."', "
            . "apt='".$editapt."', city ='".$editcity."', "
            . "zip='".$editzip."', country ='".$editcountry."', "
            . "image='".$edit_target_file."', type ='".$edittype."', "
            . "services='".$editServices."' where id='". $editid. "'";
        
        if (mysqli_query($link, $updateSql)) {
            unset($_SESSION['addLocSuccess']);
            unset($_SESSION['updateLocError']);
            unset($_SESSION['editUploadLocError']);
            $_SESSION['updateLocSuccess'] = "Record updated successfully";
            header("Location: locations.php");
        } else {
            echo "Error updating record: " . mysqli_error($link);
        }
    
} else if (isset($_GET['delete'])) {
    $deletesql = "DELETE FROM locations where id ='". $_GET['id']."'";
    if (mysqli_query($link, $deletesql)) {
        unset($_SESSION['updateLocError']);
        unset($_SESSION['addLocSuccess']);
        unset($_SESSION['addLocError']);
        
        $_SESSION['updateLocSuccess'] = "Record deleted successfully";
        header("Location: locations.php");
    } 
} else if (isset($_POST['submit'])) {
    if(empty($_POST['name']) || empty($_POST['address']) 
            || empty($_POST['phone']) || empty($_POST['city']) 
            || empty($_POST['zip']) || empty($_POST['country']) 
            || empty($_POST['services']) || empty($_POST['code']) ) {
        unset($_SESSION['addLocSuccess']);
        unset($_SESSION['updateLocError']);
        unset($_SESSION['updateLocSuccess']);
        $_SESSION['randomString'] = $_POST['code'];
        $_SESSION['addLocError'] = "Empty field(s)";
        header('Location: locations.php');
    } else {
        unset($_SESSION['addLocError']);
        unset($_SESSION['updateLocSuccess']);
        unset($_SESSION['updateLocError']);
        $target_dir = "../uploads/locations/";
        $random_digit=md5(uniqid(rand(), true));
        $new_file = $random_digit.basename($_FILES["image"]["name"]);
        $target_file = $target_dir . $new_file;
        $uploadOk = 1;
        $imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);

        // Check if image file is a actual image or fake image
        if(isset($_POST["submit"])) {
            $check = getimagesize($_FILES["image"]["tmp_name"]);
            if($check !== false) {
//                echo "File is an image - " . $check["mime"] . ".";
                $uploadOk = 1;
            } else {
                unset($_SESSION['addLocSuccess']);
                $_SESSION['uploadLocError'] = "File is not an image.";
                header('Location: locations.php');
            }
        }
        // Check if file already exists
        if (file_exists($target_file)) {
            unset($_SESSION['addLocSuccess']);
            $_SESSION['uploadLocError'] = "Sorry, file already exists.";
            header('Location: locations.php');
        }
        // Check file size
        if ($_FILES["image"]["size"] > 500000) {
            unset($_SESSION['addLocSuccess']);
            $_SESSION['uploadLocError'] = "Sorry, your file is too large.";
            header('Location: locations.php');
        }
        // Allow certain file formats
        if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
        && $imageFileType != "gif" ) {
            unset($_SESSION['addLocSuccess']);
            $_SESSION['uploadLocError'] = "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
            header('Location: locations.php');
        }
        
        $loccode = $_POST['code'];
        $locname = $_POST['name'];
        $address = $_POST['address'];
        $phone = $_POST['phone'];
        $apt = $_POST['apt'];
        $city = $_POST['city'];
        $zip = $_POST['zip'];
        $country = $_POST['country'];
        $loctype = $_POST['type'];
        $serviceArr = $_POST['services'];
        $services = "";

        for($i = 0; $i < count($serviceArr); $i++) {
            $services .= $serviceArr[$i];

            if ($i+1 !== count($serviceArr)) {
                $services.=",";
            }
        }

        $locQry = "Select * from locations where code ='". $loccode."'";
        
        $result = mysqli_query($link, $locQry);
        
        if (!mysqli_query($link,$locQry))
        {
            echo("Error description: " . mysqli_error($link));
        } else {
            // Check if $uploadOk is set to 0 by an error
            if ($uploadOk == 1) {
                if ($result->num_rows != 0) {
                    echo "loc code alr there";
                    unset($_SESSION['addLocSuccess']);
                    $_SESSION['addLocError'] = "Location code already exists";
                    header('Location: locations.php');
                } else {
                    
                    if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
                        
                        unset($_SESSION['randomString']);
                        // output data of each row
                        $locSql = "INSERT INTO locations (code, name, address, phone, apt, city, zip"
                                . ", country, image, type, services) "
                                . "VALUES ('$loccode',
                        '$locname', '$address', '$phone', '$apt', '$city', '$zip', '$country',"
                                . "'$target_file', '$loctype', '$services');";

                        mysqli_query($link, $locSql);
                        unset($_SESSION['uploadLocError']);
                        $_SESSION['addLocSuccess'] = "Location successfully added";
                        header('Location: locations.php');
                    } else {
                        unset($_SESSION['addLocSuccess']);
                        $_SESSION['uploadLocError'] = "Sorry, there was an error uploading your file.";
                        header('Location: locations.php');
                    }
                } 
            } 
        }
    }
}

