<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

session_start();
require '../config/db.php';

if (isset($_GET['edit'])) { 
    $j = 0; //Variable for indexing uploaded image 
    $editimages = '';
    $target_path = "../uploads/products/"; //Declaring Path for uploaded images
    for ($i = 0; $i < count($_FILES['editimages']['name']); $i++) { //loop to get individual element from the array

        $validextensions = array("jpeg", "jpg", "png"); //Extensions which are allowed
        $ext = explode('.', basename($_FILES['editimages']['name'][$i])); //explode file name from dot(.) 
        $file_extension = end($ext); //store extensions in the variable

        $target_path = $target_path.md5(uniqid()).
        ".".$ext[count($ext) - 1]; //set the target path with a new name of image
        $j = $j + 1; //increment the number of uploaded images according to the files in array       

        if (($_FILES["editimages"]["size"][$i] > 100000) //Approx. 100kb files can be uploaded.
            || !in_array($file_extension, $validextensions)) {
            //if file size and file type was incorrect.
            unset($_SESSION['updateProdError']);
            unset($_SESSION['addProdSuccess']);
            $_SESSION['editUploadProdError'] = "Invalid image size or type";
            header('Location: editProduct.php?id='.$_POST['editid']);
        } else { 
            if (!move_uploaded_file($_FILES['editimages']['tmp_name'][$i], $target_path)) { 
                //if file was not moved.
                unset($_SESSION['updateProdError']);
                unset($_SESSION['addProdSuccess']);
                unset($_SESSION['addProdError']);
                $_SESSION['editUploadProdError'] = "Could not upload your image. Please try again!";
                header('Location: editProduct.php?id='.$_POST['editid']);
            } else { 
                $editimages .= $target_path;

                if ($i+1 !== count($_FILES['editimages']['name'])) {
                    $editimages .= ",";
                }
                if (isset($_SESSION['editUploadProdError'])) {
                    unset($_SESSION['editUploadProdError']);
                }
            }
        }
    }
    if (!isset($_SESSION['editUploadProdError'])) {
        $editimages.= ",".$_POST['oldImage'];
        $editcode = $_POST['editcode'];
        $editname = $_POST['editname'];
        $editdesc = $_POST['editdesc'];
        $editqty = $_POST['editqty'];
        $editprice = $_POST['editprice'];
        $edittype = $_POST['edittype'];
        $edittags = $_POST['edittags'];
        $editvisArr = $_POST['editvisibility'];
        $editvis = "";

        for($i = 0; $i < count($editvisArr); $i++) {
            $editvis .= $editvisArr[$i];

            if ($i+1 !== count($editvisArr)) {
                $editvis.=",";
            }
        }

        $editavaiArr = $_POST['editavailability'];
        $editavai = "";

        for($i = 0; $i < count($editavaiArr); $i++) {
            $editavai .= $editavaiArr[$i];

            if ($i+1 !== count($editavaiArr)) {
                $editavai.=",";
            }
        }

        $editlocArr = $_POST['editlocations'];
        $editloc = "";

        for($i = 0; $i < count($editlocArr); $i++) {
            $editloc .= $editlocArr[$i];

            if ($i+1 !== count($editlocArr)) {
                $editloc.=",";
            }
        }

        $editproductSql = "UPDATE products set name='$editname', description='$editdesc',"
                . " price='$editprice', quantity='$editqty', type='$edittype',"
                . " images='$editimages', tags='$edittags', visibility='$editvis',"
                . " availability='$editavai', locations ='$editloc' "
                . "where pid='$editcode'";

        mysqli_query($link, $editproductSql);

        $editinvSql = "UPDATE inventory set quantity='$editqty', price='$editprice', "
                . "type='$edittype' where pid ='$editcode'";

        mysqli_query($link, $editinvSql);
        unset($_SESSION["updateProdError"]);
        unset($_SESSION["addProdError"]);
        unset($_SESSION["addProdSuccess"]);
        $_SESSION['updateProdSuccess'] = "Product updated successfully";
            header('Location: products.php');

    }
} else if (isset($_GET['delete'])) {
    $deletesql = "DELETE FROM products where pid ='". $_GET['pid']."'";
    $deleteInvsql = "DELETE FROM inventory where pid ='". $_GET['pid']."'";
    if (mysqli_query($link, $deletesql) && mysqli_query($link, $deleteInvsql)) {
        unset($_SESSION['updateProdError']);
        unset($_SESSION['addProdError']);
        $_SESSION['updateProdSuccess'] = "Record deleted successfully";
        header("Location: products.php");
    } 
} else if (isset($_POST['submit'])) {
    if(empty($_POST['name']) || empty($_POST['desc']) 
            || empty($_POST['qty']) || empty($_POST['price']) 
            || empty($_POST['type']) || empty($_POST['tags']) 
            || empty($_POST['visibility']) || empty($_POST['availability']) 
            || empty($_POST['locations']) || empty($_POST['code']) ) {
        unset($_SESSION['addProdSuccess']);
        unset($_SESSION['updateProdError']);
        unset($_SESSION['updateProdSuccess']);
        $_SESSION['randomString'] = $_POST['code'];
        $_SESSION['addProdError'] = "Empty field(s)";
        header('Location: products.php');
    } else {
        $checkSql = "Select * from products where pid = '" . $_POST['code'] . "'";
        $checkResult = mysqli_query($link, $checkSql);
        if ($checkResult->num_rows != 0) {
            unset($_SESSION['addProdSuccess']);
            $_SESSION['addProdError'] = "Product code already exists";
            header('Location: products.php');
        } else {
            $j = 0; //Variable for indexing uploaded image 
            $images = '';
            $target_path = "../uploads/products/"; //Declaring Path for uploaded images
            for ($i = 0; $i < count($_FILES['images']['name']); $i++) { //loop to get individual element from the array

                $validextensions = array("jpeg", "jpg", "png"); //Extensions which are allowed
                $ext = explode('.', basename($_FILES['images']['name'][$i])); //explode file name from dot(.) 
                $file_extension = end($ext); //store extensions in the variable

                $target_path = $target_path.md5(uniqid()).
                ".".$ext[count($ext) - 1]; //set the target path with a new name of image
                $j = $j + 1; //increment the number of uploaded images according to the files in array       

                if (($_FILES["images"]["size"][$i] > 100000) //Approx. 100kb files can be uploaded.
                    || !in_array($file_extension, $validextensions)) {
                    //if file size and file type was incorrect.
                    unset($_SESSION['updateProdError']);
                    unset($_SESSION['addProdSuccess']);
                    $_SESSION['addProdError'] = "Invalid image size or type";
                    header('Location: products.php');
                } else { 
                    if (!move_uploaded_file($_FILES['images']['tmp_name'][$i], $target_path)) { 
                        //if file was not moved.
                        unset($_SESSION['updateProdError']);
                        unset($_SESSION['addProdSuccess']);
                        $_SESSION['addProdError'] = "Could not upload your image. Please try again!";
                        header('Location: products.php');
                    } else { 
                        $images .= $target_path;

                        if ($i+1 !== count($_FILES['images']['name'])) {
                            $images .= ",";
                        }
                        if (isset($_SESSION['addProdError'])) {
                            unset($_SESSION['addProdError']);
                        }
                    }
                }
            }
            if (!isset($_SESSION['addProdError'])) {
                $code = $_POST['code'];
                $name = $_POST['name'];
                $desc = $_POST['desc'];
                $qty = $_POST['qty'];
                $price = $_POST['price'];
                $type = $_POST['type'];
                $tags = $_POST['tags'];
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

                $productSql = "INSERT INTO products (pid, name, description, price, quantity, type,"
                        . "images, tags, visibility, availability, locations) VALUES('$code',"
                        . "'$name', '$desc', '$price', '$qty', '$type', '$images', '$tags',"
                        . "'$vis', '$avai', '$loc')";

                mysqli_query($link, $productSql);

                $invSql = "INSERT INTO inventory (pid, quantity, price, type) "
                        . "VALUES('$code','$qty', '$price', '$type')";

                mysqli_query($link, $invSql);
                unset($_SESSION["updateProdError"]);
                unset($_SESSION["updateProdSuccess"]);
                $_SESSION['addProdSuccess'] = "Product added successfully";
                header('Location: products.php');
            }
        }
    }
}

