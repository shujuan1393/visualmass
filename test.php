<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

//require_once 'config/zyllem.php';
//
//$post_data = array(
//  'pickupTime' => "2017-08-01T12:00:00+08:00",
//    'senderLocation' => array (
//        'address' => "71 Ayer Rajah Crescent",
//        "address2" => "#04-23",
//        "countryCode" => "SG",
//        "postalCode" => "139951"
//    ),
//    'receiverLocation' => array(
//      "countryCode" => "SG",
//      "postalCode" => "610118"        
//    ),
//    'parcels' => array(
//        array(
//         "description" => "parcel1",
//         "dimension" => array (
//            "unit" => "cm",
//            "width"  => 10,
//            "height" => 15,
//            "length" => 25
//         )
//        ),
//        array(
//            "description" => "parcel2",
//            "weight" => array(
//               "unit" => "kg",
//               "value" => 2
//            )
//        ),
//        
//        array(
//            "description" => "parcel3",
//            "dimension" => array(
//               "unit" => "cm",
//               "width"  => 10,
//               "height" => 15,
//               "length" => 25
//            ),
//            "weight" => array (
//               "unit" => "kg",
//               "value" => 3
//            )
//        )
//    ),
//    "onlyServices" => array("AFTERNOON", "EVENING")
//);
//
//$query = json_encode($post_data);
//
//print_r($post_data);
//$options = array(
//    'http' => array(
//        'header' => "Authorization: bearer ".$access."\r\n".
//                    "Content-Type: application/json\r\n",
//        'method'  => "POST",
//        'content' => $query,
//    ),
//);
//
////print_r($options);
//$context = stream_context_create($options);
//$url = 'https://api.zyllem.org/api/v2/services/forecast';
//$result = file_get_contents($url, false, $context, -1, 40000);
//
//$arr = json_decode($result, true);
////print_r($arr['forecasts']);
//
//$forecasts = $arr['forecasts'];
//
//$results = array();
//$collections = array();
////loop forecasts array
//for ($i = 0; $i < count($forecasts); $i++) {
//    //get each day
//    $day = $forecasts[$i];
//    $thisday = $day['dayFormatted'];
////    echo $thisday;
////    echo "<br><br>";
////    print_r($day);
//    
//    $services = $day['services'];
//    //loop through services available for each day
//    for ($d = 0; $d < count($services); $d++) {
//        $service = $services[$d];
//        $serviceName = $service['serviceName']." ";
//        
//        //get delivery window
//        $deliver = $service['deliveryWindow'];
//        $start = date("H:i:s",strtotime($deliver['Start']));
//        $end = date("H:i:s",strtotime($deliver['End']));
//        $serviceName .= $start."-".$end;
//        array_push($results, $thisday." ".$serviceName);
//    }
//    
//    //add 1 week then get collection windows for that
//    $date = date_create(date("Y-m-d",strtotime($day['day'])));
//    date_add($date,date_interval_create_from_date_string("7 days"));
//    $collectionDate = date_format($date,"Y-m-d")."<br>";
//    
//    $collect_data = $post_data;
//    $collect_data['pickupTime'] = $collectionDate."T12:00:00+08:00";
//    
////    print_r($collect_data);
////    echo "<br><br>";
//}
//
//print_r($results);

require_once 'config/db.php';
//$filename = "statistics";
////create MySQL connection   
//$sql = "Select COUNT(pid) as count, pid from productstatistics where type = 'cartpurchase' group by pid order by count desc;";
//$result = mysqli_query($link, $sql) or die("Couldn't execute query:<br>" . mysql_error(). "<br>" . mysql_errno());    
//$file_ending = "xls";
////header info for browser
//header("Content-Type: application/xls");    
//header("Content-Disposition: attachment; filename=$filename.xls");  
//header("Pragma: no-cache"); 
//header("Expires: 0");
///*******Start of Formatting for Excel*******/   
////define separator (defines columns in excel & tabs in word)
//$sep = "\t"; //tabbed character
//$count = $result -> num_rows;
//
//$flag = false;
//while ($row = mysqli_fetch_assoc($result)) {
//    if (!$flag) {
//        // display field/column names as first row
//        echo implode("\t", array_keys($row)) . "\r\n";
//        $flag = true;
//    }
//    echo implode("\t", array_values($row)) . "\r\n";
//}

require_once 'PHPExcel/PHPExcel.php';

// Create new PHPExcel object
$objPHPExcel = new PHPExcel();

// Set document properties
$objPHPExcel->getProperties()->setLastModifiedBy("Visual Mass")
                            ->setTitle("Visual Mass Statistics ".date('d-m-Y'));

$alphas = range('A', 'Z');
$sql = "Select COUNT(pid) as count, pid from productstatistics where type = 'cartpurchase' group by pid order by count desc;";
$result = mysqli_query($link, $sql) or die("Couldn't execute query:<br>" . mysql_error(). "<br>" . mysql_errno());    

while ($row = mysqli_fetch_assoc($result)) {
    $keys = array_keys($row);
    for ($k = 0; $k < count($keys); $k++) {
        $objPHPExcel ->setActiveSheetIndex(0)
                -> setCellValue($alphas[$k]."1", $keys[$k]);
    }
    $valCount = 2;
    $vals = array_values($row);
    for($v = 0; $v < count($vals); $v++) {
        $objPHPExcel ->setActiveSheetIndex(0)
                -> setCellValue($alphas[$v].$valCount, $vals[$v]);
    }
}

// Rename worksheet
$objPHPExcel->getActiveSheet()->setTitle('Top 5 Items Added to Cart');


// Set active sheet index to the first sheet, so Excel opens this as the first sheet
//$objPHPExcel->setActiveSheetIndex(0);

$objPHPExcel->createSheet(2); //Setting index when creating
$filename = "VMstatistics_".date('dmY');
// Redirect output to a client’s web browser (Excel5)
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="'.$filename.'.xls"');
header('Cache-Control: max-age=0');
// If you're serving to IE 9, then the following may be needed
header('Cache-Control: max-age=1');

// If you're serving to IE over SSL, then the following may be needed
//header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
//header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
//header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
//header ('Pragma: public'); // HTTP/1.0

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
$objWriter->save('php://output');
exit;
?>