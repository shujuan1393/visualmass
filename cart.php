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
                <div id='emptyCart' style='display:none;'><h3>Your cart is empty. Shop now!</h3></div>
                    <form method='post' action='addCart.php?update=1'>
                    <div id='cart' class='row'>
                    <?php
                        $cartSql = "Select * from cart where cartid='".GetCartId()."' and type LIKE '%giftcard' OR type = 'purchase';";
                        $res = mysqli_query($link, $cartSql);
                        
                        if(!mysqli_query($link, $cartSql)) {
                            echo "Error: ".mysqli_error($link);
                        } else {
                            $count = 0;
                            if ($res -> num_rows === 0) {
                                echo "<script>document.getElementById('emptyCart').style.display='block';</script>";
                            } else {
                                echo "<script>document.getElementById('emptyCart').style.display='none';</script>";
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
                                    $typeArr = explode("@", $type);

                                    echo "<div class='col-md-10 col-md-offset-2'>";
                                    echo "<div class='col-md-3'>";
                                    if (in_array("giftcard", $typeArr)) {
                                        echo "<h4>GIFTCARD IMAGE</h4>";
                                    } else {
                                        echo "<img src='".$url."' style='width:100%'>";
                                    }
                                    echo "</div>";
                                    echo "<div class='product_dets col-md-3'>";
                                    
                                    if (in_array("giftcard", $typeArr)) {
                                        echo "<h4>Giftcard</h4><br>";
                                        $detArr = explode(",", $row['details']);
                                        echo "To: " .$detArr[0]."<br>";
                                        echo "From: " .$detArr[1]."<br>";
                                        echo "Note: " .$detArr[2]."<br>";
                                   } else {
                                        echo "<h4>".$prow['name']."</h4><br>".html_entity_decode($prow['description']);
                                    }
                                    echo "</div>";
                                    echo "<div class='col-md-4'>";
                                    echo "<input type='hidden' name='prod".$count."' value='$pid'>";
                                    echo "<div class='col-md-2'><input type='text' name='quantity".$count."' value='".$row['quantity']."'>"
                                    . "</div>";
                                    echo "<div class='col-md-2'><p class='totalprice'>$".$total."</p></div>";  
                                    echo "<div class='col-md-2'><button class='button'><a href='addCart.php?delete=1&id=".$type."-".$pid."'>X</a></button></div>";  
                                    echo "</div>";
                                    echo "</div>";
                                    $count++;
                                }
                            }
                        }
                    ?>
                        <h3 id='home'>HOME TRY-ON</h3>
                        <?php
                            $homeSql = "Select * from cart where cartid='".GetCartId()."' and type='hometry';";
                            $tryres = mysqli_query($link, $homeSql);

                            if(!mysqli_query($link, $homeSql)) {
                                echo "Error: ".mysqli_error($link);
                            } else {
                                if ($tryres -> num_rows === 0) {
                                    echo "<script>document.getElementById('emptyCart').style.display='block';</script>";
                                    echo "<script>document.getElementById('home').style.display='none';</script>";
                                } else {
                                    echo "<script>document.getElementById('emptyCart').style.display='none';</script>";
                                    while($row = mysqli_fetch_assoc($tryres)) {
                                        $pid = $row['pid'];
                                        $type = $row['type'];
                                        
                                        $productSql = "Select * from products where pid='$pid';";
                                        $pres = mysqli_query($link, $productSql);
                                        $prow = mysqli_fetch_assoc($pres);

                                        $img = explode(",", $prow['featured']);

                                        $pos = strpos($img[0], '/');
                                        $url = substr($img[0], $pos+1);

    //                                    $total = $row['price'] * $row['quantity'];

                                        echo "<div class='col-md-10 col-md-offset-2'>";
                                        echo "<div class='col-md-3'>";
                                        echo "<img src='".$url."' style='width:100%'></div>";
                                        echo "<div class='product_dets col-md-3'>";
                                        echo "<h4>".$prow['name']."</h4><br>".html_entity_decode($prow['description']);
                                        echo "</div>";
                                        echo "<div class='col-md-4'>";
                                        echo "<input type='hidden' name='prod".$count."' value='$pid'>";
                                        echo "<div class='col-md-2'><input type='text' name='quantity".$count."' value='".$row['quantity']."'>"
                                        . "</div>";
                                        echo "<div class='col-md-2'><p class='totalprice'>$0</p></div>";  
                                        echo "<div class='col-md-2'><button class='button'><a href='addCart.php?delete=1&id=".$type."-".$pid."'>X</a></button></div>";  
                                        echo "</div>";
                                        echo "</div>";
                                        $count++;
                                    }
                                }
                            }
                        ?>
                    </div>

                    <div id='updateCart' class='row'>
                        <div class='col-md-5 col-md-offset-7'>
                            <input type="submit" name='submit' value='UPDATE'>
                            <button class='button'>CHECKOUT</button>
                        </div>
                    </div>
                </form>
            </div>
            
            <div id="footer"><?php require_once 'nav/footer.php';?></div>
        </div>
    </body>
    
</html>