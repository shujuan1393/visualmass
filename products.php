<?php 
    require_once 'config/db.php';
    
    $fav = "Select * from favourites where email='".$_SESSION['loggedUserEmail']."';";
    $fres = mysqli_query($link, $fav);
    
    if (!mysqli_query($link, $fav)) {
        echo "Error: ".mysqli_error($link);
    } else {
        $frow = mysqli_fetch_assoc($fres);
        $favArr = explode(",", $frow['pid']);
    }
    if (isset($_SESSION['searchResult'])) {
        unset($_SESSION['searchResult']);
        unset($_SESSION['searchVal']);
    }
    if (isset($_SESSION['searchError'])) {
        unset($_SESSION['searchError']);
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
                    $banner = "Select * from productbanner where categories='".$_GET['type']."' and "
                            . "gender = '".$_GET['gender']."';";
                    
                    $bresult = mysqli_query($link, $banner);
                    
                    if (!mysqli_query($link, $banner)) {
                        echo "Error: ".mysqli_error($link);
                    } else {
                        if ($bresult -> num_rows == 0) {
                            echo "<h3 id='banner' class='banner-title'>Sorry, this page is under construction.</h3>";
                        } else {
                            $brow = mysqli_fetch_assoc($bresult);
                            $browArr = explode(".", $brow['image']);

                            $ext = $browArr[count($browArr)-1];

                            $imgArr = array("jpg", "jpeg", "png", "gif");
                            $vidArr = array("mp3", "mp4", "wma");
 
                            $pos = strpos($brow['image'], '/');
                            $url = substr($brow['image'], $pos+1);
                            echo "<div class='webbanner'>";
                            
                            if (in_array($ext, $imgArr)) {
                                echo "<img id='banner' src='".$url."'>";
                            } else {
                                echo '<video id="banner" controls>
                                <source src="'.$url.'" type="video/mp4">
                                Your browser does not support the video tag.
                                </video>';
                            }
                            echo "</div>";
                        }
                    }
                ?>
                
                <div class='search_filter'>
                    <input type='checkbox' name='homeTry' value='yes'> Available for Home Try-on?
                    <ul>
                        <li>COLOUR</li>
                        <li>|</li>
                        <li>WIDTH</li>
                        <li>|</li>
                        <li>SHAPE</li>
                        <li>|</li>
                        <li>MATERIAL</li>
                    </ul>
                <div class='rightsearch'>
                    <a href='searchFrames.php' data-toggle="modal" data-target="#searchModal">SEARCH FRAMES</a>
                </div>
                </div>
                <?php 
                    $sql = "Select * from products where type='".$_GET['type']."' and "
                            . "gender LIKE '%".$_GET['gender']."%';";
                    $result = mysqli_query($link, $sql);
                    
                    if (!mysqli_query($link, $sql)) {
                        echo "Error: ".mysqli_error($link);
                    } else {
                        if ($result -> num_rows == 0) {
                            echo "<h3>There are no products matching '".$_GET['type']
                                    ."' and '".$_GET['gender']."' at the moment.</h3>";
                        } else {
                ?>  
                    <!--<div id='terms_content'>-->
                    <div id='product_table' class='products row'>
                        <?php 
                            while ($row = mysqli_fetch_assoc($result)) {
                                $imgArr = explode(",", $row['images']);
                                
                                $imgpos = strpos($imgArr[0], '/');
                                $imgurl = substr($imgArr[0], $imgpos+1);
                                echo "<div class='products col-md-4'>";
                                echo "<a href='product.php?id=".$row['pid']."'><img src='".$imgurl."'></a><br>";
                                echo "<div class='product_name col-md-2'><a href='product.php?id=".$row['pid']."'>".$row['name']."</a></div>";
                                echo '<div class="cart_icons col-md-3">'
                                . '<ul>'
                                        . '<li><a class="addcart" href="addCart.php?type=purchase&id='.$row['pid'].
                                        '"><i class="fa fa-shopping-cart fa-2x" aria-hidden="true"></i></a></li>';
                                if (isset($_SESSION['loggedUserEmail'])) {
                                    if (in_array($row['pid'], $favArr)) {
                                        echo '<li><a id="heart" href="addFavourite.php?delete=1&id='.$row['pid'].'"><i class="fa fa-heart fa-2x" aria-hidden="true"></i></a></li>';
                                    } else {
                                        echo '<li><a id="heart" href="addFavourite.php?id='.$row['pid'].'"><i class="fa fa-heart-o fa-2x" aria-hidden="true"></i></a></li>';
                                    }
                                }
                                echo '</ul></div>';
                                echo "</div>";
                            }
                        ?>
                    </div>
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
                var obj = document.getElementById('banner');
                
                if (obj !== null) {
                    obj.style.maxHeight = height - clientHeight;
                }
            </script>
        </div>
        <div class="modal fade modal-fullscreen force-fullscreen" id="searchModal" tabindex="-1" 
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
                  <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                  <button type="button" class="btn btn-primary">Save changes</button>
                </div>
              </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
          </div><!-- /.modal -->
    </body>
    
    <script>
        $('#searchModal').appendTo("body");
    </script>
</html>
