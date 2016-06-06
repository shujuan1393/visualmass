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
        <div id="forgetWrapper">
            <div class="rightheader close_modal">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">X</button>
            </div>
            <div class="logo"></div>
            <div id='login'>
                <div class="container">
                <div class="form_header">FORGOT YOUR PASSWORD?</div>
                <div id="forgetFormError" class="error">
                    <?php 
                        if (isset($_SESSION['forgetFormError'])) {
                            echo $_SESSION['forgetFormError'];
                        }
                    ?>
                </div>
               <form id='forgetForm'>
                    <input type='hidden' name='submitted' id='submitted' value='1'/>

                    <input type='text' name='email' id='email'  maxlength="50" placeholder="Email" /><br/>
                    <input type='submit' name='Submit' value='RE-SEND PASSWORD' />
                </form>
                </div>
            </div>
            <!--<div id="footer"><?php // require_once 'nav/footer.php';?></div>-->
        </div>
    </body>
    <script>
        $('#forgetForm').validate({
            rules: {
                email: {
                    email: true,
                    required: true
                }
            },
            highlight: function(element) {
            $(element).closest('.control-group').removeClass('success').addClass('error');
            },
            submitHandler: function(form) {
                $.ajax({
                    type:"POST",
                    url: "processLogin.php?forget=1",
                    data: $(form).serialize(),
                    }).done(function(data) {
                    $('#forgetWrapper').replaceWith(data);
                    });
            }
        });
    </script>
</html>
