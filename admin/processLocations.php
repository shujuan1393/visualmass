<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
require_once '../config/db.php';
$target_dir = "../uploads/locations/";

if (isset($_GET['delete'])) {
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
        
        $loccode = $_POST['code'];
        $locname = $_POST['name'];
        $address = $_POST['address'];
        $phone = $_POST['phone'];
        $apt = $_POST['apt'];
        $city = $_POST['city'];
        $zip = $_POST['zip'];
        $country = $_POST['country'];
        $loctype = $_POST['type'];
        $desc = htmlentities($_POST['desc']);
        $opening = htmlentities($_POST['opening']);
        $serviceArr = $_POST['services'];
        $services = "";

        for($i = 0; $i < count($serviceArr); $i++) {
            $services .= $serviceArr[$i];

            if ($i+1 !== count($serviceArr)) {
                $services.=",";
            }
        }
        
        $j = 0; //Variable for indexing uploaded image 
        $image;
        $images = '';
        
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
    //                echo "File is an image - " . $check["mime"] . ".";
                    $uploadOk = 1;
                } else {
                    unset($_SESSION['addLocSuccess']);
                    $_SESSION['uploadLocError'] = "File is not an image.";
                    if (!empty($_POST['editid'])) {
                        header('Location: locations.php?id='.$_POST['editid']);
                    } else {
                        header('Location: locations.php');
                    }
                }
            }
            // Check if file already exists
            if (file_exists($target_file)) {
                unset($_SESSION['addLocSuccess']);
                $_SESSION['uploadLocError'] = "Sorry, file already exists.";
                if (!empty($_POST['editid'])) {
                    header('Location: locations.php?id='.$_POST['editid']);
                } else {
                    header('Location: locations.php');
                }
            }
            // Check file size
            if ($_FILES["image"]["size"] > 5000000) {
                unset($_SESSION['addLocSuccess']);
                $_SESSION['uploadLocError'] = "Sorry, uploads cannot be greater than 5MB.";
                if (!empty($_POST['editid'])) {
                    header('Location: locations.php?id='.$_POST['editid']);
                } else {
                    header('Location: locations.php');
                }
            }
         
            // Allow certain file formats
            if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
            && $imageFileType != "gif" ) {
                unset($_SESSION['addLocSuccess']);
                $_SESSION['uploadLocError'] = "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
                if (!empty($_POST['editid'])) {
                    header('Location: locations.php?id='.$_POST['editid']);
                } else {
                    header('Location: locations.php');
                }
            }
            // Check if $uploadOk is set to 0 by an error
            if ($uploadOk == 1) {
                if (!move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
                    unset($_SESSION['addLocSuccess']);
                    $_SESSION['uploadLocError'] = "Sorry, there was an error uploading your file.";
                    if (!empty($_POST['editid'])) {
                        header('Location: locations.php?id='.$_POST['editid']);
                    } else {
                        header('Location: locations.php');
                    }
                } else {
                    unset($_SESSION['uploadLocError']);
                    $image = $target_file;
                }
            }
        } else {
            if (!empty($_POST['oldFeaturedImage'])) {
                $image = $_POST['oldFeaturedImage'];
            }
        }
        
        if (count($_FILES['otherimage']['name']) > 1) {
            for ($i = 0; $i < count($_FILES['otherimage']['name']); $i++) { //loop to get individual element from the array

                $validextensions = array("jpeg", "jpg", "png"); //Extensions which are allowed
                $ext = explode('.', basename($_FILES['otherimage']['name'][$i])); //explode file name from dot(.) 
                $file_extension = end($ext); //store extensions in the variable

                $target_path = $target_dir.md5(uniqid()).".".$ext[count($ext) - 1]; //set the target path with a new name of image
                $j = $j + 1; //increment the number of uploaded images according to the files in array       

                if (($_FILES["otherimage"]["size"][$i] > 5000000)) {
                    //if file size and file type was incorrect.
                    unset($_SESSION['updateLocError']);
                    unset($_SESSION['addLocSuccess']);
                    $_SESSION['uploadLocError'] = "Sorry, uploads cannot be greater than 5MB.";
                    if (isset($_POST['editid'])) {
                        header('Location: locations.php?id='.$_POST['editid']);
                    } else {
                        header('Location: locations.php');
                    }
                }  else { 
                    if (!move_uploaded_file($_FILES['otherimage']['tmp_name'][$i], $target_path)) { 
                        //if file was not moved.
                        unset($_SESSION['updateLocError']);
                        unset($_SESSION['addLocSuccess']);
                        $_SESSION['uploadLocError'] = "Could not upload your image. Please try again!";
                        if (isset($_POST['editid'])) {
                            header('Location: locations.php?id='.$_POST['editid']);
                        } else {
                            header('Location: locations.php');
                        }
                    } else { 
                        $images .= $target_path;
                        if ($i+1 !== count($_FILES['otherimage']['name'])) {
                            $images .= ",";
                        }
                        if (isset($_SESSION['addProdError'])) {
                            unset($_SESSION['addProdError']);
                        }
                    }
                }
            }
            if (!empty($_POST['oldImages'])) {
                $images .=",". $_POST['oldImages'];
            }
        } else {
            if (!empty($_POST['oldImages'])) {
                $images = $_POST['oldImages'];
            }
        }
        
        if (!isset($_SESSION['uploadLocError'])) {
            if (!empty($_POST['editid'])) {
                $editid = $_POST['editid'];
                
                $updateSql = "UPDATE locations SET name='". $locname. "', "
                . "address ='" .$address. "', phone ='".$phone."', "
                . "apt='".$apt."', city ='".$city."', description='".$desc."', "
                . "zip='".$zip."', country ='".$country."', opening ='".$opening."', "
                . "featured='".$image."', images ='".$images."', type ='".$loctype."', "
                . "services='".$services."' where id='". $editid. "'";
                
                if (mysqli_query($link, $updateSql)) {
                    unset($_SESSION['addLocSuccess']);
                    unset($_SESSION['updateLocError']);
                    unset($_SESSION['addLocError']);
                    $_SESSION['updateLocSuccess'] = "Record updated successfully";
                    header("Location: locations.php");
                } else {
                    echo "Error updating record: " . mysqli_error($link);
                }
            } else {
                unset($_SESSION['randomString']);
                // output data of each row
                $locSql = "INSERT INTO locations (code, name, address, phone, apt, city, zip"
                        . ", country, type, services, featured, images, description, opening) "
                        . "VALUES ('$loccode', '$locname', '$address', '$phone', '$apt', '$city', '$zip', '$country',"
                        . "'$loctype', '$services', '$image', '$images', '$desc', '$opening');";

                mysqli_query($link, $locSql);
                unset($_SESSION['uploadLocError']);
                $_SESSION['addLocSuccess'] = "Location successfully added";
                header('Location: locations.php');
            }
        }
    }
}

