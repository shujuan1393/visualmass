<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
require_once '../config/db.php';


if(isset($_POST['submit'])) {
    $visibility = $_POST['visibility'];
    $time = $_POST['duration'];
    $amt = $_POST['amount'];
    
    $val = "visibility=".$visibility."&";
    $val .= "duration=".$time."&";
    $val .= "amount=".$amt;
    
    $checkSql = "Select * from settings where type='homeTryon'";
    $result = mysqli_query($link, $checkSql);
    
    if (!mysqli_query($link,$checkSql)) {
        echo("Error description: " . mysqli_error($link));
    } else {
        $sql = "";
        if ($result->num_rows == 0) {
            $sql = "INSERT INTO settings (type, value) VALUES"
                    . "('homeTryon','$val')";
        } else {
            $sql = "Update settings set value='".$val."' where type='homeTryon'";
        }
        if (mysqli_query($link, $sql)) {
            $_SESSION['updateHTSetSuccess'] = "Changes saved successfully";
            header("Location: homeTrySettings.php");
        } else {
            echo "Error updating record: " . mysqli_error($link);
        }

    }
}
?>
