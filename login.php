<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
require 'nav/header.php';
?>

<html>
    <body>
        <form id='login' action='processLogin.php' method='post' accept-charset='UTF-8'>
            <fieldset >
            <legend>Login</legend>
            <input type='hidden' name='submitted' id='submitted' value='1'/>

            <label for='username' >Email*:</label>
            <input type='text' name='email' id='email'  maxlength="50" />
            <br>
            <label for='password' >Password*:</label>
            <input type='password' name='password' id='password' maxlength="50" />
            <br>
            <input type='submit' name='Submit' value='Submit' />
            
            <div id="loginError" style="color:red">
                <?php 
                    $error = $_SESSION['loginFormError'];
                    echo $error;
                ?>
            </div>
            </fieldset>
        </form>
    </body>
</html>
