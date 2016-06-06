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
    
    if (isset($_POST['submit'])) {
        unset($_SESSION['updateProfile']);
        $first = isset($_POST['firstname']) ? $_POST['firstname'] : '';
        $last = isset($_POST['lastname']) ? $_POST['lastname'] : '';
        $email = isset($_POST['email']) ? $_POST['email'] : '';
        $password = isset($_POST['password']) ? $_POST['password'] : '';
        
        if (strcmp($password, "")===0) {
            $pwd = $row['password'];
        } else {
            $pwd = md5($password);
        }
        $add = isset($_POST['address']) ? $_POST['address'] : '';
        $marketing = isset($_POST['marketing']) ? $_POST['marketing'] : '';
        
        if (empty($marketing)) {
            $marketing = "no";
        }
        $updateFav = "UPDATE favourites set email='$email' where email='".$_SESSION['loggedUserEmail']."';";
        
        mysqli_query($link, $updateFav);
        
        $update = "UPDATE user set firstname='$first', lastname='$last', email='$email', "
                . "password='$pwd', address='$add', marketing='$marketing' where email='".$_SESSION['loggedUserEmail']."';";
        
        mysqli_query($link, $update);
        
            $getMailing = "Select * from mailinglist where email='".$_SESSION['loggedUserEmail']."';";
            $mailingRes = mysqli_query($link, $getMailing);        

            if (!mysqli_query($link, $getMailing)) {
                die(mysqli_error($link));
            } else {
                if (strcmp($marketing, "yes") === 0) {
                    if ($mailingRes -> num_rows === 0) {
                        $mailing = "INSERT INTO mailinglist (email, preference) VALUES ('$email', 'all');";
                    } else {
                        $mailing = "UPDATE mailinglist set email='$email' where email='".$_SESSION['loggedUserEmail'].";";
                    }
                } else {
                    $mailing = "DELETE FROM mailinglist where email='".$_SESSION['loggedUserEmail']."'";
                }
            }
            mysqli_query($link, $mailing);
        $_SESSION['loggedUserEmail'] = $email;
        $_SESSION['updateProfile'] = "Profile updated";
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
                        <div class='updateProfile' style='color: green'>
                            <p><?php 
                                if (isset($_SESSION['updateProfile'])) {
                                    echo $_SESSION['updateProfile'];
                                }
                            ?></p>
                        </div>
                        <form id='updateProfile' method="post" action='profile.php' class='col-md-offset-2'>
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
                                <div class='col-md-6 col-md-offset-2'>
                                    <input type='checkbox' name='marketing' value='yes'
                                           <?php 
                                                if (!empty($row['marketing'])) {
                                                    if (strcmp($row['marketing'], "yes") === 0) {
                                                        echo " checked";
                                                    }
                                                }
                                           ?>> I'd like to get emails from Visual Mass
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
</html>
