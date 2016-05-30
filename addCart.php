<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

require_once 'config/db.php';

if (isset($_GET['id']) && isset($_GET['type'])) {
    $pid = $_GET['id'];
    $type = $_GET['type'];
    $cartid = GetCartId();

    $getproduct = "Select * from products where pid='$pid'";
    $prodres = mysqli_query($link, $getproduct);

    if (!mysqli_query($link, $getproduct)) {
        echo "Error: ".mysqli_error($link);
    } else {
        $prod = mysqli_fetch_assoc($prodres);
        $price = $prod['price'];

        $query = "Select * from cart where pid='$pid' and cartid='$cartid' and type='$type';";
        $result = mysqli_query($link, $query);

        if (!mysqli_query($link, $query)) {
            echo "Error: ". mysqli_error($link);
        } else {
            $sql;
            if ($result -> num_rows === 0) {
                $sql = "INSERT into cart (pid, cartid, price, quantity, type) "
                        . "VALUES ('$pid', '$cartid', '$price', '1', '$type')";
            } else {
                $row = mysqli_fetch_assoc($result);
                $newQty = $row['quantity'] + 1;
                $sql = "UPDATE cart set quantity='$newQty' where pid='$pid' and cartid='$cartid' and type='$type';";
            }

            mysqli_query($link, $sql);
            header("Location: product.php?id=".$pid);
        }
    }
} else if (isset($_GET['delete']) && isset($_GET['id'])) {
    $delete = "DELETE FROM cart where pid='".$_GET['id']."' and cartid ='".GetCartId()."';";
    mysqli_query($link, $delete);
    header("Location: cart.php");
} else if (isset($_GET['update'])) {
    $cart = "Select * from cart where cartid='".GetCartId()."';";
    $res = mysqli_query($link, $cart);
    
    if (!mysqli_query($link, $cart)) {
        echo "Error: ".mysqli_error($link);
    } else {
        $count = $res -> num_rows;
        
        for ($i = 0; $i < $count; $i++) {
            $pid = $_POST['prod'.$i];
            $qty = $_POST['quantity'.$i];
            $sql = "UPDATE cart set quantity='$qty' where pid='$pid' and cartid='".GetCartId()."';";
            mysqli_query($link, $sql);
        }
        
        header("Location: cart.php");
    }
}