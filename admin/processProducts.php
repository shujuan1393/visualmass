<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

require_once '../config/db.php';

if (isset($_GET['delete'])) {
    $deletesql = "DELETE FROM products where pid ='". $_GET['pid']."'";
    $deleteInvsql = "DELETE FROM inventory where pid ='". $_GET['pid']."'";
    if (mysqli_query($link, $deletesql) && mysqli_query($link, $deleteInvsql)) {
        unset($_SESSION['updateProdError']);
        unset($_SESSION['addProdError']);
        $_SESSION['updateProdSuccess'] = "Record deleted successfully";
        header("Location: products.php");
    } 
} else if (isset($_POST['submitted'])) {
    $exist = $_POST['addExisting'];
    if (strcmp($exist, "yes") === 0 && (empty($_POST['code']))) {
        unset($_SESSION['addProdSuccess']);
        unset($_SESSION['updateProdError']);
        unset($_SESSION['updateProdSuccess']);
        $_SESSION['randomString'] = $_POST['code'];
        $_SESSION['addProdError'] = "Empty field(s)";
        if (isset($_POST['editid'])) {
            header('Location: products.php?id='.$_POST['editid']);
        } else {
            header('Location: products.php');
        }
    } else if(empty($_POST['name']) || empty($_POST['desc']) || empty($_POST['price']) 
            || empty($_POST['type']) || empty($_POST['tags']) || empty($_POST['gender']) 
            || empty($_POST['visibility']) || empty($_POST['availability']) 
            || empty($_POST['locations']) || empty($_POST['code'] 
                    || empty($_POST['width']) || empty($_POST['measurement'])) ) {
        unset($_SESSION['addProdSuccess']);
        unset($_SESSION['updateProdError']);
        unset($_SESSION['updateProdSuccess']);
        $_SESSION['randomString'] = $_POST['code'];
        $_SESSION['addProdError'] = "Empty field(s)";
        if (isset($_POST['editid'])) {
            header('Location: products.php?id='.$_POST['editid']);
        } else {
            header('Location: products.php');
        }
    } else if(!isset($_POST['editid']) && (empty($_FILES['featured']['name'][0]) || empty($_FILES['images']['name'][0]))) {
        unset($_SESSION['addProdSuccess']);
        unset($_SESSION['updateProdError']);
        unset($_SESSION['updateProdSuccess']);
        $_SESSION['randomString'] = $_POST['code'];
        $_SESSION['addProdError'] = "No image selected";
        if (isset($_POST['editid'])) {
            header('Location: products.php?id='.$_POST['editid']);
        } else {
            header('Location: products.php');
        }
    } else {
        unset($_SESSION["updateProdError"]);
        unset($_SESSION["updateProdSuccess"]);
        unset($_SESSION["addProdError"]);
        unset($_SESSION["addProdSuccess"]);
        
        $j = 0; //Variable for indexing uploaded image 
        $images = '';
        $featured = '';
        $target_path = "../uploads/products/"; //Declaring Path for uploaded images
        $feat_target_path = "../uploads/products/";
        
        if (count($_FILES['featured']['name']) > 1) {
            for ($i = 0; $i < count($_FILES['featured']['name']); $i++) { //loop to get individual element from the array

                $validextensions = array("jpeg", "jpg", "png"); //Extensions which are allowed
                $ext = explode('.', basename($_FILES['featured']['name'][$i])); //explode file name from dot(.) 
                $file_extension = end($ext); //store extensions in the variable

                $feat_target_path = $feat_target_path.md5(uniqid()).".".$ext[count($ext) - 1]; //set the target path with a new name of image
                
                $j = $j + 1; //increment the number of uploaded images according to the files in array       

                if (($_FILES["images"]["size"][$i] > 5000000)) {
                    //if file size and file type was incorrect.
                    unset($_SESSION['updateProdError']);
                    unset($_SESSION['addProdSuccess']);
                    $_SESSION['addProdError'] = "Sorry, uploads cannot be greater than 5MB.";
                    header('Location: products.php');
                } else if (!in_array($file_extension, $validextensions)) {
                    //if file size and file type was incorrect.
                    unset($_SESSION['updateProdError']);
                    unset($_SESSION['addProdSuccess']);
                    $_SESSION['addProdError'] = "Sorry, file uploads must be of .jpeg, .jpg or .png formats";
                    header('Location: products.php');
                } else { 
                    if (!move_uploaded_file($_FILES['featured']['tmp_name'][$i], $feat_target_path)) { 
                        //if file was not moved.
                        unset($_SESSION['updateProdError']);
                        unset($_SESSION['addProdSuccess']);
                        $_SESSION['addProdError'] = "Could not upload your image. Please try again!";
                        if (isset($_POST['editid'])) {
                            header('Location: products.php?id='.$_POST['editid']);
                        } else {
                            header('Location: products.php');
                        }
                    } else { 
                        $featured .= $feat_target_path;

                        if ($i+1 !== count($_FILES['featured']['name'])) {
                            $featured .= ",";
                        }
                        if (isset($_SESSION['addProdError'])) {
                            unset($_SESSION['addProdError']);
                        }
                    }
                }
            }
            if (!empty($_POST['oldFeaturedImages'])) {
                $featured .=",". $_POST['oldFeaturedImages'];
            }
        } else {
            if (!empty($_POST['oldFeaturedImages'])) {
                $featured =",". $_POST['oldFeaturedImages'];
            }
        }
        
        if (count($_FILES['images']['name']) > 1) {
            for ($i = 0; $i < count($_FILES['images']['name']); $i++) { //loop to get individual element from the array

                $validextensions = array("jpeg", "jpg", "png"); //Extensions which are allowed
                $ext = explode('.', basename($_FILES['images']['name'][$i])); //explode file name from dot(.) 
                $file_extension = end($ext); //store extensions in the variable

                $target_path = $target_path.md5(uniqid()).".".$ext[count($ext) - 1]; //set the target path with a new name of image
                $j = $j + 1; //increment the number of uploaded images according to the files in array       

//                if (($_FILES["images"]["size"][$i] > 100000) //Approx. 100kb files can be uploaded.
//                    || !in_array($file_extension, $validextensions)) {
//                    //if file size and file type was incorrect.
//                    unset($_SESSION['updateProdError']);
//                    unset($_SESSION['addProdSuccess']);
//                    $_SESSION['addProdError'] = "Invalid image size or type";
//                    header('Location: products.php');
//                } else { 
                    if (!move_uploaded_file($_FILES['images']['tmp_name'][$i], $target_path)) { 
                        //if file was not moved.
                        unset($_SESSION['updateProdError']);
                        unset($_SESSION['addProdSuccess']);
                        $_SESSION['addProdError'] = "Could not upload your image. Please try again!";
                        if (isset($_POST['editid'])) {
                            header('Location: products.php?id='.$_POST['editid']);
                        } else {
                            header('Location: products.php');
                        }
                    } else { 
                        $images .= $target_path;

                        if ($i+1 !== count($_FILES['images']['name'])) {
                            $images .= ",";
                        }
                        if (isset($_SESSION['addProdError'])) {
                            unset($_SESSION['addProdError']);
                        }
                    }
//                }
            }
            if (!empty($_POST['oldImages'])) {
                $images .=",". $_POST['oldImages'];
            }
        } else {
            if (!empty($_POST['oldImages'])) {
                $images =",". $_POST['oldImages'];
            }
        }
        if (!isset($_SESSION['addProdError'])) {
            
            $code = $_POST['code'];
            $name = $_POST['name'];
            $desc = htmlentities($_POST['desc']);
            $qty = $_POST['qty'];
            $price = $_POST['price'];
            $type = $_POST['type'];
            $tags = $_POST['tags'];
            $width = $_POST['width'];
            $measure1 = $_POST['measurement1'];
            $measure2 = $_POST['measurement2'];
            $measure3 = $_POST['measurement3'];
            
            $measurement = $measure1."-".$measure2."-".$measure3;
            
            if(strcmp($exist, "yes") === 0) {
                $existing = $_POST['existing'];
                $code = $existing ."-".$code;
            }
            $genderArr = $_POST['gender'];
            $gender = "";

            for($i = 0; $i < count($genderArr); $i++) {
                $gender .= $genderArr[$i];

                if ($i+1 !== count($genderArr)) {
                    $gender.=",";
                }
            }
            
            $visArr = $_POST['visibility'];
            $vis = "";

            for($i = 0; $i < count($visArr); $i++) {
                $vis .= $visArr[$i];

                if ($i+1 !== count($visArr)) {
                    $vis.=",";
                }
            }

            $avaiArr = $_POST['availability'];
            $avai = "";

            for($i = 0; $i < count($avaiArr); $i++) {
                $avai .= $avaiArr[$i];

                if ($i+1 !== count($avaiArr)) {
                    $avai.=",";
                }
            }

            $locArr = $_POST['locations'];
            $loc = "";

            for($i = 0; $i < count($locArr); $i++) {
                $loc .= $locArr[$i];

                if ($i+1 !== count($locArr)) {
                    $loc.=",";
                }
            }
            $track = $_POST['track'];
            
            if (!empty($_POST['editid'])) {
                $editcode = $_POST['editid'];
                $editproductSql = "UPDATE products set name='$name', description='$desc',"
                        . " price='$price', quantity='$qty', type='$type',"
                        . " images='$images', tags='$tags', visibility='$vis',"
                        . " availability='$avai', locations ='$loc', track='$track', gender='$gender',"
                        . "width='$width', measurement='$measurement', featured='$featured' "
                        . "where id='$editcode'";
                
                mysqli_query($link, $editproductSql);
                
                if (strcmp($track, "yes") === 0) {
                    $editinvSql = "UPDATE inventory set quantity='$qty', price='$price', "
                            . "type='$type' where pid ='$editcode'";

                    mysqli_query($link, $editinvSql);
                }
                $_SESSION['updateProdSuccess'] = "Product updated successfully";
                    header('Location: products.php');
            } else {
                $productSql = "INSERT INTO products (pid, name, description, price, quantity, type,featured, "
                        . "images, tags, visibility, availability, locations, track, gender, width, measurement) "
                        . "VALUES('$code','$name', '$desc', '$price', '$qty', '$type', '$featured', '$images', '$tags',"
                        . "'$vis', '$avai', '$loc', '$track', '$gender', '$width', '$measurement')";

                mysqli_query($link, $productSql);
                if (strcmp($track, "yes") === 0) {
                    $invSql = "INSERT INTO inventory (pid, quantity, price, type) "
                            . "VALUES('$code','$qty', '$price', '$type')";

                    mysqli_query($link, $invSql);
                }
                unset($_SESSION["updateProdError"]);
                unset($_SESSION["updateProdSuccess"]);
                $_SESSION['addProdSuccess'] = "Product added successfully";
                header('Location: products.php');
            }
        }
    }
    
}

