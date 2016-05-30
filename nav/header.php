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
        <script type="text/javascript" src="http://maps.google.com/maps/api/js?key=AIzaSyCR78jxaf-XgjrUTFxK-jfaj9J_anb-kRA"></script> 
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
        <link href="styles.css" rel="stylesheet" type="text/css" />     
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.2/jquery.min.js"></script>
        <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script> 
    </head>
    <body>
        <div id='whole_header'>
            <div class="left_nav">
                <div class="logo_sidebar"></div>
                <ul>
                    <li><div id='showGlasses'><a>GLASSES</a></div></li>
                    <li><div id='showSunglasses'><a>SUNGLASSES</a></div></li>
                    <li><a href='hometry.php'>HOME TRY-ON</a></li>
                    <li><a href='locations.php'>LOCATION</a></li>
                    <li><div id='showStory'><a>OUR STORY</a></div></li>
                </ul>
                <div class ="rightheader">
                    <ul>
                        <li><a href='login.php'>SIGN IN</a></li>
                        <li><a href='faq.php'>HELP</a></li>
                        <li><a href='cart.php'>CART</a></li>
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

            <div id='ourstoryheader' style='display:none;'>
                 <ul>
                    <li><a href='ourstory.php'>HISTORY</a></li>
                    <li><a href='giftInitiative.php'>ONE FOR YOU, <br> ONE FOR THEM</a></li>
                    <li><a href='culture.php'>CULTURE</a></li>
                    <li><a href='design.php'>DESIGN</a></li>
                </ul>
            </div>
        </div>
        <script>
            document.getElementById('showGlasses').onclick = function(){  
                var e = document.getElementById('glasses');
                if (e.style.display === 'block') {
                     e.style.display = 'none';
                } else {
                    e.style.display = 'block';
                }
                
                if (document.getElementById('sunglasses').style.display === 'block') {
                    document.getElementById('sunglasses').style.display = 'none';
                }
                
                if (document.getElementById('ourstoryheader').style.display === 'block') {
                    document.getElementById('ourstoryheader').style.display = 'none';
                }
             };
             
            document.getElementById('showSunglasses').onclick = function(){  
               var e = document.getElementById('sunglasses');
               if (e.style.display === 'block') {
                    e.style.display = 'none';
                } else {
                    e.style.display = 'block';
                }
                if (document.getElementById('glasses').style.display === 'block') {
                    document.getElementById('glasses').style.display = 'none';
                }
                if (document.getElementById('ourstoryheader').style.display === 'block') {
                    document.getElementById('ourstoryheader').style.display = 'none';
                }
            };
            
            document.getElementById('showStory').onclick = function(){  
                var e = document.getElementById('ourstoryheader');
                if (e.style.display === 'block') {
                    e.style.display = 'none';
                } else {
                    e.style.display = 'block';
                }
                if (document.getElementById('glasses').style.display === 'block') {
                    document.getElementById('glasses').style.display = 'none';
                }

                if (document.getElementById('sunglasses').style.display === 'block') {
                    document.getElementById('sunglasses').style.display = 'none';
                }
            };
        </script>
    </body>
</html>