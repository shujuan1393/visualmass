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
        <div id="wrapper">
            <!--<div id="header"><?php // require_once 'nav/header.php';?></div>-->
            <div class="logo"></div>
            <div class="container">
            <div class="form_header">Sign in</div>
            <div id="loginFormError" class="error">
                <?php 
                    if (isset($_SESSION['loginFormError'])) {
                        echo $_SESSION['loginFormError'];
                    }
                ?>
            </div>
            <form id='login' action='processLogin.php' method='post' accept-charset='UTF-8'>
                <input type='hidden' name='submitted' id='submitted' value='1'/>

                <input type='text' name='email' id='email'  maxlength="50" placeholder="Email" /><br/>
                <input type='password' name='password' id='password' maxlength="50" placeholder="Password" /><br/>
                <input type='submit' name='Submit' value='Sign in' />
            </form>
            </div>
            
            <!--<div id="footer"><?php // require_once 'nav/footer.php';?></div>-->
        </div>
    </body>
</html>
