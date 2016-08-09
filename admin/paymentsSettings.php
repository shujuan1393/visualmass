<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
require_once '../config/db.php';

$selectSql = "SELECT value from settings WHERE type='payments'";
$savedresult = mysqli_query($link, $selectSql);

if (!mysqli_query($link,$selectSql)) {
    echo("Error description: " . mysqli_error($link));
} else {
    $savedrow = mysqli_fetch_assoc($savedresult);
    $payments = explode("#", $savedrow['value']);
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
                                Payments/Shipping
                            </li>
                        </ol>
                        
                        <h1 class="page-header">Update Payments/Shipping Settings</h1>
        
                        <form id='paymentSettings' action='savePaymentSettings.php' method='post'>

                            <div id="paymentSetError" class="error">
                                <?php
                                    if (isset($_SESSION['updatePaymentSetError'])) {
                                        echo $_SESSION['updatePaymentSetError'];
                                    }
                                ?>
                            </div>

                            <div id="paymentSetSuccess" class="success">
                                <?php
                                    if (isset($_SESSION['updatePaymentSetSuccess'])) {
                                        echo $_SESSION['updatePaymentSetSuccess'];
                                    }
                                ?>
                            </div>
                            
                            Visibility: 
                            <?php 
                                if (!empty($payments[0])) {
                                    $visib = explode("visibility=", $payments[0]);
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
                            
                            <p class='setting-tooltips'>*Turn on/off the shipping feature</p><br>

                            <?php 
                                if(!empty($payments[1])){
                                    $disclaimer = explode("disclaimer=", $payments[1]);
                                }
                            ?>
                            Disclaimer:
                            <textarea name='disclaimer' id='disclaimer'>
                                <?php 
                                    if (isset($_SESSION['disclaimer'])) {
                                        echo $_SESSION['disclaimer'];
                                    } else if (!empty($disclaimer[1])) {
                                        echo $disclaimer[1];
                                    }
                                ?>
                            </textarea>
                            <script type='text/javascript'>
                                CKEDITOR.replace('disclaimer');
                            </script>
                            <p class='setting-tooltips'>*Set the default disclaimer to be displayed on the website</p><br>

                            <?php 
//                                if(!empty($payments[2])){
//                                    $amount = explode("amount=", $payments[2]);
//                                }
                            ?>
<!--                            Amount Chargeable:
                            <input type='text' name='amount' id='amount' 
                                    <?php
//                                        if (!empty($amount[1])) {
//                                            echo "value='". $amount[1]."'";
//                                        }
                                     ?>
                                      
                                      onkeypress="return isNumberKey(event)" > <br>
            
                            <p id='nanError' style="display: none;">Please enter numbers only</p>
                            <p class='setting-tooltips'>*Set the default amount to charge for home try-ons</p><br>-->
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