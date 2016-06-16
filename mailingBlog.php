<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
require_once 'config/db.php';

if (isset($_POST['submitted'])) {
    $email = $_POST['mailingemail'];
    if (!empty($_POST['preference'])){
        $preference = $_POST['preference'];
    }
    
    $getmail = "Select * from mailinglist where email = '$email';";
    $mailresult = mysqli_query($link, $getmail);
    
    if (!mysqli_query($link, $getmail)) {
        die(mysqli_error($link));
    } else {
        if ($mailresult -> num_rows === 0) {
            unset($_SESSION['mailError']);
            $sql = "INSERT INTO mailinglist (email, preference) VALUES ('$email', 'all');";
            
            mysqli_query($link, $sql);
            $_SESSION['mailSuccess'] = "yes";
        } else {
            $_SESSION['mailAdd'] = $email;
            $_SESSION['mailError'] = "Email address already in our mailing list";
        }
    }
}
?>
<head>
    <link href="styles.css" rel="stylesheet" type="text/css" />
</head>
<html>
    <body>
        <div id="mailingWrapper">
            <div class="rightheader close_modal">
                <button type="button" id='closeMail' class="close" data-dismiss="modal" aria-hidden="true">X</button>
            </div>
            <div class="logo"></div>
            <div id='login'>
                <div id="mailError" class="error">
                    <?php 
                        if (isset($_SESSION['mailError'])) {
                            echo $_SESSION['mailError'];
                        }
                    ?>
                </div>
                <form id='mailingForm' autofocus>
                    <input type='hidden' name='submitted' id='submitted' value='1'/>
                    <h4>WE PROMISE TO ONLY SEND YOU GOOD THINGS</h4>
                    <input type='text' name='mailingemail' id='mailingemail'  maxlength="50" 
                           placeholder="Enter your email here" value="<?php if (isset($_SESSION['mailAdd'])) { echo $_SESSION['mailAdd']; }?>"/>
                    <br>
                    <input type="submit" id="submit" name='submit' value='SUBSCRIBE'>
                </form>
            </div>
            <!--<div id="footer"><?php // require_once 'nav/footer.php';?></div>-->
        </div>
    </body>
    <script>
        <?php 
            if (isset($_SESSION['mailSuccess'])) {
                if (strcmp($_SESSION['mailSuccess'], "yes") === 0) {
        ?>
                    document.getElementById('closeMail').click();
        <?php   
                } 
            }        
        ?>
            
        $('#mailingForm').validate({
            rules: {
                mailingemail: {
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
                    url: "mailingBlog.php",
                    data: $(form).serialize(),
                    }).done(function(data) {
                    $('#mailingWrapper').replaceWith(data);
                    });
            }
        });
    </script>
</html>
