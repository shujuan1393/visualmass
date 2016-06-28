<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
require_once 'config/db.php';
?>
<head>
    <link href="styles.css" rel="stylesheet" type="text/css" />
</head>
<html>
    <body>
        <div id="fb-root"></div>
        
        <div id="loginWrapper">
            <!--<div id="header"><?php // require_once 'nav/header.php';?></div>-->
            <div class="rightheader close_modal">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">X</button>
            </div>
            <div class="logo"></div>
            <div id='login'>
                <div class="container">
                <div class="form_header">Sign in</div>
                <div id="loginFormError" style="color: green;">
                <?php 
                    if (isset($_GET['favourite'])) {
                        echo "<p>Login to your Visual Mass account so you can store your favourites</p>";
                    }
                    if (isset($_GET['reset'])) {
                        echo "<p>A new password has been sent to your email</p>";
                    }
                ?>
                </div>
                <div id="loginFormError" class="error">
                    <?php 
                        if (isset($_SESSION['loginFormError'])) {
                            echo $_SESSION['loginFormError'];
                        }
                    ?>
                </div>
                <!--action='processLogin.php' method='post' accept-charset='UTF-8'-->
                <a href='#' onclick='login();'>Facebook Login</a>
                
                <form id='loginForm'>
                    <?php 
                        if (isset($_GET['favourite'])) {
                           echo "<input type='hidden' name='addToCart' value='".$_GET['id']."'>";
                        }
                    ?>
                    <input type='hidden' name='submitted' id='submitted' value='1'/>

                    <input type='text' name='email' id='email'  maxlength="50" placeholder="Email" /><br/>
                    <input type='password' name='password' id='password' maxlength="50" placeholder="Password" /><br/>
                    <input type='submit' name='Submit' value='Sign in' />
                </form>
                <a data-toggle="modal" href="forgetPassword.php" data-target="#forgetModal">FORGOT PASSWORD?</a>
                <br>
                <a data-toggle="modal" href="signUp.php" data-target="#signupModal">NEW USER?</a>
                </div>
            </div>
            <!--<div id="footer"><?php // require_once 'nav/footer.php';?></div>-->
        </div>
        
        <div class="modal fade modal-fullscreen force-fullscreen" id="signupModal" tabindex="-1" 
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
                  <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                  <button type="button" class="btn btn-primary">Save changes</button>
                </div>
              </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
          </div><!-- /.modal -->
        
        <div class="modal fade modal-fullscreen force-fullscreen" id="forgetModal" tabindex="-1" 
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
                  <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                  <button type="button" class="btn btn-primary">Save changes</button>
                </div>
              </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
          </div><!-- /.modal -->
    </body>
    <script>
        $('#loginForm').validate({
            rules: {
                email: {
                    email: true,
                    required: true
                },
                password: {
                    required: true
                }
            },
            highlight: function(element) {
            $(element).closest('.control-group').removeClass('success').addClass('error');
            },
            submitHandler: function(form) {
                $.ajax({
                    type:"POST",
                    url: "processLogin.php",
                    data: $(form).serialize(),
                    }).done(function(data) {
                    $('#loginWrapper').replaceWith(data);
                    });
            }
        });
    </script>
<script type="text/javascript">
//<![CDATA[
window.fbAsyncInit = function() {
   FB.init({
     appId      : '666771283478329', // App ID
     channelURL : '', // Channel File, not required so leave empty
     status     : true, // check login status
     cookie     : true, // enable cookies to allow the server to access the session
     oauth      : true, // enable OAuth 2.0
     xfbml      : false  // parse XFBML
   });
};
// logs the user in the application and facebook
function login(){
FB.getLoginStatus(function(r){
     if(r.status === 'connected'){
            window.location.href = 'fbconnect.php';
     }else{
        FB.login(function(response) {
                if(response.authResponse) {
              //if (response.perms)
                    window.location.href = 'fbconnect.php';
            } else {
              // user is not logged in
            }
     },{scope:'email'}); // which data to access from user profile
 }
});
}
// Load the SDK Asynchronously
(function() {
   var e = document.createElement('script'); e.async = true;
   e.src = document.location.protocol + '//connect.facebook.net/en_US/all.js';                
   document.getElementById('fb-root').appendChild(e);
}());
//]]>
</script>
</html>
