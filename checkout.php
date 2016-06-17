<?php
    require_once 'config/db.php';
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
                    if (!isset($_SESSION['loggedUserEmail']) || isset($_GET['error'])) {
                ?>
                    <p>CIRCLE CIRCLE THING HERE (PART 1 - ACCOUNT)</p>
                    <div id='newCust' class='col-md-6'>
                        <h4>I'm new here</h4>
                        <p>Create a new account now!</p>
                        <p>OR</p>
                        <p><a id='guestCheckout'>Continue with guest checkout ></a></p>
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
                        $users = "Select * from user where email='".$_SESSION['loggedUserEmail']."';";
                        $ures = mysqli_query($link, $users);
                        
                        if (!mysqli_query($link, $users)) {
                            die(mysqli_error($link));
                        } else {
                            $urow = mysqli_fetch_assoc($ures);
                            
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
                                       value='<?php if (!empty($urow['address'])) { echo $urow['address']; } ?>'/><br/>
                                <input type='text' name='phone' id='phone'  maxlength="50" placeholder="PHONE" 
                                       value='<?php if (!empty($urow['phone'])) { echo $urow['phone']; } ?>' onkeypress='return isNumber(event)'/><br/>
                                
                                <h5>Bill to</h5>
                                <div id='payments'>
                                <input type='radio' name='payment' id='credit' value='visa/mastercard'> Visa / Mastercard <br>
                                <input type='radio' name='payment' id='braintree' value='braintree/paypal'> BrainTree / PayPal<br>
                                <input type='radio' name='payment' id='apple' value='apple'> Apple Pay <br>
                                <br>
                                </div>
                                <div id='card' style='display:none;'><input type='text' name='card' id='card'  maxlength="50" placeholder="CARD DETAILS" 
                                                                            value='<?php if (!empty($urow['phone'])) { echo $urow['phone']; } ?>'/><br/></div>
                                
                                <!--<form id="checkout" method="post" action="/checkout">-->
                                    <div id="braintree-pay" style='display:none;'></div>
<!--                                    <input type="submit" value="Pay $10">
                                </form>-->
                                
                                    <input type='submit' name='submit' value='PROCEED TO REVIEW'>
                            </form>
                        </div>
                    <?php
                        }
                    ?>
                </div>
            </div>
            
            <div id="footer"><?php require_once 'nav/footer.php';?></div>
        </div>
    </body>
    <script>
        document.getElementById('guestCheckout').onclick = function() {
            window.location = "checkout.php?signin=1";
        };
       <?php 
            if (isset($_GET['signin']) || isset($_SESSION['loggedUserEmail'])) {
        ?>
            document.getElementById('guest').style.display = "none";
            document.getElementById('particulars').style.display = "block";
        <?php
            }
       ?>
           
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