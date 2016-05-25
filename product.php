<?php 
    require_once 'config/db.php';
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
                            echo "<h3 class='banner-title' id='banner'>There are no images for this product at the moment.</h3>";
                        } else {
                            $brow = mysqli_fetch_assoc($bresult);
//                            
//                            echo "<div class='webbanner' id='banner'>";
                            $imgArr = explode(",", $brow['images']);
//                            
//                            for ($i = 0; $i < count($imgArr); $i++){
//                                $imgpos = strpos($imgArr[$i], '/');
//                                $imgurl = substr($imgArr[$i], $imgpos+1);
//                                echo "<img src='".$imgurl."'>";                                
//                            }
//                            echo "</div>";
                ?>
                <div id="banner" class="webbanner carousel slide" data-ride="carousel">
                    <!-- Indicators -->
                    <ol class="carousel-indicators">
                        <?php 
                            for ($i = 0; $i < count($imgArr); $i++){
                                echo "<li data-target='#banner' data-slide-to='".$i."'";
                                if ($i === 0) {
                                    echo "class='active'";
                                }
                                echo "'></li>";
                            }
                        ?>
                    </ol>

                    <!-- Wrapper for slides -->
                    <div class="carousel-inner" role="listbox">
                        <?php 
                            for ($i = 0; $i < count($imgArr); $i++){
                                echo "<div class='item ";
                                if ($i === 0) {
                                    echo "active";
                                }
                                echo "'>";
                                $imgpos = strpos($imgArr[$i], '/');
                                $imgurl = substr($imgArr[$i], $imgpos+1);
                                echo "<img src='".$imgurl."'>";  
                                echo "</div>";
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
                        <div class='color_swatch'> COLOUR SWATCH</div>
                    </div>
                    <div class='product-buttons'>
                        <ul>
                            <li>Try at home for free</li>
                            <li>BUY FROM $<?php echo $brow['price']; ?></li>
                        </ul>
                    </div>
                </div>
                <hr>
                <div class='product_details'>
                    <div>
                        <h4>ABOUT THE FRAME</h4>
                        <div class='product_desc'><?php echo html_entity_decode($brow['description']); ?></div>
                        <div class='product_measurements'>
                            <h5>WIDTH</h5>
                            <?php echo $brow['width']; ?>
                            <h5>MEASUREMENTS</h5>
                            <?php echo $brow['measurement']; ?>
                        <hr>
                        </div>
                        <div class='product_images'>
                            <?php 
                                for ($i = 0; $i < count($imgArr); $i++){
                                    $imgpos = strpos($imgArr[$i], '/');
                                    $imgurl = substr($imgArr[$i], $imgpos+1);
                                    echo "<img src='".$imgurl."' width>";  
                                    echo "</div>";
                                }
                            ?>
                <div id="myCarousel" class="product_images carousel slide">

                    <!-- Carousel items -->
                    <div class="carousel-inner">
                        <?php 
                            for ($i = 0; $i < count($imgArr); $i++){
                                echo "<div class='item ";
                                if ($i === 0) {
                                    echo "active";
                                }
                                echo "'>";
                                if ($i % 3 === 0 || count($imgArr) < 3) {
                                    echo "<div class='row'>";
                                }
                                $imgpos = strpos($imgArr[$i], '/');
                                $imgurl = substr($imgArr[$i], $imgpos+1);
                                echo "<div class='col-sm-3'><img src='".$imgurl."' class='img-responsive' width='50'></div>";  
                                if ($i % 3 === 0 || count($imgArr) < 3) {
                                    echo "</div>";
                                }
                                echo "</div>";
                            }
                        ?>
                        <!--/item-->
                    </div>
                    <!--/carousel-inner--> <a class="left carousel-control" href="#myCarousel" data-slide="prev">‹</a>

                    <a class="right carousel-control" href="#myCarousel" data-slide="next">›</a>
                </div>
                <!--/myCarousel-->
                        </div>
                    </div>
                    <div class='product-buttons'>
                        
                    </div>
                </div>
                <hr>
                <?php 
                        }
                    }
                ?>
                <?php 
                    $sql = "Select * from products where type='".$_GET['type'].
                            "' and gender LIKE '%".$_GET['gender']."%'";
                    $result = mysqli_query($link, $sql);
                    
                    if (!mysqli_query($link, $sql)) {
                        echo "Error: ".mysqli_error($link);
                    } else {
                        if ($result -> num_rows == 0) {
                            echo "<h3 class='banner-title'>There are no products matching '".$_GET['type']."' and '".$_GET['gender']."' at the moment.</h3>";
                        } else {
                ?>  
                    <!--<div id='terms_content'>-->
                    <table class='product_table'>
                        <?php 
                            $count = 0;
                            echo "<tr>";
                            while ($row = mysqli_fetch_assoc($result)) {
                                $count++;
                                if (!empty($row['images'])) {
                                    $imgArr = explode(",", $row['images']);
                                    $imgpos = strpos($imgArr[0], '/');
                                    $imgurl = substr($imgArr[0], $imgpos+1);
                                    echo "<td><a href='product.php?id=".$row['pid']."'><img src='".$imgurl."'></a><br>";
                                    echo "<span class='product-title'><a href='product.php?id=".$row['pid']."'>"
                                        .$row['name']."</a></span>";
                                    echo "</td>";
                                }
                            }
                        ?>
                    </table>
                    <!--</div>-->
                <?php
                        }
                    }
                ?>
                
            </div>
            
            <div id="footer"><?php require_once 'nav/footer.php';?></div>
            
            <script>
                var clientHeight = document.getElementById('header').clientHeight;
                var height = window.innerHeight || document.documentElement.clientHeight || document.body.clientHeight;
//                alert(clientHeight + " " + height);
                document.getElementById('banner').style.maxHeight = height - clientHeight;
            </script>
        </div>
    </body>
</html>
