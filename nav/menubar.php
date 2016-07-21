<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
?>

<!-- Navigation -->
<nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
    <!-- Brand and toggle get grouped for better mobile display -->
    <div class="navbar-header">
        <button type="button" class="navbar-toggle pull-left" data-toggle="collapse" data-target=".navbar-ex1-collapse">
            <i class="fa fa-navicon"></i>
        </button>
        <a href="index.php"><img class="navbar-logo" src="images/HorizontalLogo_black.png" alt=""/></a>
        
        <a href="cart.php" class="sm-nav navbar-toggle pull-right caps">Cart</a>
    </div>
    <div class="collapse navbar-collapse navbar-ex1-collapse">
        <!-- Top Menu Items -->
        <ul class="nav navbar-left top-nav caps content-inline">
            <li class="dropdown pull-left">
                <a href="#" id="showGlasses" class="dropdown-toggle" data-toggle="dropdown">Glasses</a>
            </li>
            <li class="dropdown pull-left">
                <a href="#" id="showSunglasses" class="dropdown-toggle" data-toggle="dropdown">Sunglasses</a>
            </li>
            <li class="dropdown pull-right">
                <a id="nav-collapse2" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-plus"></i></a>
                <ul class="dropdown-menu caps">
                    <li class="dropdown pull-left">
                        <a href="hometry.php" >Home Try-on</a>
                    </li>
                    <li class="dropdown pull-left">
                        <a href="location.php" >Location</a>
                    </li>
                </ul>
            </li>
            <li id="collapse-nav" class="dropdown pull-left">
                <a href="hometry.php" >Home Try-on</a>
            </li>
            <li id="collapse-nav" class="dropdown pull-left">
                <a href="location.php" >Location</a>
            </li>
            <li class="dropdown pull-left">
                <a href="#" id="showStory" class="dropdown-toggle" data-toggle="dropdown">Our Story</a>
            </li>
        </ul>
        <ul class="nav navbar-right top-nav caps content-inline">
            <li class="dropdown caps pull-left">
                <?php 
                    if (isset($_SESSION['loggedUser'])) {
                ?>
                <a href="#" class="dropdown-toggle" data-toggle="dropdown"><?php echo $_SESSION['loggedUser'] ?></a>
                <?php 
                    } else {
                ?>
                <a href='login.php' id='signin' data-toggle="modal" data-target="#myModal">Sign In</a>
                <?php 
                    } 
                ?>
                <ul class="dropdown-menu caps">
                    <li>
                        <a href="profile.php">Profile</a>
                    </li>
                    <li>
                        <a href="favourites.php">Favourites</a>
                    </li>
                    <li>
                        <a href="orders.php">Orders</a>
                    </li>
                    <li>
                        <a href="logout.php">Log Out</a>
                    </li>
                </ul>
            </li>
            <li class="pull-left">
                <a href="faq.php" > Help</a>
            </li>
            <li id="ddl-nav" class="pull-left">
                <a href="cart.php" > Cart</a>
            </li>
        </ul>
    </div>
</nav>

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

<script>
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