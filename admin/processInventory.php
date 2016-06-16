<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

require_once '../config/db.php';

if (isset($_GET['edit'])) { 
    $editcode = $_POST['editcode'];
    $editqty = $_POST['editqty'];
    $editprice = $_POST['editprice'];

    $editInvSql = "UPDATE inventory set price='$editprice', quantity='$editqty'"
            . "where pid='$editcode'";

    mysqli_query($link, $editInvSql);

    unset($_SESSION["updateInvError"]);
    $_SESSION['updateInvSuccess'] = "Inventory updated successfully";
    header('Location: inventory.php');
} else if (isset($_POST['submit'])) {
    if(empty($_POST['qty'])) {
        unset($_SESSION['updateInvSuccess']);
        $_SESSION['updateInvError'] = "Empty field(s)";
        header('Location: inventory.php');
    } else {
        $checkSql = "Select * from inventory where pid = '" . $_POST['product'] . "'";
        $checkResult = mysqli_query($link, $checkSql);
        
        $pcode = $_POST['product'];
        $loc = $_POST['location'];
        $price = $_POST['price'];
        $qty = intval($_POST['qty']);
        
        $getType = "Select * from products where pid ='$pcode';";
        $res = mysqli_query($link, $getType);
        
        if (!mysqli_query($link, $getType)) {
            die(mysqli_error($link));
        } else {
            $prow = mysqli_fetch_assoc($res);
            $type = $prow['type'];
        }
        
        if ($checkResult->num_rows != 0) {
            
            $qrow = mysqli_fetch_assoc($checkResult);
            $oldQty = intval($qrow['quantity']);
            $location = $qrow['location'];
            $newQty = $qty + $oldQty;
            
            $addInvSql = "UPDATE inventory set quantity='$newQty', price='$price', type='$type' "
                    . "where pid='$pcode' and location ='$location'";
        } else {
            $addInvSql = "INSERT INTO inventory (pid, location, quantity, price, type) VALUES"
                    . "('$pcode','$loc', '$qty', '$price', '$type');";
        }
        
        mysqli_query($link, $addInvSql);
            
        unset($_SESSION["updateInvError"]);
        $_SESSION['updateInvSuccess'] = "Inventory added successfully";
        header('Location: inventory.php');
    }
}

