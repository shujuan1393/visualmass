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
                    $banner = "Select * from products where pid='".$_GET['id']."' and status='active';";
                    $bresult = mysqli_query($link, $banner);
                    
                    $count = 0;
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
                <div id='lens_select' class='text-center' style='display:none;'>
                    <?php 
                        $lens = "Select * from products where type='Lens' and status='active';";
                        $lenres = mysqli_query($link, $lens);

                        $lcount = 0;
                        if (!mysqli_query($link, $lens)) {
                            die(mysqli_error($link));
                        } else {
                            echo "<input type='hidden' id='selected_lens'>";
                            echo "<ul>";
                            while($row1 = mysqli_fetch_assoc($lenres)) {
                                echo "<input type='hidden' id='lval$lcount' value='".$row1['pid']."'>";
                                echo "<li id='lens$lcount'>".$row1['name']."</li>";
                                $lcount++;
                            }
                            echo "</ul>";
                        }
                    ?>
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
                                    
                                    $relProds = "Select * from products where pid like '".$idToCheck."%' and status='active';";
                                    $relres = mysqli_query($link, $relProds);
                                    
                                    $relcount = 0;
                                    if (!mysqli_query($link, $relProds)) {
                                        die(mysqli_error($link));
                                    } else {
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
                                } else {
                                    echo '<li id="heart"><a href="login.php?favourite=1&id='.$brow['pid'].'" data-toggle="modal" data-target="#favModal"><i class="fa fa-heart-o fa-2x" aria-hidden="true"></i></a></li>'; 
                                }
                                
                                $availArr = explode(",", $brow['availability']);
                                
                                //get home try on settings
                                $hometry = "Select * from settings where type='homeTryon';";
                                $hres = mysqli_query($link, $hometry);

                                if (!mysqli_query($link, $hometry)) {
                                    die(mysqli_error($link));
                                } else {
                                    $row = mysqli_fetch_assoc($hres);
                                    $valArr = explode("&", $row['value']);
                                    $permission = explode("visibility=", $valArr[0]);
                                }
                            ?>
                            <?php 
                            if (strcmp($permission[1], "on") === 0) {
                                if(in_array("tryon", $availArr)) { 
                            ?>
                                <li><button id='hometry' class='product-button' value='<?php echo $brow['pid']; ?>' onclick='processHometry()'>Try at home for free</button></li>
                            <?php 
                                } 
                            }
                            ?>
                                
                            <?php if(in_array("sale", $availArr)) { ?><li><button id='buy_now' class='product-button' value='<?php echo $brow['pid']; ?>' onclick='toggleLens()'><i class="fa fa-shopping-cart fa-2x" aria-hidden="true"></i>&nbsp;BUY FROM $<?php echo $brow['price'];?></button></li><?php } ?>
                        </ul>
                    </div>
                        <?php
                            if (isset($_SESSION['homeError'])) {
                                echo "<p class='col-md-4 col-md-offset-2 error'>".$_SESSION['homeError']."</p>";
                            }
                        ?>
                </div>
                
                <div class="details row">
                    <table id='prodTable' class='full_section'> 
                        <tr width='40%'>
                            <td colspan="2" class='product_desc' width='40%'>
                                <h3>ABOUT THE FRAME</h3>
                                <?php echo html_entity_decode($brow['description']); ?>                                
                            </td>
                            <td rowspan="4" width='45%'>
                                <div id='image_display' class='product_display col-md-6'>
                                    <h3>Move your cursor over to view different angles</h3>
                                </div>
                            </td>
                        </tr>
                        <tr width='10%'>
                            <td>
                                <h5>WIDTH</h5><br>
                                <?php echo $brow['width']; ?>                                
                            </td>
                            <td>
                                <h5>MEASUREMENTS</h5><br>
                                <?php echo $brow['measurement']; ?>                             
                            </td>
                        </tr>
                        <tr width='10%'>
                            <td colspan="2">
                                <h5 class='caps'>Tags</h5>
                                <?php 
                                    if (!empty($brow['tags'])) {
                                        $tags = explode(",", $brow['tags']);

                                        for ($i = 0; $i < count($tags); $i++) {
                                            $t = $tags[$i];
                                            echo "#".$t;

                                            if ($i + 1 !== count($tags)) {
                                                echo ", ";
                                            }
                                        }
                                    } else {
                                        echo " - ";
                                    }
                                ?>
                            </td>
                        </tr>
                        <tr width='40%' id='carouselrow'>
                            <td colspan="2">
                                <div id="myCarousel" class="carousel slide">
                                    <!-- Carousel items -->
                                    <div class="carousel-inner">
                                        <div class="item active">
                                                <?php 
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
                            </td>
                        </tr>
                    </table>
<!--                    <div class='product_details col-md-6 full_section'>
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
                                <div class='col-md-10 col-md-offset-1'><hr></div>
                                <div class='col-md-12'>
                                    <h5 class='caps'>Tags</h5>
                                    <?php 
                                        if (!empty($brow['tags'])) {
                                            $tags = explode(",", $brow['tags']);

                                            for ($i = 0; $i < count($tags); $i++) {
                                                $t = $tags[$i];
                                                echo "#".$t;

                                                if ($i + 1 !== count($tags)) {
                                                    echo ", ";
                                                }
                                            }
                                        } else {
                                            echo " - ";
                                        }
                                    ?>
                                    <div class='col-md-10 col-md-offset-1'><hr></div>
                                </div>
                            </div>
                            <div id="myCarousel" class="carousel slide">
                                 Carousel items 
                                <div class="carousel-inner">
                                    <div class="item active">
                                            <?php 
//                                                for($i = 0; $i < count($browArr); $i++) {
//                                                    $count++;
//                                                    $pos = strpos($browArr[$i], '/');
//                                                    $url = substr($browArr[$i], $pos+1);
//                                                    
//                                                    echo "<div class='col-md-4'><a href='#x' id='thumb$count' class='thumbnail'>"
//                                                    . "<img class='img-responsive' src='".$url."'>";
//                                                    echo "</a><input type='hidden' id='url".$count."' value='$url'></div>";
//                                                    if ($count % 3 === 0 && $i !== (count($browArr)-1)) {
//                                                        echo "</div></div>";
//                                                        echo "<div class='item'>";
//                                                        echo "<div class='col-md-12'>";
//                                                    }
//                                                    if ($i === (count($browArr)-1)) {
//                                                        echo "</div></div>";
//                                                    }
//                                                }
                                            ?>
                                        /carousel-inner 
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
                </div>-->
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
                        $productsSql = "Select * from products where pid <> '".$brow['pid']."' and type ='".$brow['type']."' "
                                . "and status='active' LIMIT 3;";
                       
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
            <div class="modal fade modal-fullscreen force-fullscreen" id="favModal" tabindex="-1" 
                role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
               <div class="modal-dialog">
                 <div class="modal-content">
                   <div class="modal-header">
                     <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                     <h4 class="modal-title">Modal title</h4>
                   </div>
                   <div class="modal-body">
                   </div>
                   <div class="modal-footer">
                   </div>
                 </div><!-- /.modal-content -->
               </div><!-- /.modal-dialog -->
             </div><!-- /.modal -->
            <script>
                function addLensSelector(num) {
                    var str = "lens" + num;
                    var lval = "lval" + num;
                    var sel = document.getElementById('buy_now').value;
                    document.getElementById(str).onclick = function() {
                        var val = document.getElementById(lval).value;
                        window.location="addCart.php?type=purchase&id=" + sel + "&lens=" + val;
                    };
                }
                
                for (var i = 0; i < <?php echo $lcount; ?>; i++) {
                    addLensSelector(i);
                }
                
                function toggleLens() {
                    var el = document.getElementById('lens_select');
                    if (el.style.display === "none") {
                        el.style.display = "block";
                    } else {
                        el.style.display = "none";
                    }         
                }
                
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
                var taboffset = document.getElementById('prodTable').offsetTop;
                var troffset = document.getElementById('carouselrow').offsetTop;
                var tableheight = taboffset-troffset;
//                alert($wHeight-tableheight);
                $item.height(tableheight); 

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
                    var taboffset = document.getElementById('prodTable').offsetTop;
                    var troffset = document.getElementById('carouselrow').offsetTop;
                    var tableheight = taboffset-troffset;
                    $item.height(tableheight);
                });
                $item.eq(0).addClass('active');
            </script>
        </div>
    </body>
</html>
