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
        <div id="loginWrapper">
            <!--<div id="header"><?php // require_once 'nav/header.php';?></div>-->
            <div class="rightheader close_modal">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">X</button>
            </div>
            <div class="logo"></div>
            <div id='login'>
                <div class="container">
                <div class="form_header">Sign in</div>
                <div id="loginFormError" class="error">
                    <?php 
                        if (isset($_SESSION['loginFormError'])) {
                            echo $_SESSION['loginFormError'];
                        }
                    ?>
                </div>
                <!--action='processLogin.php' method='post' accept-charset='UTF-8'-->
                <form id='loginForm'>
                    <input type='hidden' name='submitted' id='submitted' value='1'/>

                    <input type='text' name='email' id='email'  maxlength="50" placeholder="Email" /><br/>
                    <input type='password' name='password' id='password' maxlength="50" placeholder="Password" /><br/>
                    <input type='submit' name='Submit' value='Sign in' />
                </form>
                </div>
            </div>
            <!--<div id="footer"><?php // require_once 'nav/footer.php';?></div>-->
        </div>
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
</html>
