<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

require_once 'config/db.php';
require_once 'config/braintree.php';
require_once 'mailer/PHPMailerAutoload.php';
require_once 'config/zyllem.php';

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

function sendNewUserEmail($pwd, $link, $email, $firstname, $lastname) {
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

//get how much to add
$set = "Select * from settings where type='storecredit';";
$sres = mysqli_query($link, $set);

if (!mysqli_query($link, $set)) {
    die(mysqli_error($link));
} else {
    $srow = mysqli_fetch_assoc($sres);
    $valArr = explode("&", $srow['value']);
    if(!empty($valArr[0])){
        $amount = explode("redeemamount=", $valArr[0]);
    }
}

if (isset($_GET['id']) && isset($_GET['cost'])) {
    if (!isset($_SESSION['loggedUserEmail'])) {
        $email = $_GET['email'];
        $firstname = $_GET['firstname'];
        $lastname = $_GET['lastname'];
        $address = $_GET['address'];
        $phone = $_GET['phone'];
        $zip = $_GET['zip'];
        $apt = $_GET['apt'];
        $country = $_GET['country'];
        
        $pwd = md5('P@ssw0rd!23');
        $isSent = sendNewUserEmail($pwd, $link, $email, $firstname, $lastname);
        
        if ($isSent === true) {
            $getuser = "Select * from user where email='$email';";
            $ures = mysqli_query($link, $getuser);

            if (!mysqli_query($link, $getuser)) {
                die(mysqli_error($link));
            } else {
                if ($ures -> num_rows === 0) {
                    $sql = "INSERT INTO user (accountType, password, email, firstname, lastname, address, "
                            . "country, zip, apt, phone) "
                            . "VALUES ('customer','$pwd', '$email', '$firstname', '$lastname', '$address',"
                            . "'$country', '$zip', '$apt', '$phone');";
                } else {
                    $sql = "UPDATE user set firstname ='$firstname', lastname='$lastname', "
                            . "address='$address', country='$country', "
                            . "zip='$zip', apt='$apt', password='$pwd', phone='$phone' where email='$email';";
                }
                mysqli_query($link, $sql);
            }
        }
        $_SESSION['loggedUserEmail'] = $email;
    } else {
        $email = $_SESSION['loggedUserEmail'];
        
        $user = "Select * from user where email='$email';";
        $userres = mysqli_query($link, $user);
        
        if(!mysqli_query($link, $user)) {
            die(mysqli_error($link));
        } else {
            $userrow = mysqli_fetch_assoc($userres);
            $address = $userrow['address'];
            $phone = $userrow['phone'];
            $firstname = $userrow['firstname'];
            $lastname = $userrow['lastname'];
            $zip = $userrow['zip'];
            $apt = $userrow['apt'];
            $country = $userrow['country'];
        }
    }
    
    $discount = $_GET['code'];
    $amt = $_GET['amount'];
    
    $nonce = $_GET['id'];
    $cost = $_GET['cost'];
    if (!empty($amt)) {
        $finalcost = $cost - $amt;
    } else {
        $finalcost = $cost;
    }
    
    $result = Braintree_Transaction::sale([
        'amount' => $finalcost,
        'paymentMethodNonce' => 'fake-valid-nonce',
        'options' => [
          'submitForSettlement' => True
        ]
    ]);

    //create orderid
    $orderid = "ON-".rand();
    
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
                
//                if (!empty($discount)) {
//                    //use existing credit
//                    if (strcmp($discount, "existing") === 0) {
//                        $sql = "Select * from user where email='$email';";
//                        $user = mysqli_query($link, $sql);
//                        
//                        if (!mysqli_query($link, $sql)) {
//                            die(mysqli_error($link));
//                        } else {
//                            $creditrow = mysqli_fetch_assoc($user);
//                            $credit = $creditrow['credit'];
//                            if (strcmp($credit,"0") !== 0) {
//                                $newCred = intval($credit) - intval($amt);
//
//                                $minusCredit = "UPDATE user set credit='$newCred' where email='$email';";
//                                mysqli_query($link, $minusCredit);
//                            }
//                        }
//                        
//                        //add credit to person whose code was used
//                        $refer = "Select * from referrals where email='$email';";
//                        $refers = mysqli_query($link, $refer);
//                        
//                        if (!mysqli_query($link, $refer)) {
//                            die(mysqli_error($link));
//                        } else {
//                            $r1 = mysqli_fetch_assoc($refers);
//                            //credit user 
//                            $sql = "Select * from user where code ='".$r1['code']."';";
//                            $userSql = mysqli_query($link, $sql);
//                            
//                            if (!mysqli_query($link, $sql)) {
//                                die(mysqli_error($link));
//                            } else {
//                                $userrow = mysqli_fetch_assoc($userSql);
//                                $newCredit = intval($userrow['credit']) + intval($amount[1]);
//
//                                $addCredit = "UPDATE user set credit='$newCredit' where code='".$r1['code']."' and email='".$userrow['email']."';";
//                                mysqli_query($link, $addCredit);
//                                
//                                $canuse = true;
//                            }
//                        }                        
//                    } else {
//                        //split code and digits (if any)
//                        preg_match_all('/([\d]+)/', $discount, $match);
//                        
//                        if (count($match[0]) === 0) {
//                            $sql = "Select * from discounts where code='$discount';";
//                        } else {
//                            $arr = explode($match[0][0], $discount);
//                            $sql = "Select * from discounts where code LIKE '".$arr[0]."%';";
//                        }
//                        //update discount code usage
//                        $dres = mysqli_query($link, $sql);
//
//                        if (!mysqli_query($link, $sql)) {
//                            die(mysqli_error($link));
//                        } else {
//                            if ($dres -> num_rows !== 0) {
//                                $row = mysqli_fetch_assoc($dres);
//                                $limit = $row['disclimit'];
//                                $trackserial = $row['serial'];
//                                
//                                $canuse = false;
//                                
//                                //if need to track serial, check if number entered is within range
//                                if (strcmp($trackserial, "yes") === 0 && $match[0][0] <= $limit) {
//                                    //check if used before in transactions
//                                    $checkdisc = "Select * from orders where discountcode='$discount' group by orderid;";
//                                    $checkres = mysqli_query($link, $checkdisc);
//
//                                    if (!mysqli_query($link, $checkdisc)) {
//                                        die(mysqli_error($link));
//                                    } else {
//                                        if ($checkres -> num_rows === 0) {
//                                            //this particular discount code has never been used before
//                                            $canuse = true;
//                                        }
//                                    }
//                                } else {
//                                    //check # times used in orders
//                                    $check = "Select * from orders where discountcode ='$discount' group by orderid;";
//                                    $cres = mysqli_query($link, $check);
//                                    
//                                    if (!mysqli_query($link, $check)) {
//                                        die(mysqli_error($link));
//                                    } else {
//                                        $numused = $cres -> num_rows;
//                                        if ($numused < $limit) {
//                                            //this particular discount code still can be used
//                                            $canuse = true;
//                                        }
//                                    }
//                                }
//                            } else {
//                                $refer = "Select * from referrals where email ='$email';";
//                                $result = mysqli_query($link, $refer);
//
//                                if (!mysqli_query($link, $refer)) {
//                                    die(mysqli_error($link));
//                                } else {
//                                    if ($result -> num_rows === 0) {
//                                        //record referral transaction made by user
//                                        $insert = "INSERT INTO referrals (orderid, email, code) "
//                                                . "VALUES ('$orderid', '$email', '$discount');";
//                                        mysqli_query($link, $insert);
//
//                                        //credit person whose code was used
//                                        $credit = "Select * from user where code='$discount';";
//                                        $res = mysqli_query($link, $credit);
//
//                                        if (!mysqli_query($link, $credit)) {
//                                            die(mysqli_error($link));
//                                        } else {
//                                            $canuse = true;
//                                            $crow = mysqli_fetch_assoc($res);
//                                            $newCredit = intval($crow['credit']) + intval($amount[1]);
//
//                                            $addCredit = "UPDATE user set credit='$newCredit' where code='$discount' and email='".$crow['email']."';";
//                                            mysqli_query($link, $addCredit);
//                                        }
//                                    }
//                                }
//                            }
//                        }
//                    }
//                    
//                    if (is_numeric(strpos($orderid, "ON"))) {
//                        $location = "online";
//                    }
//                    
//                    if ($canuse === true) {
//                        $order = "INSERT INTO orders (orderid, pid, price, quantity, type, payment, details, status, orderedby, "
//                                . "totalcost, dateordered, discountcode, location)"
//                                . " VALUES ('$orderid','$pid', '$price', '$quantity', '$type', '$payment','$details', 'paid', "
//                                . "'$email', '$unitcost', '".$row['datetime']."', '$discount', '$location');";
//                        mysqli_query($link, $order);
//                        $remove = "DELETE FROM cart where id ='".$row['id']."';";
//                        mysqli_query($link, $remove);      
//
//                        //add to statistics
//                        $stats = "INSERT INTO productstatistics (type, pid, customer, orderid) VALUES ('$type', '$pid', '$email', '$orderid');";
//                        mysqli_query($link, $stats);
//                    }
//                } else {
//                    $order = "INSERT INTO orders (orderid, pid, price, quantity, type, payment, details, status, orderedby, "
//                            . "totalcost, dateordered, discountcode)"
//                            . " VALUES ('$orderid','$pid', '$price', '$quantity', '$type', '$payment','$details', 'paid', "
//                            . "'$email', '$unitcost', '".$row['datetime']."', '$discount');";
//                    mysqli_query($link, $order);
//                    $remove = "DELETE FROM cart where id ='".$row['id']."';";
//                    mysqli_query($link, $remove);      
//
//                    //add to statistics
//                        $stats = "INSERT INTO productstatistics (type, pid, customer, orderid) VALUES ('$type', '$pid', '$email', '$orderid');";
//                        mysqli_query($link, $stats);
//                }
                
                //get products that are hometry
                $sql = "Select * from cart where cartid='".GetCartId()."' and type = 'hometry';";
                $res = mysqli_query($link, $sql);
                $parcels = array();

                if (!mysqli_query($link, $sql)) {
                    die(mysqli_error($link));
                } else {
                    if($res -> num_rows > 0) {
                        $count = 0;
                        while($row = mysqli_fetch_assoc($res)) {
                            $pid = $row['pid'];

                            $prod = "Select * from products where pid='$pid';";
                            $pres = mysqli_query($link, $prod);

                            if (!mysqli_query($link, $prod)) {
                                die(mysqli_error($link));
                            } else {
                                $prow = mysqli_fetch_assoc($pres);
                                $parcelArr = array(
                                    "description" => $pid,
                                    "dimension" => array(
                                        "unit" => "cm",
                                        "width" => $prow['width'],
                                        "height" => 15,
                                        "length" => 25
                                    )
                                );

                                array_push($parcels, $parcelArr);
                            }
                        }
                    }
                }
                
                //create delivery 
                if (!empty($_GET['comments'])) {
                    $comments = $_GET['comments'];
                } else {
                    $comments = "No special instructions";
                }
                
                if (isset($_SESSION['selectedDeliveryDate'])) {
                    $deliveryDate = $_SESSION['selectedDeliveryDate'];
                }
                $dateArr = explode(" ", $deliveryDate);
                $date = str_replace('/', '-', $dateArr[1]);
                $thisdate = date('Y-m-d', strtotime($date));
//                $thisdate = date('Y-m-d', strtotime($dateArr[1]));
//                print_r($dateArr);
                $timeArr = explode("-", $dateArr[4]);
//                print_r($timeArr);
                //DateTime::createFromFormat('d/m/Y', $dateArr[1])->format('Y-m-d');
                //get primary store from settings
                $settings = "Select * from settings where type='general';";
                $setres = mysqli_query($link, $settings);
                
                if (!mysqli_query($link, $settings)) {
                    die(mysqli_error($link));
                } else {
                    $savedrow = mysqli_fetch_assoc($setres);
                    $valArr = explode("&", $savedrow['value']);
                    $priStore = explode("primary=", $valArr[0]);
                    $store = $priStore[1];
                    
                    //get address from locations table
                    $loc = "Select * from locations where code='$store';";
                    $locres = mysqli_query($link, $loc);
                    
                    if (!mysqli_query($link, $loc)) {
                        die(mysqli_error($link));
                    } else {
                        $locrow = mysqli_fetch_assoc($locres);
                        $locadd = $locrow['address'];
                        $locapt = $locrow['apt'];
                        $loczip = $locrow['zip'];
                        $loccountry = $locrow['country'];
                        
                        
                        $deliveryArr = array(
                            "eOrderId" => $orderid,
                            "sender" => array(
                                "companyName" => "Visual Mass",
                                "contactName" => "Jerial Tan",
                                "location" => array(
                                    "address" => $locadd,
                                    "address2" => $locapt,
                                    "countryCode" => "SG",
                                    "postalCode" => $loczip
                                ),
                                "contactNumber" => "67178330"
                            ),

                            "receiver" => array(
                                "contactName" => $firstname." ".$lastname,
                                "location" => array(
                                    "address" => $address,
                                    "address2" => $apt,
                                    "countryCode" => "SG",
                                    "postalCode" => $zip
                                ),
                                "contactNumber" => $phone
                            ),

                            "service" => strtoupper($dateArr[2]),
                            "pickupTime" => $thisdate."T".$timeArr[0]."+08:00",
        //                    "pickupTime" => $thisdate."T12:00:00+08:00",
                            "parcels" => $parcels,
                            "comments" => $comments
                        );

                        //encode and process delivery array
                        $arr = json_encode($deliveryArr);
                        $result = zyllemConnect($access, $arr);
                        $resArr = json_decode($result, true);

                        //create deliverites for collections
                        if (isset($_SESSION['corrCollectionDate'])) {
                            $collDate = $_SESSION['corrCollectionDate'];
                        }

                        $colDateArr = explode(" ", $collDate);
        //                $colDate = date('Y-m-d', strtotime($colDateArr[1]));
                        $collectDate = str_replace('/', '-', $colDateArr[1]);
                        $colDate = date('Y-m-d', strtotime($collectDate));
                        $colTimeArr = explode("-", $colDateArr[4]);
        //                print_r($colDateArr);

                        $collectionArr = $deliveryArr;
                        $receiver = $collectionArr['sender'];
                        $sender = $collectionArr['receiver'];

                        //swop and update values in collection array
                        $collectionArr['sender'] = $sender;
                        $collectionArr['receiver'] = $receiver;
                        $collectionArr['service'] = strtoupper($colDateArr[2]);
                        $collectionArr['pickupTime'] = $colDate."T".$colTimeArr[0]."+08:00";

                        //encode and process collection array
                        $colArr = json_encode($collectionArr);
                        $colResult = zyllemConnect($access, $colArr);
                        $colResArr = json_decode($colResult, true); 

                        print_r($arr);
        //                echo "<br><br>";
        //                print_r($colArr);
        //                exit();
        //                
                        echo "<br><br>";
                        print_r($resArr);
                        echo "<br><br>";
                        print_r($colResArr);
                        exit();
                        if (strcmp($resArr['status'], "Success") === 0 && strcmp($colResArr['status'], "Success") === 0) {
                            $bool = saveDeliveryDetails($resArr['delivery'], $colResArr['delivery'], $link);

                            if ($bool === true) {
                                unset($_SESSION['selectedDeliveryDate']);
                                unset($_SESSION['corrCollectionDate']);
                                $_SESSION['order'] = "Order successfully completed!";   
        //                        header("Location: cart.php");  
                            } else {
                                $_SESSION['orderError'] = "Unable to process order";
        //                        header("Location: checkout.php");
                            }
                        } 
                    }
                }    
            }
        }      
    } else {
        $_SESSION['orderError'] = "Unable to process order";
//        header("Location: checkout.php");
    }
}

function zyllemConnect($access, $arr) {
    $options = array(
        'http' => array(
            'header' => "Authorization: bearer ".$access."\r\n".
                        "Content-Type: application/json\r\n",
            'method'  => "POST",
            'content' => $arr,
        ),
    );

    $delcontext = stream_context_create($options);
    $url = 'https://api.zyllem.org/api/v2/deliveries/Create';
    $result = file_get_contents($url, false, $delcontext, -1, 40000);
    return $result;
}

function saveDeliveryDetails($arr, $colArr, $link) {
    $orderid = $arr['eOrderId'];
    $deliveryid = $arr['deliveryId'];
    $collectionid = $colArr['deliveryId'];
    $trackingNum = $arr['trackingNumber'];
    $trackingUrl = $arr['trackingUrl'];
//    $cost = $arr['cost']['currency']." ".$arr['cost']['value'];
    $comments = $arr['comments'];
    $status = $arr['status'];
    $stateName = $arr['stateName'];
    
    //check if delivery exists 
    $del = "Select * from deliveries where trackingnumber ='$trackingNum';";
    $dres = mysqli_query($link, $del);
    
    if (!mysqli_query($link, $del)) {
        die(mysqli_error($link));
    } else {
        if($dres -> num_rows === 0) {
            $sql = "INSERT INTO deliveries (orderid, deliveryid, collectionid, trackingnumber, trackingurl, comments, status, statename) VALUES ('$orderid', '$deliveryid', '$collectionid', '$trackingNum', '$trackingUrl', '$comments', '$status', '$stateName');";
            mysqli_query($link, $sql);
            return true;
        }
        return false;
    }
}