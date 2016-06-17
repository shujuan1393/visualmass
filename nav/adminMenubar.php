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
    
    
    $curUrl = $_SERVER['REQUEST_URI'];
    $isAdminArr = explode("/", $curUrl);

    if (in_array("admin", $isAdminArr)) {
        if (!isset($_SESSION['loggedUserEmail'])) {
            header("Location: login.php");
        }
    }
    $urlArr = explode("?", $isAdminArr[3]);
?>

<!-- Navigation -->
<nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
    <!-- Brand and toggle get grouped for better mobile display -->
    <div class="navbar-header">
        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
        </button>
        <a class="navbar-brand" href="index.html"><img class="navbar-logo" src="../images/HorizontalLogo_black.png" alt=""/></a>
       
    </div>
    <!-- Top Menu Items -->
    <ul class="nav navbar-right top-nav">
<!--        <li class="dropdown">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-envelope"></i> <b class="caret"></b></a>
            <ul class="dropdown-menu message-dropdown">
                <li class="message-preview">
                    <a href="#">
                        <div class="media">
                            <span class="pull-left">
                                <img class="media-object" src="http://placehold.it/50x50" alt="">
                            </span>
                            <div class="media-body">
                                <h5 class="media-heading">
                                    <strong>John Smith</strong>
                                </h5>
                                <p class="small text-muted"><i class="fa fa-clock-o"></i> Yesterday at 4:32 PM</p>
                                <p>Lorem ipsum dolor sit amet, consectetur...</p>
                            </div>
                        </div>
                    </a>
                </li>
                <li class="message-footer">
                    <a href="#">Read All New Messages</a>
                </li>
            </ul>
        </li>-->
        <li class="dropdown">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-bell"></i> <b class="caret"></b></a>
            <ul class="dropdown-menu alert-dropdown">
<!--                <li>
                    <a href="#">Alert Name <span class="label label-default">Alert Badge</span></a>
                </li>
                <li>
                    <a href="#">Alert Name <span class="label label-primary">Alert Badge</span></a>
                </li>
                <li>
                    <a href="#">Alert Name <span class="label label-success">Alert Badge</span></a>
                </li>
                <li>
                    <a href="#">Alert Name <span class="label label-info">Alert Badge</span></a>
                </li>
                <li>
                    <a href="#">Alert Name <span class="label label-warning">Alert Badge</span></a>
                </li>-->
                <li>
                    <a href="#">Alert Name <span class="label label-danger">Alert Badge</span></a>
                </li>
                <li class="divider"></li>
                <li>
                    <a href="#">View All</a>
                </li>
            </ul>
        </li>
        <li class="dropdown">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-user"></i> <?php echo $_SESSION['loggedUser'] ?> <b class="caret"></b></a>
            <ul class="dropdown-menu">
                <li>
                    <a href="profile.php"><i class="fa fa-fw fa-user"></i> Profile</a>
                </li>
                <li class="divider"></li>
                <li>
                    <a href="../logout.php"><i class="fa fa-fw fa-power-off"></i> Log Out</a>
                </li>
            </ul>
        </li>
    </ul>
    <!-- Sidebar Menu Items - These collapse to the responsive navigation menu on small screens -->
    <div class="collapse navbar-collapse navbar-ex1-collapse">
        <ul class="nav navbar-nav side-nav">
            <li <?php if (in_array("admin.php", $urlArr)) echo "class='active'"; ?> >
                <a href='admin.php'><i class="fa fa-fw fa-home"></i> HOME</a>
            </li>
            <?php if (in_array("cust", $checkArr)) { ?>
            <li <?php if (in_array("customers.php", $urlArr)) echo "class='active'"; ?> >
                <a href='customers.php'><i class="fa fa-fw fa-users"></i> CUSTOMERS</a>
            </li>
            <?php } ?>
            <?php if (in_array("disc", $checkArr)) { ?>
            <li <?php if (in_array("discounts.php", $urlArr)) echo "class='active'"; ?> >
                <a href='discounts.php'><i class="fa fa-fw fa-percent"></i> DISCOUNTS</a>
            </li>
            <?php } ?>
            <?php if (in_array("gift", $checkArr)) { ?>
            <li <?php if (in_array("giftcards.php", $urlArr)) echo "class='active'"; ?> >
                <a href='giftcards.php'><i class="fa fa-fw fa-gift"></i> GIFT CARDS</a>
            </li>
            <?php } ?>
            <?php if (in_array("inv", $checkArr)) { ?>
            <li <?php if (in_array("inventory.php", $urlArr)) echo "class='active'"; ?> >
                <a href='inventory.php'><i class="fa fa-fw fa-archive"></i> INVENTORY</a>
            </li>
            <?php } ?>
            <?php if (in_array("career", $checkArr)) { ?>
            <li <?php if (in_array("jobs.php", $urlArr)) echo "class='active'"; ?> >
                <a href='jobs.php'><i class="fa fa-fw fa-briefcase"></i> JOBS</a>
            </li>
            <?php } ?>   
            <?php if (in_array("loc", $checkArr)) { ?>
            <li <?php if (in_array("locations.php", $urlArr)) echo "class='active'"; ?> >
                <a href='locations.php'><i class="fa fa-fw fa-location-arrow"></i> LOCATIONS</a>
            </li>
            <?php } ?>
            <?php if (in_array("media", $checkArr)) { ?>
            <li <?php if (in_array("media.php", $urlArr)) echo "class='active'"; ?> >
                <a href='media.php'><i class="fa fa-fw fa-picture-o"></i> MEDIA GALLERY</a>
            </li>
            <?php } ?>
            <?php if (in_array("orders", $checkArr)) { ?>
            <li <?php if (in_array("orders.php", $urlArr)) echo "class='active'"; ?> >
                <a href='orders.php'><i class="fa fa-fw fa-check-square-o"></i> ORDERS</a>
            </li>
            <?php } ?>
            <?php if (in_array("partners", $checkArr)) { ?>
            <li <?php if (in_array("partners.php", $urlArr)) echo "class='active'"; ?> >
                <a href='#'><i class="fa fa-fw fa-suitcase>"></i> PARTNERS</a>
            </li>
            <?php } ?>
            <?php if (in_array("products", $checkArr)) { ?>
            <li <?php if (in_array("products.php", $urlArr)) echo "class='active'"; ?> >
                <a href='products.php'><i class="fa fa-fw fa-tag"></i> PRODUCTS</a>
            </li>
            <?php } ?>
            <?php if (in_array("settings", $checkArr)) { ?>
            <li>
                <a href="javascript:;" data-toggle="collapse" data-target="#settings">
                    <i class="fa fa-fw fa-gear"></i> SETTINGS 
                    <i class="fa fa-fw fa-angle-right" style="float: right;"></i></a>
                    <?php
                        $settingsList = array("generalSettings.php", "accountSettings.php", "blogSettings.php", 
                            "checkoutSettings.php", "exportSettings.php", "formSettings.php", "giftcardSettings.php", 
                            "homeTrySettings.php", "notificationSettings.php", "paymentsSettings.php", 
                            "productSettings.php", "receiptsSettings.php", "serviceSettings.php", 
                            "storeCreditSettings.php", "virtualTrySettings.php", "webSettings.php");
                        $countList = count(array_intersect($urlArr, $settingsList));
                    ?>
                <ul id="settings" <?php if ($countList > 0) echo "class='collapse in'"; else echo "class='collapse'"; ?> >
                    <li <?php if (in_array("generalSettings.php", $urlArr)) echo "class='active'"; ?> >
                        <a href='generalSettings.php'>GENERAL</a>
                    </li>
                    <li <?php if (in_array("accountSettings.php", $urlArr)) echo "class='active'"; ?> >
                        <a href='accountSettings.php'>ACCOUNTS</a>
                    </li>
                    <li <?php if (in_array("blogSettings.php", $urlArr)) echo "class='active'" ?> >
                        <a href='blogSettings.php'>BLOG</a>
                    </li>
                    <li <?php if (in_array("checkoutSettings.php", $urlArr)) echo "class='active'" ?> >
                        <a href='checkoutSettings.php'>CHECKOUT</a>
                    </li>
                    <li <?php if (in_array("exportSettings.php", $urlArr)) echo "class='active'" ?> >
                        <a href='#'>EXPORTS</a>
                    </li>
                    <li <?php if (in_array("formSettings.php", $urlArr)) echo "class='active'" ?> >
                        <a href='formSettings.php'>FORMS</a>
                    </li>
                    <li <?php if (in_array("giftcardSettings.php", $urlArr)) echo "class='active'" ?> >
                        <a href='giftcardSettings.php'>GIFT CARDS</a>
                    </li>
                    <li <?php if (in_array("homeTrySettings.php", $urlArr)) echo "class='active'" ?> >
                        <a href='homeTrySettings.php'>HOME TRY-ON</a>
                    </li>
                    <li <?php if (in_array("notificationSettings.php", $urlArr)) echo "class='active'" ?> >
                        <a href='notificationSettings.php'>NOTIFICATIONS</a>
                    </li>
                    <li <?php if (in_array("paymentsSettings.php", $urlArr)) echo "class='active'" ?> >
                        <a href='#'>PAYMENTS</a>
                    </li>
                    <li <?php if (in_array("productSettings.php", $urlArr)) echo "class='active'" ?> >
                        <a href='productSettings.php'>PRODUCT</a>
                    </li>
                    <li <?php if (in_array("receiptsSettings.php", $urlArr)) echo "class='active'" ?> >
                        <a href='#'>RECEIPTS</a>
                    </li>
                    <li <?php if (in_array("serviceSettings.php", $urlArr)) echo "class='active'" ?> >
                        <a href='serviceSettings.php'>SERVICES</a>
                    </li>
                    <li <?php if (in_array("storeCreditSettings.php", $urlArr)) echo "class='active'" ?> >
                        <a href='#'>STORE CREDIT</a>
                    </li>
                    <li <?php if (in_array("virtualTrySettings.php", $urlArr)) echo "class='active'" ?> >
                        <a href='#'>VIRTUAL TRY-ON</a>
                    </li>
                    <li <?php if (in_array("webSettings.php", $urlArr)) echo "class='active'" ?> >
                        <a href='webSettings.php'>WEB</a>
                    </li>
                </ul>
            </li>
            <?php } ?> 
            <?php if (in_array("stats", $checkArr)) { ?>
            <li <?php if (in_array("statistics.php", $urlArr)) echo "class='active'" ?> >
                <a href='#'><i class="fa fa-fw fa-line-chart"></i> STATISTICS</a>
            </li>
            <?php } ?> 
            <?php if (in_array("emp", $checkArr)) { ?>
            <li <?php if (in_array("users.php", $urlArr)) echo "class='active'" ?> >
                <a href='users.php'><i class="fa fa-fw fa-user"></i> USERS</a>
            </li>
            <?php } ?>        
            <?php if (in_array("web", $checkArr)) { ?>
            <li class="dropdown">
                <a href="javascript:;" data-toggle="collapse" data-target="#web">
                    <i class="fa fa-fw fa-globe"></i> WEB 
                    <i class="fa fa-fw fa-angle-right" style="float: right;"></i></a>
                    <?php
                        $webList = array("advertisements.php", "blog.php", "careers.php", 
                            "faq.php", "homepage.php", "homeTry.php", "mainstory.php", 
                            "culturestory.php", "designstory.php", "onestory.php", "terms.php", 
                            "productdesc.php", "prodBanner.php", "pages.php" );
                        $countList = count(array_intersect($urlArr, $webList));
                    ?>
                <ul id="web" <?php if ($countList > 0) echo "class='collapse in'"; else echo "class='collapse'"; ?>>
                    <li <?php if (in_array("advertisements.php", $urlArr)) echo "class='active'" ?> >
                        <a href='advertisements.php'>ADVERTISEMENTS</a>
                    </li>
                    <li <?php if (in_array("blog.php", $urlArr)) echo "class='active'" ?> >
                        <a href='blog.php'>BLOG</a>
                    </li>
                    <li <?php if (in_array("careers.php", $urlArr)) echo "class='active'" ?> >
                        <a href='careers.php'>CAREERS</a>
                    </li>
<!--                    <li <?php if (in_array("contact.php", $urlArr)) echo "class='active'" ?> >
                        <a href='contact.php'>CONTACT</a>
                    </li>-->
                    <li <?php if (in_array("faq.php", $urlArr)) echo "class='active'" ?> >
                        <a href='faq.php'>FAQ</a>
                    </li>
                    <li <?php if (in_array("homepage.php", $urlArr)) echo "class='active'" ?> >
                        <a href='homepage.php'>HOMEPAGE</a>
                    </li>
                    <li <?php if (in_array("homeTry.php", $urlArr)) echo "class='active'" ?> >
                        <a href='homeTry.php'>HOME TRY-ON</a>
                    </li>
                    <li <?php if (in_array("mainstory.php", $urlArr)) echo "class='active'" ?> >
                        <a href='mainstory.php'>OUR STORY</a>
                    </li>
                    <li <?php if (in_array("culturestory.php", $urlArr)) echo "class='active'" ?> >
                        <a href='culturestory.php'>CULTURE</a>
                    </li>
                    <li <?php if (in_array("designstory.php", $urlArr)) echo "class='active'" ?> >
                        <a href='designstory.php'>DESIGN</a>
                    </li>
                    <li <?php if (in_array("onestory.php", $urlArr)) echo "class='active'" ?> >
                        <a href='onestory.php'>GIFT INITIATIVE</a>
                    </li>
                    <li <?php if (in_array("prodBanner.php", $urlArr)) echo "class='active'" ?> >
                        <a href='prodBanner.php'>PRODUCT BANNERS</a>
                    </li>
                    <li <?php if (in_array("productdesc.php", $urlArr)) echo "class='active'" ?> >
                        <a href='productdesc.php'>PRODUCTS</a>
                    </li>
                    <li <?php if (in_array("terms.php", $urlArr)) echo "class='active'" ?> >
                        <a href='terms.php'>TERMS</a>
                    </li>
                    <li <?php if (in_array("pages.php", $urlArr)) echo "class='active'" ?> >
                        <a href='pages.php'>PAGES</a>
                    </li>
                </ul>
            </li>
            <?php } ?>
        </ul>
    </div>
    <!-- /.navbar-collapse -->
</nav>