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
                                Cart
                            </li>
                        </ol>
                        
                        <form method='post' action='processCart.php?update=1'>
                        
                        <div class="col-lg-12">
                            <h1 class="page-header col-lg-8">Cart</h1>
                        
                            <div id='updateCart' class='col-lg-4 text-right' style='display:none;'>
                                <input type="submit" name='submit' value='UPDATE'>
                                <a class='button' href='checkout.php'>PAY</a>
                            </div>
                        </div>
                        <div class='col-lg-12'>

                        <input type='hidden' name='numrows' id='numrows'>
                        <div id="emptyCart" style="display:none;">
                            <h4>Cart is empty.</h4>
                        </div>
                            <?php 
                                $cart = "Select * from cart where cartid='".$_SESSION['loggedUserEmail']."' order by type desc;";
                                $cres = mysqli_query($link, $cart);

                                $count = 0;
                                if (!mysqli_query($link, $cart)) {
                                    die(mysqli_error($link));
                                } else {
                                    if ($cres -> num_rows !== 0) {
                                        while($row = mysqli_fetch_assoc($cres)) {
                                            $type = $row['type'];
                                            $pid = $row['pid'];

                                            $productSql = "Select * from products where pid='$pid';";
                                            $pres = mysqli_query($link, $productSql);
                                            $prow = mysqli_fetch_assoc($pres);

                                            $img = explode(",", $prow['featured']);

//                                            $pos = strpos($img[0], '/');
                                            $url = $img[0];

                                            $total = $row['price'] * $row['quantity'];

                                            $giftpos = strpos($type, 'giftcard');

                                            echo "<div class='col-lg-11'>";
                                            echo "<div class='col-lg-2'>";
                                            if (is_numeric($giftpos)) {
                                                echo "<h4>GIFTCARD IMAGE</h4>";
                                            } else {
                                                echo "<img src='".$url."' style='width:100%'>";
                                            }
                                            echo "</div>";
                                            echo "<div class='product_dets col-lg-3'>";

                                            echo "<input type='hidden' name='prod".$count."' value='".$row['pid']."'>";
                                            echo "<input type='hidden' name='id$count' value='".$row['id']."'>";

                                            if (is_numeric($giftpos)) {
                                                echo "<h4>Giftcard</h4><br>";
                                                if (!empty($row['details'])) {
                                                    $detArr = explode(",", $row['details']);
                                                }
                                                echo "To: " .$detArr[0]."<br>";
                                                echo "From: " .$detArr[1]."<br>";
                                                echo "Note: " .$detArr[3]."<br>";
                                                $giftArr = array("physical@giftcard", "ecard@giftcard");
                                                echo "<select name='colour$count'>";
                                                    for ($i = 0; $i < count($giftArr); $i++) {
                                                        echo "<option value='".$giftArr[$i]."' ";

                                                        if (strcmp($type, $giftArr[$i]) === 0) {
                                                            echo "selected";
                                                        }
                                                        echo ">";
                                                        $gift = explode("@", $giftArr[$i]);
                                                        echo $gift[0];
                                                        echo "</option>";
                                                    }
                                                echo "</select>";
                                           } else {
                                                echo "<h4>".$prow['name']."</h4><br>".html_entity_decode($prow['description']);
                                                $lens = $row['lens'];

                                                $getlens = "Select * from products where pid ='$lens';";
                                                $lres = mysqli_query($link, $getlens);

                                                if(!mysqli_query($link, $getlens)) {
                                                    die(mysqli_error($link));
                                                } else {
                                                    $lrow = mysqli_fetch_assoc($lres);
                                                    echo "<br> Lens: ";
                                                    if (empty($lrow['name'])) {
                                                        echo "-";
                                                    } else {
                                                        echo $lrow['name'];   
                                                    }
                                                }
                                            }
                                                //get related products
                                                $relpos = strpos($pid, '-');
                                                if (is_numeric($relpos)) {
                                                    $idArr = explode("-", $pid);
                                                    $idToCheck = $idArr[0];
                                                } else {
                                                    $idToCheck = $pid;
                                                }

                                                $relsql = "Select * from products where pid like '".$idToCheck."%';";
                                                $relres = mysqli_query($link, $relsql);

                                                if (!mysqli_query($link, $relsql)) {
                                                    die(mysqli_error($link));
                                                } else {
                                                    if ($relres -> num_rows > 1) {
                                                        echo "<select name='colour".$count."'>";
                                                        while($relrow = mysqli_fetch_assoc($relres)) {
                                                            $relpid = $relrow['pid'];
                                                            echo "<option value='".$relpid."'";
                                                            if (strcmp($relpid, $pid) === 0) {
                                                                echo " selected";
                                                            }
                                                            echo ">";
                                                            $newpos = strpos($relpid, '-');
                                                            if (is_numeric($newpos)) {
                                                                $relidArr = explode("-", $relpid);
                                                                echo $relidArr[1];
                                                            } else {
                                                                echo $relrow['name'];
                                                            }
                                                            echo "</option>";
                                                        }
                                                        echo "</select>";
                                                    }
                                                }

                                            echo "</div>";
        //                                    echo "<div class='col-lg-4'>";
                                            echo "<input type='hidden' name='type".$count."' value='$type'>";
                                            echo "<div class='col-lg-2'>".$type."</div>";
                                            echo "<div class='col-lg-2'><input type='text' name='quantity".$count."' value='".$row['quantity']."'>"
                                            . "</div>";
                                            echo "<div class='col-lg-1'><p class='totalprice'>$".$total."</p></div>";  
                                            echo "<div class='col-lg-1'><p><a class='cart_button' href='processCart.php?delete=1&id=".$type."/".$pid."'>X</a></p></div>";  
        //                                    echo "</div>";
                                            echo "</div>";
                                            $count++;
                                        }
                                    }
                                    
                                    $totalcount = $count;
                                }
                                
                                echo "<script>document.getElementById('numrows').value = ".$count.";</script>";
                            ?>
                            </div> 
                            
                            <p id='nanError' class='error' style="display: none;">Please enter numbers only</p>
                        </div>
                    </form>
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
        function isNumber(evt) {
            evt = (evt) ? evt : window.event;
            var charCode = (evt.which) ? evt.which : evt.keyCode;
            if (charCode > 31 && (charCode < 48 || charCode > 57)) {
                document.getElementById('nanError').style.display='block';
                document.getElementById('nanError').style.color='red';
                return false;
            }
            document.getElementById('nanError').style.display='none';
            return true;
        }
        
        var num = document.getElementById('numrows').value;
        if (num === "0") {
            document.getElementById('emptyCart').style.display = "block";
            document.getElementById('updateCart').style.display = "none";
        } else {
            <?php unset($_SESSION['order']); ?>
            document.getElementById('emptyCart').style.display = "none";
            document.getElementById('updateCart').style.display = "block";            
        }
    </script>
</html>