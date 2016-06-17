<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

require_once 'config/db.php';
require_once 'config/braintree.php';
require_once 'mailer/PHPMailerAutoload.php';

function getWelcomeTemplate($link) {
    $noti = "Select * from settings where type='notifications'";
    $res = mysqli_query($link, $noti);
    
    if (!mysqli_query($link, $noti)) {
        die(mysqli_error($link));
    } else {
        $row = mysqli_fetch_assoc($res);
        $valArr = explode("#", $row['value']);
        if(!empty($valArr[0])){
            $emailArr = explode("email=", $valArr[0]);
            $emailVal = explode(",", $emailArr[1]);
        }
        
        return $emailVal[0];
    }
}

function sendNewUserEmail($pwd, $link, $email, $firstname, $lastname, $address, $phone) {
    $mail = new PHPMailer;
    $mail->isSMTP();                                      // Set mailer to use SMTP
    $mail->Host = 'smtp.gmail.com';  // Specify main and backup SMTP servers
    $mail->SMTPAuth = true;                               // Enable SMTP authentication
    $mail->Username = 'shujuan1393@gmail.com';                 // SMTP username
    $mail->Password = 'Milkyway2309SJ';                           // SMTP password
    $mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
    $mail->Port = 587;                                    // TCP port to connect to

    $mail->setFrom('contact@visualmass.com', 'Admin');
    $mail->addAddress($email);     
//                $mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
//                $mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name
    $mail->isHTML(true);                                  // Set email format to HTML

    $mail->Subject = 'Welcome to Visual Mass';
//    $str = getWelcomeTemplate($link);
    
    $noti = "Select * from settings where type='notifications'";
    $res = mysqli_query($link, $noti);
    
    if (!mysqli_query($link, $noti)) {
        die(mysqli_error($link));
    } else {
        $row = mysqli_fetch_assoc($res);
        $valArr = explode("#", $row['value']);
        if(!empty($valArr[0])){
            $emailArr = explode("email=", $valArr[0]);
            $emailVal = explode(",", $emailArr[1]);
        }
        
        $str = $emailVal[0];
    }
    
    $mail->Body    = 'Hi '.$firstname.' '.$lastname.',<br><br>'
            . $str;
//                $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

    if($mail->send()) {
        return true;
    } else {
        return false;
    }
}


if (isset($_GET['id']) && isset($_GET['cost'])) {
    echo "paying";
    if (!isset($_SESSION['loggedUserEmail'])) {
        $email = $_GET['email'];
        $firstname = $_GET['firstname'];
        $lastname = $_GET['lastname'];
        $address = $_GET['address'];
        $phone = $_GET['phone'];
        $pwd = md5('P@ssw0rd!23');
        $isSent = sendNewUserEmail($pwd, $link, $email, $firstname, $lastname, $address, $phone);
        
        if ($isSent === true) {
            $getuser = "Select * from user where email='$email';";
            $ures = mysqli_query($link, $getuser);

            if (!mysqli_query($link, $getuser)) {
                die(mysqli_error($link));
            } else {
                if ($ures -> num_rows === 0) {
                    $sql = "INSERT INTO user (accountType, password, email, firstname, lastname, address, phone) "
                            . "VALUES ('customer','$pwd', '$email', '$firstname', '$lastname', '$address', '$phone');";
                } else {
                    $sql = "UPDATE user set firstname ='$firstname', lastname='$lastname', "
                            . "address='$address', password='$pwd', phone='$phone' where email='$email';";
                }
                mysqli_query($link, $sql);
            }
        }
        $_SESSION['loggedUserEmail'] = $email;
    } else {
        $email = $_SESSION['loggedUserEmail'];
    }
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
                
                if (strcmp($type, "hometry") === 0) {
                    $unitcost = 0;
                } else {
                    $unitcost = $price * $quantity;
                }
                
                if (is_numeric(strpos($type, "@"))) {
                    $details = $row['details'];
                } else {
                    $details = $row['lens'];
                }
                $orderid = "ON-".rand();
                $order = "INSERT INTO orders (orderid, pid, price, quantity, type, payment, details, status, orderedby, totalcost, dateordered)"
                        . " VALUES ('$orderid','$pid', '$price', '$quantity', '$type', '$payment','$details', 'paid', "
                        . "'$email', '$unitcost', '".$row['datetime']."');";
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
