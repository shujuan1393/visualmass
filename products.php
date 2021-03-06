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
                    
                    $cols = array();
                    $colours = "Select * from products where type='".$_GET['type']."';";
                    $colres = mysqli_query($link, $colours);

                    if (!mysqli_query($link, $colours)) {
                        die(mysqli_error($link));
                    } else {
                        if($colres -> num_rows > 0) {
                            while($row = mysqli_fetch_assoc($colres)) {
                                $pid = $row['pid'];
                                $idArr = explode("-", $pid);
                                if (!empty($idArr[1])) {
                                    if(!in_array($idArr[1], $cols)) {
                                        array_push($cols, $idArr[1]);
                                    } 
                                }
                            }
                        }
                    }
                ?>
                
                <div class='search_filter'>
                    <input type='checkbox' name='homeTry' id='hometry' value='yes'> Available for Home Try-on?
                    <ul class="addMore">
                        <li id='colour'>COLOUR</li>
                        <li>|</li>
                        <li id='width'>WIDTH</li>
                        <li>|</li>
                        <li id='shape'>SHAPE</li>
                        <li>|</li>
                        <li id='material'>MATERIAL</li>
                    </ul>
                
                <div class='rightsearch'>
                    <a href='searchFrames.php' data-toggle="modal" data-target="#searchModal">SEARCH FRAMES</a>
                </div>
                </div>
                <input type='hidden' name='filterColours' id='filterColours'>
                <div id='showColour' class='row' style='display:none;'>
                    <?php 
                        for($i = 0; $i < count($cols); $i++) {
                            echo "<input type='checkbox' name='storedColour".$i."' onclick='addColours(this)' id='storedColour".$i."' value='".$cols[$i]."'>";
                            echo $cols[$i]."&nbsp;";
                        }
                    ?>
                </div>
                
                <div id='showMaterial' class='row' style='display: none;'>
                    <?php 
                        $mat = "Select * from materials where type='".$_GET['type']."';";
                        $mres = mysqli_query($link, $mat);
                        
                        if (!mysqli_query($link, $mat)) {
                            die(mysqli_error($link));
                        } else {
                            if($mres -> num_rows > 0) {
                                $matcount = 0;
                                while($mrow = mysqli_fetch_assoc($mres)) {
                                    echo "<input type='checkbox' name='storedMat".$matcount."' onclick='' id='storedMat".$matcount."' value='".$mrow['name']."'>";
                                    echo $mrow['name']."&nbsp;";
                                }
                            }
                        }
                    ?>
                </div>
                <?php 
                    $sql = "Select * from products where type='".$_GET['type']."' and "
                            . "gender LIKE '%".$_GET['gender']."%' and status='active';";
                    $result = mysqli_query($link, $sql);
                    
                    $count = 0;
                    $rowcount = 1;
                    $relcount = 0;
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
                        <div class="col-md-12" id='row0'>
                        <?php 
                            //for each product
                            $allId = array();
                            while ($row = mysqli_fetch_assoc($result)) {
                                //get product id
                                $pid = $row['pid'];
                                $idpos = strpos($pid, "-");
                                $pidArr = explode("-", $pid);                                    
                                
                                if (!in_array($pidArr[0], $allId)) {
                                    //get all colours 
                                    $col = "Select * from products where pid like '".$pidArr[0]."%';";
                                    $cres = mysqli_query($link, $col);
                                    $pcolours = array();
                                    
                                    if (!mysqli_query($link, $col)) {
                                        die(mysqli_error($link));
                                    } else {
                                        if($cres -> num_rows > 0) {
                                            while($crow = mysqli_fetch_assoc($cres)) {
                                                $id = explode("-", $crow['pid']);
                                                if (!empty($id[1])) {
                                                    if(!in_array($id[1], $pcolours)) {
                                                        array_push($pcolours, $id[1]);
                                                    }
                                                }
                                            }
                                        }
                                    }
                                    $pcolour = implode(",", $pcolours);
                                    
                                    array_push($allId, $pidArr[0]);
                                    
                                    echo "<div class='products col-md-4 full-section' id='prod".$count."'>";
                                    echo "<input type='hidden' id='prodColour$count' value='".$pcolour."'>";
                                    echo "<input type='hidden' id='id$count' value='".$pid."'>";
                                    echo "<input type='hidden' id='avail".$count."' value='".$row['availability']."'>";
                                    echo "<input type='hidden' id='prodName$count' value='".$row['name']."'>";
                                    if (!empty($row['images'])) {
                                        $imgArr = explode(",", $row['images']);
                                        if (!empty($imgArr[0])) {
                                            $imgpos = strpos($imgArr[0], '/');
                                            $imgurl = substr($imgArr[0], $imgpos+1);
                                        } else {
                                            $imgpos = strpos($imgArr[1], '/');
                                            $imgurl = substr($imgArr[1], $imgpos+1);                                        
                                        }
                                        echo "<input type='hidden' id='image$count' value='$imgurl'>";
                                        echo "<div id='imgLink$count' class='catalogue'><a href='product.php?id=".$pid."'><img src='".$imgurl."'></a></div><br>";
                                    } else {
                                        echo "<input type='hidden' id='image$count' value=''>";         
                                        echo "<div id='imgLink$count' class='catalogue'><a href='product.php?id=".$pid."'><img src=''></a></div><br>";                               
                                    }
                                    echo "<div id='nameLink$count' class='product_name col-md-2'><a href='product.php?id=".$pid."'>".$row['name']."</a></div>";
                                    echo '<div class="cart_icons col-md-3">'. '<ul>';
                                    echo '<li id="cartLink'.$count.'"><a class="addcart" href="addCart.php?type=purchase&id='.$pid.
                                            '"><i class="fa fa-shopping-cart fa-2x" aria-hidden="true"></i></a></li>';
                                    if (isset($_SESSION['loggedUserEmail'])) {
                                        if (in_array($pid, $favArr)) {
                                            echo '<li id="heartLink'.$count.'"><a id="heart" href="addFavourite.php?delete=1&id='.$pid.'"><i class="fa fa-heart fa-2x" aria-hidden="true"></i></a></li>';
                                        } else {
                                            echo '<li id="heartLink'.$count.'"><a id="heart" href="addFavourite.php?id='.$pid.'"><i class="fa fa-heart-o fa-2x" aria-hidden="true"></i></a></li>';
                                        }
                                    } else {
                                        echo '<li id="heartLink'.$count.'"><a href="login.php?favourite=1&id='.$pid.'" data-toggle="modal" data-target="#favModal"><i class="fa fa-heart-o fa-2x" aria-hidden="true"></i></a></li>'; 
                                    }
                                    echo '</ul></div>';

                                    $relProd = "Select * from products where pid like '".$pidArr[0]."%' and pid <> '$pid' and status='active';";
                                    $relres = mysqli_query($link, $relProd);

                                    if (!mysqli_query($link, $relProd)) {
                                        die(mysqli_error($link));
                                    } else {
                                        if ($relres -> num_rows > 0) {
                                            echo "<div class='col-md-6 colours'>";
                                            echo "<ul>";
                                            while ($relrow = mysqli_fetch_assoc($relres)) {
                                                $idArr = explode("-", $relrow['pid']);
                                                if (count($idArr) > 1) {
                                                    $relimgArr = explode(",", $relrow['images']);
                                                    $relpos = strpos($relimgArr[0], '/');
                                                    $relimg = substr($relimgArr[0], $relpos+1);
                                                    echo "<input type='hidden' id='parent$relcount' value='".$pid."'>";
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
                                    if ($count % 3 === 0) {
                                        echo "</div><div class='col-md-12' id='row$rowcount'>";
                                        $rowcount++;                            
                                    }
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
        
        <div class="modal fade modal-fullscreen force-fullscreen" id="favModal" tabindex="-1" 
             role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog">
              <div class="modal-content">
                <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                  <h4 class="modal-title"></h4>
                </div>
                <div class="modal-body">
                </div>
                <div class="modal-footer">
                </div>
              </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
          </div><!-- /.modal -->
        
        <div class="modal fade modal-fullscreen force-fullscreen" id="searchModal" tabindex="-1" 
             role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog">
              <div class="modal-content">
                <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                  <h4 class="modal-title"></h4>
                </div>
                <div class="modal-body">
                </div>
                <div class="modal-footer">
                </div>
              </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
          </div><!-- /.modal -->
    </body>
    
    <script>
        $('#searchModal').appendTo("body");
        
        document.getElementById('colour').onclick = function() {
            var el = document.getElementById('showColour');
            
            if (el.style.display === "block") {
                el.style.display = "none";
                document.getElementById('colour').style.textDecoration = "none";
                document.getElementById('colour').style.fontWeight = "normal";
            } else {
                el.style.display = "block";
                document.getElementById('colour').style.textDecoration = "underline";
                document.getElementById('colour').style.fontWeight = "bold";
                document.getElementById('showMaterial').style.display = "none";
                document.getElementById('material').style.textDecoration = "none";
                document.getElementById('material').style.fontWeight = "normal";
            }
        };
        
        document.getElementById('material').onclick = function() {
            var el = document.getElementById('showMaterial');
            
            if (el.style.display === "block") {
                el.style.display = "none";
                document.getElementById('material').style.textDecoration = "none";
                document.getElementById('material').style.fontWeight = "normal";
            } else {
                el.style.display = "block";
                document.getElementById('material').style.textDecoration = "underline";
                document.getElementById('material').style.fontWeight = "bold";
                document.getElementById('showColour').style.display = "none";
                document.getElementById('colour').style.textDecoration = "none";
                document.getElementById('colour').style.fontWeight = "normal";
            }
        };
        
        function checkColours() {
            var colours = document.getElementById('filterColours').value;
            for(var i = 0; i < <?php echo $count; ?>; i++) {
                var str = "prodColour"+i;
                var cols = document.getElementById(str).value;
                
                var prod = "prod" + i;
                if (colours === "") {
                    document.getElementById(prod).style.display = "block";
                } else if (cols === "" || cols === null || cols === undefined) {
                    document.getElementById(prod).style.display = "none";
                } else {
                    var arr = colours.split(",");
                    if (arr.length === 0) {
                        arr = [colours];
                    }
                    toShow = false;
                    
                    for (var s = 0; s < arr.length; s++) {
                        var col = arr[s];
//                    alert(cols + " - " + col);
                        if (!toShow && s !== 0 && cols.indexOf(col) > -1) {
                            toShow = true;
                        } else if (toShow && s !== 0) {
                            return;
                        }
                    }
                    
                    if (toShow) {
                        document.getElementById(prod).style.display = "block";
                    } else {
                        document.getElementById(prod).style.display = "none";
                    }
                }
            }
        }
        
        function addColours(checkbox) {
            var exist = document.getElementById('filterColours').value;
            var val = checkbox.value;
            var index = exist.indexOf(val);
            
            if (checkbox.checked) {
                if (index === -1) {
                    document.getElementById('filterColours').value += ","+val;
                }
            } else {
                if (index > -1) {
                    var arr = exist.split(",");
                    var arrindex = exist.indexOf(val);
                    arr.splice(arrindex, 1);
                    arr = arr.join();
                    document.getElementById('filterColours').value = arr;
                }
            }
            checkColours();
        }
        
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
                if (avail.indexOf("tryon") > -1) {
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
        
        function changeParent(newPar, oldPar) {
            for (var i = 0; i < <?php echo $relcount; ?>; i++) {
                var par = "parent" + i;
                var parObj = document.getElementById(par);
                var val = parObj.value;
                if (val.indexOf(oldPar) > -1) {
                    parObj.value = newPar;
                }
            }
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
//            
//            var parId = "parent" + parentLoc;
//            var parIdObj = document.getElementById(parId);
//            var parIdVal = parIdObj.value;
            
            //switch parent values
            imgObj.value = parImgUrl;
            nameObj.value = parNameVal;
            parImgObj.value = newImgVal;
            parNameObj.value = newNameVal;
            idObj.value = selIdVal;
            selIdObj.value = idVal;
            changeParent(selIdVal, parentId);
//            parIdObj.value = selIdVal;
            
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
            if (heartLinkObj !== null) {
                heartLinkObj.innerHTML = "<a id='heart' href='addFavourite.php?id=" + selIdVal +"'><i class='fa fa-heart-o fa-2x' aria-hidden='true'></i></a>";
            }
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
        
        for (var i = 0; i < <?php echo $rowcount; ?>; i++) {
            var str = "row" + i;
            var sectionObj = document.getElementById(str);

            if (sectionObj !== null) {
            var secheight = sectionObj.offsetTop;

            <?php 
                $advSql = "Select * from advertisements where status='active' and visibility like '%catalogue%';";
                $advres = mysqli_query($link, $advSql);

                if (!mysqli_query($link, $advSql)) {
                    die(mysqli_error($link));
                } else {
                    while ($advrow = mysqli_fetch_assoc($advres)) {
                        $minheight = $advrow['minheight'];
            ?>
                    if (secheight > <?php echo $minheight; ?>) {
            <?php
                $advimg = $advrow['image'];
                $advimagepos = strpos($advimg, '/');
                $advimageurl = substr($advimg, $advimagepos+1);
                $toPrint = "";

                $toPrint .= "<div class='col-md-12' ";
                if (strcmp($advrow['imagepos'], "background") === 0) {
                    $image = '"'.$advimageurl.'"';
                    $toPrint .= "style='background-image: url($advimg); background-repeat: no-repeat; background-size: 500px auto;'>";
                } else {
                    $toPrint .= ">";
                    $toPrint .= "<div class='section-image' style='text-align:".$advrow['imagepos']."; float:".$advrow['imagepos']."'>";
                    $toPrint .= "<img src='".$advimageurl."'>";
                    $toPrint .= "</div>";
                }

                if (!empty($advrow['html'])) {
                    if (strcmp($advrow['htmlpos'], "center") === 0) {
                        $toPrint .= "<div class='section-text' style='left: 25%; right: 25%;'>";
                    } else {
                        $toPrint .= "<div class='section-text' style='float:".$advrow['htmlpos'].";'>";
                    }
//                        $toPrint .= "<div class='section-text' style='float:".$advrow['htmlpos']."'>"; 
                    $toPrint .= trim(html_entity_decode($advrow['html']))."</div>";
                }

                if (!empty($advrow['buttontext'])) {
                    $textArr = explode(",", $advrow['buttontext']);
                    $linkArr = explode(",", $advrow['link']);
                    $linkposArr = explode(",", $advrow['linkpos']);
                    $prevpos = $linkposArr[0];

                    if (strcmp($linkposArr[0], "center") === 0) {
                        $toPrint .= "<div class='section-link' style='left: 25%; right: 25%;'>";
                    } else {
                        $toPrint .= "<div class='section-link' style='text-align:".$linkposArr[0]."; ".$linkposArr[0].": 0;'>";
                    }
                    for ($i = 0; $i < count($textArr); $i++) {
                        if (strcmp($linkposArr[$i], $prevpos)!==0 ) {
                            $toPrint .= "</div>";
                        }
                        if (strcmp($linkposArr[$i], $prevpos)!==0 ) {
                            if (strcmp($linkposArr[$i], "center") === 0) {
                                $toPrint .= "<div class='section-link' style='left: 25%; right: 25%;'>";
                            } else {
                                $toPrint .= "<div class='section-link' style='text-align:".$linkposArr[$i]."; ".$linkposArr[$i].": 0;'>";
                            }
                        }
                        $toPrint .= "<a class='button' href='".$linkArr[$i]."'>".$textArr[$i]."</a>";
                        $prevpos = $linkposArr[$i];
                    }
                    $toPrint .= "</div>";

                }
                $toPrint .= "</div>";
            ?>    
                var newElm = document.createElement('div');
                newElm.innerHTML = "<?php echo $toPrint; ?>";  
                sectionObj.parentNode.insertBefore(newElm, sectionObj);// firstChild.nextSibling);
                    }
            <?php
                    }
                }
            ?>
            }
        }
    </script>
</html>
