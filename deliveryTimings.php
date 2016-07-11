<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

require_once 'config/db.php';
require_once 'config/zyllem.php';

if (isset($_GET['id']) && isset($_GET['date']) && isset($_GET['add'])
        && isset($_GET['zip']) && isset($_GET['apt']) 
        && isset($_GET['country']) && isset($_GET['timing'])) {
    //get variables to process delivery possibilities
    $cartid = $_GET['id'];
    $date = $_GET['date'];
    $add = $_GET['add'];
    $zip = $_GET['zip'];
    $apt = $_GET['apt'];
    $country = $_GET['country'];
    $timing = $_GET['timing'];
    $timingArr = explode(",", $timing);
    
    //get products that are hometry
    $sql = "Select * from cart where cartid='$cartid' and type = 'hometry';";
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
        }
    }
    
    $post_data = array(
        //should be visual mass address
        'pickupTime' => $date."T12:00:00+08:00",
          'senderLocation' => array (
              'address' => $locadd,
              "address2" => $locapt,
              "countryCode" => $loccountry,
              "postalCode" => $loczip
          ),
        'receiverLocation' => array(
            'address' => $add,
              "address2" => $apt,
            "countryCode" => "SG",
            "postalCode" => $zip        
        ),
        'parcels' => $parcels,
        "onlyServices" => $timingArr
    );
    
    $arr = json_encode($post_data);
    
    $result = zyllemConnect($access, $arr);
    
    $resArr = json_decode($result, true);
//    print_r($resArr);
    
    //extract forecast array from result array
    $forecasts = $resArr['forecasts'];

    $deliveryArr = getDeliveryWindow($date, $forecasts);
//    print_r($deliveryArr);
    
    //loop through collection dates
    for($c = 0; $c < count($deliveryArr); $c++) {
        $element = $deliveryArr[$c];
        $date = $element['date'];
        //add 1 week then get collection windows for that
        $createDate = date_create(date("Y-m-d",strtotime($date)));
        date_add($createDate,date_interval_create_from_date_string("7 days"));
        $newdate = date_format($createDate,"Y-m-d");
        $collectionArr = getCollectionWindow($access, $newdate, $post_data);
        $element['collection'] = $collectionArr;
        $deliveryArr[$c] = $element;
    }
//    print_r($deliveryArr);
//    exit();
    //set delivery array and redirect to page
    $_SESSION['deliveryTimings'] = $deliveryArr;
    header("Location: checkout.php#deliveryAvailability");
}

function getDeliveryWindow($date, $forecasts) {
    $results = array();
    
    //loop forecasts array
    for ($i = 0; $i < count($forecasts); $i++) {
        //get each day
        $day = $forecasts[$i];
        $thisday = $day['dayFormatted'];

        $services = $day['services'];
        $collectionArr = array();
        
        $thisdate = getDateFromString($day['day']);
        
        if (strcmp($thisdate, $date) === 0) {
            //loop through services available for each day
            for ($d = 0; $d < count($services); $d++) {
                $service = $services[$d];
                $serviceName = $service['serviceName']." ";

                //get delivery window
                $deliver = $service['deliveryWindow'];
                $start = date("H:i:s",strtotime($deliver['Start']));
                $end = date("H:i:s",strtotime($deliver['End']));
                $serviceName .= $start."-".$end;
                array_push($collectionArr, $thisday." ".$serviceName);
            }

            $dayArr = array('date' => $day['day'], 'deliveryWindow' => $collectionArr);
            array_push($results, $dayArr);
        }
    }
    
    return $results;
}

function getCollectionWindow($access, $newdate, $data) {
    $data['pickupTime'] = $newdate."T12:00:00+08:00";
    $arr = json_encode($data);
    $result = zyllemConnect($access, $arr);
    $resArr = json_decode($result, true);
    $forecasts = $resArr['forecasts'];
    
    //loop forecasts array
    for ($i = 0; $i < count($forecasts); $i++) {
        //get each day
        $day = $forecasts[$i];
        $thisday = $day['dayFormatted'];

        $services = $day['services'];
        $collectionArr = array();
        $thisdate = getDateFromString($day['day']);
        
        if (strcmp($thisdate, $newdate) === 0) {
            //loop through services available for each day
            for ($d = 0; $d < count($services); $d++) {
                $service = $services[$d];
                $serviceName = $service['serviceName']." ";

                //get delivery window
                $deliver = $service['collectionWindow'];
                $start = date("H:i:s",strtotime($deliver['Start']));
                $end = date("H:i:s",strtotime($deliver['End']));
                $serviceName .= $start."-".$end;
                array_push($collectionArr, $thisday." ".$serviceName);
            }

            $dayArr = array('date' => $day['day'], 'collectionWindow' => $collectionArr);
            return $dayArr;
        }
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

//    print_r($post_data);
    $delcontext = stream_context_create($options);
    $url = 'https://api.zyllem.org/api/v2/services/forecast';
    $result = file_get_contents($url, false, $delcontext, -1, 40000);
    
    return $result;
}

function getDateFromString($date) {
    $pos = strpos($date, "T");
    $str = substr($date, 0, $pos);
    return $str;
}