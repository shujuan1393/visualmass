<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
require_once '../config/db.php';

if (isset($_GET['delete'])) {
    $deletesql = "DELETE FROM categories where id ='". $_GET['id']."'";
    if (mysqli_query($link, $deletesql)) {
        unset($_SESSION['addProdCatSuccess']);
        unset($_SESSION['updateProdCatError']);
        unset($_SESSION['addProdCatError']);
        $_SESSION['updateProdCatSuccess'] = "Record deleted successfully";
        header("Location: productCatSettings.php");
    } 
} else {
    if(empty($_POST['name']) ) {
        unset($_SESSION['addProdCatSuccess']);
        unset($_SESSION['updateProdCatError']);
        unset($_SESSION['updateProdCatSuccess']);
        $_SESSION['addProdCatError'] = "Empty field(s)";
        header('Location: productCatSettings.php');
    } else {
        unset($_SESSION['addProdCatError']);
        unset($_SESSION['updateProdCatError']);
        unset($_SESSION['updateProdCatSuccess']);
        
        $name = $_POST['name'];

        if (!empty($_POST['editid'])) {
            $editid = $_POST['editid'];
            $updateSql = "UPDATE categories SET type='product', "
                    . "name ='" .$name. "' where id='". $editid. "'";

            if (mysqli_query($link, $updateSql)) {
                unset($_SESSION['addProdCatSuccess']);
                unset($_SESSION['updateProdCatError']);
                $_SESSION['updateProdCatSuccess'] = "Record updated successfully";
                header("Location: productCatSettings.php");
            } else {
                echo "Error updating record: " . mysqli_error($link);
            }
        } else {
            // output data of each row
            $sql = "INSERT INTO categories (type, name) "
                    . "VALUES ('product','$name');";

            mysqli_query($link, $sql);

            $_SESSION['addProdCatSuccess'] = "Product Category successfully added";
            header('Location: productCatSettings.php');
        }
    } 
}
