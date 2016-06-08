<?php 
    require_once 'config/db.php';
    
    if (isset($_SESSION['loggedUserEmail'])) {
        $fav = "Select * from favourites where email='".$_SESSION['loggedUserEmail']."';";
        $fres = mysqli_query($link, $fav);

        if (!mysqli_query($link, $fav)) {
            echo "Error: ".mysqli_error($link);
        } else {
            $frow = mysqli_fetch_assoc($fres);
            $favArr = explode(",", $frow['pid']);
        }
    }
?>
<html>
    <head>
        <meta charset="UTF-8">
    </head>
    <body>
        
        <div id="wrapper">
            <div id="header"><?php require_once 'nav/header.php';?></div>
            
            <div id="content">
                <?php
                    $banner = "Select * from products where pid='".$_GET['id']."';";
                    $bresult = mysqli_query($link, $banner);
                    
                    if (!mysqli_query($link, $banner)) {
                        echo "Error: ".mysqli_error($link);
                    } else {
                        if ($bresult -> num_rows == 0) {
                            echo "<h3 class='banner-title'>Sorry, this product is no longer available.</h3>";
                        } else {
                            $brow = mysqli_fetch_assoc($bresult);
                            
                            $browArr = explode(",", $brow['images']);
                ?>
                
                <div id="banner" class="webbanner carousel slide" data-ride="carousel">
                    <!-- Indicators -->
                    <ol class="carousel-indicators">
                        <?php
                            for ($i = 0; $i < count($browArr); $i++) {
                                echo "<li data-target='#banner' data-slide-to='$i' ";
                                if ($i === 0) {
                                    echo "class='active'";
                                }
                                echo "></li>";
                            }
                        ?>
                    </ol>
                    <!-- Wrapper for slides -->
                    <div class="carousel-inner" role="listbox">
                        <?php
                            for ($i = 0; $i < count($browArr); $i++) {
                                    if (strcmp($browArr[$i], "") !== 0) {
                                    echo "<div class='item ";
                                    if ($i === 0) {
                                        echo "active";
                                    }
                                    echo "'>";

                                    $pos = strpos($browArr[$i], '/');
                                    $url = substr($browArr[$i], $pos+1);
                                    echo "<img src='".$url."'>";
                                    echo "</div>";
                                }
                            }
                        ?>
                    </div>

                    <!-- Left and right controls -->
                    <a class="left carousel-control" href="#banner" role="button" data-slide="prev">
                      <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
                      <span class="sr-only">Previous</span>
                    </a>
                    <a class="right carousel-control" href="#banner" role="button" data-slide="next">
                      <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
                      <span class="sr-only">Next</span>
                    </a>
                  </div>
                
                <div class='product_title'>
                    <div>
                        <h3><?php echo $brow['name']; ?></h3>
                        <div class='colours'>
                            <ul>
                                <?php 
                                    $idToCheck;
                                    
                                    $selPid = $_GET['id'];
                                    $pos = strpos($selPid, '-');
                                    if (is_numeric($pos)) {
                                        $idArr = explode("-", $selPid);
                                        $idToCheck = $idArr[0];
                                    } else {
                                        $idToCheck = $selPid;
                                    }
                                    
                                    
                                    $relProds = "Select * from products where pid like '".$idToCheck."%';";
                                    $relres = mysqli_query($link, $relProds);
                                    
                                    if (!mysqli_query($link, $relProds)) {
                                        die(mysqli_error($link));
                                    } else {
                                        $relcount = 0;
                                        while($row = mysqli_fetch_assoc($relres)) {
                                            $pid = $row['pid'];
                                            if (strcmp($pid, $selPid) !== 0) {
                                                $relpos = strpos($pid, "-");
                                                
                                                if (is_numeric($relpos)) {
                                                    $pidArr = explode("-", $pid);
                                                    $color = $pidArr[1];
                                                } else {
                                                    $color = $row['name'];
                                                }
//                                                echo "<script>alert($color);</script>";
                                                
                                                echo "<input type='hidden' id='selectedId$relcount' value='".$pid."'>";
                                                echo "<li id='colour$relcount' class='swatch'>".$color."</li>";
                                                $relcount++;
                                            }
                                        }
                                    }
                                ?>
                            </ul>
                        </div>

                    </div>
                    <div class='product-buttons'>
                        <ul>
                            <?php 
                                if (isset($favArr)) {
                                    if (in_array($brow['pid'], $favArr)) {
                                        echo '<li class="heart"><a id="heart" href="addFavourite.php?delete=1&id='.$brow['pid'].'"><i class="fa fa-heart fa-2x" aria-hidden="true"></i></a></li>';
                                    } else {
                                        echo '<li class="heart"><a id="heart" href="addFavourite.php?id='.$brow['pid'].'"><i class="fa fa-heart-o fa-2x" aria-hidden="true"></i></a></li>';
                                    }
                                }
                            ?>
                            <li><button id='hometry' class='product-button' value='<?php echo $brow['pid']; ?>' onclick='processHometry()'>Try at home for free</button></li>
                            <li><button id='buy_now' class='product-button' value='<?php echo $brow['pid']; ?>' onclick='processPurchase()'><i class="fa fa-shopping-cart fa-2x" aria-hidden="true"></i>&nbsp;BUY FROM $<?php echo $brow['price'];?></button></li>
                        </ul>
                    </div>
                </div>
                <div class="details row">
                    <div class='product_details col-md-6'>
                        <div class='row'>
                            <div class='product_desc col-md-12'>
                                <h3>ABOUT THE FRAME</h3>
                                <?php echo html_entity_decode($brow['description']); ?>
                            </div>

                            <div class='product_measurements col-md-12'>
                                <div class='col-md-6'>
                                    <h5>WIDTH</h5><br>
                                    <?php echo $brow['width']; ?>
                                </div>
                                <div class='col-md-6'>
                                    <h5>MEASUREMENTS</h5><br>
                                    <?php echo $brow['measurement']; ?>
                                </div>
                            </div>
                            <div class='row'>
                                <div class='col-md-12 col-md-offset-1'><hr></div>
                            </div>
                            <div id="myCarousel" class="carousel slide">
                                <!-- Carousel items -->
                                <div class="carousel-inner">
                                    <div class="item active">
                                        <div class="col-md-12">
                                            <?php 
                                                $count = 0;
                                                for($i = 0; $i < count($browArr); $i++) {
                                                    $count++;
                                                    $pos = strpos($browArr[$i], '/');
                                                    $url = substr($browArr[$i], $pos+1);
                                                    
                                                    echo "<div class='col-md-4'><a href='#x' id='thumb$count' class='thumbnail'>"
                                                    . "<img class='img-responsive' src='".$url."'>";
                                                    echo "</a><input type='hidden' id='url".$count."' value='$url'></div>";
                                                    if ($count % 3 === 0 && $i !== (count($browArr)-1)) {
                                                        echo "</div></div>";
                                                        echo "<div class='item'>";
                                                        echo "<div class='col-md-12'>";
                                                    }
                                                    if ($i === (count($browArr)-1)) {
                                                        echo "</div></div>";
                                                    }
                                                }
                                            ?>
                                        </div>
                                        <!--/carousel-inner--> 
                                        <a class="left carousel-control" href="#myCarousel" data-slide="prev">
                                            <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
                                        </a>

                                        <a class="right carousel-control" href="#myCarousel" data-slide="next">
                                            <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
                                        </a>
                                    </div>
                                </div>
                            </div>
                    <div id='image_display' class='product_display col-md-6'>
                        <h3>Move your cursor over to view different angles</h3>
                    </div>
                        </div>
                </div>
            </div>
                
            <div id='shipping_terms' class='row'>
                <h3>SHIPPING TERMS</h3>
            </div>
                
            <div id='virtual_tryon' class='row'>
                <div class='col-md-2'></div>
                <div class='col-md-8'>
                    <h3>GET FEEDBACK</h3>
                    <p>If you're having a hard time deciding which frame suits you, <br>ask your friends / family / co-workers </p>
                    <p>TWITTER | PINTEREST | FACEBOOK</p>
                    <img src='images/virtual_tryon.png' width='500'>
                    <div>             
                        <div class='col-md-2'></div>          
                        <div class='col-md-4'>
                        UPLOAD YOUR OWN IMAGE <br>
                        <button>CHOOSE FILE</button></div>
                        <div class='col-md-4'>ADJUST CROPPING</div>
                        <div class='col-md-2'></div>   
                    </div>
                </div>
                <div class='col-md-2'></div>
            </div>
            <hr>
            
            <div id='recommended' class='row'>
                <h3>YOU MAY ALSO LIKE</h3>
                <div class='col-md-2'></div>
                <div class='col-md-8'>
                    <div class='row'>
                    <?php 
                        $productsSql = "Select * from products where pid <> '".$brow['pid']."' and type ='".$brow['type']."' LIMIT 3;";
                       
                        $recResult = mysqli_query($link, $productsSql);
                        if (!mysqli_query($link, $productsSql)) {
                            echo "Error: ". mysqli_error($link);
                        } else {
                            while ($rec = mysqli_fetch_assoc($recResult)) {
                                $imgArr = explode(",", $rec['images']);
                                $imgpos = strpos($imgArr[0], '/');
                                $imgurl = substr($imgArr[0], $imgpos+1);
                    ?>
                        <div id='recom_prod' class='col-md-4'>
                            <a href='product.php?id=<?php echo $rec['pid']; ?>'><img src='<?php echo $imgurl; ?>'></a> <br>
                            <?php echo "<a href='product.php?id=".$rec['pid']."'>".$rec['name']."</a>"; ?>
                        </div>
                    <?php
                            }
                        }
                    ?>
                    </div>
                </div>
                <div class='col-md-2'></div>
            </div>
                        
            <?php        }
                }
            ?>
            <div id="footer"><?php require_once 'nav/footer.php';?></div>
            
            <script>
                function changeColour(num) {
                    var color = "colour" + num;
                    var colObj = document.getElementById(color);
                    
                    colObj.onclick = function() {
                        var sel = "selectedId" + num;
                        var selVal = document.getElementById(sel).value;
                        window.location = "product.php?id=" + selVal;                        
                    };
                }
                
                for (var s = 0; s < <?php echo $relcount; ?>; s++) {
                    changeColour(s);
                }
                
                function processHometry() {
                    var sel = document.getElementById('hometry').value;
                    window.location="addCart.php?type=hometry&id=" + sel;
                }
                
                function processPurchase() {
                    var sel = document.getElementById('buy_now').value;
                    window.location="addCart.php?type=purchase&id=" + sel;
                }
                
                $(document).ready(function() {
                    $('#myCarousel').carousel({
                        interval: 10000
                    });

                    $('#myCarousel').on('slid.bs.carousel', function() {
                        //alert("slid");
                    });
                });
                
                function handleElement(i) {
                    var u = "url" + i;
                    var url = document.getElementById(u).value;
                    document.getElementById("thumb"+i).onclick=function() {
                        document.getElementById('image_display').style.backgroundColor = "transparent";
                        document.getElementById('image_display').innerHTML = "<img src='" + url +"'>";
                    };
                }

                for(i=1; i<=<?php echo $count; ?>; i++) {
                    handleElement(i);
                }
                
                $("#myCarousel").mouseleave(function(){
                    var someElement = document.getElementById('image_display');
                    someElement.innerHTML = "<h3>Move your cursor over to view different angles</h3>";
                    someElement.style.backgroundColor = "#000";
                });
                var clientHeight = document.getElementById('header').clientHeight;
                var height = window.innerHeight || document.documentElement.clientHeight || document.body.clientHeight;
//                alert(clientHeight + " " + height);
                document.getElementById('banner').style.maxHeight = height - clientHeight;
                
                var $item = $('.carousel .item');
                var $wHeight = $(window).height();

                $item.height($wHeight); 

                $('.carousel img').each(function() {
                  var $src = $(this).attr('src');
                  var $color = $(this).attr('data-color');
                  $(this).parent().css({
                    'background-image' : 'url(' + $src + ')',
                    'background-color' : $color
                  });
                  $(this).remove();
                });

                $(window).on('resize', function (){
                  $wHeight = $(window).height();
                  $item.height($wHeight);
                });
                $item.eq(0).addClass('active');
            </script>
        </div>
    </body>
</html>
