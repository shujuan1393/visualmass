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
        <h2>Edit Employee</h2>
    <?php 
    $qry = "Select * from staff where id ='". $_GET['id']."'";

    $result = mysqli_query($link, $qry);
    if (!mysqli_query($link,$qry))
    {
        echo("Error description: " . mysqli_error($link));
    } else {
        while ($row = mysqli_fetch_assoc($result)) {
    ?>
        <form id='editUser' method='post' action='processUsers.php?edit=1'>
            <input type='hidden' name='editid' value='<?php echo $row['id']?>'>
            First Name: <input type="text" name='editfirstname' value="<?php echo $row['firstname']?>"/> </br>
            Last Name: <input type="text" name='editlastname' value="<?php echo $row['lastname']?>"/> </br>
            Email: <input type="text" name='editemail' value="<?php echo $row['email']?>"/> </br>
            Type: 
            <select name="edittype">
                <?php 
                    $emptypeSql = "Select * from employeeTypes";
                    $typeresult = mysqli_query($link, $emptypeSql);
                    
                    if (!mysqli_query($link,$emptypeSql)) {
                        echo("Error description: " . mysqli_error($link));
                    } else {
                        if ($typeresult->num_rows === 0) {
                            echo "You have not created any employee types yet.";
                        } else {
                            while ($row1 = mysqli_fetch_assoc($typeresult)) {
                                echo "<option value='".$row1['code']."' ";
                                if (strcmp($row['type'], $row1['code']) === 0) {
                                    echo "selected"; 
                                }
                                echo ">".$row1['name']."</option>";
                            }
                        }
                    }
                ?>
            </select><br>
            <input type='submit' name='submit' value='Update'>
        </form>
    <?php
        }
    }
    ?>
        </div>
    </div>
</html>
