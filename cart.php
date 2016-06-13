<?php
    require_once 'config/db.php';
?>
<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<html>
    <head>
        <meta charset="UTF-8">
    </head>
    <body>
        
        <div id="wrapper">
            <div id="header"><?php require_once 'nav/header.php';?></div>
            
            <div id="content">
                <h3>CART</h3>
                <div id='emptyCart' style='display:none;'>
                    <?php
                        if (isset($_SESSION['order'])) {
                            echo $_SESSION['order'];
                        }
                    ?>
                    <h4>Your cart is empty. Shop now!</h4>
                </div>
                    <form method='post' action='addCart.php?update=1'>
                        <input type='hidden' name='numrows' id='numrows'>
                    <div id='cart' class='row'>
                    <?php
                        $cartSql = "Select * from cart where cartid='".GetCartId()."' and type LIKE '%giftcard' OR type = 'purchase';";
                        $res = mysqli_query($link, $cartSql);
                        
                        if(!mysqli_query($link, $cartSql)) {
                            echo "Error: ".mysqli_error($link);
                        } else {
                            $count = 0;
                            if ($res -> num_rows === 0) {
                                echo "<script>document.getElementById('numrows').value = 0;</script>";
                            } else {
                                echo "<script>document.getElementById('numrows').value = ".$res->num_rows.";</script>";
                                while($row = mysqli_fetch_assoc($res)) {
                                    $type = $row['type'];
                                    $pid = $row['pid'];

                                    $productSql = "Select * from products where pid='$pid';";
                                    $pres = mysqli_query($link, $productSql);
                                    $prow = mysqli_fetch_assoc($pres);

                                    $img = explode(",", $prow['featured']);

                                    $pos = strpos($img[0], '/');
                                    $url = substr($img[0], $pos+1);

                                    $total = $row['price'] * $row['quantity'];
                                    
                                    $giftpos = strpos($type, 'giftcard');

                                    echo "<div class='col-md-9 col-md-offset-3'>";
                                    echo "<div class='col-md-2'>";
                                    if (is_numeric($giftpos)) {
                                        echo "<h4>GIFTCARD IMAGE</h4>";
                                    } else {
                                        echo "<img src='".$url."' style='width:100%'>";
                                    }
                                    echo "</div>";
                                    echo "<div class='product_dets col-md-3'>";
                                    
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
//                                    echo "<div class='col-md-4'>";
                                    echo "<input type='hidden' name='type".$count."' value='$type'>";
                                    echo "<div class='col-md-1'><input type='text' name='quantity".$count."' value='".$row['quantity']."'>"
                                    . "</div>";
                                    echo "<div class='col-md-1'><p class='totalprice'>$".$total."</p></div>";  
                                    echo "<div class='col-md-1'><p><a class='cart_button' href='addCart.php?delete=1&id=".$type."-".$pid."'>X</a></p></div>";  
//                                    echo "</div>";
                                    echo "</div>";
                                    $count++;
                                }
                            }
                        }
                    ?>
                        <h4 id='home'>HOME TRY-ON</h4>
                        <?php
                            $homeSql = "Select * from cart where cartid='".GetCartId()."' and type='hometry';";
                            $tryres = mysqli_query($link, $homeSql);

                            if(!mysqli_query($link, $homeSql)) {
                                echo "Error: ".mysqli_error($link);
                            } else {
                                if ($tryres -> num_rows === 0) {
                                    echo "<script>document.getElementById('numrows').value = 0;</script>";
                                    echo "<script>document.getElementById('emptyCart').style.display='block';</script>";
                                    echo "<script>document.getElementById('home').style.display='none';</script>";
                                } else {
                                    echo "<script>document.getElementById('emptyCart').style.display='none';</script>";
                                    while($row = mysqli_fetch_assoc($tryres)) {
                                        echo "<script>document.getElementById('numrows').value = ".$tryres->num_rows.";</script>";
                                        $pid = $row['pid'];
                                        $type = $row['type'];
                                        
                                        $productSql = "Select * from products where pid='$pid';";
                                        $pres = mysqli_query($link, $productSql);
                                        $prow = mysqli_fetch_assoc($pres);

                                        $img = explode(",", $prow['featured']);

                                        $pos = strpos($img[0], '/');
                                        $url = substr($img[0], $pos+1);

    //                                    $total = $row['price'] * $row['quantity'];

                                        echo "<div class='col-md-9 col-md-offset-3'>";
                                        echo "<div class='col-md-2'>";
                                        echo "<img src='".$url."' style='width:100%'></div>";
                                        echo "<div class='product_dets col-md-3'>";
                                        echo "<h4>".$prow['name']."</h4><br>".html_entity_decode($prow['description']);
                                        
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
//                                        echo "<div class='col-md-4'>";
                                        echo "<input type='hidden' name='prod".$count."' value='$pid'>";
                                        echo "<input type='hidden' name='id$count' value='".$row['id']."'>";
                                        echo "<input type='hidden' name='type".$count."' value='$type'>";                                     
                                    ?>
                                    <?php 
                                        echo "<div class='col-md-1'><input type='text' name='quantity".$count."' value='".$row['quantity']."'"
                                                . " onkeypress='return isNumber(event)'>"
                                        . "</div>";
                                        echo "<div class='col-md-1'><p class='totalprice'>$0</p></div>";  
                                        echo "<div class='col-md-1'><a class='cart_button' href='addCart.php?delete=1&id=".$type."-".$pid."'>X</a></div>";  
                                        echo "</div>";
//                                        echo "</div>";
                                        $count++;
                                    }
                                }
                            }
                        ?>
                    </div>

                    <div id='updateCart' class='row' style='display:none;'>
                        <div class='col-md-5 col-md-offset-7'>
                            <input type="submit" name='submit' value='UPDATE'>
                            <a class='button' href='checkout.php'>CHECKOUT</a>
                        </div>
                    </div>
                        
                    <p id='nanError' style="display: none;">Please enter numbers only</p>
                </form>
            </div>
            
            <div id="footer"><?php require_once 'nav/footer.php';?></div>
        </div>
    </body>
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