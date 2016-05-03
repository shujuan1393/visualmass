<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
session_start();
require_once '../config/db.php';

$selectSql = "SELECT value from settings WHERE type='homeTryon'";
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
        <h2>Settings - Home Try-on</h2>
        
        <form id='homeTrySettings' action='saveHomeTrySettings.php' method='post'>
            Visibility: 
            <select name='visibility'>
                <?php 
                    $visib = explode("visibility=", $valArr[0]);
                ?>
                <option value='on' 
                        <?php 
                        if (!empty($visib[1])) {
                            if (strcmp($visib[1], "on")===0) {
                                echo " selected";
                            }
                        }
                        ?>
                        >On</option>
                <option value='off' 
                        <?php 
                        if (!empty($visib[1])) {
                            if (strcmp($visib[1], "off")===0) {
                                echo " selected";
                            }
                        }
                        ?>
                        >Off</option>
            </select>
            <p class='setting-tooltips'>*Turn on/off the home try-on feature</p><br>
            <?php 
                $duration = explode("duration=", $valArr[1]);
            ?>
            Duration: <input type="text" class="textfield" id="duration" name="duration" 
                             <?php
                                if (!empty($duration[1])) {
                                    echo "value='". $duration[1]."'";
                                }
                             ?>
                             onkeypress="return isNumber(event)" />
            <br>
            <p class='setting-tooltips'>*Set the default duration for home try-ons</p><br>
            <?php 
                $amount = explode("amount=", $valArr[2]);
            ?>
            Amount Chargeable: <input type='text' name='amount' id='amount' 
                            <?php
                                if (!empty($amount[1])) {
                                    echo "value='". $amount[1]."'";
                                }
                             ?>
                                      
                                      onkeypress="return isNumber(event)" > <br>
            <p id='nanError' style="display: none;">Please enter numbers only</p>
            <p class='setting-tooltips'>*Set the default amount to charge for home try-ons</p><br>
            <input type='submit' name='submit' value='Save Changes' />
        </form>
        
        <div id="htSetSuccess" style='color:green'>
            <?php
                if (isset($_SESSION['updateHTSetSuccess'])) {
                    echo $_SESSION['updateHTSetSuccess'];
                }
            ?>
        </div>
        </div>
    </div>
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
    </script>
</html>
<?php } ?>