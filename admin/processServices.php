<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
require_once '../config/db.php';

if (isset($_GET['delete'])) {
    $deletesql = "DELETE FROM services where id ='". $_GET['id']."'";
    if (mysqli_query($link, $deletesql)) {
        unset($_SESSION['addServSuccess']);
        unset($_SESSION['updateServError']);
        unset($_SESSION['addServError']);
        $_SESSION['updateServSuccess'] = "Record deleted successfully";
        header("Location: serviceSettings.php");
    } 
} else {
    if(empty($_POST['code']) || empty($_POST['name']) ) {
        unset($_SESSION['addServSuccess']);
        unset($_SESSION['updateServError']);
        unset($_SESSION['updateServSuccess']);
        $_SESSION['addServError'] = "Empty field(s)";
        header('Location: serviceSettings.php');
    } else {
        unset($_SESSION['addServError']);
        unset($_SESSION['updateServError']);
        unset($_SESSION['updateServSuccess']);
        
        $code = $_POST['code'];
        $name = $_POST['name'];
        $desc = htmlentities($_POST['desc']);
        
        if (!empty($_POST['editid'])) {
            $editid = $_POST['editid'];
            $updateSql = "UPDATE services SET servicecode='". $code. "', "
                    . "servicename ='" .$name. "', description='$desc' where id='". $editid. "'";

            if (mysqli_query($link, $updateSql)) {
                unset($_SESSION['addServSuccess']);
                unset($_SESSION['updateServError']);
                $_SESSION['updateServSuccess'] = "Record updated successfully";
                header("Location: serviceSettings.php");
            } else {
                echo "Error updating record: " . mysqli_error($link);
            }
        } else {
            // output data of each row
            $sql = "INSERT INTO services (servicecode, servicename, description) "
                    . "VALUES ('$code','$name', '$desc');";

            mysqli_query($link, $sql);

            $_SESSION['addServSuccess'] = "Service successfully added";
            header('Location: serviceSettings.php');
        }
    } 
}
