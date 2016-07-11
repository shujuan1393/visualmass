<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

//$access = "gAAAABij5nAcLjoZ6o9T7OsKvQJ5_hNePixM-Rimpenv2hhCWjKfsMfIVwL0pEif7UILlAZtJcPPR1FKQ59wP1tzjAWFIDbXSTbwGP-JJlDeivd-dRD_"
//        . "6fjYsqyGZLdJy6CPKtowkJz2V2E2_Tvh47L_Q_ivCTLpIEYmvG-4Rn8qsmInJAEAAIAAAABKTjHvwn6QgNCfO4KLOLcgJuo2naPu3rc798pJheft3FACi0HOO6U1G-iFS_"
//        . "mkhrGI6bQVtnW-M4gH_vRwjJ3l0_AGip3RkcwsDoFET0O_PaS1eR2wakxuHJpu-BUJ3rzCsJUsASez4p267XbbIx0bEo-1OIOV1AWWcDac4S3EN8XKrRRD3-7HRIS4GxxeYZv-T14E-j-z9Mch07_"
//        . "7EllQ5ymtqZpxoIcuafv47eewG1bvc9nLpAxOnyN4XtmJQyoE90gcPlQ3Dr165-1vXxp3o6qqHhmuYpUoMqJsULguz9oG4eiajT-p37t72cxOelm1fbfoRBBjtjwCwP53ObjNUpV4pgtoA6X8o2sHF-YHBCr2vc1aKML2PLfPO4gq54w";

$accpost_data = array(
    'grant_type' => 'client_credentials',
    'client_id' => 'uz33H94dsL2JE3fKKQOslXSuvs8Hp5Lc',
    'scope' => 'order services',
    'client_secret' => 'A05RrHBy0KcaLMhmxBdiQafc6CMMyRRW'
);

$accquery = http_build_query ($accpost_data);

$accoptions = array(
    'http' => array(
        'header' => "Content-Type: application/x-www-form-urlencoded \r\n".
                    "Cache-control: no-cache\r\n",
        'method'  => "POST",
        'content' => $accquery,
    ),
);

$acccontext = stream_context_create($accoptions);
$accurl = 'https://sg.zyllem.org/oauth/token';
$accresult = file_get_contents($accurl, false, $acccontext, -1, 40000);

//remove ending braces in result string
$len = strlen($accresult);
$arr = explode(",", substr($accresult, 1, $len-2));

//get first instance of array our -> access_token
$accessArr = explode(':', $arr[0]);

//extract access token
$accLen = strlen($accessArr[1]);
$access = substr($accessArr[1], 1, $accLen-2);