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
    </head>
    <body>
        <?php             
            $curUserType = $_SESSION['userType'];
            
            $accessSql = "Select value from settings where type='account'";
            $accessResult = mysqli_query($link, $accessSql);
            
            $accessRow = mysqli_fetch_assoc($accessResult);
            
            $accessArr = explode("&", $accessRow['value']);
            $checkArr;
            if ($curUserType === 'admin') {
                $checkArr = array("career", "cust", "disc", "gift", "inv", "loc", "media", "orders", "partners",
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
        <ul id="nav" class="nav">
            <li><a href='admin.php'><img src="../icons/home.png" alt=""/>HOME</a></li>  
            <li><a href='profile.php'><img src="../icons/home.png" alt=""/>MY PROFILE</a></li>         
            <?php if (in_array("career", $checkArr)) { ?><li><a href='jobs.php'><img src="../icons/home.png" alt=""/>CAREERS</a></li><?php } ?>   
            <?php if (in_array("cust", $checkArr)) { ?><li>CUSTOMERS</li><?php } ?>
            <?php if (in_array("disc", $checkArr)) { ?><li><a href='discounts.php'>DISCOUNTS</a></li><?php } ?>
            <?php if (in_array("gift", $checkArr)) { ?><li><a href='giftcards.php'>GIFT CARDS</a></li><?php } ?>
            <?php if (in_array("inv", $checkArr)) { ?><li><a href='inventory.php'>INVENTORY</a></li><?php } ?>
            <?php if (in_array("loc", $checkArr)) { ?><li><a href='locations.php'>LOCATIONS</a></li><?php } ?>
            <?php if (in_array("media", $checkArr)) { ?><li><a href='media.php'>MEDIA GALLERY</a></li><?php } ?>
            <?php if (in_array("orders", $checkArr)) { ?><li>ORDERS</li><?php } ?>
            <?php if (in_array("partners", $checkArr)) { ?><li>PARTNERS</li><?php } ?>
            <?php if (in_array("products", $checkArr)) { ?><li><a href='products.php'>PRODUCTS</a></li><?php } ?>
            <?php if (in_array("settings", $checkArr)) { ?><li><a href='#' id='settings' class='subnav'>SETTINGS</a></li> <?php } ?> 
            <?php if (in_array("stats", $checkArr)) { ?><li>STATISTICS</li><?php } ?> 
            <?php if (in_array("emp", $checkArr)) { ?><li><a href='users.php'>USERS</a></li><?php } ?>        
            <?php if (in_array("web", $checkArr)) { ?><li><a href='#' id='web' class='subnav'>WEB</a></li><?php } ?>
        </ul>
        
        <div id='subcontent'>
            <table>
                <tr class='subtable'>
                    <td id='navlist' class='sub' style='display:none'>
                        <ul>
                            <li><a href='admin.php'><img src="../icons/home.png" alt=""/></a></li>  
                            <li><a href='profile.php'><img src="../icons/home.png" alt=""/></a></li>         
                            <?php if (in_array("career", $checkArr)) { ?><li><a href='jobs.php'><img src="../icons/home.png" alt=""/></a></li><?php } ?>   
                            <?php if (in_array("cust", $checkArr)) { ?><li>CUSTOMERS</li><?php } ?>
                            <?php if (in_array("disc", $checkArr)) { ?><li><a href='discounts.php'>DISCOUNTS</a></li><?php } ?>
                            <?php if (in_array("gift", $checkArr)) { ?><li><a href='giftcards.php'>GIFT CARDS</a></li><?php } ?>
                            <?php if (in_array("inv", $checkArr)) { ?><li><a href='inventory.php'>INVENTORY</a></li><?php } ?>
                            <?php if (in_array("loc", $checkArr)) { ?><li><a href='locations.php'>LOCATIONS</a></li><?php } ?>
                            <?php if (in_array("media", $checkArr)) { ?><li><a href='media.php'>MEDIA GALLERY</a></li><?php } ?>
                            <?php if (in_array("orders", $checkArr)) { ?><li>ORDERS</li><?php } ?>
                            <?php if (in_array("partners", $checkArr)) { ?><li>PARTNERS</li><?php } ?>
                            <?php if (in_array("products", $checkArr)) { ?><li><a href='products.php'>PRODUCTS</a></li><?php } ?>
                            <?php if (in_array("settings", $checkArr)) { ?><li><a href='#' id='settings' class='subnav'>SETTINGS</a></li> <?php } ?> 
                            <?php if (in_array("stats", $checkArr)) { ?><li>STATISTICS</li><?php } ?> 
                            <?php if (in_array("emp", $checkArr)) { ?><li><a href='users.php'>USERS</a></li><?php } ?>        
                            <?php if (in_array("web", $checkArr)) { ?><li><a href='#' id='web' class='subnav'>WEB</a></li><?php } ?>
                        </ul>
                    </td>
                    <td id='settingslist' class='sub' style='display:none'>
                        <ul>
                            <li><a href="#" id='settingsback'>< BACK</a></li>
                            <li><a href="generalSettings.php">GENERAL</a></li>
                            <li><a href='accountSettings.php'>ACCOUNTS</a></li>
                            <li><a href='blogSettings.php'>BLOG</a></li>
                            <li><a href='checkoutSettings.php'>CHECKOUT</a></li>
                            <li>EXPORTS</li>
                            <li><a href="formSettings.php">FORMS</a></li>
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
                        <ul id='weblist' class='sub' style='display:none'>
                            <li><a href="#" id='webback'>< BACK</a></li>
                            <li><a href='advertisements.php'>ADVERTISEMENTS</a></li>
                            <li><a href='blog.php'>BLOG</a></li>
                            <li><a href='careers.php'>CAREERS</a></li>
                <!--                    <li><a href='contact.php'>CONTACT</a></li>-->
                            <li><a href='faq.php'>FAQ</a></li>
                            <li><a href='homepage.php'>HOMEPAGE</a></li>
                            <li><a href='homeTry.php'>HOME TRY-ON</a></li>
                            <li><a href='mainstory.php'>OUR STORY - MAIN</a></li>
                            <li><a href='culturestory.php'>OUR STORY - CULTURE</a></li>
                            <li><a href='designstory.php'>OUR STORY - DESIGN</a></li>
                            <li><a href='onestory.php'>OUR STORY - GIFT INITIATIVE</a></li>
                            <li><a href='terms.php'>TERMS</a></li>
                        </ul>
                    </td>
                </tr>
            </table>
        </div>

    </body>
    <script>
        document.getElementById('settingsback').onclick = function(){  
            var e = document.getElementById('nav');
            var set = document.getElementById('settingslist');
            if(e.style.display == 'block') {
                e.style.display = 'none';
            } else {
                e.style.display = 'block';
                set.style.display = 'none';
            }
        };
        
        document.getElementById('webback').onclick = function(){  
            var e = document.getElementById('nav');
            var set = document.getElementById('weblist');
            if(e.style.display == 'block') {
                e.style.display = 'none';
            } else {
                e.style.display = 'block';
                set.style.display = 'none';
            }
        };
        
        document.getElementById('settings').onclick = function(){  
            var e = document.getElementById('settingslist');
            var nav = document.getElementById('nav');
            var navlist = document.getElementById('navlist');
            if(e.style.display == 'block') {
                e.style.display = 'none';
            } else {
                e.style.display = 'block';
                nav.style.display = 'none';
                navlist.style.display = 'block';
            }
        };
        
        document.getElementById('web').onclick = function(){  
           var e = document.getElementById('weblist');
            var nav = document.getElementById('nav');
           if(e.style.display == 'block')
                e.style.display = 'none';
             else
                e.style.display = 'block';
                nav.style.display = 'none';
        };
    </script>
</html>


