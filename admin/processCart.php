<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

require_once '../config/db.php';

if (isset($_GET['delete']) && isset($_GET['id'])) {
    $idArr = explode("/", $_GET['id']);
    $delete = "DELETE FROM cart where pid='".$idArr[1]."' and type='".$idArr[0]."' and cartid ='".$_SESSION['loggedUserEmail']."';";
    
    mysqli_query($link, $delete);
    header("Location: cart.php");
} else if (isset($_POST['submitted'])) {
    $qty = $_POST['quantity'];
    $lens = $_POST['lens'];
    $pid = $_POST['colour'];
    $type = $_POST['type'];
    $email = $_SESSION['loggedUserEmail'];
    $loc = $_SESSION['curStore'];
    
    $price = $_POST['price'];
    
    $getprod = "Select * from cart where cartid = '$email' and pid ='$pid' and type='$type' and location ='$loc';";
    $cartres = mysqli_query($link, $getprod);
    
    $pStats;
    if (strcmp($type, "purchase") === 0) {
        $pStats = "cartpurchase";
    } else if (strcmp($type, "hometry") === 0){
        $pStats = "carttry";
    } else {
        $pStats = "giftcard";
    }
    
    if (!mysqli_query($link, $getprod)) {
        die(mysqli_error($link));
    } else {
        if ($cartres -> num_rows === 0) {
            $cartsql = "INSERT into cart (pid, cartid, quantity, type, lens, location, price) "
                                . "VALUES ('$pid', '".$email."', '$qty', '$type', '$lens', '$loc', '$price')";
        } else {
            $row = mysqli_fetch_assoc($cartres);
            $newqty = intval($row['quantity']) + intval($qty);
            $cartsql = "UPDATE cart set quantity='$newqty', price='$price' where cartid='$email' and pid='$pid' and location ='$loc' and type='$type' and lens ='$lens';";
        }
        mysqli_query($link, $cartsql);
        
        //add to statistics
        $stats = "INSERT INTO productstatistics (type, pid, customer) VALUES "
            . "('$pStats', '$pid', '$email');";
        mysqli_query($link, $stats); 
        
        $_SESSION['addCartSuccess'] = "yes";
        
        header("Location: addCart.php");
    }
} else if (isset($_GET['update'])) {
    $cart = "Select * from cart where cartid='".$_SESSION['loggedUserEmail']."';";
    $res = mysqli_query($link, $cart);
    
    if (!mysqli_query($link, $cart)) {
        echo "Error: ".mysqli_error($link);
    } else {
        $count = $res -> num_rows;
        $cartid = $_SESSION['loggedUserEmail'];
        
        for ($i = 0; $i < $count; $i++) {
            $id = $_POST['id'.$i];
            $pid = $_POST['prod'.$i];
            $qty = $_POST['quantity'.$i];
            $type = $_POST['type'.$i];
            $colour = $_POST['colour'.$i];
            $colpos = strpos($colour, "giftcard");
            
            if (is_numeric($colpos)) {
                $sql = "UPDATE cart set quantity='$qty', type ='$colour' where pid='$pid' and cartid='".$_SESSION['loggedUserEmail']."' and type='$type' and id='$id';";
            } else {
                $sql = "UPDATE cart set quantity='$qty', pid ='$colour' where pid='$pid' and cartid='".$_SESSION['loggedUserEmail']."' and type='$type' and id='$id';";
            }
            
            $pStats;
            if (strcmp($type, "purchase") === 0) {
                $pStats = "cartpurchase";
            } else if (strcmp($type, "hometry") === 0){
                $pStats = "carttry";
            } else {
                $pStats = "giftcard";
            }
            
            //add to statistics (every time a product is added to cart)
            $stats = "INSERT INTO productstatistics (type, pid, customer) VALUES "
                . "('$pStats', '$pid', '$cartid');";
            mysqli_query($link, $stats);   
            
            mysqli_query($link, $sql);
        }
        
        header("Location: cart.php");
    }
} 
//else if (isset($_GET['card'])) {
//    $type = $_POST['selectedType'];
//    $code = $_POST['selectedAmount'];
//    $date = $_POST['selectedDate'];
//    $rname = $_POST['recipientname'];
//    $yname = $_POST['yourname'];
//    $remail = $_POST['email'];
//    $msg = $_POST['yourmessage'];
//    
//    if(empty($type) || empty($code) || empty($date) || empty($rname) || empty($yname) 
//            || empty($remail) || empty($msg)) {
//        $_SESSION['giftcardError'] = "Empty field(s)";
//        header("Location: giftcard.php#start");
//    } else if (!filter_var($remail, FILTER_VALIDATE_EMAIL)) {
//        $_SESSION['giftcardError'] = "Invalid email";  
//        header("Location: giftcard.php#start");      
//    } else {
//        $cartid = $_SESSION['loggedUserEmail'];
//        $details = $rname.",".$yname.",".$remail.",".$msg;
//        unset($_SESSION['giftcardError']);
//        $giftcard = "Select * from giftcards where code='$code';";
//        $gres = mysqli_query($link, $giftcard);
//        
//        if(!mysqli_query($link, $giftcard)) {
//            echo "Error: ".mysqli_error($link);
//        } else {
//            $grow = mysqli_fetch_assoc($gres);
//            $price = $grow['amount'];
//            $gifttype = $type."@giftcard";
//            $query = "Select * from cart where pid='$code' and cartid='$cartid' and type='$gifttype' and details='$details' and datetime='$date';";
//            $result = mysqli_query($link, $query);
//
//            if (!mysqli_query($link, $query)) {
//                echo "Error: ". mysqli_error($link);
//            } else {
//                $sql;
//                if ($result -> num_rows === 0) {
//                    $sql = "INSERT into cart (pid, cartid, price, quantity, type, details, datetime) "
//                            . "VALUES ('$code', '$cartid', '$price', '1', '$gifttype', '$details', '$date')";
//                } else {
//                    $row = mysqli_fetch_assoc($result);
//                    $newQty = $row['quantity'] + 1;
//                    $sql = "UPDATE cart set quantity='$newQty', details = '$details' where pid='$code' and "
//                            . "cartid='$cartid' and type='$gifttype' and datetime='$date';";
//                }
//                
//                $user;
//                if (isset($_SESSION['loggedUserEmail'])) {
//                    $user = $_SESSION['loggedUserEmail'];
//                } else {
//                    $user = $cartid;
//                }
//
//                //add to statistics
//                $stats = "INSERT INTO productstatistics (type, pid, customer) VALUES "
//                    . "('giftcard', '$code', '$user');";
//                mysqli_query($link, $stats);   
//                
//                mysqli_query($link, $sql);
//                header("Location: giftcard.php");
//            }
//        }
//    }
//}