<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
require_once '../config/db.php';

if (isset($_POST['submit'])) {
    $_SESSION['title'] = $_POST['title'];
    $_SESSION['metadata'] = $_POST['metadata'];
    $_SESSION['ticker'] = $_POST['ticker'];
    $_SESSION['maintenance'] = $_POST['maintenance'];
    $_SESSION['password'] = $_POST['password'];
    $_SESSION['message'] = $_POST['message'];
    
    if (empty($_POST['title']) || empty($_SESSION['metadata']) || empty($_POST['maintenance'])) {
        unset($_SESSION['updateWebSetSuccess']);
        $_SESSION['updateWebSetError'] = "Empty field(s)";
    } else if (strcmp($_POST['maintenance'], "on") === 0 && (empty($_POST['password']) || empty($_POST['message']))) { 
        unset($_SESSION['updateWebSetSuccess']);
        $_SESSION['updateWebSetError'] = "Password and message required";
    } else {
        unset($_SESSION['updateWebSetError']);
        
        $webstore = $_POST['title'];
        $metadata = htmlentities($_POST['metadata']);
        $ticker = $_POST['ticker'];
        $maintain = $_POST['maintenance'];
        $store = $_POST['password'];
        $msg = htmlentities($_POST['message']);

        $val = "web=".$webstore."#";
        $val .= "meta=".$metadata."#";
        $val .= "ticker=".$ticker."#";
        $val .= "maintenance=".$maintain."#";
        $val .= "password=".$store."#";
        $val .= "message=".$msg;
        
        $checkSql = "Select * from settings where type='web'";
        $cresult = mysqli_query($link, $checkSql);

        if (!mysqli_query($link,$checkSql)) {
            echo("Error description: " . mysqli_error($link));
        } else {
            unset($_SESSION['title']);
            unset($_SESSION['metadata']);
            unset($_SESSION['ticker']);
            unset($_SESSION['maintenance']);
            unset($_SESSION['password']);
            unset($_SESSION['message']);

            if ($cresult -> num_rows == 0) {
                $webSql = "INSERT INTO settings (type, value) VALUES ('web', '$val');";
            } else {
                $webSql = 'UPDATE settings SET value="'.$val.'" where type="web";';
            }

            mysqli_query($link, $webSql);
            $_SESSION['updateWebSetSuccess'] = "Changes saved successfully";
        }
    }
}

$selectSql = "SELECT value from settings WHERE type='web'";
$savedresult = mysqli_query($link, $selectSql);

if (!mysqli_query($link,$selectSql)) {
    echo("Error description: " . mysqli_error($link));
} else {
    $savedrow = mysqli_fetch_assoc($savedresult);
    $webset = explode("#", $savedrow['value']);
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
                                Web
                            </li>
                        </ol>
                        
                        <h1 class="page-header">Update Web Settings</h1>
        
                        <form id='generalSettings' action='webSettings.php' method='post'>

                            <div id="updateWebSetError" class="error">
                                <?php
                                    if (isset($_SESSION['updateWebSetError'])) {
                                        echo $_SESSION['updateWebSetError'];
                                    }
                                ?>
                            </div>
                            <div id="updateWebSetSuccess" class="success">
                                <?php
                                    if (isset($_SESSION['updateWebSetSuccess'])) {
                                        echo $_SESSION['updateWebSetSuccess'];
                                    }
                                ?>
                            </div>

                            Web Store Title:
                            <?php 
                                $web = explode("web=", $webset[0]);
                            ?>
                            <input type='text' name='title' 
                                   value="<?php 
                                   if (isset($_SESSION['title'])) { 
                                       echo $_SESSION['title'];
                                   } else if (!empty($web[1])) { echo $web[1]; } ?>"><br>
                            Metadata Description:
                            <?php 
                                if(!empty($webset[1])){
                                    $meta = explode("meta=", $webset[1]);
                                }
                            ?>
                            <textarea name='metadata'><?php 
                            if (isset($_SESSION['metadata'])) { 
                                echo $_SESSION['metadata'];
                            } else if (!empty($meta[1])) { echo $meta[1]; } ?></textarea>
                            <script type="text/javascript">
                                CKEDITOR.replace('metadata');
                            </script><br>
                            Ticker:
                            <?php 
                                if (!empty($webset[2])) {
                                    $tick = explode("ticker=", $webset[2]);
                                }
                            ?>
                            <input type='text' name='ticker' 
                                   value="<?php 
                                   if (isset($_SESSION['ticker'])) { 
                                       echo $_SESSION['ticker'];
                                   } else if (!empty($tick[1])) { echo $tick[1]; } ?>">
                            <br><br>
                            Maintenance Mode:
                            <?php 
                                if (!empty($webset[3])) {
                                    $maintain = explode("maintenance=", $webset[3]);
                                }
                            ?>
                            <input name='maintenance' type='radio' value='on' 
                                    <?php 
                                    if (isset($_SESSION['maintenance'])) { 
                                        if (strcmp($_SESSION['maintenance'], "on")===0) {
                                            echo " checked";
                                            $_SESSION['passwordOff'] = "on";
                                        }
                                    } else if (!empty($maintain[1])) {
                                        if (strcmp($maintain[1], "on")===0) {
                                            echo " checked";
                                            $_SESSION['passwordOff'] = "on";
                                        }
                                    }
                                    ?>
                                    onclick="toggleTextbox(true);">On
                            <input type='radio' name='maintenance' value='off' 
                                    <?php 
                                    if (isset($_SESSION['maintenance'])) { 
                                        if (strcmp($_SESSION['maintenance'], "off")===0) {
                                            echo " checked";
                                            $_SESSION['passwordOff'] = "off";
                                        }
                                    } else if (!empty($maintain[1])) {
                                        if (strcmp($maintain[1], "off")===0) {
                                            echo " checked";
                                            $_SESSION['passwordOff'] = "off";
                                        }
                                    }
                                    ?>
                                    onclick="toggleTextbox(false);">Off
                            
                            <br><br>
                            <div id='storePwd' style='display: none'>
                                <?php 
                                    if (!empty($webset[4])) {
                                        $pwd = explode("password=", $webset[4]);
                                    }
                                ?>
                                Store Password: 
                                <input type="text" name='password' id='password' value='<?php 
                                    if (isset($_SESSION['password'])) {
                                        echo $_SESSION['password'];
                                    } else if (!empty ($pwd[1])) {
                                        echo $pwd[1];
                                    }
                                    ?>'>
                                <br>
                                <?php 
                                    if (!empty($webset[5])) {
                                        $message = explode("message=", $webset[5]);
                                    }
                                ?>
                                <br>
                                Maintenance Message:
                                <textarea name='message' id='message'><?php 
                                    if (isset($_SESSION['message'])) {
                                        echo $_SESSION['message'];
                                    } else if (!empty ($message[1])) {
                                        echo $message[1];
                                    }
                                    ?></textarea>
                                <script type='text/javascript'>
                                    CKEDITOR.replace('message');
                                </script>
                            </div>
                            <input type='submit' name='submit' value='Save' />
                        </form>
                    </div>
                </div>
                <!-- /.row -->

            </div>
            <!-- /.container-fluid -->

        </div>
        <!-- /#page-wrapper -->
    </div>
    <script>
        function toggleTextbox(rdo) {
//            document.getElementById("duration").disabled = !rdo;
//            document.getElementById("amount").disabled = !rdo;
            if (rdo) {
                document.getElementById("storePwd").style.display = "block";
            } else {
                document.getElementById("storePwd").style.display = "none";
                document.getElementById('password').value = "";
                document.getElementById('message').value = "";
            }
        }

        window.onload = function() {
            <?php 
            if (isset($_SESSION['passwordOff'])) {
                if ($_SESSION['passwordOff'] === "off") {
            ?>
                toggleTextbox(false);
            <?php 
                } else {
            ?>
                toggleTextbox(true);                    
            <?php 
                } 
            }?>
        };
    </script>
</html>
<?php } ?>