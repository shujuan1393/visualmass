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
        <h2>Edit Product</h2>
    <?php 
    $qry = "Select * from products where id ='". $_GET['id']."'";

    $result = mysqli_query($link, $qry);
    
    if (!mysqli_query($link,$qry))
    {
        echo("Error description: " . mysqli_error($link));
    } else {
        while ($row = mysqli_fetch_assoc($result)) {
            $editvisi = explode(',', $row['visibility']);            
            $editavail = explode(',', $row['availability']);
            $editloc = explode(',', $row['locations']);
    ?>
        <form id='editProd' method='post' action='processProducts.php?edit=1' enctype="multipart/form-data">
            <input type='hidden' name='editid' value='<?php echo $row['id']?>'>
            Product Code: <input type="text" name='editcode' value="<?php echo $row['pid']?>"/> </br>
            Name: <input type="text" name='editname' value="<?php echo $row['name']?>"/> </br>
            Description: <textarea name='editdesc'><?php echo $row['description']?></textarea> </br>
            Price: <input type="text" name='editprice' value="<?php echo $row['price']?>"/> 
            Quantity: <input type="text" name='editqty' value="<?php echo $row['quantity']?>"/></br>   
            Type: 
            <select name="edittype">
                <option value='sunglasses' <?php if (strcmp($row['type'], "sunglasses") === 0) {
                    echo "selected"; } ?>>Sunglasses</option>
                <option value ='frames' <?php if (strcmp($row['type'], "frames") === 0) {
                    echo "selected"; } ?>>Frames</option>
            </select><br>
            Visibility*:
            <table width='500px' style='margin-left: 70px; margin-top:-20px'>
                <tr><td>
            <?php
                echo '<input type="checkbox" name="editvisibility[]" 
                value="retail"';
                if (in_array("retail", $editvisi)) {
                    echo " checked";
                }
                echo '><label>Retail</label> ';
                echo '<input type="checkbox" name="editvisibility[]" 
                value="popup"';
                if (in_array("popup", $editvisi)) {
                    echo " checked";
                }
                echo '><label>Pop-up</label><br>';
            ?>
                    </td></tr></table> 
            Availability*:
            <table width='500px' style='margin-left: 70px; margin-top:-20px'>
                <tr><td>
            <?php
                echo '<input type="checkbox" name="editavailability[]" 
                value="sale"';
                if (in_array("sale", $editavail)) {
                    echo " checked";
                }
                echo '><label>For Sale</label> ';
                echo '<input type="checkbox" name="editavailability[]" 
                value="tryon"';
                if (in_array("tryon", $editavail)) {
                    echo " checked";
                }
                echo '><label>Home Tryon</label><br>';
            ?>
                    </td></tr></table> 
            Locations*:
            <table width='500px' style='margin-left: 70px; margin-top:-20px'>
                <tr><td>
            <?php
                $locSql = "Select * from locations";
                $locResult = mysqli_query($link, $locSql);
            if (!mysqli_query($link,$locSql))
            {
                echo("Error description: " . mysqli_error($link));
            } else {
                $count = 0;
                while($locrow = mysqli_fetch_assoc($locResult)) {
                    echo '<input type="checkbox" name="editlocations[]" 
                value="'.$locrow['code'].'"';
                    if (in_array($locrow['code'], $editloc)) {
                        echo " checked";
                    }
                    echo '><label>'.
                            $locrow['name'].'</label>';
                    $count++;

                    if ($count % 2 === 0) {
                        echo "<br>";
                    }
                }
            }
            
            ?>
                </td></tr>
            </table>
            Tags: <input type="text" name='edittags' value="<?php echo $row['tags']?>"/> </br>
            Image: 
            <?php 
                $imageArr = explode(",", $row['images']);
                if (count($imageArr) > 0) {
                    foreach ($imageArr as $image) {
                        echo "<img src='".$image."' width='200'>";
                    }
                } else {
                    echo "<img src='".$row['images']."' width='200'>";
                }
            ?>
            <br>
            <input type='hidden' name="oldImage" value='<?php echo $row['images'] ?>'>
            <input type="file" name="editimages[]" id='editimages' multiple accept='image/*'/>
            <br>        
    <?php
        }
    ?>
            <input type='submit' name='submit' value='Update'>
        </form>
    <?php
        }
    ?>
        <div id="editLocError" style="color:red">
            <?php 
//                if (isset($_SESSION['editLocError'])) {
//                    echo $_SESSION['editLocError'];
//                }

                if (isset($_SESSION['editUploadProdError'])) {
                    echo $_SESSION['editUploadProdError'];
                }
            ?>
        </div>
        </div>
    </div>
</html>

