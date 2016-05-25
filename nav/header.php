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
        <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
        <link href="styles.css" rel="stylesheet" type="text/css" />     
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.2/jquery.min.js"></script>
        <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>   
    </head>
    <body>
        <div class="left_nav">
            <div class="logo_sidebar"></div>
            <ul>
                <li><div id='showGlasses'>GLASSES</div></li>
                <li><div id='showSunglasses'>SUNGLASSES</div></li>
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
        <div id='glasses' style='display:none;'>
            <ul>
                <li><a href='products.php?type=frames&gender=men'>SHOP MEN</a></li>
                <li><a href='products.php?type=frames&gender=women'>SHOP WOMEN</a></li>
            </ul>
        </div>
        <div id='sunglasses' style='display:none;'>
            <ul>
                <li><a href='products.php?type=sunglasses&gender=men'>SHOP MEN</a></li>
                <li><a href='products.php?type=sunglasses&gender=women'>SHOP WOMEN</a></li>
            </ul>
        </div>
        
        <script>
            document.getElementById('showGlasses').onclick = function(){  
                var e = document.getElementById('glasses');
                if (e.style.display == 'block') {
                     e.style.display = 'none';
                 } else {
                     e.style.display = 'block';
                 }
                if (document.getElementById('sunglasses').style.display == 'block') {
                    document.getElementById('sunglasses').style.display = 'none';
                }
             };
             
             document.getElementById('showSunglasses').onclick = function(){  
                var e = document.getElementById('sunglasses');
                if (e.style.display == 'block') {
                     e.style.display = 'none';
                 } else {
                     e.style.display = 'block';
                 }
                if (document.getElementById('glasses').style.display == 'block') {
                    document.getElementById('glasses').style.display = 'none';
                }
             };
        </script>
    </body>
</html>