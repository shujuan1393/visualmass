<?php
    require_once 'config/db.php';
    require_once 'config/braintree.php';
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
                <h3>CHECKOUT</h3>
                
                <div id='guest' class='col-md-12'>
                <?php
                    if (isset($_SESSION['order'])) {
                        echo $_SESSION['order'];
                    }
                ?>
                <?php 
                    //get guest checkout settings
                    $guest = "Select * from settings where type='checkout';";
                    $gres = mysqli_query($link, $guest);
                    
                    if (!mysqli_query($link, $guest)) {
                        die(mysqli_error($link));
                    } else {
                        $row = mysqli_fetch_assoc($gres);
                        $valArr = explode("&", $row['value']);
                        
                        $guestPerm = explode("guest=", $valArr[2]);
                        $permission = $guestPerm[1];
                    }
                
                    if (!isset($_SESSION['loggedUserEmail']) || isset($_GET['error'])) {
                ?>
                    <p>CIRCLE CIRCLE THING HERE (PART 1 - ACCOUNT)</p>
                    <div id='newCust' class='col-md-6'>
                        <h4>I'm new here</h4>
                        <?php if(strcmp($permission, "no") === 0) { ?>
                        <h5>What are you waiting for?</h5>
                        <p id='signup' class='addMore'>Create a new account now!</p>
                        <p id='backButton' class='addMore' style='display:none;'> < Back </p>
                        <?php } else if (strcmp($permission, "yes") === 0) { ?>
                        <p><a id='guestCheckout' class='addMore'>Continue with guest checkout ></a></p>
                        <?php } ?>
                    </div>
                    <div id='signupNow' class='col-md-6' style='display: none;'>
                        <h4>CREATE A NEW ACCOUNT</h4>
                        
                        <div id="signUpError" class="error">
                            <?php 
                                if (isset($_SESSION['signUpError'])) {
                                    echo $_SESSION['signUpError'];
                                }
                            ?>
                        </div>
                       <form id='signupForm' action='processSignup.php?checkout=1' method='post' accept-charset='UTF-8'>
                            <input type='hidden' name='submitted' id='submitted' value='1'/>

                            <input type='text' name='firstName' id='firstName'  maxlength="50" placeholder="First Name" /><br>
                            <input type='text' name='lastName' id='lastName'  maxlength="50" placeholder="Last Name" /><br>
                            <input type='text' name='email' id='email'  maxlength="50" placeholder="Email" /><br>
                            <input type='password' name='password' id='password' maxlength="50" placeholder="Password" /><br>
                            <input type='submit' name='Submit' value='Create account' />

                        </form>
                    </div>
                    
                    <div id='existingCust' class='col-md-6'>
                        <h4>SIGN IN</h4>
                        <div id="loginFormError" class="error">
                            <?php 
                                if (isset($_SESSION['loginFormError'])) {
                                    echo $_SESSION['loginFormError'];
                                }
                            ?>
                        </div>
                        <!--action='processLogin.php' method='post' accept-charset='UTF-8'-->
                        <form id='loginForm' action='processLogin.php?checkout=1' method='post'>
                            <input type='hidden' name='submitted' id='submitted' value='1'/>

                            <input type='text' name='email' id='email'  maxlength="50" placeholder="Email" /><br/>
                            <input type='password' name='password' id='password' maxlength="50" placeholder="Password" /><br/>
                            <input type='submit' name='Submit' value='Sign in' />
                        </form>
                    </div>
                <?php
                    }
                ?>
                </div>
                <div id='particulars' style="display:none;" class='col-md-12'>
                    <p>CIRCLE CIRCLE THING HERE (PART 2 - PARTICULARS)</p>
                    <?php
                    if (isset($_SESSION['loggedUserEmail'])) {
                        $users = "Select * from user where email='".$_SESSION['loggedUserEmail']."';";
                        $ures = mysqli_query($link, $users);
                        
                        if (!mysqli_query($link, $users)) {
                            die(mysqli_error($link));
                        } else {
                            $urow = mysqli_fetch_assoc($ures);
                            if (empty($urow['address']) && empty($urow['apt']) && empty($urow['country']) && empty($urow['zip'])) {
                                $add = "";
                            } else {
                                $add = $urow['address']." ".$urow['apt'].", ".$urow['country']." ".$urow['zip'];
                            }
                        }
                    }
                            
                    $count = 0;
                    ?>
                    <div class='col-md-6 col-md-offset-3'>
                        <p id='nanError' style="display: none;">Please enter numbers only</p>
                        <form method='post' action='review.php'>
                            <?php if (!isset($_SESSION['loggedUserEmail'])) { ?>
                                <input type='hidden' id='isGuest' name='isGuest' value='yes'>
                            <?php } ?>
                            <input type='text' name='firstname' id='firstname'  maxlength="50" placeholder="FIRST NAME" 
                                   value='<?php if (!empty($urow['firstname'])) { echo $urow['firstname']; } ?>'/><br/>
                            <input type='text' name='lastname' id='lastname'  maxlength="50" placeholder="LAST NAME" 
                                   value='<?php if (!empty($urow['lastname'])) { echo $urow['lastname']; } ?>'/><br/>
                            <input type='text' name='email' id='email'  maxlength="50" placeholder="EMAIL" 
                                   value='<?php if (!empty($urow['email'])) { echo $urow['email']; } ?>'/><br/>
                            <input type='text' name='address' id='address'  maxlength="50" placeholder="STREET ADDRESS" 
                                   value='<?php if (!empty($add)) { echo $add; } ?>'/><br/>
                            <input type='text' name='phone' id='phone'  maxlength="50" placeholder="PHONE" 
                                   value='<?php if (!empty($urow['phone'])) { echo $urow['phone']; } ?>' onkeypress='return isNumber(event)'/><br/>
                            
                            <input type='hidden' name='hiddenaddress' id='hiddenaddress' 
                                   value='<?php if (!empty($urow['address'])) { echo $urow['address']; } ?>'/>
                            <input type='hidden' name='apt' id='apt' 
                                   value='<?php if (!empty($urow['apt'])) { echo $urow['apt']; } ?>'/>
                            <input type='hidden' name='country' id='country' 
                                   value='<?php if (!empty($urow['country'])) { echo $urow['country']; } ?>'/>
                            <input type='hidden' name='zip' id='zip'
                                   value='<?php if (!empty($urow['zip'])) { echo $urow['zip']; } ?>'/>
                            
                            <?php if (isset($_SESSION['hometrydeliver'])) { ?>
                            <div id='hometrydeliver' class='col-md-6 col-md-offset-3'>
                            <h5 class='caps'>Home Try-On Delivery Options</h5>
                            <input type='hidden' name='selectedDeliveryDate' id='selectedDeliveryDate'>
                            <div id='deliverError' class='error' style='display:none;'>No timings selected</div>
                            <p>Select the most suitable date and timing(s) to start your 1-week trial</p>
                                <input type="checkbox" name="timings[]" value="MORNING"> Morning 
                                <input type="checkbox" name="timings[]" value="AFTERNOON"> Afternoon
                                <input type="checkbox" name="timings[]" value="EVENING"> Evening  
                                <input type='hidden' name='selectedDate' id='selectedDate'>
                                <div id='calendar' class='col-md-3' style='height: 300px;'></div>
                            </div>
                            <button id='getTimes' class='caps button'>Get Timings</button>
                            <?php } ?>
                            <br>
                            <?php if (isset($_SESSION['deliveryTimings'])) { ?>
                            <div id='deliveryAvailability' class='col-md-6 col-md-offset-3'>
                                <?php 
                                    $deliveryArr = $_SESSION['deliveryTimings'];
                                    $selectedDate = $deliveryArr[0]['date'];
                                    $date = date("d M y",strtotime($selectedDate));
                                    echo "<p>For your selected date, ".$date.",</p>";
                                    echo "<p> there are ".count($deliveryArr[0]['deliveryWindow'])." available slots</p>";
                                    $slots = $deliveryArr[0]['deliveryWindow'];
                                    for ($s = 0; $s < count($slots); $s++) {
                                        $slot = explode(" ",$slots[$s]);
                                        $time = count($slot)-1;
                                        echo "<input type='radio' name='time' id='time".$count."' value='".$slots[$s]."'>&nbsp;"
                                                .$slot[$time]."<br>";
                                        $count++;
                                    }
                                ?>
                                <br>
                                Delivery Comments (if any): <br>
                                <input type='text' name='comments' id='comments'><br>
                                <p>Please select another date/timing if you do not find any suitable options</p>
                            </div>
                            <?php } ?>
                            <div class='col-md-6 col-md-offset-3'>
                                <h5>Bill to</h5>
                                <div id='payments'>
                                <input type='radio' name='payment' id='credit' value='visa/mastercard'> Visa / Mastercard <br>
                                <input type='radio' name='payment' id='braintree' value='braintree/paypal'> BrainTree / PayPal<br>
                                <input type='radio' name='payment' id='apple' value='apple'> Apple Pay <br>
                                <br>
                                </div>
                                <div id='card' style='display:none;'><input type='text' name='card' id='card'  maxlength="50" placeholder="CARD DETAILS" 
                                                                            value='<?php if (!empty($urow['phone'])) { echo $urow['phone']; } ?>'/><br/></div>

                                    <div id="braintree-pay" style='display:none;'></div>

                                    <input type='submit' name='submit' value='PROCEED TO REVIEW'>
                            </div>
                        </form>
                    </div>

                </div>
            </div>
            
            <div id="footer"><?php require_once 'nav/footer.php';?></div>
        </div>
    </body>
    <script>
        function getRadio(num) {
            var str = "time"+num;
            
            document.getElementById(str).onclick = function() {
                var val = this.value;
                if (val !== null) {
                    document.getElementById('selectedDeliveryDate').value = val;
                }
            };
        }
        
        for(var i = 0; i < <?php echo $count; ?>; i++) {
            getRadio(i);
        }
        
        var timesBtn = document.getElementById('getTimes');
        
        if (timesBtn !== null) {
            timesBtn.onclick = function() {
                //get values from timing checkbox
                var checkboxes = document.getElementsByName('timings[]');
                var vals = "";
                for (var i=0, n=checkboxes.length;i<n;i++) {
                  if (checkboxes[i].checked) 
                  {
                  vals += ","+checkboxes[i].value;
                  }
                }
                if (vals) vals = vals.substring(1);
                
                if (vals !== "") {
                    document.getElementById('deliverError').style.display = "none";
                    //get address & selected date & pass to another page to process (via AJAX)
                    var date = document.getElementById('selectedDate').value;
                    var add = document.getElementById('hiddenaddress').value;
                    var zip = document.getElementById('zip').value;
                    var country = document.getElementById('country').value;
                    var apt = document.getElementById('apt').value;
                    var userid = <?php echo "'".GetCartId()."'"; ?>;
                    window.location = "deliveryTimings.php?id="+userid+"&date="+date+"&add="+add
                    +"&country="+country+"&zip="+zip+"&apt="+apt+"&timing="+vals;
                } else {
                    document.getElementById('deliverError').style.display = "block";
                }
            };
        }
        
        //handle calendar events
        var myCalendar = new dhtmlXCalendarObject("calendar");
        myCalendar.hideTime();
        myCalendar.show();
        myCalendar.setPosition(null, null);
        var myEvent = myCalendar.attachEvent("onClick", function(){
            var date = myCalendar.getDate(true);
            document.getElementById('selectedDate').value = date;
        });
        
        var obj = document.getElementById('guestCheckout');
        if (obj !== null) {
            obj.onclick = function() {
                window.location = "checkout.php?signin=1";
            };
        }
        
       <?php 
            if (isset($_GET['signin']) || isset($_SESSION['loggedUserEmail'])) {
        ?>
            document.getElementById('guest').style.display = "none";
            document.getElementById('particulars').style.display = "block";
        <?php
            }
       ?>
        var obj = document.getElementById('signup');
        
        if (obj !== null) {
            obj.onclick = function() {
                document.getElementById('existingCust').style.display = "none";
                document.getElementById('signupNow').style.display = "block";
                obj.style.display = "none";
                document.getElementById('backButton').style.display = "block";
            };
        }
        
        <?php if(isset($_GET['error']) && isset($_GET['signup'])) { ?>
                document.getElementById('existingCust').style.display = "none";
                document.getElementById('signupNow').style.display = "block";
                obj.style.display = "none";
                document.getElementById('backButton').style.display = "block";
        <?php } ?>
        var back = document.getElementById('backButton');
        
        if (back !== null) {
            back.onclick = function() {
                document.getElementById('existingCust').style.display = "block";
                document.getElementById('signupNow').style.display = "none";
                back.style.display = "none";
                document.getElementById('signup').style.display = "block";
            };
        }
        
        document.getElementById('credit').onclick = function() {
            document.getElementById('braintree-pay').style.display = "none";
            document.getElementById('card').style.display = "block";
        };
        
        document.getElementById('braintree').onclick = function() {
            document.getElementById('card').style.display = "none";
            document.getElementById('braintree-pay').style.display = "block";
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
        
        // We generated a client token for you so you can test out this code
        // immediately. In a production-ready integration, you will need to
        // generate a client token on your server (see section below).
        var clientToken = "eyJ2ZXJzaW9uIjoyLCJhdXRob3JpemF0aW9uRmluZ2VycHJpbnQiOiI2MTFhMDhjNzIxMTM0NDcyNTU0MjdiZDFlNjhiZjE3MGVhZDViNWQ0NmQ3MGE4MDQzMTE2YmJjMzlmMzU5ZDZmfGNyZWF0ZWRfYXQ9MjAxNi0wNi0xMlQwNzozMTozMi4wMTQ4OTE1NjcrMDAwMFx1MDAyNm1lcmNoYW50X2lkPTM0OHBrOWNnZjNiZ3l3MmJcdTAwMjZwdWJsaWNfa2V5PTJuMjQ3ZHY4OWJxOXZtcHIiLCJjb25maWdVcmwiOiJodHRwczovL2FwaS5zYW5kYm94LmJyYWludHJlZWdhdGV3YXkuY29tOjQ0My9tZXJjaGFudHMvMzQ4cGs5Y2dmM2JneXcyYi9jbGllbnRfYXBpL3YxL2NvbmZpZ3VyYXRpb24iLCJjaGFsbGVuZ2VzIjpbXSwiZW52aXJvbm1lbnQiOiJzYW5kYm94IiwiY2xpZW50QXBpVXJsIjoiaHR0cHM6Ly9hcGkuc2FuZGJveC5icmFpbnRyZWVnYXRld2F5LmNvbTo0NDMvbWVyY2hhbnRzLzM0OHBrOWNnZjNiZ3l3MmIvY2xpZW50X2FwaSIsImFzc2V0c1VybCI6Imh0dHBzOi8vYXNzZXRzLmJyYWludHJlZWdhdGV3YXkuY29tIiwiYXV0aFVybCI6Imh0dHBzOi8vYXV0aC52ZW5tby5zYW5kYm94LmJyYWludHJlZWdhdGV3YXkuY29tIiwiYW5hbHl0aWNzIjp7InVybCI6Imh0dHBzOi8vY2xpZW50LWFuYWx5dGljcy5zYW5kYm94LmJyYWludHJlZWdhdGV3YXkuY29tLzM0OHBrOWNnZjNiZ3l3MmIifSwidGhyZWVEU2VjdXJlRW5hYmxlZCI6dHJ1ZSwicGF5cGFsRW5hYmxlZCI6dHJ1ZSwicGF5cGFsIjp7ImRpc3BsYXlOYW1lIjoiQWNtZSBXaWRnZXRzLCBMdGQuIChTYW5kYm94KSIsImNsaWVudElkIjpudWxsLCJwcml2YWN5VXJsIjoiaHR0cDovL2V4YW1wbGUuY29tL3BwIiwidXNlckFncmVlbWVudFVybCI6Imh0dHA6Ly9leGFtcGxlLmNvbS90b3MiLCJiYXNlVXJsIjoiaHR0cHM6Ly9hc3NldHMuYnJhaW50cmVlZ2F0ZXdheS5jb20iLCJhc3NldHNVcmwiOiJodHRwczovL2NoZWNrb3V0LnBheXBhbC5jb20iLCJkaXJlY3RCYXNlVXJsIjpudWxsLCJhbGxvd0h0dHAiOnRydWUsImVudmlyb25tZW50Tm9OZXR3b3JrIjp0cnVlLCJlbnZpcm9ubWVudCI6Im9mZmxpbmUiLCJ1bnZldHRlZE1lcmNoYW50IjpmYWxzZSwiYnJhaW50cmVlQ2xpZW50SWQiOiJtYXN0ZXJjbGllbnQzIiwiYmlsbGluZ0FncmVlbWVudHNFbmFibGVkIjp0cnVlLCJtZXJjaGFudEFjY291bnRJZCI6ImFjbWV3aWRnZXRzbHRkc2FuZGJveCIsImN1cnJlbmN5SXNvQ29kZSI6IlVTRCJ9LCJjb2luYmFzZUVuYWJsZWQiOmZhbHNlLCJtZXJjaGFudElkIjoiMzQ4cGs5Y2dmM2JneXcyYiIsInZlbm1vIjoib2ZmIn0=";
        <?php $clientToken = Braintree_ClientToken::generate(); ?>
        braintree.setup('<?php echo $clientToken; ?>', "dropin", {
            container: "braintree-pay"
        });
    </script>
</html>