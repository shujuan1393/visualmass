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
                        if (isset($_SESSION['loggedUserEmail'])) {
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
                        <?php 
                            }
                        } else {
                            $firstname = $_POST['firstname'];
                            $lastname = $_POST['lastname'];
                            $address = $_POST['address'];
                            $email = $_POST['email'];
                            $isGuest = $_POST['isGuest'];
                            $phone = $_POST['phone'];
                            
                            echo $firstname . " ".$lastname."<br>";
                            echo $email."<br>";
                            echo $address."<br>";
                        } ?>
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
                            <span>Discount:</span>
                        </div>
                        <div class='col-md-3' id='reviewCosts'>
                            $<?php echo $cost; ?><br>
                            $0.00<br>
                            <strong>Free</strong><br>
                            <h6>$<?php echo $cost; ?></h6>
                            <span id='showDiscount'> - </span><br>
                            <input type='hidden' name='cost' value='<?php echo $cost; ?>'>
                            
                            <?php 
                                //get all referral codes
                                $check = "Select * from referrals where email ='".$_SESSION['loggedUserEmail']."';";
                                $cres = mysqli_query($link, $check);

                                if (!mysqli_query($link, $check)) {
                                    die(mysqli_error($link));
                                } else {
                                    if ($cres -> num_rows === 0) {
                                        $_SESSION['canRefer'] = "yes";
                                        //get all users' codes except current user
                                        $getcodes = "Select * from user where email <> '".$_SESSION['loggedUserEmail']."';";
                                        $gres = mysqli_query($link, $getcodes);

                                        if (!mysqli_query($link, $getcodes)) {
                                            die(mysqli_error($link));
                                        } else {
                                            if ($gres -> num_rows !== 0) {
                                                //redeem credit amount
                                                $getamt = "Select * from settings where type='storecredit';";
                                                $sres = mysqli_query($link, $getamt);
                                                
                                                if (!mysqli_query($link, $getamt)) {
                                                    die(mysqli_error($link));
                                                } else {
                                                    $srow = mysqli_fetch_assoc($sres);
                                                    $valArr = explode("&", $srow['value']);
                                                    if(!empty($valArr[0])){
                                                        $amount = explode("redeemamount=", $valArr[0]);
                                                        echo "<input type='hidden' id='referralAmount' name='referralAmount' value='".$amount[1]."'>";
                                                    }
                                                }
                                                
                                                while ($grow = mysqli_fetch_assoc($gres)) {
                                                    $code = $grow['code'];
                                                    if (!empty($code)) {
                                                        echo "<input type='hidden' class='allrefers' value='".$code."'>";
                                                    }
                                                }
                                            }
                                        }
                                    } else {
                                        unset($_SESSION['canRefer']);
                                    }
                                }
                            
                                //get all discount codes
                                $disc = "Select * from discounts;";
                                $dres = mysqli_query($link, $disc);

                                if (!mysqli_query($link, $disc)) {
                                    die(mysqli_error($link));
                                } else {
                                    while ($row = mysqli_fetch_assoc($dres)) {
                                        $date = date('Y-m-d');
                                        $today = date('Y-m-d', strtotime($date));
                                        //echo $paymentDate; // echos today! 
                                        $start = date('Y-m-d', strtotime($row['start']));
                                        $end = date('Y-m-d', strtotime($row['end']));

                                        if ($today >= $start && $today <= $end) {
                                            echo "<input type='hidden' id='".$row['code']."Amount' value='".$row['amount']."'>";
                                            echo "<input type='hidden' class='allcodes' value='".$row['code']."'>";
                                        } 
                                    }
                                }
                            ?>
                            <?php if (isset($_SESSION['canRefer'])) { ?>
                            <a id='useRefer' class='addMore'> Use Referral Code</a><br>
                            <input type="hidden" name="redeem" id='redeem'>
                            <input type="hidden" name="redeemAmount" id='redeemAmount'>
                            <?php } ?>
                            <a id='addCode' class='addMore'> + Add Discount Code</a>
                            <input type="hidden" name="discount" id='discount'>
                            <input type="hidden" name="discountAmount" id='discountAmount'>
                        </div>
                            <?php if (isset($_SESSION['canRefer'])) { ?>
                        <div id='refer' class='col-md-6' style='display:none;'>
                            <span class='col-md-8'><input type='text' name='refercode' id='refercode' placeholder="Referral Code"></span>
                            <span class='col-md-3'><button class='btn' id='applyRefer'>Redeem Credit</button></span>
                        <div id='validRefer' class='col-md-8 success' style='display:none;'>
                            Valid Referral Code
                        </div>
                        <div id='invalidRefer' class='col-md-8 error' style='display:none;'>
                            Invalid Referral Code
                        </div>
                        </div>
                        
                        <div id='emptyCode' class='col-md-8 error' style='display:none;'>
                            Empty Field
                        </div>
                            <?php } ?>
                        <div id='discounts' class='col-md-6' style='display:none;'>
                            <span class='col-md-8'><input type='text' name='discountCode' id='discountCode' placeholder="Discount Code"></span>
                            <span class='col-md-3'><button class='btn' id='apply'>Apply Code</button></span>
                        <div id='validCode' class='col-md-8 success' style='display:none;'>
                            Valid Discount Code
                        </div>
                        <div id='invalidCode' class='col-md-8 error' style='display:none;'>
                            Invalid Discount Code
                        </div>
                        </div>
                    </div>
                    <div id='summary' class='col-md-3 col-md-offset-6'>
                        <button class='button' onclick='makePayment()'>PLACE ORDER</button>
                    </div>
                    <?php 
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
            var firstname = <?php echo '"'.$firstname.'"'; ?>;
            var lastname = <?php echo '"'.$lastname.'"'; ?>;
            var address = <?php echo '"'.$address.'"'; ?>;
            var email = <?php echo '"'.$email.'"'; ?>;
            var phone = <?php echo '"'.$phone.'"'; ?>;
            var code = document.getElementById('discount').value;
            var amount = document.getElementById('discountAmount').value;
            
            if (code === "" || amount === "") {
                code = document.getElementById('redeem').value;
                amount = document.getElementById('redeemAmount').value;
            }
            
            window.location = "processPayment.php?id=" + str + "&cost=" + cost + "&payment=" + payment + 
                    "&firstname=" + firstname + "&lastname="+lastname + 
                    "&email="+email+"&phone="+phone+"&address=" + address + 
                    "&code=" + code + "&amount=" + amount;
        }
        
        document.getElementById('addCode').onclick = function() {
            var obj = document.getElementById('discounts');
            
            if (obj.style.display === "none") {
                obj.style.display = "block";
            } else {
                obj.style.display = "none";
            }
            
            var obj = document.getElementById('refer');

            if (obj.style.display === "block") {
                obj.style.display = "none";
            }
        };
        
        function checkReferrals(code) {
            var classes = document.getElementsByClassName('allrefers');
            for (var i = 0; i < classes.length; i++) {
                var val = classes[i].value;
                
                if (val === code) {
                    return true;
                }
            }
            
            return false;
        }
        
        function checkDiscountCode(code) {
            var classes = document.getElementsByClassName('allcodes');
            for (var i = 0; i < classes.length; i++) {
                var val = classes[i].value;
                
                if (val === code) {
                    return true;
                }
            }
            
            return false;
        }
        
        var code = document.getElementById('apply');
        
        if (code !== null) {
            code.onclick = function() {
                var val = document.getElementById('discountCode').value;
                if (val === "") {
                    document.getElementById('emptyCode').style.display = "block";
                } else {
                    document.getElementById('emptyCode').style.display = "none";
                    var isFound = checkDiscountCode(val);

                    if (isFound) {
                        document.getElementById('validCode').style.display = "block";
                        document.getElementById('discount').value = val;
                        //get amount
                        var str = val + "Amount";
                        var amt = document.getElementById(str).value;
                        if (amt !== "") {
                            document.getElementById('discountAmount').value = amt;
                            document.getElementById('showDiscount').innerHTML = "<strong> -$"+amt+"</strong>";
                        }
    //                    document.getElementById('addCode').style.display = "none";
    //                    document.getElementById('discounts').style.display = "none";
                        document.getElementById('invalidCode').style.display = "none";
                    } else {
                        document.getElementById('validCode').style.display = "none";
                        document.getElementById('discount').value = "";
                        document.getElementById('discountCode').value = "";
                        document.getElementById('invalidCode').style.display = "block";
                        document.getElementById('showDiscount').innerHTML = "-";
                    }
                }
            };
        }
        
        var refercode = document.getElementById('applyRefer');
        
        if (refercode !== null) {
            refercode.onclick = function() {
                var val = document.getElementById('refercode').value;
                if (val === "") {
                    document.getElementById('emptyCode').style.display = "block";
                } else {
                    document.getElementById('emptyCode').style.display = "none";
                    var isFound = checkReferrals(val);

                    if (isFound) {
                        document.getElementById('redeem').value = val;
                        //get redeem amount
                        var amt = document.getElementById('referralAmount').value;
                        if (amt !== "") {
                            document.getElementById('redeemAmount').value = amt;
    //                        document.getElementById('showDiscount').innerHTML = "<strong> -$"+amt+"</strong>";
                        }
    //                    document.getElementById('addCode').style.display = "none";
    //                    document.getElementById('discounts').style.display = "none";
                        document.getElementById('validRefer').style.display = "block";
                        document.getElementById('invalidRefer').style.display = "none";
                    } else {
                        document.getElementById('validRefer').style.display = "none";
                        document.getElementById('redeem').value = "";
                        document.getElementById('refercode').value = "";
                        document.getElementById('invalidRefer').style.display = "block";
    //                    document.getElementById('showDiscount').innerHTML = "-";
                    }
                }
            };
        }
        
        var refer = document.getElementById('useRefer');
        
        if (refer !== null) {
            refer.onclick = function() {
                var show = document.getElementById('refer');
                
                if (show !== null) {
                    if (show.style.display === "block") {
                        show.style.display = "none";
                    } else {
                        show.style.display = "block";
                    }
                }
                
                var obj = document.getElementById('discounts');
            
                if (obj.style.display === "block") {
                    obj.style.display = "none";
                }
            };
        }
    </script>
</html>