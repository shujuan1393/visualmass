<?php 
    require_once 'config/db.php';
    
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
    
    $userSql = "Select * from user where email='".$_SESSION['loggedUserEmail']."';";
    $ures = mysqli_query($link, $userSql);
    $row;
    if (!mysqli_query($link, $userSql)) {
        echo "Error: ".mysqli_error($link);
    } else {
        $row = mysqli_fetch_assoc($ures);
    } 
?>
<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<html>
    <head>
        <meta charset="UTF-8">
    </head>
    <body>
        
        <div id="wrapper">
            <div id="header"><?php require_once 'nav/header.php';?></div>
            
            <div id="content">
                <div class='row'>
                    <div class='col-md-8 col-md-offset-2'>
                        <h3>PROFILE</h3>
                        <a href='refer.php' data-toggle="modal" data-target="#referModal">CLICK HERE TO USE A REFERRAL CODE</a><br>
                        <div id='credit'>
                            <h5 class='caps'>Remaining Credit: 
                            <?php 
                                if (isset($row['credit'])) {
                                    echo $row['credit'];
                                }
                            ?>
                            </h5>
                        </div>
                        <div class='updateProfile' style='color: red'>
                            <p><?php 
                                if (isset($_SESSION['updateProfileError'])) {
                                    echo $_SESSION['updateProfileError'];
                                }
                            ?></p>
                        </div>
                        <div class='updateProfile' style='color: green'>
                            <p><?php 
                                if (isset($_SESSION['updateProfile'])) {
                                    echo $_SESSION['updateProfile'];
                                }
                            ?></p>
                        </div>
                        <form id='updateProfile' method="post" action='saveProfile.php' class='col-md-offset-2'>
                            <div class='row'>
                                <div class='col-md-4 col-md-offset-1'>First Name*: 
                                    <input type='text' name='firstname' 
                                           value='<?php if (isset($row['firstname'])) { echo $row['firstname']; }?>'></div>
                                <div class='col-md-4'>Last Name*: 
                                    <input type='text' name='lastname' 
                                           value='<?php if (isset($row['lastname'])) { echo $row['lastname']; }?>'></div>
                            </div>
                            <br>
                            <div class='row'>
                                <div class='col-md-8 col-md-offset-1'>Email*: 
                                    <input type='text' name='email' 
                                           value='<?php if (isset($row['email'])) {echo $row['email']; }?>'></div>
                            </div>
                            <br>
                            <div class='row'>
                                <div class='col-md-8 col-md-offset-1'>Change Password*: 
                                    <input type='password' name='password'></div>
                            </div>
                            <br>
                            <div class='row'>
                                <div class='col-md-8 col-md-offset-1'>Address*: 
                                    <textarea name='address'><?php if (isset($row['address'])) { echo $row['address']; }?></textarea>
                                </div>
                            </div>
                            <div class='row'>
                                <div class='col-md-4 col-md-offset-1'>Apt, suite*: 
                                    <input type='text' name='apt' value='<?php if (isset($row['apt'])) { echo $row['apt']; } ?>'>
                                </div>
                                <div class='col-md-4'>Zip Code*: 
                                    <input type='text' name='zip' id='zip' value='<?php if (isset($row['zip'])) { echo $row['zip']; } ?>' 
                                           onkeypress="return isNumber(event)" >
                                </div>
                            </div>
                            <div class='row'>
                                <div class='col-md-4 col-md-offset-1'>City*: 
                                    <input type='text' name='city' value='<?php if (isset($row['city'])) { echo $row['city']; } ?>'>
                                </div>
                                <div class='col-md-4'>Country*: 
                                    <input type='text' name='country' id='country' value='<?php if (isset($row['country'])) { echo $row['country']; } ?>'>
                                </div>
                            </div>
                            <div class='row'>
                                <div class='col-md-8 col-md-offset-1' id='results'></div>
                            </div>
                            <div class='row'>
                                <div class='col-md-8 col-md-offset-1'>Phone*: <input type='text' name='phone' 
                                                onkeypress="return isNumber(event)" value='<?php if (isset($row['phone'])) { echo $row['phone']; }?>'></div>
                            </div>
                            <p id='nanError' class='col-md-8 col-md-offset-1' style="display: none;">Please enter numbers only</p>
                            
                            <div class='row'>
                                <div class='col-md-8 col-md-offset-1'>
                                    <a id='showReferral' class='caps addMore'>Show My Referral Code</a>
                                </div>
                            </div>
                            <div class='row' id='showCode' style='display:none;'>
                                <div class='col-md-8 col-md-offset-1'>
                                    <?php 
                                        if (isset($row['code'])) {
                                            echo "<h5><strong>".$row['code']."</strong></h5>";
                                        }
                                    ?>
                                </div>
                            </div>
                            <div class='row'>
                                <div class='col-md-8 col-md-offset-1'>
                                    <input type='checkbox' name='marketing' id='marketing' value='yes'
                                           <?php 
                                                if (!empty($row['marketing'])) {
                                                    if (strcmp($row['marketing'], "yes") === 0) {
                                                        echo " checked";
                                                    }
                                                }
                                           ?>> I'd like to get emails from Visual Mass
                                </div>
                            </div>
                            
                            <div id='showPref' class='row' style='display:none;'>
                                <div class='col-md-6 col-md-offset-2'>
                                    <?php 
                                        $pref = "Select * from mailinglist where email='".$_SESSION['loggedUserEmail']."';";
                                        $prefres = mysqli_query($link, $pref);

                                        if (!mysqli_query($link, $pref)) {
                                            die(mysqli_error($link));
                                        } else {
                                            $row = mysqli_fetch_assoc($prefres);
                                            $pos = strpos($row['preference'], ",");
                                            if (is_numeric($pos)) {
                                                $prefArr = explode(",", $row['preference']);
                                            } else if (strcmp($row['preference'], "all") === 0) {  
                                                $prefArr = array("male", "female");
                                            } else {
                                                $prefArr = array($row['preference']);
                                            }
                                    ?>
                                    <input type='checkbox' name='preference[]' value='male' <?php 
                                        if (in_array("male", $prefArr)) {
                                            echo " checked";
                                        }
                                    ?>> Male
                                    <input type='checkbox' name='preference[]' value='female' <?php 
                                        if (in_array("female", $prefArr)) {
                                            echo " checked";
                                        }
                                    ?>> Female

                                    <?php
                                        }
                                    ?>
                                </div>
                            </div>
                            
                            <div class='row'>
                                <div class='col-md-6 col-md-offset-2'>
                                    <input type='submit' name='submit' value='SAVE PROFILE'>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            
            <div id="footer"><?php require_once 'nav/footer.php';?></div>
            
          <div class="modal fade" id="referModal" tabindex="-1" 
             role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog">
              <div class="modal-content">
                <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                  <h4 class="modal-title"></h4>
                </div>
                <div class="modal-body">
                </div>
                <div class="modal-footer">
                </div>
              </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
          </div><!-- /.modal -->
        </div>
    </body>
    <script>
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
                for ($i = 0; $i < $total; $i+= 3) {
                    $formatted_address = $data_arr[$i];
                    $country = $data_arr[$i+1];
                    $zip = $data_arr[$i+2];
            ?>
            <script>
                var id = "res"+count;
                var val = "val" + count;
                var zip = "zip" + count;
                str += "<div class='addMore' id='"+id+"'><?php echo $formatted_address; ?></div>";
                str += "<input type='hidden' id='"+val+"' value='<?php echo $country; ?>'>";
                str += "<input type='hidden' id='"+zip+"' value='<?php echo $zip; ?>'>";
                count++;
            </script>
            <?php
                }
            ?>
            <script>
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
            var zip = "zip" + num;
            document.getElementById(str).onclick = function() {
                var val = document.getElementById(value).value;
                var zipVal = document.getElementById(zip).value;
                document.getElementById('country').value = val;
                document.getElementById('zip').value = zipVal;
                document.getElementById('results').innerHTML = "";
            };
        };

        for (var i =0; i < count; i++) {
            handleElement(i);
        }
        
        document.getElementById('zip').onchange = function() {
            var zip = document.getElementById('zip').value;
            window.location = "profile.php?zip=" + zip;
        }; 
        function isNumber(evt) {
            evt = (evt) ? evt : window.event;
            var charCode = (evt.which) ? evt.which : evt.keyCode;
            if (charCode > 31 && (charCode < 48 || charCode > 57)) {
                document.getElementById('nanError').style.display='block';
                document.getElementById('nanError').style.color='red';
                return false;
            }
            document.getElementById('nanError').style.display='none';
            return true;
        }
        
        if (document.getElementById('marketing').checked) {
           document.getElementById('showPref').style.display = "block";            
        }
        document.getElementById('marketing').onclick = function(){ 
           document.getElementById('showPref').style.display = "block";
        };
        
        document.getElementById('showReferral').onclick = function() {
            var obj = document.getElementById('showCode');
                    
            if (obj.style.display === "block") {
                obj.style.display = "none";
            } else {
                obj.style.display = "block";
            }
        };
    </script>
   
</html>
