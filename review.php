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
                            $qty = "Select * from cart where cartid ='". GetCartId()."' order by price desc;";
                            $qres = mysqli_query($link, $qty);

                            $cost = 0;
                            if (!mysqli_query($link, $qty)) {
                                die(mysqli_error($link));
                            } else {
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
                            <span>Discount:</span> <br>
                            <?php if (!empty($urow['credit'])) { ?>
                            <span>Existing Credit:</span>
                            <?php } ?>
                        </div>
                        <div class='col-md-3' id='reviewCosts'>
                            $<?php echo $cost; ?><br>
                            $0.00<br>
                            <strong>Free</strong><br>
                            <span>$<?php echo $cost; ?></span><br>
                            <span id='showDiscount'> - </span><br>
                            
                            <?php if (!empty($urow['credit'])) { ?>
                            <span id='existingCredit'>$<?php echo $urow['credit']; ?></span><br>
                            <?php } ?>
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
                                            $track = $row['serial'];
                                            $limit = $row['disclimit'];
                                            $userlimit = $row['userlimit'];
                                            if (empty($userlimit)) {
                                                $userlimit = 0;
                                            }
                                            
                                            if (strcmp($track, "yes") === 0) {
                                                //loop all possible codes
                                                for ($i = 1; $i <= $limit; $i++) {
                                                    $code = $row['code'].$i;
                                                    
                                                    $check = "Select * from orders where discountcode = '$code' order by orderid;";
                                                    $checkres = mysqli_query($link, $check);
                                                    
                                                    if (!mysqli_query($link, $check)) {
                                                        die(mysqli_error($link));
                                                    } else {
                                                        if ($checkres -> num_rows === 0) {
                                                            echo "<input type='hidden' id='".$code."Limit' value='".$userlimit."'>";
                                                            echo "<input type='hidden' id='".$code."Condition' value='".$row['disctype']."'>";
                                                            echo "<input type='hidden' id='".$code."Terms' value='".$row['disccondition']."'>";
                                                            echo "<input type='hidden' class='allcodes' value='".$code."'>";
                                                        }
                                                    }
                                                }
                                            } else {
                                                echo "<input type='hidden' id='".$row['code']."Limit' value='".$limit."'>";
                                                echo "<input type='hidden' id='".$row['code']."Condition' value='".$row['disctype']."'>";
                                                echo "<input type='hidden' id='".$row['code']."Terms' value='".$row['disccondition']."'>";
                                                echo "<input type='hidden' class='allcodes' value='".$row['code']."'>";
                                            }
                                        } 
                                    }
                                }
                            ?>
                            <?php if (!empty($urow['credit'])) { ?>
                            <a id='useCredit' class='addMore'> Use Existing Credit</a><br>
                            <?php } ?>
                            <input type="hidden" name="redeem" id='redeem'>
                            <input type="hidden" name="redeemAmount" id='redeemAmount'>
                            <a id='useCode' class='addMore'> Use Code</a><br>
                            <?php if (isset($_SESSION['canRefer'])) { ?>
                            <!--<a id='useRefer' class='addMore'> Use Referral Code</a><br>-->
                            <?php } ?>
                            <!--<a id='addCode' class='addMore'> + Add Discount Code</a>-->
                            
                            <input type="hidden" name="discount" id='discount'>
                            <input type="hidden" name="discountAmount" id='discountAmount'>
                        </div>
                        <div id='emptyCode' class='col-md-8 error' style='display:none;'>
                            Empty Field
                        </div>
                        <div id='invalidTerms' class='col-md-8 error'>
                        </div>
                        <div id='tabs' class='col-md-6' style='display:none;'>
                            <ul class="nav nav-tabs" id="myTabs">
                                <li id='addDisc' class="active"><a data-toggle="tab" href="#discounts">Discount Code</a></li>
                                <?php if (isset($_SESSION['canRefer'])) { ?><li id="addRefer"><a data-toggle="tab" href="#refer">Referral Code</a></li><?php } ?>
                            </ul>
                            <div class="tab-content">
                                <div id="discounts" class="col-md-12 tab-pane fade in active">
                                    <div class='error'>
                                    <?php
                                        if (isset($_SESSION['discount'])) {
                                            echo $_SESSION['discount'];
                                        }
                                    ?>
                                    </div>
                                    <span class='col-md-8'><input type='text' name='discountCode' id='discountCode' placeholder="Discount Code"></span>
                                    <span class='col-md-3'><button class='btn' id='apply'>Apply</button></span>
                                    <div id='validCode' class='col-md-8 success' style='display:none;'>
                                        Valid Discount Code
                                    </div>
                                    <div id='invalidCode' class='col-md-8 error' style='display:none;'>
                                        Invalid Discount Code
                                    </div>
                                </div>
                                <div id="refer" class="col-md-12 tab-pane fade">
                                    <span class='col-md-8'><input type='text' name='refercode' id='refercode' placeholder="Referral Code"></span>
                                    <span class='col-md-3'><button class='btn' id='applyRefer'>Redeem</button></span>
                                    <div id='validRefer' class='col-md-8 success' style='display:none;'>
                                        Valid Referral Code
                                    </div>
                                    <div id='invalidRefer' class='col-md-8 error' style='display:none;'>
                                        Invalid Referral Code
                                    </div>
                                </div>
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
                        $prodCount = 0;
                        
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

                                echo "<input type='hidden' id='pid".$prodCount."' value='".$prow['name']."'>";
                                echo "<input type='hidden' id='prodTags" . $prodCount."' value='".$prow['tags']."'>";
                                echo "<input type='hidden' id='prodqty".$prodCount."' value='".$row['quantity']."'>";
                                echo "<input type='hidden' id='prodprice".$prodCount."' value='".$row['price']."'>";
                                echo "</div>";
                                echo "<div class='col-md-4'>";
                                echo "<p class='totalprice caps'>".$type."</p>";
                                echo "</div>";
                                echo "<input type='hidden' id='totalcost".$prodCount."' value='".$total."'>";
                                echo "<div class='col-md-4'><p class='totalprice'>$".$total."</p></div>";  
//                                    echo "</div>";
                                echo "</div>";
                                $prodCount++;
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
        
//        document.getElementById('addCode').onclick = function() {
//            var obj = document.getElementById('discounts');
//            
//            if (obj.style.display === "none") {
//                obj.style.display = "block";
//            } else {
//                obj.style.display = "none";
//            }
//            
//            var obj = document.getElementById('refer');
//
//            if (obj.style.display === "block") {
//                obj.style.display = "none";
//            }
//        };
        
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
        
        function getConditions(result, type, code, limit) {
            //get condition of that code
            var condition = code + "Condition";
            var val = document.getElementById(condition).value;
            var valArr = val.split(" ");
            
            //get total amount discounted 
            var totaldisc = 0;
            
            var pcount = <?php echo $prodCount; ?>; 
            
            //processing for bundle amount offer (Buy A For $B)
            if (valArr.indexOf("For") > -1) {
                var qty = parseFloat(valArr[1]);
                //drop the $ sign
                var price = parseFloat(valArr[3].substring(1));
                
                //product cats
                if (type === "category") {
                    var totalqty = 0;
                    //get total qty of products purchased from this arr
                    for (var j = 0; j < result.length; j++) {
                        var s = "prodqty" + result[j];
                        totalqty += parseFloat(document.getElementById(s).value);
                    }
                    
                    if (limit === "0") {
                        limit = Math.ceil(totalqty/qty);
                    } 
                    
                    //get # of times can run
                    var times = Math.floor(totalqty/qty);
                    
                    //check if there are enough sets
                    if (totalqty > 0 && (totalqty % qty === 0 || times > 0)) {
                        //keep track of how many items have been considered
                        var qtychanged = qty;
                        
                        //loop through all elements in the array unless otherwise stated
                        var i = 0;
                        do {
                            //price of one pid
                            var pricestr = "prodprice" + result[i];
                            var priceval = parseFloat(document.getElementById(pricestr).value);
                            
                            //quantity of one pid
                            var qtystr = "prodqty" + result[i];
                            var qtyval = parseFloat(document.getElementById(qtystr).value);
                            
                            if (qtyval === qtychanged) {
                                var disc = priceval * qtyval;
                                totaldisc += disc - price;
                                qtychanged -= qtyval;
                                totalqty -= qtyval;
                            } else if (qtyval > qtychanged) {
                                disc = qtychanged * priceval;
                                totaldisc += disc - price;
                                qtychanged -= qtychanged;
                                totalqty -= qtychanged;
                            } else if (qtyval < qtychanged) {
                                disc = qtyval * priceval;
                                totaldisc += disc - price;
                                qtychanged -= qtyval;
                                totalqty -= qtyval;
                            }
                            
                            //increment i
                            i++;
                            
                            //finished one full set but still can continue
                            if (qtychanged === 0 && times > 0 && totalqty > 0) {
                                //reset counter
                                qtychanged = qty;
                                limit--;
                                times--;
                            } 
                        } while (i < pcount && times > 0 && times < limit)
                            
                        document.getElementById('discountAmount').value = totaldisc;
                        document.getElementById('showDiscount').innerHTML = "<strong> -$"+totaldisc+"</strong>";
                        return true;
                    } else {
                        return false;
                    }
                } else if (type === "product") {
                    //reset total discount
                    totaldisc = 0;
                    
                    //get total quantity of specified product
                    var pqty = "prodqty" + result;
                    var qtyval = parseFloat(document.getElementById(pqty).value);
                    
                    var prodp = "prodprice" + result;
                    var orip = parseFloat(document.getElementById(prodp).value);
                    totalqty = qtyval;
                    
                    if (limit === "0") {
                        limit = Math.ceil(qtyval/qty);
                    } 
                    
                    //get # times to run
                    times = Math.floor(qtyval/qty);
                    
                    if (qtyval > 0 && (times > 0 || qtyval % qty === 0)) {
                        
                        //set counter
                        qtyused = qty;
                        
                        do {
                            if (totalqty > qtyused) {
                                disc = qtyused * orip;
                                totaldisc += disc - price; 
                                qtyused -= qtyused;
                                totalqty -= qtyused;
                            } else if (totalqty === qtyused) {
                                disc = qty * orip;
                                totaldisc += disc - price; 
                                qtyused -= qtyused;
                                totalqty -= qtyused;
                            } 
                            if (qtyused === 0 && times > 0 && totalqty > 0) { 
                                //reset counter
                                qtyused = qty;
                                times--;
                                limit--;
                            } 
                        } while (times > 0 && times < limit);
                        document.getElementById('discountAmount').value = totaldisc;
                        document.getElementById('showDiscount').innerHTML = "<strong> -$"+totaldisc+"</strong>";
                        return true;
                    } else {
                        return false;
                    }
                } else {
                    //reset total discount
                    totaldisc = 0;
                    
                    //for both all orders and orders above a certain amt            
                    var totalqty = 0;
                    //get total qty of each pid in cart
                    for (var q = 0; q < pcount; q++) {
                        var pqty = "prodqty" + q;
                        totalqty += parseFloat(document.getElementById(pqty).value);
                    }
                    
                    if (limit === "0") {
                        limit = Math.ceil(totalqty/qty);
                    } 
                    
                    //get # times to run
                    times = Math.floor(totalqty / qty);  
                    
                    if (totalqty > 0 && (times > 0 || totalqty % qty === 0)) {  
                        //set counter
                        var qtyused = qty;
                        var i = 0;
                        
                        //loop that runs the same num of times as the qty specified
                        do {
                            //qty and price of this object
                            var objqty = "prodqty"+ i;
                            var obj = document.getElementById(objqty);
                            if (obj !== null) {
                                var qtyobj = parseFloat(document.getElementById(objqty).value);
                            }
                            var obj = "prodprice"+ i;
                            var priceobj = parseFloat(document.getElementById(obj).value);
                            
                            if (qtyobj > qtyused) {
                                disc = qtyused * priceobj;
                                totaldisc += disc - price; 
                                totalqty -= qtyused;
                                qtyused -= qtyused;
                            } else if (qtyobj === qtyused) {
                                disc = qtyused * priceobj;
                                totaldisc += disc - price; 
                                qtyused -= qtyobj;
                                totalqty -= qtyused;
                            } else if (qtyobj < qtyused) {
                                disc = qtyobj * priceobj;
                                totaldisc += disc - price; 
                                qtyused -= qtyobj;
                                totalqty -= qtyobj;
                            }
                            
                            //increment counter
                            i++;
                            
                            if (qtyused === 0 && times > 0 && totalqty > 0) {
                                qtyused = qty;
                                times--;
                                limit--;
                            }
                        } while (i < pcount && times > 0 && times < limit);
                        document.getElementById('discountAmount').value = totaldisc;
                        document.getElementById('showDiscount').innerHTML = "<strong> -$"+totaldisc+"</strong>";
                        return true;
                    } else {
                        return false;
                    }
                } 
            } else if (valArr.indexOf("Free") > -1) {
                //next free
                var qtybuy = parseFloat(valArr[1]);
                var qtyfree = parseFloat(valArr[3]);
                qty = qtybuy + qtyfree;
                
                qtyused = qtyfree;
                
                //get total quantity of cart
                totalqty = 0;
                for (var j = 0; j < pcount; j++) {
                    var s = "prodqty" + j;
                    totalqty += parseFloat(document.getElementById(s).value);
                }
                
                //get number of times maximum to run
                if (limit === "0") {
                    limit = Math.ceil(totalqty/qty);
                } 
                
                //product categories
                if (type === "category") {
                    //reset total discount
                    totaldisc = 0;       

                    var totprod = 0;

                    //check if qty of prods in array is a multiple
                    for (var m = 0; m < result.length; m++) {
                        pstr = "prodqty" + result[m];
                        totprod += parseFloat(document.getElementById(pstr).value);
                    }

                    //get number of times to iterate
                    times = Math.floor(totprod / qty);
                    
                    //check if total qty of products in array are enough for the set
                    if (totprod > 0 && (times > 0 || totprod % qty === 0)) {
                        qtyused = qtyfree;
                        var i = 0;
                        
                        do {
                            //get price & quantity of products in the array
                            var pstr = "prodprice" + result[i];
                            var pval = parseFloat(document.getElementById(pstr).value);
                            var qstr = "prodqty" + result[i];
                            var qval = parseFloat(document.getElementById(qstr).value);

                            if (qval > qtyused) {
                                disc = pval * qtyused;
                                totaldisc += disc;
                                qtyused -= qtyused;
                                totprod -= qtyused;
                            } else if (qval === qtyused) {
                                disc = pval * qtyused;
                                totaldisc += disc;
                                qtyused -= qtyused; 
                                totprod -= qtyused;                                
                            } else if (qval < qtyused) {
                                disc = pval * qval;
                                totaldisc += disc;
                                qtyused -= qval;
                                totprod -= qval;
                            }

                            //increment i 
                            i++;
                            
                            if (qtyused === 0 && times > 0 && totprod > 0) { 
                                qtyused = qtyfree;
                                times--;
                                limit--;
                            } 
                        } while (i < pcount && times > 0 && times < limit);
                        document.getElementById('discountAmount').value = totaldisc;
                        document.getElementById('showDiscount').innerHTML = "<strong> -$"+totaldisc+"</strong>";
                        return true;
                    } else {
                        return false;
                    }
                } else if (type === "product") {
                    //reset total discount 
                    totaldisc = 0;
                    
                    //get qty of this prod
                    qtystr = "prodqty" + result;
                    qtyval = parseFloat(document.getElementById(qtystr).value);
                    prodp = "prodprice" + result;
                    priceval = parseFloat(document.getElementById(prodp).value);
                    
                    totalqty = qtyval;
                    //get number of times maximum to run
                    if (limit === "0") {
                        limit = Math.ceil(totalqty/qty);
                    } 
                    
                    //get # times to run
                    times = Math.floor(totalqty/qty);
                    
                    if (totalqty > 0 && (times > 0 || qtyval % qty === 0)) {
                        qtyused = qtyfree;
                        do {
                            if (qtyval > qtyused) {
                                disc = qtyused * priceval;
                                totaldisc += disc;
                                qtyused -= qtyused;
                                qtyval -= qtyused;
                            } else if (qtyval === qtyused) {
                                disc = qtyused * priceval;
                                totaldisc += disc;
                                qtyfree -= qtyval;
                                qtyval -= qtyval;
                            } else if (qtyval < qtyused) {
                                disc = qtyval * priceval;
                                totaldisc += disc;
                                qtyused -= qtyval;
                                qtyval -= qtyval;
                            }
                            
                            if (qtyused === 0 && times > 0 && qtyval > 0) {
                                //reset counter
                                qtyused = qtyfree;
                                
                                //decrease # times 
                                times--;  
                                limit--;
                            } 
                        } while(times > 0 && times < limit);
                        document.getElementById('discountAmount').value = totaldisc;
                        document.getElementById('showDiscount').innerHTML = "<strong> -$"+totaldisc+"</strong>";
                        return true;
                    } else {
                        return false;
                    }
                } else {
                    //reset total discount
                    totaldisc = 0;
                    
                    //get number of times maximum to run
                    if (limit === "0") {
                        limit = Math.ceil(totalqty/qty);
                    } 

                    //get number of 'sets'
                    times = Math.floor(totalqty / qty);
                    
                    //check totalqty of cart if enough for sets
                    if (totalqty > 0 && (times > 0 || totalqty % qty === 0)) {
                        //get last instance of id in cart
                        var count = <?php echo $prodCount-1; ?>;
                        qtyused = qtyfree;
                        
                        
                        do {
                            qtystr = "prodqty" + count;
                            qtyval = parseFloat(document.getElementById(qtystr).value);
                            pstr = "prodprice" + count;
                            pval = parseFloat(document.getElementById(pstr).value);
                            
                            if (qtyval > qtyused) {
                                disc = pval * qtyfree;
                                totaldisc += disc;
                                qtyused -= qtyused;
                                totalqty -= qtyused;
                            } else if (qtyval === qtyused) {
                                disc = pval * qtyval;
                                totaldisc += disc;
                                qtyused -= qtyval;
                                totalqty -= qtyval;
                            } else if (qtyval < qtyfree) {
                                disc = pval * qtyval;
                                totaldisc += disc;
                                qtyused -= qtyval;
                                totalqty -= qtyval;
                            }
                            
                            //decrement count
                            count--;
                            if (times > 0 && qtyused === 0 && totalqty > 0) { 
                                qtyused = qtyfree;
                                times--;
                                limit--;
                            } 
                        } while (count >= 0 || (times > 0 && times < limit));
                        document.getElementById('discountAmount').value = totaldisc;
                        document.getElementById('showDiscount').innerHTML = "<strong> -$"+totaldisc+"</strong>";
                        return true;
                    } else {
                        return false;
                    }
                }
            } else if (valArr.indexOf('Discount') > -1 && valArr.indexOf('Next') === -1) {
                //bundle discount
                qty = parseFloat(valArr[1]);
                
                //get length to find position of % & drop it
                var length = valArr[3].length;
                price = parseFloat(valArr[3].substring(0, length));
                
                if (type === "category") {
                    //get total quantity of products in array
                    
                    totalqty = 0;
                    for (var i = 0; i < result.length; i++) {
                        var s = "prodqty" + result[i];
                        totalqty += parseFloat(document.getElementById(s).value);
                    }
                    
                    //get number of times maximum to run
                    if (limit === "0") {
                        limit = Math.ceil(totalqty/qty);
                    } 

                    //get number of 'sets'
                    times = Math.floor(totalqty / qty);
                    
                    if (totalqty > 0 && (times > 0 || totalqty % qty === 0)) {
                        //set counter 
                        qtyused = qty;
                        
                        var i = 0;
                        var beforeDisc = 0;
                        totaldisc = 0;
                        qtystr = "prodqty" + result[i];
                        qtyval = parseFloat(document.getElementById(qtystr).value);
                        pstr = "prodprice" + result[i];
                        pval = parseFloat(document.getElementById(pstr).value);

                        do { 
                            if (qtyval === qtyused) {
                                disc = qtyval * pval;
                                beforeDisc += disc;
                                qtyused -= qtyval;
                                totalqty -= qtyval;
                            } else if (qtyval > qtyused) {
                                disc = qtyused * pval;
                                beforeDisc += disc;
                                qtyused -= qtyused;
                                totalqty -= qtyused;
                            } else if (qtyval < qtyused) {
                                disc = qtyval * pval;
                                beforeDisc += disc;
                                qtyused -= qtyval;
                                totalqty -= qtyval;
                            }
                            
                            //increment i 
                            if (qtyval === 0) {
                                i++;
                                qtystr = "prodqty" + result[i];
                                qtyval = parseFloat(document.getElementById(qtystr).value);
                                pstr = "prodprice" + result[i];
                                pval = parseFloat(document.getElementById(pstr).value);
                            }
                            
                            if (qtyused === 0) {
                                totaldisc += beforeDisc * (price/100);
                            }
                            
                            if (qtyused === 0 && times > 0 && totalqty > 0) {
                                beforeDisc = 0;
                                qtyused = qty;
                                times--;
                                limit--;
                            }                
                        } while (i < pcount && times > 0 && times < limit)   
                        document.getElementById('discountAmount').value = totaldisc;
                        document.getElementById('showDiscount').innerHTML = "<strong> -$"+totaldisc+"</strong>";
                        return true; 
                    } else {
                        return false;
                    }
                } else if (type === "product") {
                    //get total qty of this product
                    qtystr = "prodqty" + result;
                    qtyval = parseFloat(document.getElementById(qtystr).value);
                    pstr = "prodprice" + result;
                    pval = parseFloat(document.getElementById(pstr).value);
                    
                    totalqty = qtyval;
                    
                    //get number of times maximum to run
                    if (limit === "0") {
                        limit = Math.ceil(totalqty/qty);
                    } 
                    times = Math.floor(totalqty/qty);
                    
                    if (totalqty > 0 && (times > 0 || totalqty % qty === 0)) {
                        
                        qtyused = qty;
                        beforeDisc = 0;
                        totaldisc = 0;
                        
                        do {
                            if (totalqty < qtyused) {
                                disc = totalqty * pval;
                                beforeDisc += disc;
                                totalqty -= totalqty;
                                qtyused -= totalqty;
                            } else if (totalqty > qtyused) {
                                disc = qtyused * pval;
                                beforeDisc += disc;
                                totalqty -= qtyused;
                                qtyused -= qtyused;
                            } else if (totalqty === qtyused) {
                                disc = qtyused * pval;
                                beforeDisc += disc;
                                totalqty -= qtyused;
                                qtyused -= qtyused;
                            }
                            
                            if (qtyused === 0) {
                                totaldisc += beforeDisc * (price/100);
                            }
                            
                            if (qtyused === 0 && times > 0 && totalqty > 0) {
                                beforeDisc = 0;
                                qtyused = qty;
                                times--;
                                limit--;
                            }
                        } while (times > 0 && times < limit)
                        document.getElementById('discountAmount').value = totaldisc;
                        document.getElementById('showDiscount').innerHTML = "<strong> -$"+totaldisc+"</strong>";
                        return true;
                    } else {
                        return false;
                    }
                } else {
                    //for all orders and orders above a specified amount
                    
                    //get total quantity of cart
                    totalqty = 0;
                    for (var i = 0; i < pcount; i++) {
                        var s = "prodqty" + i;
                        totalqty += parseFloat(document.getElementById(s).value);
                    }
                    
                    //get number of times maximum to run
                    if (limit === "0") {
                        limit = Math.ceil(totalqty/qty);
                    } 
                    times = Math.floor(totalqty / qty);
                    
                    if (totalqty > 0 && (times > 0 || totalqty % qty === 0)) {
                        qtyused = qty;
                        beforeDisc = 0;
                        totaldisc = 0;
                        
                        var e = pcount-1;
                        qtystr = "prodqty" + e;
                        qtyval = parseFloat(document.getElementById(qtystr).value);
                        pstr = "prodprice" + e;
                        pval = parseFloat(document.getElementById(pstr).value);
                        var curqty = qtyval;
                        do {
                            if (curqty < qtyused) {
                                disc = curqty * pval;
                                beforeDisc += disc;
                                qtyused -= curqty;
                                curqty -= curqty;
                            } else if (curqty > qtyused) {
                                disc = qtyused * pval;
                                beforeDisc += disc;
                                qtyused -= qtyused;
                                curqty -= qtyused;
                            } else if (curqty === qtyused) {
                                disc = qtyused * pval;
                                beforeDisc += disc;
                                qtyused -= qtyused;
                                curqty -= qtyused;
                            }
                            
                            if (qtyval === 0) {
                                e--;
                                qtystr = "prodqty" + e;
                                qtyval = parseFloat(document.getElementById(qtystr).value);
                                pstr = "prodprice" + e;
                                pval = parseFloat(document.getElementById(pstr).value);
                                curqty = qtyval;
                            }
                            
                            if (qtyused === 0) {
                                totaldisc += beforeDisc * (price/100);
                            }
                            if (qtyused === 0 && times > 0 && curqty > 0) {
                                beforeDisc = 0;
                                qtyused = qty;
                                times--;
                                limit--;
                            } 
                            
                        } while (times > 0 && times < limit)
                        document.getElementById('discountAmount').value = totaldisc;
                        document.getElementById('showDiscount').innerHTML = "<strong> -$"+totaldisc+"</strong>";
                        return true;
                    } else {
                        return false;
                    }
                }
            } else if (valArr.indexOf("Next") > -1) {
                //next discount (Buy A Next Pair B% Discount)
                qty = parseFloat(valArr[1]);
                
                //get length to find position of % & drop it
                length = valArr[4].length;
                price = parseFloat(valArr[4].substring(0, length));
                
                if (type === "category") {
                
                    //get total qty in array
                    totalqty = 0;
                    for (var i = 0; i < result.length; i++) {
                        var s = "prodqty" + i;
                        totalqty += parseFloat(document.getElementById(s).value);
                    }
                    
                    qtyused = qty;
                    beforeDisc = 0;
                    totaldisc = 0;
                    
                    //get number of times maximum to run
                    if (limit === "0") {
                        limit = Math.ceil(totalqty/qty);
                    } 
                    
                    times = Math.floor(totalqty / (qty + 1));
                    if (totalqty > 0 && (times > 0 || totalqty % (qty +1) === 0)) {
                        
                        e = 0;
                        
                        do {
                            qtystr = "prodqty" + result[e];
                            qtyval = parseFloat(document.getElementById(qtystr).value);
                            pstr = "prodprice" + result[e];
                            pval = parseFloat(document.getElementById(pstr).value);
                            
                            if (qtyval > qtyused) {
                                disc = 1 * pval;
                                beforeDisc += disc;
                                qtyused -= qtyused;
                                totalqty -= qtyused;
                            } else if (qtyval === qtyused) {
                                disc = 1 * pval;
                                beforeDisc += disc;
                                qtyused -= qtyused;
                                totalqty -= qtyused;
                            }
                            e++;
                            
                            if (qtyused === 0) {
                                totaldisc += beforeDisc * (price/100);
                            }
                            
                            if (qtyused === 0 && times > 0 && totalqty > 0) {
                                beforeDisc = 0;
                                qtyused = qty;
                                times--;
                                limit--;
                            } 
                        } while (e < pcount && times > 0 && times < limit)
                        document.getElementById('discountAmount').value = totaldisc;
                        document.getElementById('showDiscount').innerHTML = "<strong> -$"+totaldisc+"</strong>";
                        return true;
                    } else {
                        return false;
                    }
                } else if (type === "product") {
                    qtystr = "prodqty" + result;
                    qtyval = parseFloat(document.getElementById(qtystr).value);
                    pstr = "prodprice" + result;
                    pval = parseFloat(document.getElementById(pstr).value);
                    
                    totalqty = qtyval;
                    qtyused = qty;
                    beforeDisc = 0;
                    totaldisc = 0;
                    
                    //get number of times maximum to run
                    if (limit === "0") {
                        limit = Math.ceil(totalqty/qty);
                    } 
                    
                    times = Math.floor(totalqty/qty);
                    
                    if (totalqty > 0 && (times > 0 || totalqty % qty === 0)) {
                    
                        do {
                            if (totalqty > qtyused) {
                                disc = 1 * pval;
                                beforeDisc += disc;
                                qtyused -= qtyused;
                                totalqty -= qtyused;
                            } else if (totalqty === qtyused) {
                                disc = 1 * pval;
                                beforeDisc += disc;
                                qtyused -= qtyused;
                                totalqty -= qtyused;
                            }

                            if (qtyused === 0) {
                                totaldisc += beforeDisc * (price/100);
                            }
                            
                            if (qtyused === 0 && times > 0 && totalqty > 0) {
                                beforeDisc = 0;
                                qtyused = qty;
                                times--;
                                limit--;
                            } 
                        } while (times > 0 && times < limit)
                        document.getElementById('discountAmount').value = totaldisc;
                        document.getElementById('showDiscount').innerHTML = "<strong> -$"+totaldisc+"</strong>";
                        return true;
                    } else {
                        return false;
                    }
                } else {
                    //get total quantity in cart
                    totalqty = 0;
                    for (var i = 0; i < pcount; i++) {
                        var s = "prodqty" + i;
                        totalqty += parseFloat(document.getElementById(s).value);
                    }
                    
                    qtyused = qty;
                    beforeDisc = 0;
                    totaldisc = 0;
                    
                    //get number of times maximum to run
                    if (limit === "0") {
                        limit = Math.ceil(totalqty/qty);
                    } 
                    
                    times = Math.floor(totalqty/qty);
                    e = pcount-1;
                    
                    if (totalqty > 0 && (times > 0 || totalqty % qty === 0)) {
                        do {
                            qtystr = "prodqty" + e;
                            qtyval = parseFloat(document.getElementById(qtystr).value);
                            pstr = "prodprice" + e;
                            pval = parseFloat(document.getElementById(pstr).value);
                            
                            if (qtyval > qtyused) {
                                disc = 1 * pval;
                                beforeDisc += disc;
                                qtyused -= qtyused;
                                totalqty -= qtyused;
                            } else if (qtyval === qtyused) {
                                disc = 1 * pval;
                                beforeDisc += disc;
                                qtyused -= qtyused;
                                totalqty -= qtyused;
                            }
                            
                            e--;
                            
                            if (qtyused === 0) {
                                totaldisc += beforeDisc * (price/100);
                            }
                            
                            if (qtyused === 0 && times > 0 && totalqty > 0) {
                                beforeDisc = 0; 
                                qtyused = qty;
                                times--;
                                limit--;
                            }
                        } while (e >= 0 && times > 0 && times < limit)
                        document.getElementById('discountAmount').value = totaldisc;
                        document.getElementById('showDiscount').innerHTML = "<strong> -$"+totaldisc+"</strong>";
                        return true;
                    } else {
                        return false;
                    }
                }
            } else if (valArr.indexOf("Get") > -1 && valArr[1].indexOf('%') > -1) {
                //fixed discount
                
                //drop the %
                var len = valArr[1].length;
                price = parseFloat(valArr[1].substring(0, len));
                
                if (type === "category") {
                    //reset values
                    beforeDisc = 0;
                    totaldisc = 0;
                    
                    //reset total quantity
                    totalqty = 0;
                    for (var e = 0; e < result.length; e++) {
                        var s = "prodqty" + result[e];
                        totalqty += parseFloat(document.getElementById(s).value);
                    }
                    
                    if (totalqty > 0) {
                        for (var i = 0; i < result.length; i++) {
                            qtystr = "prodqty" + result[i];
                            qtyval = parseFloat(document.getElementById(qtystr).value);
                            pstr = "prodprice" + result[i];
                            pval = parseFloat(document.getElementById(pstr).value);

                            beforeDisc += qtyval * pval;
                        }
                        
                        totaldisc += beforeDisc * (price/100);
                        document.getElementById('discountAmount').value = totaldisc;
                        document.getElementById('showDiscount').innerHTML = "<strong> -$"+totaldisc+"</strong>";
                        return true;
                    } else {
                        return false;
                    }
                } else if (type === "product") {
                    //reset values
                    totaldisc = 0;
                    beforeDisc = 0;
                    
                    //reset total quantity
                    totalqty = 0;
                    var s = "prodqty" + result;
                    totalqty += parseFloat(document.getElementBtId(s).value);
                    
                    if (totalqty > 0) {
                        qtystr = "prodqty" + result;
                        qtyval = document.getElementById(qtystr).value;
                        pstr = "prodprice" + result;
                        pval = document.getElementById(pstr).value;
                        
                        beforeDisc += qtyval * pval;
                        
                        totaldisc += beforeDisc * (price/100);
                        document.getElementById('discountAmount').value = totaldisc;
                        document.getElementById('showDiscount').innerHTML = "<strong> -$"+totaldisc+"</strong>";
                        return true;
                    } else {
                        return false;
                    }
                } else {
                    //reset values
                    totaldisc = 0;
                    beforeDisc = 0;
                    
                    //get qty of cart
                    if (pcount > 0) {
                        //get cost of all products
                        for (var i = 0; i < pcount; i++) {
                            qtystr = "prodqty" + i;
                            qtyval = parseFloat(document.getElementById(qtystr).value);
                            pstr = "prodprice" + i;
                            pval = parseFloat(document.getElementById(pstr).value);
                            
                            beforeDisc += qtyval * pval;
                        }   
                        totaldisc += beforeDisc * (price/100);
                        document.getElementById('discountAmount').value = totaldisc;
                        document.getElementById('showDiscount').innerHTML = "<strong> -$"+totaldisc+"</strong>";
                        return true;
                    } else {
                        return false;
                    }
                }
            } else if (valArr.indexOf("Get") > -1 && valArr[1].indexOf('$') > -1) {
                //fixed amount
                
                //drop the $
                price = parseFloat(valArr[1].substring(1));
                
                check = false;
                
                if (type === "category") {
                    //if quantity purchased from that array is more than 0, apply discount
                    if (result.length > 0) {
                        check = true;
                    }
                } else if (type === "product") {
                    qtystr = "prodqty" + result;
                    qtyval = parseFloat(document.getElementById(qtystr).value);
                    
                    //if quantity purchased from that product is more than 0, apply discount
                    if (qtyval > 0) {
                        check = true;
                    }
                } else {
                    //if quantity purchased is more than 0, apply discount
                    if (pcount > 0) {
                        check = true;
                    }
                }
                
                if (check) {
                    document.getElementById('discountAmount').value = price;
                    document.getElementById('showDiscount').innerHTML = "<strong> -$"+price+"</strong>";
                    return true;
                } else {
                    return false;
                }
            }
        }
        
        function checkPids(prod) {
            for (var i = 0; i < <?php echo $prodCount; ?>; i++) {
                var str = "pid" + i;
                var val = document.getElementById(str).value;
                
                if (val === prod) {
                    return i;
                }
            }
            return null;
        }
        
        function checkTags(tags) {
            var result = new Array();
            for (var i = 0; i < <?php echo $prodCount; ?>; i++) {
                var str = "prodTags" + i;
                var t = document.getElementById(str).value;
                var prodtags = t.split(",");
                for(var j = 0; j < prodtags.length; j++) {
                    var p = prodtags[j];
                    
                    if (tags.indexOf(p) > -1) {
//                        var id = "pid" + i;
//                        var pidVal = document.getElementById(id).value;
                        result.push(i);
                    }
                }
            }
            return result;
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
                        //get terms for this code
                        var terms = val + "Terms";
                        var termVal = document.getElementById(terms).value;
                        var cond = val + "Condition";
                        var conVal = document.getElementById(cond).value;
                        //get limit of that code
                        var lim = val + "Limit";
                        var limit = document.getElementById(lim).value;
                        
                        
                        if (conVal !== "" && termVal !== "") {
                            var termArr = termVal.split(" ");
                            termsMet = false;
                            conditionsMet = false;

                            if (termArr.indexOf("All") > -1) { 
                                termsMet = true;
                                conditionsMet = getConditions("all", "string", val, limit);
                            } else if (termArr.indexOf("above") > -1) {
                                var amt = termArr[termArr.length-1];
                                amt = amt.substring(1);
                                
                                if (<?php echo intval($cost); ?> > amt) {
                                    termsMet = true;
                                    //get conditions
                                    conditionsMet = getConditions("above", "string", val, limit);
                                }
                            } else if (termArr.indexOf("categories:") > -1) {
                                var tags = termArr[termArr.length-1];
                                //array of product positions that have the tag(s)
                                var arrResult = checkTags(tags);
                                if (arrResult[0] !== "") {
                                    termsMet = true;
                                    //get conditions
                                    conditionsMet = getConditions(arrResult, "category", val, limit);

                                } 
                            } else if (termArr.indexOf("For") > -1) {
                                var prod = termArr[termArr.length-1];
                                //get position of pid
                                var pidFound = checkPids(prod, val);

                                if (pidFound !== null) {
                                    termsMet = true;
                                    //get conditions
                                    conditionsMet = getConditions(pidFound, "product", val, limit);
                                }
                            }

                            if (termsMet === false || conditionsMet === false) {
                                if (conVal !== "" && termVal !== "") {
                                    document.getElementById('invalidTerms').innerHTML = conVal + ", For " + termVal;
                                    document.getElementById('validCode').style.display = "none";
                                    document.getElementById('discount').value = "";
                                }
                            } else {
                                document.getElementById('invalidTerms').innerHTML = "";
                                document.getElementById('validCode').style.display = "block";
                                document.getElementById('discount').value = val;
                                document.getElementById('invalidCode').style.display = "none";
                            }
//                        //get amount
//                        var str = val + "Amount";
//                        var amt = document.getElementById(str).value;
//                        if (amt !== "") {
//                            document.getElementById('discountAmount').value = amt;
//                            document.getElementById('showDiscount').innerHTML = "<strong> -$"+amt+"</strong>";
//                        }
    //                    document.getElementById('addCode').style.display = "none";
    //                    document.getElementById('discounts').style.display = "none";
                        } else {
                            document.getElementById('invalidCode').style.display = "block";
                            document.getElementById('showDiscount').innerHTML = "-";
                        }
                    } else {
                        document.getElementById('validCode').style.display = "none";
                        document.getElementById('discount').value = "";
//                        document.getElementById('discountCode').value = "";
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
                            document.getElementById('showDiscount').innerHTML = "<strong> -$"+amt+"</strong>";
                        }
    //                    document.getElementById('addCode').style.display = "none";
    //                    document.getElementById('discounts').style.display = "none";
                        document.getElementById('validRefer').style.display = "block";
                        document.getElementById('invalidRefer').style.display = "none";
                    } else {
                        document.getElementById('validRefer').style.display = "none";
                        document.getElementById('redeem').value = "";
//                        document.getElementById('refercode').value = "";
                        document.getElementById('invalidRefer').style.display = "block";
    //                    document.getElementById('showDiscount').innerHTML = "-";
                    }
                }
            };
        }
        
        var credit = document.getElementById('useCredit');
        
        if (credit !== null) {
            credit.onclick = function() {
                var val = <?php echo $urow['credit']; ?>;
                document.getElementById('redeem').value = "existing";
                //get redeem amount
                document.getElementById('redeemAmount').value = val;
                document.getElementById('existingCredit').style.fontWeight = "bold";
            };
        }
        
        var usecode = document.getElementById('useCode');
        
        if (usecode !== null) {
            usecode.onclick = function() {
                var show = document.getElementById('tabs');
                
                if (show !== null) {
                    if (show.style.display === "block") {
                        show.style.display = "none";
                    } else {
                        show.style.display = "block";
                    }
                }
            };
        }
    </script>
</html>