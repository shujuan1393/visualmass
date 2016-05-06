<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
require_once '../config/db.php';
require_once('../nav/adminHeader.php');

$selectSql = "SELECT value from settings WHERE type='notifications'";
$savedresult = mysqli_query($link, $selectSql);

if (!mysqli_query($link,$selectSql)) {
    echo("Error description: " . mysqli_error($link));
} else {
    $savedrow = mysqli_fetch_assoc($savedresult);
    $valArr = explode("#", $savedrow['value']);
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
        <h2>Settings - Notifications</h2>
        
        <form id='notificationSettings' action='saveNotiSettings.php' method='post'>
            <h4>Email Templates - <span class='setting-tooltips'>Set default email templates to be received by customers</span></h4>
            <?php 
                $emailArr = explode("email=", $valArr[0]);
                $emailVal = explode(",", $emailArr[1]);
            ?>
            <table>
                <tr>
                    <td>Welcome</td>
                    <td><textarea name="welcome"><?php echo $emailVal[0];?></textarea></td>
                    <script type="text/javascript">
                        CKEDITOR.replace('welcome');
                    </script>
                </tr>
                <tr>
                    <td>Thank you for purchasing</td>
                    <td><textarea name="purchase"><?php echo $emailVal[1];?></textarea></td>
                    <script type="text/javascript">
                        CKEDITOR.replace('purchase');
                    </script>
                </tr>
                <tr>
                    <td>Incomplete checkout reminders</td>
                    <td><textarea name="incomplete"><?php echo $emailVal[2];?></textarea></td>
                    <script type="text/javascript">
                        CKEDITOR.replace('incomplete');
                    </script>
                </tr>
                <tr>
                    <td>Password reset</td>
                    <td><textarea name="password"><?php echo $emailVal[3];?></textarea></td>
                    <script type="text/javascript">
                        CKEDITOR.replace('password');
                    </script>
                </tr>
                <tr>
                    <td>Order cancellation</td>
                    <td><textarea name="cancel"><?php echo $emailVal[4];?></textarea></td>
                    <script type="text/javascript">
                        CKEDITOR.replace('cancel');
                    </script>
                </tr>
                <tr>
                    <td>Order refunds</td>
                    <td><textarea name="refund"><?php echo $emailVal[5];?></textarea></td>
                    <script type="text/javascript">
                        CKEDITOR.replace('refund');
                    </script>
                </tr>
                <tr>
                    <td>Giftcard recipients</td>
                    <td><textarea name="gift"><?php echo $emailVal[6];?></textarea></td>
                    <script type="text/javascript">
                        CKEDITOR.replace('gift');
                    </script>
                </tr>
                <tr>
                    <td>Feedback</td>
                    <td><textarea name="feedback"><?php echo $emailVal[7];?></textarea></td>
                    <script type="text/javascript">
                        CKEDITOR.replace('feedback');
                    </script>
                </tr>
                <tr>
                    <td>Eyecheck reminders</td>
                    <td><textarea name="eyecheck"><?php echo $emailVal[8];?></textarea></td>
                    <script type="text/javascript">
                        CKEDITOR.replace('eyecheck');
                    </script>
                </tr>
            </table>
            <h4>SMS Templates - <span class='setting-tooltips'>Set default SMS templates to be received by customers</span></h4>
            <?php 
                $smsArr = explode("sms=", $valArr[1]);
                $smsVal = explode(",", $smsArr[1]);
            ?>
            <table>
                <tr>
                    <td>Order complete</td>
                    <td><textarea name="complete"><?php echo $smsVal[0];?></textarea></td>
                    <script type="text/javascript">
                        CKEDITOR.replace('complete');
                    </script>
                </tr>
                <tr>
                    <td>Collection reminders</td>
                    <td><textarea name="collect"><?php echo $smsVal[1];?></textarea></td>
                    <script type="text/javascript">
                        CKEDITOR.replace('collect');
                    </script>
                </tr>
            </table>
            <input type='submit' name='submit' value='Save Changes' />
        </form>
        <div id="notiSetError" style='color:red'>
            <?php
                if (isset($_SESSION['notisetError'])) {
                    echo $_SESSION['notisetError'];
                }
            ?>
        </div>
        <div id="notiSetSuccess" style='color:green'>
            <?php
                if (isset($_SESSION['updateNotiSetSuccess'])) {
                    echo $_SESSION['updateNotiSetSuccess'];
                }
            ?>
        </div>
        </div>
    </div>
</html>
<?php } ?>