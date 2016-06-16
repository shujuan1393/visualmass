<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

require_once 'config/db.php';

if (isset($_GET['id']) && isset($_GET['cost'])) {
    $nonce = $_GET['id'];
    $cost = $_GET['cost'];
    $result = Braintree_Transaction::sale([
        'amount' => $cost,
        'paymentMethodNonce' => 'fake-valid-nonce',
        'options' => [
          'submitForSettlement' => True
        ]
    ]);

    if ($result->success) {
        $transaction = $result->transaction;
        $transaction->status;
        
        $getCart = "SELECT * FROM cart where cartid='".GetCartId()."';";
        $gres = mysqli_query($link, $getCart);
        if (!mysqli_query($link, $getCart)) {
            die(mysqli_error($link));
        } else {
            $payment = $_GET['payment'];
            while($row = mysqli_fetch_assoc($gres)) {
                $pid = $row['pid'];
                $quantity = $row['quantity'];
                $type = $row['type'];
                $price = $row['price'];
                
                if (is_numeric(strpos($type, "@"))) {
                    $details = $row['details'];
                } else {
                    $details = $row['lens'];
                }
                $orderid = "ON-".rand();
                $order = "INSERT INTO orders (orderid, pid, price, quantity, type, payment, details, status, orderedby, totalcost, dateordered)"
                        . " VALUES ('$orderid','$pid', '$price', '$quantity', '$type', '$payment','$details', 'paid', "
                        . "'".$_SESSION['loggedUserEmail']."', '$cost', '".$row['datetime']."');";
                mysqli_query($link, $order);
                $remove = "DELETE FROM cart where id ='".$row['id']."';";
                mysqli_query($link, $remove);      
                $_SESSION['order'] = "Order successfully completed!";   
                header("Location: cart.php");         
            }
        }      
    } else {
        $_SESSION['orderError'] = "Unable to process order";
        header("Location: checkout.php");
    }
}
