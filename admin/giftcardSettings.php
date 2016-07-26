<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
require_once '../config/db.php';

if (isset($_POST['submit'])) {
    $_SESSION['expiry'] = $_POST['expiry'];
    $_SESSION['duration'] = $_POST['duration'];
    
    if (empty($_POST['expiry']) || 
            ($_POST['expiry'] === "on" && empty($_POST['duration']) )) {
        unset($_SESSION['updateGcSetSuccess']);
        $_SESSION['updateGcSetError'] = "Empty field(s)";
    } else {
        unset($_SESSION['expiry']);
        unset($_SESSION['duration']);
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
                            <li>
                                Settings
                            </li>
                            <li class="active">
                                Gift Cards
                            </li>
                        </ol>
        
                        <h1 class="page-header">Update Gift Cards Settings</h2>
        
                        <form id='giftcardSettings' action='giftcardSettings.php' method='post'>

                            <div id="updateGcSetError" class="error">
                                <?php
                                    if (isset($_SESSION['updateGcSetError'])) {
                                        echo $_SESSION['updateGcSetError'];
                                    }
                                ?>
                            </div>
                            <p id='nanError' class='error' style="display: none;">Please enter numbers only</p>
                            
                            <div id="updateGcSetSuccess" class="success">
                                <?php
                                    if (isset($_SESSION['updateGcSetSuccess'])) {
                                        echo $_SESSION['updateGcSetSuccess'];
                                    }
                                ?>
                            </div>
                            
                            <?php
                                if(!empty($valArr[0])){
                                    $expiry = explode("expiry=", $valArr[0]);
                                }
                            ?>
                            Enable Expiry:
                            <input type="radio" name='expiry' id="expiry" value='on' 
                                    <?php 
                                        if(isset($_SESSION['expiry'])){
                                             if (strcmp("on", $_SESSION['expiry'])===0) {
                                                 echo " checked";
                                                 $_SESSION['expiryOff'] = "on";
                                             }
                                        } else if(!empty($expiry[1])){
                                             if (strcmp("on", $expiry[1])===0) {
                                                 echo " checked";
                                                 $_SESSION['expiryOff'] = "on";
                                             }
                                        }
                                    ?>
                                    onclick="toggleTextbox(true);">On
                            <input type="radio" name='expiry' value='off' 
                                    <?php 
                                        if(isset($_SESSION['expiry'])){
                                             if (strcmp("off", $_SESSION['expiry'])===0) {
                                                 echo " checked";
                                                 $_SESSION['expiryOff'] = "off";
                                             }
                                        } else if(!empty($expiry[1])){
                                            if (strcmp("off", $expiry[1])===0) {
                                                echo " checked";
                                                $_SESSION['expiryOff'] = "off";
                                            }
                                        }
                                    ?>
                                    onclick="toggleTextbox(false);">Off
            <br/><br/>
                            <?php 
                                if(!empty($valArr[1])){
                                    $duration = explode("duration=", $valArr[1]);
                                }
                            ?>
                            Duration (days): <input type='text' name='duration' id='duration' 
                                             value='<?php 
                                                if(isset($_SESSION['duration'])){
                                                    echo $_SESSION['duration'];
                                                } else if (!empty($duration[1])) {
                                                    echo $duration[1];
                                                }
                                             ?>' onkeypress="return isNumber(event)">
                            <!--<span id='days'>days</span>-->
                            
                            <p class='setting-tooltips'>*Set the default validity period for purchased gift cards</p>
                            <input type='submit' name='submit' value='Save Changes' />
                        </form>
                    </div>
                </div>
                <!-- /.row -->

            </div>
            <!-- /.container-fluid -->

        </div>
        <!-- /#page-wrapper -->
    </div>
</html>
<?php } ?>

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
        
        function toggleTextbox(rdo) {
            document.getElementById("duration").disabled = !rdo;
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