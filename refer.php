<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
require_once 'config/db.php';

if (isset($_POST['submitted'])) {
    $code = $_POST['refercode'];
    
    $getrefer = "Select * from referrals where email = '".$_SESSION['loggedUserEmail']."';";
    $result = mysqli_query($link, $getrefer);
    
    if (!mysqli_query($link, $getrefer)) {
        die(mysqli_error($link));
    } else {
        if ($result -> num_rows === 0) {
            unset($_SESSION['referError']);
            
            $check = "Select * from user where code='$code';";
            $cres = mysqli_query($link, $check);
            
            if (!mysqli_query($link, $check)) {
                die(mysqli_error($link));
            } else {
                if ($cres -> num_rows === 0) {
                    $_SESSION['refer'] = $code;
                    $_SESSION['referError'] = "Invalid referral code";                    
                } else {
                    //update user's account with credit
                    $user = "Select * from user where email ='".$_SESSION['loggedUserEmail']."';";
                    $uresult = mysqli_query($link, $user);

                    if (!mysqli_query($link, $user)) {
                        die(mysqli_error($link));
                    } else {
                        $userow = mysqli_fetch_assoc($uresult);
                        $credit = $userow['credit'];
                    }
                    
                    
                    //get store credit from settings
                    $getamt = "Select * from settings where type='storecredit';";
                    $sres = mysqli_query($link, $getamt);
                    $amt;
                    
                    if (!mysqli_query($link, $getamt)) {
                        die(mysqli_error($link));
                    } else {
                        $srow = mysqli_fetch_assoc($sres);
                        $valArr = explode("&", $srow['value']);
                        if(!empty($valArr[0])){
                            $amount = explode("redeemamount=", $valArr[0]);
                            $amt = $amount[1];
                        }
                    }
                    if (empty($credit)) {
                        $credit = 0;
                    }
                    
                    $credit = intval($credit) + intval($amt);
                    $addCredit = "UPDATE user set credit='$credit' where email ='".$_SESSION['loggedUserEmail']."';";
                    mysqli_query($link, $addCredit);
                    
                    //store referral transaction
                    $sql = "INSERT INTO referrals (email, code) VALUES ('".$_SESSION['loggedUserEmail']."', '$code');";
                    mysqli_query($link, $sql);
                    unset($_SESSION['refer']);
                    unset($_SESSION['referError']);
                    $_SESSION['referSuccess'] = "yes";                    
                }
            }
        } else {
            unset($_SESSION['referSuccess']);
            $_SESSION['refer'] = $code;
            $_SESSION['referError'] = "You have already redeemed your store credit";
        }
    }
}
?>
<head>
    <link href="styles.css" rel="stylesheet" type="text/css" />
</head>
<html>
    <body>
        <div id="referWrapper">
            <div class="rightheader close_modal">
                <button type="button" id='closeRefer' class="close" data-dismiss="modal" aria-hidden="true">X</button>
            </div>
            <div class="logo"></div>
            <div id='content'>
                <div id="mailError" class="error">
                    <?php 
                        if (isset($_SESSION['referError'])) {
                            echo $_SESSION['referError'];
                        }
                    ?>
                </div>
                <form id='referForm' autofocus>
                    <input type='hidden' name='submitted' id='submitted' value='1'/>
                    <h4 class='caps'>Get your store credit now</h4>
                    <input type='text' name='refercode' id='refercode'  maxlength="50" 
                           placeholder="Enter your code here" value="<?php if (isset($_SESSION['refer'])) { echo $_SESSION['refer']; }?>"/>
                    <br>
                    <input type='submit' name='submit' value='REDEEM'>
                </form>
            </div>
            <!--<div id="footer"><?php // require_once 'nav/footer.php';?></div>-->
        </div>
    </body>
    <script>
        <?php 
            if (isset($_SESSION['referSuccess'])) {
                if (strcmp($_SESSION['referSuccess'], "yes") === 0) {
        ?>
                    document.getElementById('closeRefer').click();
        <?php   
                } 
            }        
        ?>
        
        $('#referForm').validate({
            rules: {
                refercode: {
                    required: true
                }
            },
            highlight: function(element) {
            $(element).closest('.control-group').removeClass('success').addClass('error');
            },
            submitHandler: function(form) {
                $.ajax({
                    type:"POST",
                    url: "refer.php",
                    data: $(form).serialize(),
                    }).done(function(data) {
                    $('#referWrapper').replaceWith(data);
                    });
            }
        });
    </script>
</html>
