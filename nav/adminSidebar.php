<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
require_once '../config/db.php';
?>
<html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="../styles.css" type="text/css">
    </head>
    <body>
        <div class="logo_sidebar"></div>
        <?php 
            echo "Welcome, " .$_SESSION['loggedUser']; 
            echo "<br> <a href='../logout.php'>Logout</a>";
            
            $curUserType = $_SESSION['userType'];
            
            $accessSql = "Select value from settings where type='account'";
            $accessResult = mysqli_query($link, $accessSql);
            
            $accessRow = mysqli_fetch_assoc($accessResult);
            
            $accessArr = explode("&", $accessRow['value']);
            $checkArr;
            if ($curUserType === 'admin') {
                $checkArr = array("cust", "disc", "gift", "inv", "loc", "media", "orders", "partners",
                    "products", "settings", "stats", "emp", "web");
            } else {
                for ($i = 0; $i < count($accessArr); $i++) {
                    $val = $accessArr[$i];
                    $arr = explode("=", $val);
                    if (strcmp($curUserType, $arr[0]) === 0) {
                        $checkArr = explode(",", $arr[1]);
                    }
                }
            }
            
        ?>
        <ul>
            <li><a href='admin.php'>HOME</a></li>  
            <li><a href='profile.php'>MY PROFILE</a></li>            
            <?php if (in_array("cust", $checkArr)) { ?><li>CUSTOMERS</li><?php } ?>
            <?php if (in_array("disc", $checkArr)) { ?><li><a href='discounts.php'>DISCOUNTS</a></li><?php } ?>
            <?php if (in_array("gift", $checkArr)) { ?><li><a href='giftcards.php'>GIFT CARDS</a></li><?php } ?>
            <?php if (in_array("inv", $checkArr)) { ?><li><a href='inventory.php'>INVENTORY</a></li><?php } ?>
            <?php if (in_array("loc", $checkArr)) { ?><li><a href='locations.php'>LOCATIONS</a></li><?php } ?>
            <?php if (in_array("media", $checkArr)) { ?><li><a href='media.php'>MEDIA GALLERY</a></li><?php } ?>
            <?php if (in_array("orders", $checkArr)) { ?><li>ORDERS</li><?php } ?>
            <?php if (in_array("partners", $checkArr)) { ?><li>PARTNERS</li><?php } ?>
            <?php if (in_array("products", $checkArr)) { ?><li><a href='products.php'>PRODUCTS</a></li><?php } ?>
            <?php if (in_array("settings", $checkArr)) { ?><li id='settings'>SETTINGS
                <ul id='settingslist' style='display:none'>
                    <li><a href="generalSettings.php">GENERAL</a></li>
                    <li><a href='accountSettings.php'>ACCOUNTS</a></li>
                    <li><a href='checkoutSettings.php'>CHECKOUT</a></li>
                    <li>EXPORTS</li>
                    <li>FORMS</li>
                    <li><a href="giftcardSettings.php">GIFT CARDS</a></li>
                    <li><a href='homeTrySettings.php'>HOME TRY-ON</a></li>
                    <li><a href='notificationSettings.php'>NOTIFICATIONS</a></li>
                    <li>PAYMENTS</li>
                    <li>RECEIPTS</li>
                    <li><a href='serviceSettings.php'>SERVICES</a></li>
                    <li>STORE CREDIT</li>
                    <li>VIRTUAL TRY-ON</li>
                    <li><a href='webSettings.php'>WEB</a></li>
                </ul>
            </li> <?php } ?> 
            <?php if (in_array("stats", $checkArr)) { ?><li>STATISTICS</li><?php } ?> 
            <?php if (in_array("emp", $checkArr)) { ?><li><a href='users.php'>USERS</a></li><?php } ?>        
            <?php if (in_array("web", $checkArr)) { ?><li id='web'>WEB
                <ul id='webDropdown' style='display:none'>
                    <li><a href='advertisements.php'>ADVERTISEMENTS</a></li>
                    <li><a href='blog.php'>BLOG</a></li>
                    <li><a href='contact.php'>CONTACT</a></li>
                    <li><a href='faq.php'>FAQ</a></li>
                    <li><a href='terms.php'>TERMS</a></li>
                </ul>
            </li><?php } ?>
        </ul>
        
    </body>
    <script>
        document.getElementById('settings').onclick = function(){  
            var e = document.getElementById('settingslist');
            if(e.style.display == 'block') {
                e.style.display = 'none';
            } else {
                e.style.display = 'block';
            }
        };
        document.getElementById('web').onclick = function(){  
           var e = document.getElementById('webDropdown');
           if(e.style.display == 'block')
                e.style.display = 'none';
             else
                e.style.display = 'block';
        };
    </script>
</html>


