<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
session_start();
require '../config/db.php';
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
        <h2>Edit Service</h2>
    <?php 
    $qry = "Select * from services where id ='". $_GET['id']."'";

    $result = mysqli_query($link, $qry);
    if (!mysqli_query($link,$qry))
    {
        echo("Error description: " . mysqli_error($link));
    } else {
        while ($row = mysqli_fetch_assoc($result)) {
    ?>
        <form id='editService' method='post' action='processServices.php?edit=1'>
            <input type='hidden' name='editid' value='<?php echo $row['id']?>'>
            Service Code: <input type="text" name='editcode' value="<?php echo $row['servicecode']?>"/> </br>
            Name: <input type="text" name='editname' value="<?php echo $row['servicename']?>"/> </br>
            <input type='submit' name='submit' value='Update'>
        </form>
    <?php
        }
    }
    ?>
        </div>
    </div>
</html>
