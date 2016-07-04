<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

require_once 'config/zyllem.php';

//split code and digits (if any)
//preg_match_all('/([\d]+)/', "wNasd023", $match);
//                        
//print_r($match[0]);
//echo "<br>";
//
//if (count($match[0]) === 0) {
//    echo "Empty!";
//} else {
//    $arr = explode($match[0][0], "wNasd023");
//    print_r($arr);
//}
//
//if ($match[0][0] <= "1000") {
//    echo "true";
//}

//echo "<br>";
$post_data = array(
  'Authorization' => "bearer ".$access,
  'services' => array(
    'service' => "MORNING",
    'serviceName' => "Morning Standard",
    'serviceDescription' => "4 Hour (1000 - 1400)"
  )
);

$arr = json_encode($post_data);

//echo $arr;
//
//$url = "sg.zyllem.org";
//$http = new HttpRequest($url, HttpRequest::METH_POST);
//$http->setOptions(array(
//    'timeout' => 10,
//    'redirect' => 4
//));
//$http->addPostFields(array(
//    'grant_type' => 'client_credentials',
//    'client_id' => 'uz33H94dsL2JE3fKKQOslXSuvs8Hp5Lc',
//    'scope' => 'order services',
//    'client_secret' => 'A05RrHBy0KcaLMhmxBdiQafc6CMMyRRW'
//));
//$response = $http->send();
//echo $response->getBody();
//header("Location: https://api.zyllem.org/api/v2/services");

?>

<script>
    var str = "Get $20 Off";
    var arr = str.split(" ");
//    alert(arr[1].indexOf('$'));
    alert(parseFloat(arr[1].substring(1)));
</script>