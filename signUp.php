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
        <form id='signUp' action='processSignup.php' method='post' accept-charset='UTF-8'>
            <fieldset >
            <legend>Create Visual Mass Account</legend>
            <input type='hidden' name='submitted' id='submitted' value='1'/>
            
            <label for='firstName' >First Name*:</label>
            <input type='text' name='firstName' id='firstName'  maxlength="50" />
            <br>
            <label for='lastName' >Last Name*:</label>
            <input type='text' name='lastName' id='lastName'  maxlength="50" />
            <br>
            <label for='email' >Email*:</label>
            <input type='text' name='email' id='email'  maxlength="50" />
            <br>
            <label for='password' >Password*:</label>
            <input type='password' name='password' id='password' maxlength="50" />
            <br>
            <input type='submit' name='Submit' value='Submit' />
            <div id="signupError" style="color:red">
                <?php 
                    $error = $_SESSION['signUpError'];
                    echo $error;
                ?>
            </div>
            </fieldset>
        </form>
    </body>
</html>

