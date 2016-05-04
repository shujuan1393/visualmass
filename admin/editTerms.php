<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
session_start();
require '../config/db.php';
require_once('../nav/adminHeader.php');
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
        <h2>Edit Terms Section</h2>
    <?php 
    $qry = "Select * from terms where id ='". $_GET['id']."'";

    $result = mysqli_query($link, $qry);
    
    if (!mysqli_query($link,$qry))
    {
        echo("Error description: " . mysqli_error($link));
    } else {
        while ($row = mysqli_fetch_assoc($result)) {
    ?>
        <form id='editTerm' method='post' action='processTerms.php?edit=1'>
            <input type='hidden' name='editid' value='<?php echo $row['id']?>'>
            Title: <input type="text" name='edittitle' value="<?php echo $row['title']?>"/> </br>
            <label for='html' >Content:</label>
            <textarea name="edithtml" id='edithtml'><?php echo $row['html']; ?></textarea>
            <script type="text/javascript">
                CKEDITOR.replace('edithtml');
            </script>
            <br>
            <input type='submit' name='submit' value='Update'>
        </form>
    <?php
        }
    }
    ?>
        </div>
    </div>
</html>
