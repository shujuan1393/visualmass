<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
    
require_once 'config/db.php';

if (isset($_SESSION['searchResult'])) {
    unset($_SESSION['searchResult']);
    unset($_SESSION['searchVal']);
}
if (isset($_SESSION['searchError'])) {
    unset($_SESSION['searchError']);
}

if (isset($_SESSION['mailError'])) {
    unset($_SESSION['mailError']);
    unset($_SESSION['mailAdd']);
}

if (isset($_SESSION['mailSuccess'])) {
    unset($_SESSION['mailSuccess']);
}

require_once('braintree/lib/Braintree.php');

Braintree_Configuration::environment('sandbox');
Braintree_Configuration::merchantId('t6f3x7thfrp85fxr');
Braintree_Configuration::publicKey('zwffr27gfdksxmxz');
Braintree_Configuration::privateKey('f4d205166ddd37027a37a5fed3cdbba5');
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
        <link rel="stylesheet" type="text/css" href="calendar/codebase/dhtmlxcalendar.css"/>
        <script type="text/javascript" src="calendar/codebase/dhtmlxcalendar.js"></script>  
        <script src="http://ajax.aspnetcdn.com/ajax/jquery.validate/1.11.1/jquery.validate.min.js"></script>
        <script src="https://js.braintreegateway.com/js/braintree-2.24.1.min.js"></script>
        <link rel="stylesheet" href="styles/font-awesome.min.css">
    </head>
    <body>
        <div id='whole_header'>
            <div class="left_nav">
                <a id='logoheader' class="navbar-brand" href="index.html"><img class="navbar-logo" src="images/HorizontalLogo_black.png" alt=""/></a>
                <!--<div id='logoheader' class="logo_sidebar"></div>-->
                <ul>
                    <li><div id='showGlasses'><a>GLASSES</a></div></li>
                    <li><div id='showSunglasses'><a>SUNGLASSES</a></div></li>
                    <li><a href='hometry.php'>HOME TRY-ON</a></li>
                    <li><a href='locations.php'>LOCATION</a></li>
                    <li><div id='showStory'><a>OUR STORY</a></div></li>
                </ul>
                <div class ="rightheader">
                    <ul>
                        <?php 
                            if (isset($_SESSION['loggedUser'])) {
                        ?>
                        <li id='user'><a><?php echo $_SESSION['loggedUser']; ?></a>
                            <div id='userMenu' style='float: none;display:none;'>
                                <p><a href='profile.php'>PROFILE</a></p>
                                <p><a href='favourites.php'>FAVOURITES</a></p>
                                <p><a href='orders.php'>ORDERS</a></p>
                                <p><a href='logout.php'>LOGOUT</a></p>
                            </div>
                        </li>
                            
                        <?php 
                            } else {
                        ?>
                        <li><a href='login.php' id='signin' data-toggle="modal" data-target="#myModal">SIGN IN</a></li>
                        <?php 
                            } 
                        ?>
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
                    <li><button id='glassesmen' class='button'>SHOP MEN</button></li>
                    <li><button id='glasseswomen' class='button'>SHOP WOMEN</button></li>
                </ul>
            </div>

            <div id='sunglasses' style='display:none;'>
                <ul>
                    <li><button id='sunglassesmen' class='button'>SHOP MEN</button></li>
                    <li><button id='sunglasseswomen' class='button'>SHOP WOMEN</button></li>
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
        
        <div class="modal fade modal-fullscreen force-fullscreen" id="myModal" tabindex="-1" 
             role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog">
              <div class="modal-content">
                <div class="modal-header">
                  <button type="button" class="close" id='closeModal' data-dismiss="modal" aria-hidden="true">&times;</button>
                  <h4 class="modal-title">Modal title</h4>
                </div>
                <div class="modal-body">
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                  <button type="button" class="btn btn-primary">Save changes</button>
                </div>
              </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
          </div><!-- /.modal -->
          
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
            
            document.getElementById('logoheader').onclick = function(){  
                window.location = "index.php";
            };
            
            $('#user').mouseover(function () {
                $('#userMenu').show();   
            });
            $('#user').mouseout(function () {
                $('#userMenu').hide();      
            });
            
            document.getElementById('glassesmen').onclick = function() {
                window.location = 'products.php?type=frames&gender=men';
            };
            
            document.getElementById('glasseswomen').onclick = function() {
                window.location = 'products.php?type=frames&gender=women';
            };
            
            document.getElementById('sunglassesmen').onclick = function() {
                window.location = 'products.php?type=sunglasses&gender=men';
            };
            
            document.getElementById('sunglasseswomen').onclick = function() {
                window.location = 'products.php?type=sunglasses&gender=women';
            };
        </script>
    </body>
</html>