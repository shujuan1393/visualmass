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
        <h2>Edit Location</h2>
    <?php 
    $qry = "Select * from locations where id ='". $_GET['id']."'";

    $result = mysqli_query($link, $qry);
    
    if (!mysqli_query($link,$qry))
    {
        echo("Error description: " . mysqli_error($link));
    } else {
        while ($row = mysqli_fetch_assoc($result)) {
            $editservices = explode(',', $row['services']);
    ?>
        <form id='editLoc' method='post' action='processLocations.php?edit=1' enctype="multipart/form-data">
            <input type='hidden' name='editid' value='<?php echo $row['id']?>'>
            Location Code: <input type="text" name='editcode' value="<?php echo $row['code']?>"/> </br>
            Name: <input type="text" name='editname' value="<?php echo $row['name']?>"/> </br>
            Address: <input type="text" name='editaddress' value="<?php echo $row['address']?>"/> </br>
            Phone: <input type="text" name='editphone' value="<?php echo $row['phone']?>"/> </br>   
            Apt, Suite: <input type="text" name='editapt' value="<?php echo $row['apt']?>"/> </br>
            City: <input type="text" name='editcity' value="<?php echo $row['city']?>"/> </br>
            ZIP Code: <input type="text" name='editzip' value="<?php echo $row['zip']?>"/> </br>
            Country: <input type="text" name='editcountry' value="<?php echo $row['country']?>"/> </br>
            Image: <img src='<?php echo $row['image']; ?>' width='200'><br>
            <input type='hidden' name="oldImage" value='<?php echo $row['image'] ?>'>
            <input type="file" name="editimage" id='editimage' accept="image/*" /></br>
            Type: 
            <select name="edittype">
                <option value='retail' <?php if (strcmp($row['type'], "retail") === 0) {
                    echo "selected"; } ?>>Retail</option>
                <option value ='popup' <?php if (strcmp($row['type'], "popup") === 0) {
                    echo "selected"; } ?>>Pop-up</option>
            </select><br>
            Services*:
            <table width='500px' style='margin-left: 70px; margin-top:-20px'>
                <tr><td>
            <?php
                $serviceSql = "Select * from services";
                $serviceResult = mysqli_query($link, $serviceSql);
                $editServiceArr = explode(",", $row['services']);
            if (!mysqli_query($link,$serviceSql))
            {
                echo("Error description: " . mysqli_error($link));
            } else {
                $count = 0;
                while($servicerow = mysqli_fetch_assoc($serviceResult)) {
                    echo '<input type="checkbox" name="editservices[]" 
                value="'.$servicerow['servicecode'].'"';
                    if (in_array($servicerow['servicecode'], $editServiceArr)) {
                        echo " checked";
                    }
                    echo '><label>'.
                            $servicerow['servicename'].'</label>';
                    $count++;

                    if ($count % 2 === 0) {
                        echo "<br>";
                    }
                }
            }
        }
    ?>
                    </td></tr>
            </table>
            <br>
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

                if (isset($_SESSION['editUploadLocError'])) {
                    echo $_SESSION['editUploadLocError'];
                }
            ?>
        </div>
        </div>
    </div>
</html>
