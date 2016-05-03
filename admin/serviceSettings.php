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
            <h2>Settings - Services</h2><br>
        <?php 
            $qry = "Select * from services";

            $result = mysqli_query($link, $qry);

            if (!mysqli_query($link,$qry))
            {
                echo("Error description: " . mysqli_error($link));
            } else {
                if ($result->num_rows === 0) {
                    echo "You have not created any services yet.";
                } else {
            ?>
            <table>
                <thead>
                    <th>Service Code</th>
                    <th>Name</th>
                    <th>Edit</th>
                    <th>Delete</th>                        
                </thead>
            <?php
                // output data of each row
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<tr>";
                    echo "<td>".$row['servicecode']."</td>";
                    echo "<td>".$row['servicename']."</td>";                          
                    echo '<td><button onClick="window.location.href=`editService.php?id='.$row['id'].'`">E</button>';
                    echo '<td><button onClick="deleteFunction('.$row['id'].')">D</button></td>';
                    echo "</tr>";
                }
            ?>
            </table>
            <?php
                } 
            }
            ?>  
            <div id="updateServSuccess" style="color:green">
                <?php 
                    if (isset($_SESSION['updateServSuccess'])) {
                        echo $_SESSION['updateServSuccess'];
                    }
                ?>
            </div>
            <div id="updateServError" style="color:red">
                <?php 
                    if (isset($_SESSION['updateServError'])) {
                        echo $_SESSION['updateServError'];
                    }
                ?>
            </div>
        
        <hr><br>
        
        <form id='addService' action='processServices.php' method='post' accept-charset='UTF-8'>
            <fieldset >
            <legend>Add Service</legend>
            <input type='hidden' name='submitted' id='submitted' value='1'/>
            
            <label for='code' >Service Code*:</label>
            <input type='text' name='code' id='code'  maxlength="50" />
            <br>
            <label for='name' >Name*:</label>
            <input type='text' name='name' id='name'  maxlength="50" />
            <br>
            <input type='submit' name='submit' value='Submit' />
            <div id="addServError" style="color:red">
                <?php 
                    if (isset($_SESSION['addServError'])) {
                        echo $_SESSION['addServError'];
                    }
                ?>
            </div>
            
            <div id="addServSuccess" style="color:green">
                <?php 
                    if (isset($_SESSION['addServSuccess'])) {
                        echo $_SESSION['addServSuccess'];
                    }
                ?>
            </div>
            </fieldset>
        </form>
        </div>
    </div>
    <script>
    function deleteFunction(empId) {
        var r = confirm("Are you sure you wish to delete this service?");
        if (r === true) {
            window.location="processServices.php?delete=1&id=" + empId;
        } else if (r === false) {
            <?php
                unset($_SESSION['updateServSuccess']);
                $_SESSION['updateServError'] = "Nothing was deleted";
            ?>
            window.location='serviceSettings.php';
        }
    }
    </script>
</html>

