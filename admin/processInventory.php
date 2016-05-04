<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

session_start();
require '../config/db.php';

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
        if ($checkResult->num_rows != 0) {
            $pcode = $_POST['product'];
            $qty = intval($_POST['qty']);
            
            $getQty = "Select * from inventory where pid ='$pcode'";
            $qresult = mysqli_query($link, $getQty);
            $qrow = mysqli_fetch_assoc($qresult);
            $oldQty = intval($qrow['quantity']);
            $newQty = $qty + $oldQty;
            
            $addInvSql = "UPDATE inventory set quantity='$newQty'"
                    . "where pid='$pcode'";
            
            mysqli_query($link, $addInvSql);
            
            unset($_SESSION["updateInvError"]);
            $_SESSION['updateInvSuccess'] = "Inventory added successfully";
            header('Location: inventory.php');
        } 
    }
}

