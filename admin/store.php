<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
require_once '../config/db.php';

?>

<!DOCTYPE html>
<html lang="en">
    <?php require '../nav/adminHeader.php'; ?>
    <body>
        <div id="wrapper">
            <?php require '../nav/adminMenubar.php'; ?>
            
            <!-- Content -->
            <div id="page-wrapper">

            <div class="container-fluid">

                <!-- Page Heading -->
                <div class="row">
                    <div class="col-lg-12">
                        <ol class="breadcrumb">
                            <li>
                                <a href="index.php"><i class="fa fa-home"></i></a>
                            </li>
                            <li class="active">
                                POS
                            </li>
                        </ol>
                        
                        <h1 class="page-header">Store</h1>
                        
                        Currently at:
<!--                        <select name='location' id='location'>-->
                        <input type="hidden" name="curLoc" id="curLoc" value="<?php if (isset($_SESSION['curStore'])) 
                            { echo $_SESSION['curStore']; } ?>">
                        <?php 
                        if (isset($_SESSION['curStore'])) {
                            $getCurLoc = "Select * from locations where code='".$_SESSION['curStore']."';";
                            $res = mysqli_query($link, $getCurLoc);

                            if (!mysqli_query($link, $getCurLoc)) {
                                die(mysqli_error($link));
                            } else {
                                if ($res -> num_rows > 0) {
                                    $row = mysqli_fetch_assoc($res);

                                    if (strcmp($_SESSION['curStore'], $row['code']) === 0) {
                                        echo $row['name'];
                                    }
                                }
                            }
                        } else {
                            header("Location: storeLoc.php");
                        }
//                            $locsql = "Select * from locations where status='active' and name <> 'banner';";
//                            $lres = mysqli_query($link, $locsql);
//                            
//                            if (!mysqli_query($link, $locsql)) {
//                                die(mysqli_error($link));
//                            } else {
//                                if ($lres -> num_rows === 0) {
//                                    $_SESSION['activeLocations'] = "none";
//                                    echo "<option disabled value='null'>No active locations</option>";
//                                } else {
//                                    unset($_SESSION['activeLocations']);
//                                    while($lrow = mysqli_fetch_assoc($lres)) {
//                                        echo "<option value='".$lrow['code']."' ";
//                                        
//                                        if (isset($_SESSION['curStore'])) {
//                                            if (strcmp($_SESSION['curStore'], $lrow['code']) === 0) {
//                                                echo " selected";
//                                            }
//                                        }
//                                        
//                                        echo ">".$lrow['name']."</option>";
//                                    }
//                                }
//                            }
                        ?>
                        <!--</select>-->
                        <div class='col-lg-12'>
                        <?php
                            //get all available products
                            $products = "Select * from products where status = 'active' and locations LIKE '%".$_SESSION['curStore']."%';";
                            $pres = mysqli_query($link, $products);
                            
                            $pcount = 0;
                            $rowcount = 1;
                            $relcount = 0;
                            
                            if (!mysqli_query($link, $products)) {
                                die(mysqli_error($link));
                            } else {
                                if ($pres -> num_rows === 0 || isset($_SESSION['activeLocations'])) {
                                    echo "<h4>There are no active products at the moment.</h4>";
                                } else {
                                    //for each product
                                    $allId = array();
                                    while ($row = mysqli_fetch_assoc($pres)) {
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

                                            echo "<div class='products col-lg-4 full-section' id='prod".$pcount."'>";
                                            echo "<input type='hidden' id='prodColour$pcount' value='".$pcolour."'>";
                                            echo "<input type='hidden' id='id$pcount' value='".$pid."'>";
                                            echo "<input type='hidden' id='loc".$pcount."' value='".$row['locations']."'>";
                                            echo "<input type='hidden' id='avail".$pcount."' value='".$row['availability']."'>";
                                            echo "<input type='hidden' id='prodName$pcount' value='".$row['name']."'>";
                                            if (!empty($row['images'])) {
                                                $imgArr = explode(",", $row['images']);
                                                if (!empty($imgArr[0])) {
                                                    $imgurl = $imgArr[0];
                                                } else {
                                                    $imgurl = $imgArr[1];                                        
                                                }
                                                echo "<input type='hidden' id='image$pcount' value='$imgurl'>";
                                                echo "<div id='imgLink$pcount' class='catalogue'><img src='".$imgurl."'></div><br>";
                                            } else {
                                                echo "<input type='hidden' id='image$pcount' value=''>";         
                                                echo "<div id='imgLink$pcount' class='catalogue'><img src=''></div><br>";                               
                                            }
                                            echo "<div id='nameLink$pcount' class='col-lg-6 padded'>".$row['name']."</div>";
                                            echo '<div class="cart_icons col-lg-3">'. '<ul>';
                                            echo '<li id="cartLink'.$pcount.'"><a class="addcart" href="addCart.php?type=purchase&id='.$pid.
                                                    '" data-toggle="modal" data-target="#addCartModal"><i class="fa fa-shopping-cart fa-2x" aria-hidden="true"></i></a></li>';
                                            echo '</ul></div>';
                                            
//                                            $relProd = "Select * from products where pid like '".$pidArr[0]."%' and pid <> '$pid' and status='active';";
//                                            $relres = mysqli_query($link, $relProd);
//
//                                            if (!mysqli_query($link, $relProd)) {
//                                                die(mysqli_error($link));
//                                            } else {
//                                                if ($relres -> num_rows > 0) {
//                                                    echo "<div class='col-lg-8 colours'>";
//                                                    echo "<ul>";
//                                                    while ($relrow = mysqli_fetch_assoc($relres)) {
//                                                        $idArr = explode("-", $relrow['pid']);
//                                                        if (count($idArr) > 1) {
//                                                            $relimgArr = explode(",", $relrow['images']);
//                                                            $relimg = $relimgArr[0];
//                                                            echo "<input type='hidden' id='parent$relcount' value='".$pid."'>";
//                                                            echo "<input type='hidden' id='name$relcount' value='".$idArr[1]."'>";
//                                                            echo "<input type='hidden' id='selectedId$relcount' value='".$relrow['pid']."'>";
//                                                            echo "<input type='hidden' id='img$relcount' value='$relimg'>";
//                                                            echo "<li id='colour$relcount' class='swatch'>".$idArr[1]."</li>";
//                                                            $relcount++;
//                                                        }
//                                                    }
//                                                    echo "</ul>";
//                                                    echo "</div>";
//                                                }
//                                            }
                                            echo "</div>";
                                            $pcount++;
                                            if ($pcount % 3 === 0) {
                                                echo "</div><div class='col-lg-12' id='row$rowcount'>";
                                                $rowcount++;                            
                                            }
                                        }
                                    }
                                }
                            }
                        ?>
                        </div> 
                    </div>
                </div>
                <!-- /.row -->

            </div>
            <!-- /.container-fluid -->
            <div class="modal fade" id="addCartModal" tabindex="-1" 
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
        </div>
        <!-- /#page-wrapper -->
    </div>
    <script>
        $('#addCartModal').appendTo("body");
        
        setInterval(function() {
            // method to be executed;
            $('#addCartModal').removeData('bs.modal');
        }, 500);
        
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
            imageLinkObj.innerHTML = "<img src='"+newImgVal+"'>";
            
            var nameLink = "nameLink"+parentLoc;
            var nameLinkObj = document.getElementById(nameLink);
            nameLinkObj.innerHTML = newNameVal;
            
            var cartLink = "cartLink" + parentLoc;
            var cartLinkObj = document.getElementById(cartLink);
            cartLinkObj.innerHTML = "<a class='addcart' href='addCart.php?type=purchase&id="+ selIdVal +"' data-toggle='modal' data-target='#addCartModal'><i class='fa fa-shopping-cart fa-2x' aria-hidden='true'></i></a>";
        }
        
        function findParent(parentId, numToReplace) {
            for (var i = 0; i < <?php echo $pcount; ?>; i++) {
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
        
//        function checkLocations(num) {
//            var val = document.getElementById('curLoc').value;
//            
//            var prodLoc = "loc" + num;
//            var locs = document.getElementById(prodLoc).value;
//            
//            var prod = "prod" + num;
//            
//            if (locs.indexOf(val) === -1) {
//                document.getElementById(prod).style.display = "none";
//            } else {
//                document.getElementById(prod).style.display = "block";
//            }
//        }
//        
////        document.getElementById('location').onchange = function() {
////            for (var k = 0; k < <?php // echo $pcount; ?>; k++) {
////                checkLocations(k);
////            }
////        };
//        
//        for (var k = 0; k < <?php // echo $pcount; ?>; k++) {
//            checkLocations(k);
//        }
    </script>
</html>