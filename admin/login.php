<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
session_start();
?>
<head>
    <link href="../styles.css" rel="stylesheet" type="text/css" />
</head>
<html>
    <body>
        <div class="logo"></div>
        <div class="container">
        <div class="form_header">Sign in</div>
        <form id='login' action='processAdminLogin.php' method='post' accept-charset='UTF-8'>
            <input type='hidden' name='submitted' id='submitted' value='1'/>

            <input type='text' name='email' id='email'  maxlength="50" placeholder="Email" /><br>
            <input type='password' name='password' id='password' maxlength="50" placeholder="Password" /><br>
            <input type='submit' name='Submit' value='Sign in' />
            
            <div id="loginError" style="color:red">
                <?php 
                    if (isset($_SESSION['adminLoginError'])) {
                        echo $_SESSION['adminLoginError'];
                    }
                ?>
            </div>
        </form>
        </div>
    </body>
</html>

