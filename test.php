<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

require_once 'config/zyllem.php';

$post_data = array(
  'pickupTime' => "2017-08-01T12:00:00+08:00",
    'senderLocation' => array (
        'address' => "71 Ayer Rajah Crescent",
        "address2" => "#04-23",
        "countryCode" => "SG",
        "postalCode" => "139951"
    ),
    'receiverLocation' => array(
      "countryCode" => "SG",
      "postalCode" => "610118"        
    ),
    'parcels' => array(
        array(
         "description" => "parcel1",
         "dimension" => array (
            "unit" => "cm",
            "width"  => 10,
            "height" => 15,
            "length" => 25
         )
        ),
        array(
            "description" => "parcel2",
            "weight" => array(
               "unit" => "kg",
               "value" => 2
            )
        ),
        
        array(
            "description" => "parcel3",
            "dimension" => array(
               "unit" => "cm",
               "width"  => 10,
               "height" => 15,
               "length" => 25
            ),
            "weight" => array (
               "unit" => "kg",
               "value" => 3
            )
        )
    ),
    "onlyServices" => array("AFTERNOON", "EVENING")
);

$query = json_encode($post_data);

print_r($post_data);
$options = array(
    'http' => array(
        'header' => "Authorization: bearer ".$access."\r\n".
                    "Content-Type: application/json\r\n",
        'method'  => "POST",
        'content' => $query,
    ),
);

//print_r($options);
$context = stream_context_create($options);
$url = 'https://api.zyllem.org/api/v2/services/forecast';
$result = file_get_contents($url, false, $context, -1, 40000);

$arr = json_decode($result, true);
//print_r($arr['forecasts']);

$forecasts = $arr['forecasts'];

$results = array();
$collections = array();
//loop forecasts array
for ($i = 0; $i < count($forecasts); $i++) {
    //get each day
    $day = $forecasts[$i];
    $thisday = $day['dayFormatted'];
//    echo $thisday;
//    echo "<br><br>";
//    print_r($day);
    
    $services = $day['services'];
    //loop through services available for each day
    for ($d = 0; $d < count($services); $d++) {
        $service = $services[$d];
        $serviceName = $service['serviceName']." ";
        
        //get delivery window
        $deliver = $service['deliveryWindow'];
        $start = date("H:i:s",strtotime($deliver['Start']));
        $end = date("H:i:s",strtotime($deliver['End']));
        $serviceName .= $start."-".$end;
        array_push($results, $thisday." ".$serviceName);
    }
    
    //add 1 week then get collection windows for that
    $date = date_create(date("Y-m-d",strtotime($day['day'])));
    date_add($date,date_interval_create_from_date_string("7 days"));
    $collectionDate = date_format($date,"Y-m-d")."<br>";
    
    $collect_data = $post_data;
    $collect_data['pickupTime'] = $collectionDate."T12:00:00+08:00";
    
//    print_r($collect_data);
//    echo "<br><br>";
}

print_r($results);

?>