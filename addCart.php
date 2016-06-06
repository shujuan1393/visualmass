<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

require_once 'config/db.php';

if (isset($_GET['delete']) && isset($_GET['id'])) {
    $idArr = explode("-", $_GET['id']);
    $delete = "DELETE FROM cart where pid='".$idArr[1]."' and type='".$idArr[0]."' and cartid ='".GetCartId()."';";
    
    mysqli_query($link, $delete);
    header("Location: cart.php");
} else if (isset($_GET['id']) && isset($_GET['type'])) {
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
            echo "<script>window.history.back();</script>";
//            header("Location: product.php?id=".$pid);
        }
    }
} else if (isset($_GET['update'])) {
    $cart = "Select * from cart where cartid='".GetCartId()."';";
    $res = mysqli_query($link, $cart);
    
    if (!mysqli_query($link, $cart)) {
        echo "Error: ".mysqli_error($link);
    } else {
        $count = $res -> num_rows;
        
        for ($i = 0; $i < $count; $i++) {
            $id = $_POST['id'.$i];
            $pid = $_POST['prod'.$i];
            $qty = $_POST['quantity'.$i];
            $type = $_POST['type'.$i];
            $colour = $_POST['colour'.$i];
            $colpos = strpos($colour, "giftcard");
            
            if (is_numeric($colpos)) {
                $sql = "UPDATE cart set quantity='$qty', type ='$colour' where pid='$pid' and cartid='".GetCartId()."' and type='$type' and id='$id';";
            } else {
                $sql = "UPDATE cart set quantity='$qty', pid ='$colour' where pid='$pid' and cartid='".GetCartId()."' and type='$type' and id='$id';";
            }
            
            mysqli_query($link, $sql);
        }
        
        header("Location: cart.php");
    }
} else if (isset($_GET['card'])) {
    $type = $_POST['selectedType'];
    $code = $_POST['selectedAmount'];
    $date = $_POST['selectedDate'];
    $rname = $_POST['recipientname'];
    $yname = $_POST['yourname'];
    $remail = $_POST['email'];
    $msg = $_POST['yourmessage'];
    
    if(empty($type) || empty($code) || empty($date) || empty($rname) || empty($yname) 
            || empty($remail) || empty($msg)) {
        $_SESSION['giftcardError'] = "Empty field(s)";
        header("Location: giftcard.php#start");
    } else if (!filter_var($remail, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['giftcardError'] = "Invalid email";  
        header("Location: giftcard.php#start");      
    } else {
        $cartid = GetCartId();
        $details = $rname.",".$yname.",".$remail.",".$msg;
        unset($_SESSION['giftcardError']);
        $giftcard = "Select * from giftcards where code='$code';";
        $gres = mysqli_query($link, $giftcard);
        
        if(!mysqli_query($link, $giftcard)) {
            echo "Error: ".mysqli_error($link);
        } else {
            $grow = mysqli_fetch_assoc($gres);
            $price = $grow['amount'];
            $gifttype = $type."@giftcard";
            $query = "Select * from cart where pid='$code' and cartid='$cartid' and type='$gifttype' and details='$details' and datetime='$date';";
            $result = mysqli_query($link, $query);

            if (!mysqli_query($link, $query)) {
                echo "Error: ". mysqli_error($link);
            } else {
                $sql;
                if ($result -> num_rows === 0) {
                    $sql = "INSERT into cart (pid, cartid, price, quantity, type, details, datetime) "
                            . "VALUES ('$code', '$cartid', '$price', '1', '$gifttype', '$details', '$date')";
                } else {
                    $row = mysqli_fetch_assoc($result);
                    $newQty = $row['quantity'] + 1;
                    $sql = "UPDATE cart set quantity='$newQty', details = '$details' where pid='$code' and "
                            . "cartid='$cartid' and type='$gifttype' and datetime='$date';";
                }
                
                mysqli_query($link, $sql);
                header("Location: giftcard.php");
            }
        }
    }
}