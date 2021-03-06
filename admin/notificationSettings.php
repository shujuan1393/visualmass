<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
require_once '../config/db.php';

$selectSql = "SELECT value from settings WHERE type='notifications'";
$savedresult = mysqli_query($link, $selectSql);

if (!mysqli_query($link,$selectSql)) {
    echo("Error description: " . mysqli_error($link));
} else {
    $savedrow = mysqli_fetch_assoc($savedresult);
    $notifications = explode("#", $savedrow['value']);
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
                                Notification
                            </li>
                        </ol>
        
                        <h1 class="page-header">Update Notification Settings</h1>
        
                        <form id='notificationSettings' action='saveNotiSettings.php' method='post'>

                            <div id="notiSetError" class="error">
                                <?php
                                    if (isset($_SESSION['notisetError'])) {
                                        echo $_SESSION['notisetError'];
                                    }
                                ?>
                            </div>
                            <div id="notiSetSuccess" class="success">
                                <?php
                                    if (isset($_SESSION['updateNotiSetSuccess'])) {
                                        echo $_SESSION['updateNotiSetSuccess'];
                                    }
                                ?>
                            </div>
                            
                            <p>Email Templates - 
                                <span class='setting-tooltips'>
                                    Set default email templates to be received by customers
                                </span></p>
                            <?php 
                                if(!empty($notifications[0])){
                                    $emailArr = explode("email=", $notifications[0]);
                                    $emailVal = explode(",", $emailArr[1]);
                                }
                            ?>
                                
                            <table>
                                <tr>
                                    <td>Welcome</td>
                                    <td><textarea name="welcome"><?php if(!empty($emailVal[0])) { echo $emailVal[0]; }?></textarea></td>
                                    <script type="text/javascript">
                                        CKEDITOR.replace('welcome');
                                    </script>
                                </tr>
                                <tr>
                                    <td>Thank you for purchasing</td>
                                    <td><textarea name="purchase"><?php if(!empty($emailVal[1])) { echo $emailVal[1]; }?></textarea></td>
                                    <script type="text/javascript">
                                        CKEDITOR.replace('purchase');
                                    </script>
                                </tr>
                                <tr>
                                    <td>Incomplete checkout reminders</td>
                                    <td><textarea name="incomplete"><?php if(!empty($emailVal[2])) { echo $emailVal[2]; }?></textarea></td>
                                    <script type="text/javascript">
                                        CKEDITOR.replace('incomplete');
                                    </script>
                                </tr>
                                <tr>
                                    <td>Password reset</td>
                                    <td><textarea name="password"><?php if(!empty($emailVal[3])) { echo $emailVal[3]; }?></textarea></td>
                                    <script type="text/javascript">
                                        CKEDITOR.replace('password');
                                    </script>
                                </tr>
                                <tr>
                                    <td>Order cancellation</td>
                                    <td><textarea name="cancel"><?php if(!empty($emailVal[4])) { echo $emailVal[4]; }?></textarea></td>
                                    <script type="text/javascript">
                                        CKEDITOR.replace('cancel');
                                    </script>
                                </tr>
                                <tr>
                                    <td>Order refunds</td>
                                    <td><textarea name="refund"><?php if(!empty($emailVal[5])) echo $emailVal[5];?></textarea></td>
                                    <script type="text/javascript">
                                        CKEDITOR.replace('refund');
                                    </script>
                                </tr>
                                <tr>
                                    <td>Giftcard recipients</td>
                                    <td><textarea name="gift"><?php if(!empty($emailVal[6])) { echo $emailVal[6]; }?></textarea></td>
                                    <script type="text/javascript">
                                        CKEDITOR.replace('gift');
                                    </script>
                                </tr>
                                <tr>
                                    <td>Feedback</td>
                                    <td><textarea name="feedback"><?php if(!empty($emailVal[7])) { echo $emailVal[7]; }?></textarea></td>
                                    <script type="text/javascript">
                                        CKEDITOR.replace('feedback');
                                    </script>
                                </tr>
                                <tr>
                                    <td>Eyecheck reminders</td>
                                    <td><textarea name="eyecheck"><?php if(!empty($emailVal[8])) { echo $emailVal[8]; }?></textarea></td>
                                    <script type="text/javascript">
                                        CKEDITOR.replace('eyecheck');
                                    </script>
                                </tr>
                            </table>
                                
                            <p>SMS Templates - 
                                <span class='setting-tooltips'>
                                    Set default SMS templates to be received by customers
                                </span></p>
                            <?php 
                                if(!empty($notifications[1])){
                                    $smsArr = explode("sms=", $notifications[1]);
                                    $smsVal = explode(",", $smsArr[1]);
                                }
                            ?>
                                
                            <table>
                                <tr>
                                    <td>Order complete</td>
                                    <td><textarea name="complete"><?php if(!empty($smsVal[0])) { echo $smsVal[0]; }?></textarea></td>
                                    <script type="text/javascript">
                                        CKEDITOR.replace('complete');
                                    </script>
                                </tr>
                                <tr>
                                    <td>Collection reminders</td>
                                    <td><textarea name="collect"><?php if(!empty($smsVal[1])) { echo $smsVal[1]; }?></textarea></td>
                                    <script type="text/javascript">
                                        CKEDITOR.replace('collect');
                                    </script>
                                </tr>
                            </table>
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