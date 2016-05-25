<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
require_once '../config/db.php';

if (isset($_POST['submit'])) {
    if (empty($_POST['promo']) || empty($_POST['gift']) || empty($_POST['guest'])) {
        unset($_SESSION['updateCheckSetSuccess']);
        $_SESSION['updateCheckSetError'] = "Empty field(s)";
    } else {
        $promotion = $_POST['promo'];
        $giftcard = $_POST['gift'];
        $guestcheck = $_POST['guest'];
        
        $val = "promo=".$promotion."&";
        $val .= "gift=".$giftcard."&";
        $val .= "guest=".$guestcheck;
        
        $checkSql = "Select * from settings where type='checkout'";
        $cresult = mysqli_query($link, $checkSql);
        if (!mysqli_query($link,$checkSql)) {
            echo("Error description: " . mysqli_error($link));
        } else {
            $checkoutSql;
            
            if ($cresult -> num_rows == 0) {
                $checkoutSql = "INSERT INTO settings (type, value) VALUES ('checkout',"
                        . "'$val');";
            } else {
                $checkoutSql = "UPDATE settings SET value ='$val' where type ='checkout'";
            }
            
            mysqli_query($link, $checkoutSql);
            unset($_SESSION['updateCheckSetError']);
            $_SESSION['updateCheckSetSuccess'] = "Changes saved successfully";
        }        
    }
}

$selectSql = "SELECT value from settings WHERE type='checkout'";
$savedresult = mysqli_query($link, $selectSql);

if (!mysqli_query($link,$selectSql)) {
    echo("Error description: " . mysqli_error($link));
} else {
    $savedrow = mysqli_fetch_assoc($savedresult);
    $valArr = explode("&", $savedrow['value']);
?>
<html>    
    <div id="frameheader">
        <?php
            require '../nav/adminHeader.php';
        ?>
    </div>
    <div id="framecontent">
        <?php
            require '../nav/adminSidebar.php';
        ?>
    </div>
    <div id="maincontent">
        <div class="innertube">
        <h2>Settings - Checkout</h2>
        
        <form id='checkoutSettings' action='checkoutSettings.php' method='post'>
        
        <div id="updateCheckSetError" style='color:red'>
            <?php
                if (isset($_SESSION['updateCheckSetError'])) {
                    echo $_SESSION['updateCheckSetError'];
                }
            ?>
        </div>
        <div id="updateCheckSetSuccess" style='color:green'>
            <?php
                if (isset($_SESSION['updateCheckSetSuccess'])) {
                    echo $_SESSION['updateCheckSetSuccess'];
                }
            ?>
        </div>
            Get customers' consent to receive promotional emails: 
                <?php 
                    $promo = explode("promo=", $valArr[0]);
                ?>
                <input name='promo' type='radio' value='yes' 
                        <?php 
                        if (!empty($promo[1])) {
                            if (strcmp($promo[1], "yes")===0) {
                                echo " checked";
                            }
                        }
                        ?>>Yes
                <input type='radio' name='promo' value='no' 
                        <?php 
                        if (!empty($promo[1])) {
                            if (strcmp($promo[1], "no")===0) {
                                echo " checked";
                            }
                        }
                        ?>>No
            <p class='setting-tooltips'>*If you select 'No', customers will not be asked for consent. They can unsubscribe from their accounts.</p><br>
            
            Automatically fulfill electronic gift cards: 
                <?php 
                    $gift = explode("gift=", $valArr[1]);
                ?>
                <input name='gift' type='radio' value='yes' 
                        <?php 
                        if (!empty($gift[1])) {
                            if (strcmp($gift[1], "yes")===0) {
                                echo " checked";
                            }
                        }
                        ?>>Yes
                <input type='radio' name='gift' value='no' 
                        <?php 
                        if (!empty($gift[1])) {
                            if (strcmp($gift[1], "no")===0) {
                                echo " checked";
                            }
                        }
                        ?>>No
            <br>
            <p class='setting-tooltips'>*If you select 'No', manual input is needed to fulfill electronic gift card purchases.</p><br>
            <?php 
                $guest = explode("guest=", $valArr[2]);
            ?>
            Enable Guest Checkout: 
            <input name='guest' type='radio' value='yes' 
                        <?php 
                        if (!empty($guest[1])) {
                            if (strcmp($guest[1], "yes")===0) {
                                echo " checked";
                            }
                        }
                        ?>>Yes
                <input type='radio' name='guest' value='no' 
                        <?php 
                        if (!empty($guest[1])) {
                            if (strcmp($guest[1], "no")===0) {
                                echo " checked";
                            }
                        }
                        ?>>No
            <br>
            <p class='setting-tooltips'>*If you select 'No', orders can be processed without logging in or signing up first.</p><br>
            <input type='submit' name='submit' value='Save Changes' />
        </form>
        </div>
    </div>
</html>
<?php } ?>