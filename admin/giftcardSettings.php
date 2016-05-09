<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
require_once '../config/db.php';

if (isset($_POST['submit'])) {
    if (empty($_POST['expiry']) || 
            ($_POST['expiry'] === "on" && empty($_POST['duration']) )) {
        unset($_SESSION['updateGcSetSuccess']);
        $_SESSION['updateGcSetError'] = "Empty field(s)";
    } else {
        unset($_SESSION['updateGcSetError']);
        $expiry = $_POST['expiry'];
        $duration = $_POST['duration'];
        
        $checkSql = "SELECT * from settings WHERE type='giftcard'";
        $checkresult = mysqli_query($link, $checkSql);
        
        $val = "expiry=".$expiry."&duration=".$duration;
        
        if ($checkresult -> num_rows === 0) {
            $giftcardSql = "INSERT INTO settings (type, value) VALUES ('giftcard',"
                    . "'$val');";
        } else {
            $giftcardSql = "UPDATE settings SET value='$val' where type='giftcard';";
        }
        
        mysqli_query($link, $giftcardSql);
        $_SESSION['updateGcSetSuccess'] = "Changes saved successfully"; 
    }
}

$selectSql = "SELECT value from settings WHERE type='giftcard'";
$savedresult = mysqli_query($link, $selectSql);

if (!mysqli_query($link,$selectSql)) {
    echo("Error description: " . mysqli_error($link));
} else {
    $savedrow = mysqli_fetch_assoc($savedresult);
    $valArr = explode("&", $savedrow['value']);
?>
<html>    
    <div id="framecontent">
        <div class='innertube'>
        <?php
            require '../nav/adminSidebar.php';
        ?>
        </div>
    </div>
    <div id="maincontent">
        <div class="innertube">
        <h2>Settings - Gift cards</h2>
        
        <form id='giftcardSettings' action='giftcardSettings.php' method='post'>
            <?php
                $expiry = explode("expiry=", $valArr[0]);
            ?>
            Enable Expiry:
            <input type="radio" name='expiry' id="expiry" value='on' 
                   <?php 
                    if (strcmp("on", $expiry[1])===0) {
                        echo " checked";
                        $_SESSION['expiryOff'] = "on";
                    }
                   ?>
                   onclick="toggleTextbox(true);">On
            <input type="radio" name='expiry' value='off' 
                   <?php 
                    if (strcmp("off", $expiry[1])===0) {
                        echo " checked";
                        $_SESSION['expiryOff'] = "off";
                    }
                   ?>
                   onclick="toggleTextbox(false);">Off
            <br><br>
            <?php 
            $duration = explode("duration=", $valArr[1]);
            ?>
            Duration: <input type='text' name='duration' id='duration' 
                             value='<?php 
                                if (!empty($duration[1])) {
                                    echo $duration[1];
                                }
                             ?>' onkeypress="return isNumber(event)"> <span id='days'>days</span><br>
            <p class='setting-tooltips'>*Set the default validity period for purchased gift cards</p>
            <input type='submit' name='submit' value='Save Changes' />
        </form>
        <div id="updateGcSetError" style='color:red'>
            <?php
                if (isset($_SESSION['updateGcSetError'])) {
                    echo $_SESSION['updateGcSetError'];
                }
            ?>
        </div>
        <div id="updateGcSetSuccess" style='color:green'>
            <?php
                if (isset($_SESSION['updateGcSetSuccess'])) {
                    echo $_SESSION['updateGcSetSuccess'];
                }
            ?>
        </div>
        </div>
    </div>
    <script type="text/javascript">
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
        
        function toggleTextbox(rdo) {
            document.getElementById("duration").disabled = !rdo;
            if (rdo) {
                document.getElementById("days").style.display = "inline";
            } else {
                document.getElementById("days").style.display = "none";
            }
        }
        
        window.onload = function() {
            <?php 
                if ($_SESSION['expiryOff'] === "off") {
            ?>
                toggleTextbox(false);
            <?php 
                } else {
            ?>
                toggleTextbox(true);                    
            <?php } ?>
        };
    </script>
</html>
<?php } ?>