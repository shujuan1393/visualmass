<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
?>
        <script type="text/javascript" src="http://maps.google.com/maps/api/js?key=AIzaSyCR78jxaf-XgjrUTFxK-jfaj9J_anb-kRA"></script> 

<?php
//require_once('braintree/lib/Braintree.php');
//
//Braintree_Configuration::environment('sandbox');
//Braintree_Configuration::merchantId('t6f3x7thfrp85fxr');
//Braintree_Configuration::publicKey('zwffr27gfdksxmxz');
//Braintree_Configuration::privateKey('f4d205166ddd37027a37a5fed3cdbba5');
//
////echo($clientToken = Braintree_ClientToken::generate());
//
//$nonceFromTheClient = $_POST["payment_method_nonce"];
//
////echo $nonceFromTheClient;
//
//$result = Braintree_Transaction::sale([
//  'amount' => '10.00',
//  'paymentMethodNonce' => 'fake-valid-nonce',
//  'options' => [
//    'submitForSettlement' => True
//  ]
//]);
//
//$result->success;
//$transaction = $result->transaction;
//$transaction->status;
////echo $result;
//
//$orderid = "ON-".rand();
//echo $orderid;

// function to geocode address, it will return false if unable to geocode address
    function getCountry($address) {
        for ($i = 0; $i < count($address); $i++) {
            if(in_array("country", $address[$i]['types'])) {
                return $address[$i]['long_name'];
            }  
        }
    }
    function getZip($address) {
        for ($i = 0; $i < count($address); $i++) {
            if(in_array("postal_code", $address[$i]['types'])) {
                return $address[$i]['long_name'];
            }  
        }
    }
    function geocode($add){

        // url encode the address
        $address = urlencode($add);
        
        // google map geocode api url
        $url = "http://maps.google.com/maps/api/geocode/json?address={$address}";

        // get the json response
        $resp_json = file_get_contents($url);

        // decode the json
        $resp = json_decode($resp_json, true);

        // response status will be 'OK', if able to geocode given address 
        if($resp['status']=='OK'){
            // get the important data
            $count = count($resp['results']);
            
            $data_arr = array();  
            
            for ($i=0; $i < $count; $i++) {
                $formatted_address = $resp['results'][$i]['formatted_address'];
                $addrComp = $resp['results'][$i]['address_components'];
                $countryi = getCountry($addrComp);
                $zipi = getZip($addrComp);

                // verify if data is complete
                if($formatted_address && $countryi && $zipi){

                    // put the data in the array 
                    array_push(
                        $data_arr, 
                            $formatted_address,
                            $countryi,
                            $zipi
                        );
                }
            }
            if (!empty($data_arr)) {
                return $data_arr;
            } else {
                return false;
            }
        }else{
            return false;
        }
    }
    
    /******* GEOCODE ******/
?>

Country: <input type='text' id='country'> <br>
Zip: <input type='text' id='zip' name='zip'><br>
<input type='button' id='submit' value='submit'>
<div id='results'></div>
<script>                       
    document.getElementById('submit').onclick = function() {
        var zip = document.getElementById('zip').value;
        window.location = "test.php?zip=" + zip;
    }; 
                                            var count = 0;
</script>
                                <?php 
                                    if (isset($_GET['zip'])) {
                                        $str = $_GET['zip'];
                                        $data_arr = geocode($str);
                                        // get latitude, longitude and formatted address
    //                                    $data_arr = geocode($row['country']. " ". $row['zip']);

                                        // if able to geocode the address
                                        if($data_arr){
                                            $total = count($data_arr);
                                            $c = count($data_arr)/3;
                                        ?>
                                        <script>
                                            var str = "Total of <?php echo $c; ?> results found: <br>";
                                        </script>
                                        <?php
                                                                                print_r($data_arr);
                                            for ($i = 0; $i < $total; $i+= 3) {
                                                $formatted_address = $data_arr[$i];
                                                $country = $data_arr[$i+1];
                                                $zip = $data_arr[$i+2];
                                        ?>
                                        <script>
                                            var id = "res"+count;
                                            var val = "val" + count;
                                            str += "<div id='"+id+"'><?php echo $formatted_address; ?></div>";
                                            str += "<input type='hidden' id='"+val+"' value='<?php echo $country; ?>'>";
                                            count++;
                                        </script>
                                        <?php
                                            }
                                        ?>
                                        <script>
//                                            alert(document.getElementById('results'));
                                            document.getElementById('results').innerHTML = str;
                                        </script>
                                        <?php
                                        // if unable to geocode the address                                
                                        }else{
                                            echo "No map found.";
                                        }
                                    }
?>
<script>
    function handleElement(num) {
        var str = "res" + num;
        var value = "val" + num;
        document.getElementById(str).onclick = function() {
            var val = document.getElementById(value).value;
            document.getElementById('country').value = val;
        };
    };
    
    for (var i =0; i < count; i++) {
        handleElement(i);
    }
</script>