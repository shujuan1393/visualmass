<?php
    require_once 'config/db.php';
    $nonceFromTheClient = $_POST["payment_method_nonce"];
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
                <h3>REVIEW YOUR ORDER</h3>
                <div id='review' class='col-md-12'>
                    <p>CIRCLE CIRCLE THING HERE (PART 3 - REVIEW ORDER)</p>
                    <div id='information' class='col-md-6'>
                        <?php 
                            $qty = "Select * from cart where cartid ='". GetCartId()."';";
                            $qres = mysqli_query($link, $qty);

                            if (!mysqli_query($link, $qty)) {
                                die(mysqli_error($link));
                            } else {
                                $cost = 0;
                                while ($row = mysqli_fetch_assoc($qres)) {
                                    if (strcmp($row['type'], "purchase") === 0) {
                                        preg_match('/\d+\.?\d*/', $row['price'], $matches);
                                        $price = $matches[0];
    //                                    echo $row['price']."<br>";
                                        $q = intval($row['quantity']);
                                        $cost += ($q * $price);
                                    } else if (strcmp($row['type'], "hometry") === 0) {
                                        $cost += 1;
                                    }
                                }
                        
                            $user = "Select * from user where email='".$_SESSION['loggedUserEmail']."';";
                            $ures = mysqli_query($link, $user);
                            
                            if (!mysqli_query($link, $user)) {
                                die(mysqli_error($link));
                            } else {
                                $urow = mysqli_fetch_assoc($ures);
                        ?>
                        <strong>Ship To</strong><br>
                        <?php echo $urow['firstname'] ." ".$urow['lastname']; ?><br>
                        <?php echo $urow['address']; ?>
                        <br>
                        <strong>Billing Details</strong> <br>
                        <?php 
                            $payment = $_POST['payment'];
                            echo "<div class='caps'>". $payment."</div>";
                            // echo $_POST['']; 
                        ?>
                    </div>
                    <div id='summary' class='col-md-6'>
                        <div class='col-md-3' id='reviewHeadings'>
                            Subtotal: <br>
                            Tax: <br>
                            Shipping: <br>
                            Total: <br>
                        </div>
                        <div class='col-md-3' id='reviewCosts'>
                            $<?php echo $cost; ?><br>
                            $0.00<br>
                            <strong>Free</strong><br>
                            <h6>$<?php echo $cost; ?></h6>
                            <input type='hidden' name='cost' value='<?php echo $cost; ?>'>
                        </div>
                    </div>
                    <div id='summary' class='col-md-3 col-md-offset-6'>
                        <button class='button' onclick='makePayment()'>PLACE ORDER</button>
                    </div>
                    <?php 
                            }
                        }
                    ?>
                    <hr>
                    <?php 
                        $order = "Select * from cart where cartid ='". GetCartId()."';";
                        $ores = mysqli_query($link, $order);
                        
                        if (!mysqli_query($link, $order)) {
                            die(mysqli_error($link));
                        } else {
                            while ($row = mysqli_fetch_assoc($ores)) {
                                $type = $row['type'];
                                    $pid = $row['pid'];

                                    $productSql = "Select * from products where pid='$pid';";
                                    $pres = mysqli_query($link, $productSql);
                                    $prow = mysqli_fetch_assoc($pres);

                                    $img = explode(",", $prow['featured']);

                                    $pos = strpos($img[0], '/');
                                    $url = substr($img[0], $pos+1);
                                    
                                    if (strcmp($row['type'], "hometry") === 0) {
                                        $price = 1;
                                        $quantity = 1;
                                    } else {
                                        $price = $row['price'];
                                        $quantity = $row['quantity'];
                                    }
                                    $total = $price * $quantity;
                                    
                                    $giftpos = strpos($type, 'giftcard');

                                    echo "<div class='col-md-10 col-md-offset-1'>";
                                    echo "<div class='col-md-4'>";
                                    if (is_numeric($giftpos)) {
                                        echo "<h4>GIFTCARD IMAGE</h4>";
                                    } else {
                                        echo "<img src='".$url."' style='width:100%'>";
                                    }
                                    
                                    if (is_numeric($giftpos)) {
                                        echo "<h4>Giftcard</h4>";
                                        $giftType = explode("@", $type);
                                        echo "<h6>".$giftType[0]."</h6>";
                                        if (!empty($row['details'])) {
                                            $detArr = explode(",", $row['details']);
                                        }
                                        echo "To: " .$detArr[0]."<br>";
                                        echo "From: " .$detArr[1]."<br>";
                                        echo "Note: " .$detArr[3]."<br>";
                                   } else {
                                        echo "<h4>".$prow['name']."</h4>".html_entity_decode($prow['description']);
                                        $lens = $row['lens'];
                                        
                                        $getlens = "Select * from products where pid ='$lens';";
                                        $lres = mysqli_query($link, $getlens);
                                        
                                        if(!mysqli_query($link, $getlens)) {
                                            die(mysqli_error($link));
                                        } else {
                                            $lrow = mysqli_fetch_assoc($lres);
                                            echo "<br> Lens: ";
                                            
                                            if (!empty($lrow['name'])) {
                                                echo $lrow['name'];         
                                            } else {
                                                echo "-";
                                            }
                                        }
                                    }
                                        
                                    echo "</div>";
                                    echo "<div class='col-md-4'>";
                                    echo "<p class='totalprice caps'>".$type."</p>";
                                    echo "</div>";
                                    echo "<div class='col-md-4'><p class='totalprice'>$".$total."</p></div>";  
//                                    echo "</div>";
                                    echo "</div>";
                            }
                        }
                    ?>
                </div>
            </div>
            
            <div id="footer"><?php require_once 'nav/footer.php';?></div>
        </div>
    </body>
    <script>
        function makePayment() {
            var str = <?php echo '"'.$nonceFromTheClient.'"'; ?>;
            var cost = <?php echo $cost; ?>;
            var payment = <?php echo '"'.$payment.'"'; ?>;
            window.location = "processPayment.php?id=" + str + "&cost=" + cost + "&payment=" + payment;
        }
    </script>
</html>