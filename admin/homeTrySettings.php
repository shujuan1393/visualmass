<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
require_once '../config/db.php';

$selectSql = "SELECT value from settings WHERE type='homeTryon'";
$savedresult = mysqli_query($link, $selectSql);

if (!mysqli_query($link,$selectSql)) {
    echo("Error description: " . mysqli_error($link));
} else {
    $savedrow = mysqli_fetch_assoc($savedresult);
    $hometry = explode("&", $savedrow['value']);
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
                                Home Try-on
                            </li>
                        </ol>
                        
                        <h1 class="page-header">Update Home Try-on Settings</h1>
        
                        <form id='homeTrySettings' action='saveHomeTrySettings.php' method='post'>

                            <div id="htSetError" class="error">
                                <?php
                                    if (isset($_SESSION['updateHTSetError'])) {
                                        echo $_SESSION['updateHTSetError'];
                                    }
                                ?>
                            </div>

                            <div id="htSetSuccess" class="success">
                                <?php
                                    if (isset($_SESSION['updateHTSetSuccess'])) {
                                        echo $_SESSION['updateHTSetSuccess'];
                                    }
                                ?>
                            </div>
                            
                            Visibility: 
                            <?php 
                                if (!empty($hometry[0])) {
                                    $visib = explode("visibility=", $hometry[0]);
                                }
                            ?>
                            <input name='visibility' type='radio' value='on' 
                                    <?php 
                                    if (isset($_SESSION['visibility'])) { 
                                        if (strcmp($_SESSION['visibility'], "on")===0) {
                                            echo " checked";
                                            $_SESSION['visibilityOff'] = "on";
                                        }
                                    } else if (!empty($visib[1])) {
                                        if (strcmp($visib[1], "on")===0) {
                                            echo " checked";
                                            $_SESSION['visibilityOff'] = "on";
                                        }
                                    }
                                    ?>
                                    onclick="toggleTextbox(true);">On
                            <input type='radio' name='visibility' value='off' 
                                    <?php 
                                    if (isset($_SESSION['visibility'])) { 
                                        if (strcmp($_SESSION['visibility'], "off")===0) {
                                            echo " checked";
                                            $_SESSION['visibilityOff'] = "off";
                                        }
                                    } else if (!empty($visib[1])) {
                                        if (strcmp($visib[1], "off")===0) {
                                            echo " checked";
                                            $_SESSION['visibilityOff'] = "off";
                                        }
                                    }
                                    ?>
                                    onclick="toggleTextbox(false);">Off
                            
                            <p class='setting-tooltips'>*Turn on/off the home try-on feature</p><br>

                            <?php 
                                if(!empty($hometry[1])){
                                    $duration = explode("duration=", $hometry[1]);
                                }
                            ?>
                            Duration (days):
                            <input type="text" class="textfield" id="duration" name="duration" 
                                    value='<?php
                                    if (isset($_SESSION['duration'])) { 
                                        echo $_SESSION['duration'];
                                    } else if (!empty($duration[1])) {
                                        echo $duration[1];
                                    }
                                    ?>'
                                    onkeypress="return isNumber(event)" />
                            <!--<span id='days'>days</span>-->
            
                            <p class='setting-tooltips'>*Set the default duration for home try-ons</p><br>

                            <?php 
                                if(!empty($hometry[2])){
                                    $amount = explode("amount=", $hometry[2]);
                                }
                            ?>
                            Amount Chargeable:
                            <input type='text' name='amount' id='amount' 
                                    value='<?php
                                    if (isset($_SESSION['amount'])) { 
                                        echo $_SESSION['amount'];
                                    } else if (!empty($amount[1])) {
                                        echo $amount[1];
                                    }
                                     ?>'
                                      onkeypress="return isNumberKey(event)" > <br>
            
                            <p id='nanError' style="display: none;">Please enter numbers only</p>
                            <p class='setting-tooltips'>*Set the default amount to charge for home try-ons</p><br>
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
    function isNumberKey(evt) {
        var charCode = (evt.which) ? evt.which : event.keyCode
        if (charCode > 31 && (charCode < 48 || charCode > 57) && charCode != 46) {
            document.getElementById('nanError').style.display='block';
            document.getElementById('nanError').style.color='red';
            return false;
       }

        document.getElementById('nanError').style.display='none';
        return true;
    }
    function toggleTextbox(rdo) {
        document.getElementById("duration").disabled = !rdo;
        document.getElementById("amount").disabled = !rdo;
        if (rdo) {
            document.getElementById("days").style.display = "inline";
        } else {
            document.getElementById("days").style.display = "none";
        }
    }

    window.onload = function() {
        <?php 
            if ($_SESSION['visibilityOff'] === "off") {
        ?>
            toggleTextbox(false);
        <?php 
            } else {
        ?>
            toggleTextbox(true);                    
        <?php } ?>
    };
</script>