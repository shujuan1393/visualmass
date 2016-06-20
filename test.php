<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
?>

  <link rel="stylesheet" href="chosen/style.css">
  <link rel="stylesheet" href="chosen/prism.css">
  <link rel="stylesheet" href="chosen/chosen.css">
  
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.5/css/bootstrap.min.css">
    <!--<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.5/css/bootstrap-theme.min.css">-->
      <link rel="stylesheet" href="tags-input/bootstrap-tagsinput.css">
    <!--<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/rainbow/1.2.0/themes/github.css">-->
    <link rel="stylesheet" href="tags-input/app.css">
<script type="text/javascript" src="http://maps.google.com/maps/api/js?key=AIzaSyCR78jxaf-XgjrUTFxK-jfaj9J_anb-kRA"></script> 

<link rel="stylesheet" href="selectize/css/normalize.css">
<link rel="stylesheet" href="selectize/css/stylesheet.css">
		<!--[if IE 8]><script src="js/es5.js"></script><![endif]-->
<script src="selectize/js/jquery.js"></script>
<script src="selectize/js/standalone/selectize.js"></script>
<script src="selectize/js/index.js"></script>
<style type="text/css">
		.selectize-control.contacts .selectize-input > div {
			padding: 1px 10px;
			font-size: 13px;
			font-weight: normal;
			-webkit-font-smoothing: auto;
			color: #f7fbff;
			text-shadow: 0 1px 0 rgba(8,32,65,0.2);
			background: #2183f5;
			background: -moz-linear-gradient(top, #2183f5 0%, #1d77f3 100%);
			background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,#2183f5), color-stop(100%,#1d77f3));
			background: -webkit-linear-gradient(top,  #2183f5 0%,#1d77f3 100%);
			background: -o-linear-gradient(top,  #2183f5 0%,#1d77f3 100%);
			background: -ms-linear-gradient(top,  #2183f5 0%,#1d77f3 100%);
			background: linear-gradient(to bottom,  #2183f5 0%,#1d77f3 100%);
			filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#2183f5', endColorstr='#1d77f3',GradientType=0 );
			border: 1px solid #0f65d2;
			-webkit-border-radius: 999px;
			-moz-border-radius: 999px;
			border-radius: 999px;
			-webkit-box-shadow: 0 1px 1px rgba(0,0,0,0.15);
			-moz-box-shadow: 0 1px 1px rgba(0,0,0,0.15);
			box-shadow: 0 1px 1px rgba(0,0,0,0.15);
		}
		.selectize-control.contacts .selectize-input > div.active {
			background: #0059c7;
			background: -moz-linear-gradient(top, #0059c7 0%, #0051c1 100%);
			background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,#0059c7), color-stop(100%,#0051c1));
			background: -webkit-linear-gradient(top,  #0059c7 0%,#0051c1 100%);
			background: -o-linear-gradient(top,  #0059c7 0%,#0051c1 100%);
			background: -ms-linear-gradient(top,  #0059c7 0%,#0051c1 100%);
			background: linear-gradient(to bottom,  #0059c7 0%,#0051c1 100%);
			filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#0059c7', endColorstr='#0051c1',GradientType=0 );
			border-color: #0051c1;
		}
		.selectize-control.contacts .selectize-input > div .email {
			opacity: 0.8;
		}
		.selectize-control.contacts .selectize-input > div .name + .email {
			margin-left: 5px;
		}
		.selectize-control.contacts .selectize-input > div .email:before {
			content: '<';
		}
		.selectize-control.contacts .selectize-input > div .email:after {
			content: '>';
		}
		.selectize-control.contacts .selectize-dropdown .caption {
			font-size: 12px;
			display: block;
			color: #a0a0a0;
		}
		</style>

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

<!--Country: <input type='text' id='country'> <br>
Zip: <input type='text' id='zip' name='zip'><br>
<input type='button' id='submit' value='submit'>
<div id='results'></div>
<script>                       
    document.getElementById('submit').onclick = function() {
        var zip = document.getElementById('zip').value;
        window.location = "test.php?zip=" + zip;
    }; 
                                            var count = 0;
</script>-->
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

<html>
    <div class="control-group">
            <select id="select-to" class="contacts" placeholder="Pick some people..."></select>
    </div>
    <script>
    $('#select-to').selectize({
            persist: false,
            maxItems: null,
            valueField: 'email',
            labelField: 'name',
            searchField: ['tag', 'last_name', 'email'],
            sortField: [
                    {field: 'first_name', direction: 'asc'},
                    {field: 'last_name', direction: 'asc'}
            ],
            options: [
                    {email: 'nikola@tesla.com', tag: 'Nikola', last_name: 'Tesla'},
                    {email: 'brian@thirdroute.com', tag: 'Brian', last_name: 'Reavis'},
                    {email: 'someone@gmail.com'}
            ],
            render: {
                    item: function(item, escape) {
                            var name = item.tag;
                            return '<div>' +
                                    (item.email ? '<span>' + escape(item.email) + '</span>' : '') +
                            '</div>';
                    },
                    option: function(item, escape) {
                            var name = item.tag;
                            var label = name || item.email;
                            var caption = name ? item.email : null;
                            return '<div>' +
                                    '<span class="label">' + escape(label) + '</span>' +
                                    (caption ? '<span class="caption">' + escape(caption) + '</span>' : '') +
                            '</div>';
                    }
            },
            
            create: function(input) {
//                    if ((new RegExp('^' + REGEX_EMAIL + '$', 'i')).test(input)) {
                            return {email: input};
//                    }
            }
    });
    </script>
</html>
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
<script type="text/javascript" src="selectize/js/selectize.js"></script>
<link rel="stylesheet" type="text/css" href="selectize/css/selectize.css" />
<script>
$(function() {
    $('#select-to').selectize(options);
});
</script>
<!--<script>
$('#select-to').selectize({
    persist: false,
    maxItems: null,
    valueField: 'tag',
    labelField: 'name',
    searchField: ['name', 'tag'],
    options: [
        {tag: 'brian@thirdroute.com', name: 'Brian Reavis'},
        {tag: 'nikola@tesla.com', name: 'Nikola Tesla'},
        {tag: 'someone@gmail.com'}
    ],
    render: {
        item: function(item, escape) {
            return '<div>' +
                (item.name ? '<span class="name">' + escape(item.name) + '</span>' : '') +
                (item.email ? '<span class="tag">' + escape(item.tag) + '</span>' : '') +
            '</div>';
        },
        option: function(item, escape) {
            var label = item.name || item.tag;
            var caption = item.name ? item.tag : null;
            return '<div>' +
                '<span class="label">' + escape(label) + '</span>' +
                (caption ? '<span class="caption">' + escape(caption) + '</span>' : '') +
            '</div>';
        }
    },
    create: function(input) {
        return {tag: input};
    }
});
</script>-->

<!--    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/angular.js/1.2.20/angular.min.js"></script>
<script src="tags-input/bootstrap-tagsinput.min.js"></script>
<script src="tags-input/bootstrap-tagsinput/bootstrap-tagsinput-angular.min.js"></script>
    <script src="assets/app.js"></script>
    <script src="assets/app_bs3.js"></script>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.6.4/jquery.min.js" type="text/javascript"></script>
  <script src="chosen/js/chosen.jquery.js" type="text/javascript"></script>
  <script src="chosen/js/prism.js" type="text/javascript" charset="utf-8"></script>
  <script type="text/javascript">
    var config = {
      '.chosen-select'           : {},
      '.chosen-select-deselect'  : {allow_single_deselect:true},
      '.chosen-select-no-single' : {disable_search_threshold:10},
      '.chosen-select-no-results': {no_results_text:'Oops, nothing found!'},
      '.chosen-select-width'     : {width:"95%"}
    }
    for (var selector in config) {
      $(selector).chosen(config[selector]);
    }
    $('input').tagsinput('add', 'some tag', {preventPost: true});
  </script>-->