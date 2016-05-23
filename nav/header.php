<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
    
?>

<html>
    <head>
        <title>Visual Mass</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="styles.css" rel="stylesheet" type="text/css" />        
    </head>
    <body>
        <div class="left_nav">
            <div class="logo_sidebar"></div>
            <ul>
                <li>GLASSES</li>
                <li>SUNGLASSES</li>
                <li><a href='hometry.php'>HOME TRY-ON</a></li>
                <li>LOCATION</li>
                <li>OUR STORY</li>
            </ul>
            <div class ="rightheader">
                <ul>
                    <li><a href='login.php'>SIGN IN</a></li>
                    <li>HELP</li>
                    <li>CART</li>
                </ul>
                <?php 
    //                echo "Welcome, " .$_SESSION['loggedUser']; 
    //                echo "&nbsp<a href='../logout.php'>Logout</a>";
                ?>
            </div>
        </div>
    </body>
</html>