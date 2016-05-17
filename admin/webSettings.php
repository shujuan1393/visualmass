<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
require_once '../config/db.php';
require_once '../nav/adminHeader.php';

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
            $webSql = "UPDATE settings SET value='$val' where type='web';";
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
        <h2>Settings - Web</h2>
        
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
                   value='<?php if (!empty($web[1])) { echo $web[1]; } ?>'><br>
            Metadata Description:
            <?php 
                $meta = explode("meta=", $valArr[1]);
            ?>
            <textarea name='metadata'><?php if (!empty($meta[1])) { echo $meta[1]; } ?></textarea>
            <script type="text/javascript">
                CKEDITOR.replace('metadata');
            </script>
            <br>
            <input type='submit' name='submit' value='Save Changes' />
        </form>
        </div>
    </div>
</html>
<?php } ?>