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
            <h2>Manage Employee Accounts</h2><br>
        <?php 
            $qry = "Select * from staff where email <> '".$_SESSION['loggedUserEmail']."'";

            $result = mysqli_query($link, $qry);

            if (!mysqli_query($link,$qry))
            {
                echo("Error description: " . mysqli_error($link));
            } else {
                if ($result->num_rows === 0) {
                    echo "You have not created any employees accounts yet.";
                } else {
            ?>
            <table>
                <thead>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Employee Type</th>
                    <th>Edit</th>
                    <th>Delete</th>                        
                </thead>
            <?php
                // output data of each row
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<tr>";
                    echo "<td>".$row['firstname'] . " " . $row['lastname']."</td>";
                    echo "<td>".$row['email']."</td>";                           
                    echo "<td>".$row['type']."</td>";                         
                    echo '<td><button onClick="window.location.href=`editUser.php?id='.$row['id'].'`">E</button>';
                    echo '<td><button onClick="deleteFunction('.$row['id'].')">D</button></td>';
                    echo "</tr>";
                }

            ?>
            </table>
            <?php
                } 
            }
            ?>  
            <div id="updateEmpSuccess" style="color:green">
                <?php 
                    if (isset($_SESSION['updateSuccess'])) {
                        echo $_SESSION['updateSuccess'];
                    }
                ?>
            </div>
            <div id="updateEmpError" style="color:red">
                <?php 
                    if (isset($_SESSION['updateError'])) {
                        echo $_SESSION['updateError'];
                    }
                ?>
            </div>
        
        <hr><br>
        
        <form id='addUser' action='processUsers.php' method='post' accept-charset='UTF-8'>
            <fieldset >
            <legend>Add Employee Account</legend>
            <input type='hidden' name='submitted' id='submitted' value='1'/>
            
            <label for='firstName' >First Name*:</label>
            <input type='text' name='firstName' id='firstName'  maxlength="50" />
            <br>
            <label for='lastName' >Last Name*:</label>
            <input type='text' name='lastName' id='lastName'  maxlength="50" />
            <br>
            <label for='email' >Email*:</label>
            <input type='text' name='email' id='email'  maxlength="50" />
            <br>
            Type*:
            <select name="type">
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
                                echo "<option value='".$row1['code']."'>";
                                echo $row1['name']."</option>";
                            }
                        }
                    }
                ?>
            </select>
            <br>
            <input type='submit' name='submit' value='Submit' />
            <div id="addUserError" style="color:red">
                <?php 
                    if (isset($_SESSION['addEmpError'])) {
                        echo $_SESSION['addEmpError'];
                    }
                ?>
            </div>
            
            <div id="addEmpSuccess" style="color:green">
                <?php 
                    if (isset($_SESSION['addEmpSuccess'])) {
                        echo $_SESSION['addEmpSuccess'];
                    }
                ?>
            </div>
            </fieldset>
        </form>
        </div>
    </div>
    <script>
    function deleteFunction(empId) {
        var r = confirm("Are you sure you wish to delete this employee's account?");
        if (r === true) {
            window.location="processUsers.php?delete=1&id=" + empId;
        } else if (r === false) {
            <?php
                unset($_SESSION['updateSuccess']);
                unset($_SESSION['addEmpSuccess']);
                unset($_SESSION['addEmpError']);
                $_SESSION['updateError'] = "Nothing was deleted";
            ?>
            window.location='users.php';
        }
    }
    </script>
</html>

