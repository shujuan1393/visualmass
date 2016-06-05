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
                    <input type='checkbox' name='homeTry' id='hometry' value='yes'> Available for Home Try-on?
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
                        <div id='noHometry' style='display:none;'><h3>There are no products available for Home Try</h3></div>
                        <?php 
                            $count = 0;
                            $relcount = 0;
                            //for each product
                            while ($row = mysqli_fetch_assoc($result)) {
                                //get product id
                                $pid = $row['pid'];
                                $pidArr = explode("-", $pid);
                                if (count($pidArr) === 1) {
                                    $imgArr = explode(",", $row['images']);
                                    $imgpos = strpos($imgArr[0], '/');
                                    $imgurl = substr($imgArr[0], $imgpos+1);
                                    echo "<div class='products col-md-4' id='prod".$count."'>";
                                    echo "<input type='hidden' id='id$count' value='".$pidArr[0]."'>";
                                    echo "<input type='hidden' id='avail".$count."' value='".$row['availability']."'>";
                                    echo "<input type='hidden' id='image$count' value='$imgurl'>";
                                    echo "<input type='hidden' id='prodName$count' value='".$row['name']."'>";
                                    echo "<div id='imgLink$count'><a href='product.php?id=".$pidArr[0]."'><img src='".$imgurl."'></a></div><br>";
                                    echo "<div id='nameLink$count' class='product_name col-md-2'><a href='product.php?id=".$pidArr[0]."'>".$row['name']."</a></div>";
                                    echo '<div class="cart_icons col-md-3">'. '<ul>';
                                    echo '<li id="cartLink'.$count.'"><a class="addcart" href="addCart.php?type=purchase&id='.$pidArr[0].
                                            '"><i class="fa fa-shopping-cart fa-2x" aria-hidden="true"></i></a></li>';
                                    if (isset($_SESSION['loggedUserEmail'])) {
                                        if (in_array($pidArr[0], $favArr)) {
                                            echo '<li id="heartLink'.$count.'"><a id="heart" href="addFavourite.php?delete=1&id='.$pidArr[0].'"><i class="fa fa-heart fa-2x" aria-hidden="true"></i></a></li>';
                                        } else {
                                            echo '<li id="heartLink'.$count.'"><a id="heart" href="addFavourite.php?id='.$pidArr[0].'"><i class="fa fa-heart-o fa-2x" aria-hidden="true"></i></a></li>';
                                        }
                                    }
                                    echo '</ul></div>';
                                    
                                    $relProd = "Select * from products where pid like '".$pidArr[0]."%';";
                                    $relres = mysqli_query($link, $relProd);
                                    
                                    if (!mysqli_query($link, $relProd)) {
                                        die(mysqli_error($link));
                                    } else {
                                        if ($relres -> num_rows > 0) {
                                            echo "<div class='col-md-4 colours'>";
                                            echo "<ul>";
                                            while ($relrow = mysqli_fetch_assoc($relres)) {
                                                $idArr = explode("-", $relrow['pid']);
                                                if (count($idArr) > 1) {
                                                    $relimgArr = explode(",", $relrow['images']);
                                                    $relpos = strpos($relimgArr[0], '/');
                                                    $relimg = substr($relimgArr[0], $relpos+1);
                                                    echo "<input type='hidden' id='parent$relcount' value='".$idArr[0]."'>";
                                                    echo "<input type='hidden' id='name$relcount' value='".$idArr[1]."'>";
                                                    echo "<input type='hidden' id='selectedId$relcount' value='".$relrow['pid']."'>";
                                                    echo "<input type='hidden' id='img$relcount' value='$relimg'>";
                                                    echo "<li id='colour$relcount' class='swatch'>".$idArr[1]."</li>";
                                                    $relcount++;
                                                }
                                            }
                                            echo "</ul>";
                                            echo "</div>";
                                        }
                                    }
                                    echo "</div>";
                                    $count++;
                                }
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
        
        function checkAllProducts() {
            var count = 0;
            for (var i = 0; i < <?php echo $count; ?>; i++) {
                var str = "prod" + i;
                var obj = document.getElementById(str).style.display;
                
                if (obj === "none") {
                    count++;
                }
            }
            
            if (count === <?php echo $count; ?>) {
                document.getElementById('noHometry').style.display = "block";
            } else {
                document.getElementById('noHometry').style.display = "none";                
            }
        }
        
        function checkProducts(num, val) {
            var str = "avail" + num;
            var avail = document.getElementById(str).value;
            
            var prod = "prod" + num;
            var obj = document.getElementById(prod);
            if (val === 'yes') {
                if (avail.indexOf("hometry") > -1) {
                    obj.style.display = "block";
                } else {
                    obj.style.display = "none";                
                }
            } else if (val === "no") {
                obj.style.display = "block";                  
            }
        }
        
        document.getElementById('hometry').onclick = function () {
            var val = document.getElementById('hometry').value;
            
            if (val === "yes") {
                document.getElementById('hometry').value = "no";
                for (var i = 0; i < <?php echo $count; ?>; i++) {
                    checkProducts(i, "yes");
                }
            } else if (val === "no") {
                document.getElementById('hometry').value = "yes";
                for (var i = 0; i < <?php echo $count; ?>; i++) {
                    checkProducts(i, "no");
                }
            }
            checkAllProducts();
        };  
        
        function switchValues(parentLoc, numToReplace, parentId) {
            var image = "img" + numToReplace;
            var imgObj = document.getElementById(image);
            var newImgVal = imgObj.value;
            
            var selId = "selectedId" + numToReplace;
            var selIdObj = document.getElementById(selId);
            var selIdVal = selIdObj.value;
            
            var name = "name"+ numToReplace;
            var nameObj = document.getElementById(name);
            var newNameVal = nameObj.value;
            
            var id = "id" + parentLoc;
            var idObj = document.getElementById(id);
            var idVal = idObj.value;
            
            var parentImg = "image" + parentLoc;
            var parImgObj = document.getElementById(parentImg);
            var parImgUrl = parImgObj.value;
            
            var parentName = "prodName" + parentLoc;
            var parNameObj = document.getElementById(parentName);
            var parNameVal = parNameObj.value;
            
            //switch parent values
            imgObj.value = parImgUrl;
            nameObj.value = parNameVal;
            parImgObj.value = newImgVal;
            parNameObj.value = newNameVal;
            idObj.value = selIdVal;
            selIdObj.value = idVal;
            
            var color = "colour" + numToReplace;
            var colObj = document.getElementById(color);
            colObj.innerHTML = parNameVal;
            
            var imageLink = "imgLink" +parentLoc;
            var imageLinkObj = document.getElementById(imageLink);
            imageLinkObj.innerHTML = "<a href='product.php?id=" + selIdVal +"'><img src='"+newImgVal+"'></a>";
            
            var nameLink = "nameLink"+parentLoc;
            var nameLinkObj = document.getElementById(nameLink);
            nameLinkObj.innerHTML = "<a href='product.php?id=" + selIdVal +"'>"+ newNameVal +"</a>";
            
            var cartLink = "cartLink" + parentLoc;
            var cartLinkObj = document.getElementById(cartLink);
            cartLinkObj.innerHTML = "<a class='addcart' href='addCart.php?type=purchase&id="+ selIdVal +"'><i class='fa fa-shopping-cart fa-2x' aria-hidden='true'></i></a>";
            
            var heartLink = "heartLink" + parentLoc;
            var heartLinkObj = document.getElementById(heartLink);
            heartLinkObj.innerHTML = "<a id='heart' href='addFavourite.php?id=" + selIdVal +"'><i class='fa fa-heart-o fa-2x' aria-hidden='true'></i></a>";
            
        }
        function findParent(parentId, numToReplace) {
            for (var i = 0; i < <?php echo $count; ?>; i++) {
                var id = "id" + i;
                var obj = document.getElementById(id);
                var val = obj.value;
                
                if (val.indexOf(parentId) > -1) {
                    switchValues(i, numToReplace, parentId);
                }
            }
        }
        
        function handleElements(num) {
            var colour = "colour" + num;
            
            document.getElementById(colour).onclick = function() {
                var parent = "parent" +num;
                var parObj = document.getElementById(parent);
                var newParVal = parObj.value;
                
                findParent(newParVal, num);
            };
        }
        
        for (var i = 0; i < <?php echo $relcount; ?>; i++) {
            handleElements(i);
        }
    </script>
</html>
