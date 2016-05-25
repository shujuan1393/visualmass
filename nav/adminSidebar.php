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
        <div id='navcontent'>
            <ul id="nav" class="nav">
                <li><a href='admin.php'><img src="../icons/home.png" alt=""/>HOME</a></li>
                <?php if (in_array("career", $checkArr)) { ?><li><a href='jobs.php'><img src="../icons/home.png" alt=""/>CAREERS</a></li><?php } ?>   
                <?php if (in_array("cust", $checkArr)) { ?><li><img src="../icons/home.png" alt=""/>CUSTOMERS</li><?php } ?>
                <?php if (in_array("disc", $checkArr)) { ?><li><a href='discounts.php'><img src="../icons/home.png" alt=""/>DISCOUNTS</a></li><?php } ?>
                <?php if (in_array("gift", $checkArr)) { ?><li><a href='giftcards.php'><img src="../icons/home.png" alt=""/>GIFT CARDS</a></li><?php } ?>
                <?php if (in_array("inv", $checkArr)) { ?><li><a href='inventory.php'><img src="../icons/home.png" alt=""/>INVENTORY</a></li><?php } ?>
                <?php if (in_array("loc", $checkArr)) { ?><li><a href='locations.php'><img src="../icons/home.png" alt=""/>LOCATIONS</a></li><?php } ?>
                <?php if (in_array("media", $checkArr)) { ?><li><a href='media.php'><img src="../icons/home.png" alt=""/>MEDIA GALLERY</a></li><?php } ?>
                <?php if (in_array("orders", $checkArr)) { ?><li><img src="../icons/home.png" alt=""/>ORDERS</li><?php } ?>
                <?php if (in_array("partners", $checkArr)) { ?><li><img src="../icons/home.png" alt=""/>PARTNERS</li><?php } ?>
                <?php if (in_array("products", $checkArr)) { ?><li><a href='products.php'><img src="../icons/home.png" alt=""/>PRODUCTS</a></li><?php } ?>
                <?php if (in_array("settings", $checkArr)) { ?><li><a href='#' id='settings' class='subnav'><img src="../icons/home.png" alt=""/>SETTINGS<span style="float:right">></span></a></li> <?php } ?> 
                <?php if (in_array("stats", $checkArr)) { ?><li><img src="../icons/home.png" alt=""/>STATISTICS</li><?php } ?> 
                <?php if (in_array("emp", $checkArr)) { ?><li><a href='users.php'><img src="../icons/home.png" alt=""/>USERS</a></li><?php } ?>        
                <?php if (in_array("web", $checkArr)) { ?><li><a href='#' id='web' class='subnav'><img src="../icons/home.png" alt=""/>WEB<span style="float:right">></span></a></li><?php } ?>
            </ul>
        </div>
        
        <div id='subcontent'>
            <ul id='settingslist' class='sub' style='display:none'>
                <li class='first-sub'>SETTINGS</li>
                <li><a href='generalSettings.php'>GENERAL</a></li>
                <li><a href='accountSettings.php'>ACCOUNTS</a></li>
                <li><a href='blogSettings.php'>BLOG</a></li>
                <li><a href='checkoutSettings.php'>CHECKOUT</a></li>
                <li>EXPORTS</li>
                <li><a href='formSettings.php'>FORMS</a></li>
                <li><a href='giftcardSettings.php'>GIFT CARDS</a></li>
                <li><a href='homeTrySettings.php'>HOME TRY-ON</a></li>
                <li><a href='notificationSettings.php'>NOTIFICATIONS</a></li>
                <li>PAYMENTS</li>
                <li><a href='productCatSettings.php'>PRODUCT CATEGORIES</a></li>
                <li>RECEIPTS</li>
                <li><a href='serviceSettings.php'>SERVICES</a></li>
                <li>STORE CREDIT</li>
                <li>VIRTUAL TRY-ON</li>
                <li><a href='webSettings.php'>WEB</a></li>
            </ul>
            <ul id='weblist' class='sub' style='display:none'>
                <li class='first-sub'>WEB</li>
                <li><a href='advertisements.php'>ADVERTISEMENTS</a></li>
                <li><a href='blog.php'>BLOG</a></li>
                <li><a href='careers.php'>CAREERS</a></li>
    <!--                    <li><a href='contact.php'>CONTACT</a></li>-->
                <li><a href='faq.php'>FAQ</a></li>
                <li><a href='homepage.php'>HOMEPAGE</a></li>
                <li><a href='homeTry.php'>HOME TRY-ON</a></li>
                <li><a href='mainstory.php'>OUR STORY</a></li>
                <li><a href='culturestory.php'>CULTURE</a></li>
                <li><a href='designstory.php'>DESIGN</a></li>
                <li><a href='onestory.php'>GIFT INITIATIVE</a></li>
                <li><a href='terms.php'>TERMS</a></li>
            </ul>
        </div>
    </body>
    <script>
        var slist = document.getElementById('settingslist');
        var wlist = document.getElementById('weblist');
        
        document.getElementById('settings').onclick = function(){  
            if(slist.style.display == 'table-cell') {
                slist.style.display = 'none';
            } else {
                slist.style.display = 'table-cell';
                if (wlist.style.display == 'table-cell'){
                    wlist.style.display = 'none';
                }
            }
        };
        
        document.getElementById('web').onclick = function(){  
            if(wlist.style.display == 'table-cell') {
                wlist.style.display = 'none';
            } else {
                wlist.style.display = 'table-cell';
                if (slist.style.display == 'table-cell'){
                    slist.style.display = 'none';
                }
            }
        };
    </script>
</html>


