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
    $gethometry = "Select * from cart where cartid ='".GetCartId()."' and type='hometry';";
    $result = mysqli_query($link, $gethometry);
    $qty = 0;
    while ($row = mysqli_fetch_assoc($result)) {
        $qty += intval($row['quantity']);
    }
    
    $type = $_GET['type'];
    
    if (strcmp($type, "hometry") === 0 && ($result -> num_rows >= 5 || $qty >= 5)) {
        $_SESSION['homeError'] = "You can only choose a maximum of 5 pairs for home try-on";
        header("Location: product.php?id=".$_GET['id']);
    } else {
        unset($_SESSION['homeError']);        
        $pid = $_GET['id'];
        $cartid = GetCartId();
        if (isset($_GET['lens'])) {
            $lens = $_GET['lens'];
        } else {
            $lens = "";
        }
        
        $pStats;
        
        $getproduct = "Select * from products where pid='$pid'";
        $prodres = mysqli_query($link, $getproduct);

        if (!mysqli_query($link, $getproduct)) {
            echo "Error: ".mysqli_error($link);
        } else {
            $prod = mysqli_fetch_assoc($prodres);
            $price = $prod['price'];

            if (strcmp($type, "purchase") === 0) {
                $pStats = "cartpurchase";
                $query = "Select * from cart where pid='$pid' and cartid='$cartid' and type='$type' and lens='$lens';";
            } else {
                $pStats = "carttry";
                $query = "Select * from cart where pid='$pid' and cartid='$cartid' and type='$type';";
            }
    //        $query = "Select * from cart where pid='$pid' and cartid='$cartid' and type='$type';";
            $result = mysqli_query($link, $query);

            if (!mysqli_query($link, $query)) {
                echo "Error: ". mysqli_error($link);
            } else {
                $sql;
                if ($result -> num_rows === 0) {
                    if (strcmp($type, "purchase") === 0) {
                        $sql = "INSERT into cart (pid, cartid, price, quantity, type, lens) "
                                . "VALUES ('$pid', '$cartid', '$price', '1', '$type', '$lens')";
                    } else {
                        $sql = "INSERT into cart (pid, cartid, price, quantity, type) "
                                . "VALUES ('$pid', '$cartid', '$price', '1', '$type')";
                    }
                } else {
                    $row = mysqli_fetch_assoc($result);
                    $newQty = $row['quantity'] + 1;
                    if (strcmp($type, "purchase") === 0) {
                        $sql = "UPDATE cart set quantity='$newQty' where pid='$pid' and cartid='$cartid' and type='$type' and lens='$lens';";
                    } else {
                        $sql = "UPDATE cart set quantity='$newQty' where pid='$pid' and cartid='$cartid' and type='$type';";
                    }
                }
                
                $user;
                if (isset($_SESSION['loggedUserEmail'])) {
                    $user = $_SESSION['loggedUserEmail'];
                } else {
                    $user = $cartid;
                }
                
                //add to statistics
                $stats = "INSERT INTO productstatistics (type, pid, customer) VALUES "
                    . "('$pStats', '$pid', '$user');";
                mysqli_query($link, $stats);   
                
                mysqli_query($link, $sql);
                echo "<script>window.history.back();</script>";
    //            header("Location: product.php?id=".$pid);
            }
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
            
            $pStats;
            if (strcmp($type, "purchase") === 0) {
                $pStats = "cartpurchase";
            } else if (strcmp($type, "hometry") === 0){
                $pStats = "carttry";
            } else {
                $pStats = "giftcard";
            }
            
            $user;
            if (isset($_SESSION['loggedUserEmail'])) {
                $user = $_SESSION['loggedUserEmail'];
            } else {
                $user = $cartid;
            }
            
            //add to statistics
            $stats = "INSERT INTO productstatistics (type, pid, customer) VALUES "
                . "('$pStats', '$pid', '$user');";
            mysqli_query($link, $stats);   
            
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
                
                $user;
                if (isset($_SESSION['loggedUserEmail'])) {
                    $user = $_SESSION['loggedUserEmail'];
                } else {
                    $user = $cartid;
                }

                //add to statistics
                $stats = "INSERT INTO productstatistics (type, pid, customer) VALUES "
                    . "('giftcard', '$code', '$user');";
                mysqli_query($link, $stats);   
                
                mysqli_query($link, $sql);
                header("Location: giftcard.php");
            }
        }
    }
}