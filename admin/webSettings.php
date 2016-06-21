<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
require_once '../config/db.php';

if (isset($_POST['submit'])) {
    $webstore = $_POST['title'];
    $metadata = htmlentities($_POST['metadata']);
    
    $val = "web=".$webstore."#";
    $val .= "meta=".$metadata;
    
    $checkSql = "Select * from settings where type='web'";
    $cresult = mysqli_query($link, $checkSql);
    
    if (!mysqli_query($link,$checkSql)) {
        echo("Error description: " . mysqli_error($link));
    } else {
        if ($cresult -> num_rows == 0) {
            $webSql = "INSERT INTO settings (type, value) VALUES ('web', '$val');";
        } else {
            $webSql = 'UPDATE settings SET value="'.$val.'" where type="web";';
        }
        
        mysqli_query($link, $webSql);
        $_SESSION['updateWebSetSuccess'] = "Changes saved successfully";
    }
}

$selectSql = "SELECT value from settings WHERE type='web'";
$savedresult = mysqli_query($link, $selectSql);

if (!mysqli_query($link,$selectSql)) {
    echo("Error description: " . mysqli_error($link));
} else {
    $savedrow = mysqli_fetch_assoc($savedresult);
    $valArr = explode("#", $savedrow['value']);
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

                            <div id="updateWebSetError" style='color:red'>
                                <?php
                                    if (isset($_SESSION['updateWebSetError'])) {
                                        echo $_SESSION['updateWebSetError'];
                                    }
                                ?>
                            </div>
                            <div id="updateWebSetSuccess" style='color:green'>
                                <?php
                                    if (isset($_SESSION['updateWebSetSuccess'])) {
                                        echo $_SESSION['updateWebSetSuccess'];
                                    }
                                ?>
                            </div>

                            Web Store Title:
                            <?php 
                                $web = explode("web=", $valArr[0]);
                            ?>
                            <input type='text' name='title' 
                                   value="<?php if (!empty($web[1])) { echo $web[1]; } ?>"><br>
                            Metadata Description:
                            <?php 
                                if(!empty($valArr[1])){
                                    $meta = explode("meta=", $valArr[1]);
                                }
                            ?>
                            <textarea name='metadata'><?php if (!empty($meta[1])) { echo $meta[1]; } ?></textarea>
                            <script type="text/javascript">
                                CKEDITOR.replace('metadata');
                            </script>

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