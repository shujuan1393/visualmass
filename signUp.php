<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
require_once 'nav/header.php';
?>
<head>
    <link href="styles.css" rel="stylesheet" type="text/css" />
</head>
<html>
    <body>
        <div class="logo"></div>
        <div class="container">
        <div class="form_header">Create a Visual Mass account</div>
        <form id='signUp' action='processSignup.php' method='post' accept-charset='UTF-8'>
            <input type='hidden' name='submitted' id='submitted' value='1'/>
            
            <input type='text' name='firstName' id='firstName'  maxlength="50" placeholder="First Name" /><br>
            <input type='text' name='lastName' id='lastName'  maxlength="50" placeholder="Last Name" /><br>
            <input type='text' name='email' id='email'  maxlength="50" placeholder="Email" /><br>
            <input type='password' name='password' id='password' maxlength="50" placeholder="Password" /><br>
            <input type='submit' name='Submit' value='Create account' />
            
            <div id="signupError" >
                <?php 
                    $error = $_SESSION['signUpError'];
                    echo $error;
                ?>
            </div>
        </form>
        </div>
    </body>
</html>

