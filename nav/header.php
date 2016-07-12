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

//$pageCanonical = "";
//$pageRobots = "";
    
//check user profile
if (isset($_SESSION['loggedUserEmail'])) {
    $user = "Select * from user where email ='".$_SESSION['loggedUserEmail']."';";
    $ures = mysqli_query($link, $user);
    
    if (!mysqli_query($link, $user)) {
        die(mysqli_error($link));
    } else {
        $urow = mysqli_fetch_assoc($ures);
        
        if (empty($urow['firstname']) || empty($urow['lastname']) || empty($urow['address'])
                || empty($urow['zip']) || empty($urow['city']) || empty($urow['apt'])
                || empty($urow['country'])) {
            $_SESSION['profile'] = "incomplete";
        } else {
            unset($_SESSION['profile']);
        }
    }
}

    //get from settings
    $web = "Select * from settings where type='web';";
    $wres = mysqli_query($link, $web);
    
    if (!mysqli_query($link, $web)) {
        die(mysqli_error($link));
    } else {
        $hrow = mysqli_fetch_assoc($wres);
        $valArr = explode("#", $hrow['value']);
        $title = explode("web=", $valArr[0]);
        $meta = explode("meta=", $valArr[1]);
        $ticker = explode("ticker=", $valArr[2]);
    }
    
    // Define variables for SEO
    $pageTitle = $title[1]; //'Visual Mass - Singapore\'s Online Eyeglass & Sunglasses';
    $pageDescription = $meta[1]; // 'Provides quality prescription eyewear from $95. Free delivery and exchanges.';
    $pageCanonical = 'http://www.visualmass.co/';
    // We don't want the search engines to see our website just yet
    $pageRobots = 'noindex,nofollow';
    
    //get amount from settings
    $set = "Select * from settings where type='storecredit';";
    $sres = mysqli_query($link, $set);
    if (!mysqli_query($link, $set)) {
        die(mysqli_error($link));
    } else {
        $srow = mysqli_fetch_assoc($sres);
        $valArr = explode("&", $srow['value']);
        if(!empty($valArr[1])){
            $profile = explode("profile=", $valArr[1]);
        }
    }
?>

<html>
    <head>
        <title>
            <?php if(!empty($pageTitle)) { 
            echo $pageTitle; 
            } ?>
        </title>
        <meta name="description" content="<?php if(!empty($pageDescription)) { echo $pageDescription; } ?>">
        <meta name="author" content="Visual Mass">
        
        <?php
            // If canonical URL is specified, include canonical link element
            if($pageCanonical) {
                 if(!empty($pageCanonical)) { echo '<link rel="canonical" href="' . $pageCanonical . '">'; }
            }
            // If meta robots content is specified, include robots meta tag
            if($pageRobots) {
                 if(!empty($pageRobots)) { echo '<meta name="robots" content="' . $pageRobots . '">'; }
            }
        ?>
        
        <meta charset="UTF-8">  
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        
        <script type="text/javascript" src="http://maps.google.com/maps/api/js?key=AIzaSyCR78jxaf-XgjrUTFxK-jfaj9J_anb-kRA"></script> 

        <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
        <link href="styles.css" rel="stylesheet" type="text/css" />     
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.2/jquery.min.js"></script>
        <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script> 
        <link rel="stylesheet" type="text/css" href="calendar/codebase/dhtmlxcalendar.css"/>
        <script type="text/javascript" src="calendar/codebase/dhtmlxcalendar.js"></script>  
        <script src="http://ajax.aspnetcdn.com/ajax/jquery.validate/1.11.1/jquery.validate.min.js"></script>
        <script src="https://js.braintreegateway.com/js/braintree-2.24.1.min.js"></script>
        <script src="http://cdnjs.cloudflare.com/ajax/libs/modernizr/2.8.2/modernizr.js"></script>
        <link rel="stylesheet" href="styles/font-awesome.min.css">
    
        <script>
            //paste this code under the head tag or in a separate js file.
            // Wait for window load
            $(window).load(function() {
                // Animate loader off screen
                $(".se-pre-con").fadeOut("slow");;
            });
        </script>
    </head>
    <body>	
        <div class="se-pre-con"></div>
        <?php if(!empty($ticker[1])) { ?>
        <form name="ticker"> 
        <input id='ticker' style='text-align: center;width:100%!important;background-color: #cccccc;color:#fff!important;' name="text" VALUE="<?php 
                echo $ticker[1]." | "; 
                if (isset($_SESSION['profile'])) {
                    if(!empty($profile[1])) { 
                        echo "Complete your profile now and get store credit worth $". $profile[1]." | ";
                    }
                }
            ?>"> 
        </form>
<!--        <div id='ticker' class='text-center' style='background-color: #cccccc; color:#fff!important;'>
            <?php 
//                echo $ticker[1]." | "; 
                if (isset($_SESSION['profile'])) {
            ?>
                Complete your profile now and get store credit worth $<?php if(!empty($profile[1])) { echo $profile[1]; } ?>
            <?php
                }
            ?>
        </div>-->
        <?php } 
        
        if (isset($_SESSION['profile'])) { ?>
<!--            <div id='completeProfile' class='text-center'>
                <h5>Complete your profile now and get store credit worth $<?php if(!empty($profile[1])) { echo $profile[1]; } ?></h5>
            </div>-->
        <?php } ?>
        <div id='whole_header'>
            <div class="left_nav">
                <a id='logoheader' class="navbar-brand" href="index.php"><img class="navbar-logo" src="images/HorizontalLogo_black.png" alt=""/></a>
<!--=======
        <script src="https://js.braintreegateway.com/js/braintree-2.24.1.min.js"></script>
        <link rel="stylesheet" href="styles/font-awesome.min.css">
    </head>
    <body>
        <div id='whole_header'>
            <div class="left_nav">
                <a id='logoheader' class="navbar-brand" href="index.php"><img class="navbar-logo" src="images/HorizontalLogo_black.png" alt=""/></a>
>>>>>>> Stashed changes-->
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
                <ul class='col-md-12'>
                    <li class='col-md-6'><button id='glassesmen' class='button'>SHOP MEN</button></li>
                    <li class='col-md-6'><button id='glasseswomen' class='button'>SHOP WOMEN</button></li>
                </ul>
            </div>

            <div id='sunglasses' style='display:none;'>
                <ul class='col-md-12'>
                    <li class='col-md-6'><button id='sunglassesmen' class='button'>SHOP MEN</button></li>
                    <li class='col-md-6'><button id='sunglasseswomen' class='button'>SHOP WOMEN</button></li>
                </ul>
            </div>

            <div id='ourstoryheader' style='display:none;'>
                 <ul class='col-lg-12'>
                    <li class='col-sm-3'><button class='button' onclick='ourstory("main")'>HISTORY</button></li>
                    <li class='col-sm-3'><button id='gift' class='button' onclick='ourstory("one")'>ONE FOR YOU, <br> ONE FOR THEM</button></li>
                    <li class='col-sm-3'><button id='culture' class='button' onclick='ourstory("culture")'>CULTURE</button></li>
                    <li class='col-sm-3'><button id='design' class='button' onclick='ourstory("design")'>DESIGN</button></li>
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
            var speed = 200;
            var chars = 1;
            function ScrollMarquee() {
                window.setTimeout('ScrollMarquee()',speed);

                var msg = document.ticker.text.value; 
                document.ticker.text.value =
                msg.substring(chars) +
                msg.substring(0,chars); 
            } 
            ScrollMarquee();
            function ourstory(type) {
                window.location = "ourstory.php?type=" + type;
            }
            
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