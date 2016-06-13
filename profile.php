<?php 
    require_once 'config/db.php';
    
    $userSql = "Select * from user where email='".$_SESSION['loggedUserEmail']."';";
    $ures = mysqli_query($link, $userSql);
    $row;
    if (!mysqli_query($link, $userSql)) {
        echo "Error: ".mysqli_error($link);
    } else {
        $row = mysqli_fetch_assoc($ures);
    } 
    
?>
<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<html>
    <head>
        <meta charset="UTF-8">
    </head>
    <body>
        
        <div id="wrapper">
            <div id="header"><?php require_once 'nav/header.php';?></div>
            
            <div id="content">
                <div class='row'>
                    <div class='col-md-8 col-md-offset-2'>
                        <h3>PROFILE</h3>
                        <div class='updateProfile' style='color: red'>
                            <p><?php 
                                if (isset($_SESSION['updateProfileError'])) {
                                    echo $_SESSION['updateProfileError'];
                                }
                            ?></p>
                        </div>
                        <div class='updateProfile' style='color: green'>
                            <p><?php 
                                if (isset($_SESSION['updateProfile'])) {
                                    echo $_SESSION['updateProfile'];
                                }
                            ?></p>
                        </div>
                        <form id='updateProfile' method="post" action='saveProfile.php' class='col-md-offset-2'>
                            <div class='row'>
                                <div class='col-md-3 col-md-offset-2'>First Name*: <input type='text' name='firstname' 
                                                                                          value='<?php echo $row['firstname'];?>'></div>
                                <div class='col-md-3'>Last Name*: <input type='text' name='lastname' value='<?php echo $row['lastname'];?>'></div>
                            </div>
                            <br>
                            <div class='row'>
                                <div class='col-md-6 col-md-offset-2'>Email*: <input type='text' name='email' value='<?php echo $row['email'];?>'></div>
                            </div>
                            <br>
                            <div class='row'>
                                <div class='col-md-6 col-md-offset-2'>Change Password*: 
                                    <input type='password' name='password'></div>
                            </div>
                            <br>
                            <div class='row'>
                                <div class='col-md-6 col-md-offset-2'>Address*: 
                                    <textarea name='address'><?php echo $row['address']; ?></textarea>
                                </div>
                            </div>
                            <div class='row'>
                                <div class='col-md-6 col-md-offset-2'>Phone*: <input type='text' name='phone' 
                                                onkeypress='isNumber(event)' value='<?php echo $row['phone'];?>'></div>
                            </div>
                            <div class='row'>
                                <div class='col-md-6 col-md-offset-2'>
                                    <input type='checkbox' name='marketing' id='marketing' value='yes'
                                           <?php 
                                                if (!empty($row['marketing'])) {
                                                    if (strcmp($row['marketing'], "yes") === 0) {
                                                        echo " checked";
                                                    }
                                                }
                                           ?>> I'd like to get emails from Visual Mass
                                </div>
                            </div>
                            
                            <div id='showPref' class='row' style='display:none;'>
                                <div class='col-md-6 col-md-offset-2'>
                                    <?php 
                                        $pref = "Select * from mailinglist where email='".$_SESSION['loggedUserEmail']."';";
                                        $prefres = mysqli_query($link, $pref);

                                        if (!mysqli_query($link, $pref)) {
                                            die(mysqli_error($link));
                                        } else {
                                            $row = mysqli_fetch_assoc($prefres);
                                            $pos = strpos($row['preference'], ",");
                                            if (is_numeric($pos)) {
                                                $prefArr = explode(",", $row['preference']);
                                            } else if (strcmp($row['preference'], "all") === 0) {  
                                                $prefArr = array("male", "female");
                                            } else {
                                                $prefArr = array($row['preference']);
                                            }
                                    ?>
                                    <input type='checkbox' name='preference[]' value='male' <?php 
                                        if (in_array("male", $prefArr)) {
                                            echo " checked";
                                        }
                                    ?>> Male
                                    <input type='checkbox' name='preference[]' value='female' <?php 
                                        if (in_array("female", $prefArr)) {
                                            echo " checked";
                                        }
                                    ?>> Female

                                    <?php
                                        }
                                    ?>
                                </div>
                            </div>
                            
                            <div class='row'>
                                <div class='col-md-6 col-md-offset-2'>
                                    <input type='submit' name='submit' value='SAVE PROFILE'>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            
            <div id="footer"><?php require_once 'nav/footer.php';?></div>
        </div>
    </body>
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
        
        if (document.getElementById('marketing').checked) {
           document.getElementById('showPref').style.display = "block";            
        }
        document.getElementById('marketing').onclick = function(){ 
           document.getElementById('showPref').style.display = "block";
        };
    </script>
</html>
