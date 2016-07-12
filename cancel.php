<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

require_once 'config/zyllem.php';

if (isset($_GET['reason']) && isset($_GET['did'])) {
    $reason = $_GET['reason'];
    $did = $_GET['did'];
    
    $data = array("reason" => $reason);
    $post_data = json_encode($data);
    
    //cancel delivery
    $options = array(
        'http' => array(
            'header' => "Authorization: bearer ".$access."\r\n".
                        "Content-Type: application/json\r\n",
            'method'  => "POST",
            'content' => $post_data,
        ),
    );

    $delcontext = stream_context_create($options);
    $url = 'https://api.zyllem.org/api/v2/deliveries/'.$did.'/cancel';
    $result = file_get_contents($url, false, $delcontext, -1, 40000);
    $arr = json_decode($result, true);
//    print_r($arr);
    
    $status = $arr['status'];
    
    if (strcmp($status, "Success") === 0) {
        $_SESSION['cancelledDelivery'] = "success";
        //update delivery table
        $sql = "UPDATE deliveries set statename='cancelled' where deliveryid='$did';";
        mysqli_query($link, $sql);
    } else {
        $_SESSION['cancelledDelivery'] = "unsuccessful";
    }
//    exit();
    header("Location: orders.php?did=".$did);
}