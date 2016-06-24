<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
require_once '../config/db.php';

if (isset($_POST['submit'])) {
    if (empty($_POST['redeem'])) {
        unset($_SESSION['updateScSetSuccess']);        
        $_SESSION['updateScSetError'] = "Empty field(s)";
    } else {
        unset($_SESSION['updateScSetError']);
        $redeem = $_POST['redeem'];
        
        $checkSql = "SELECT * from settings WHERE type='storecredit'";
        $checkresult = mysqli_query($link, $checkSql);
        
        $val = "redeemamount=".$redeem;
        
        if ($checkresult -> num_rows === 0) {
            $sql = "INSERT INTO settings (type, value) VALUES ('storecredit',"
                    . "'$val');";
        } else {
            $sql = "UPDATE settings SET value='$val' where type='storecredit';";
        }
        
        mysqli_query($link, $sql);
        $_SESSION['updateScSetSuccess'] = "Changes saved successfully"; 
    }
}

$selectSql = "SELECT value from settings WHERE type='storecredit'";
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
                                Store Credit
                            </li>
                        </ol>
        
                        <h1 class="page-header">Update Store Credit Settings</h2>
        
                        <form id='storeCreditSettings' action='storeCreditSettings.php' method='post'>

                            <div id="updateScSetError" class="error">
                                <?php
                                    if (isset($_SESSION['updateScSetError'])) {
                                        echo $_SESSION['updateScSetError'];
                                    }
                                ?>
                            </div>
                            <p id='nanError' class='error' style="display: none;">Please enter numbers only</p>
                            
                            <div id="updateScSetSuccess" class="success">
                                <?php
                                    if (isset($_SESSION['updateScSetSuccess'])) {
                                        echo $_SESSION['updateScSetSuccess'];
                                    }
                                ?>
                            </div>
                            <?php 
                                if(!empty($valArr[0])){
                                    $amount = explode("redeemamount=", $valArr[0]);
                                }
                            ?>
                            Amount awarded: <input type='text' name='redeem' id='redeem' 
                                             value='<?php 
                                                if (!empty($amount[1])) {
                                                    echo $amount[1];
                                                }
                                             ?>' onkeypress="return isNumberKey(event)">
                            <!--<span id='days'>days</span>-->
                            
                            <p class='setting-tooltips'>*Set the default store credit amount redeemed from using referral codes</p>
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
        
        window.onload = function() {
        };
    </script>