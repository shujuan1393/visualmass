<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

require_once '../config/db.php';

$selectSql = "SELECT value from settings WHERE type='account'";
$savedresult = mysqli_query($link, $selectSql);

if (isset($_GET['id'])) {
    unset($_SESSION['addEmpTypeError']);
    unset($_SESSION['addEmpTypeSuccess']);
    unset($_SESSION['updateEmpTypeError']);
    unset($_SESSION['updateEmpTypeSuccess']);
    $getEmpType = "Select * from employeetypes where id='".$_GET['id']."';";
    $eresult = mysqli_query($link, $getEmpType);
    $erow = mysqli_fetch_assoc($eresult);
}

if (!mysqli_query($link,$selectSql)) {
    echo("Error description: " . mysqli_error($link));
} else {
    $savedrow = mysqli_fetch_assoc($savedresult);
    $valArr = explode("&", $savedrow['value']);
?>
<html>    
    <div id="frameheader">
        <?php
            require '../nav/adminHeader.php';
        ?>
    </div>
    <div id="framecontent">
        <div class='innertube'>
        <?php
            require '../nav/adminSidebar.php';
        ?>
        </div>
    </div>
    <div id="maincontent">
        <div class="innertube">
        <h2>Settings - Accounts</h2>
        <h3>Employee Types</h3> 
        <?php 
            $empTypeSql = "select * from employeeTypes";
            $empresult = mysqli_query($link, $empTypeSql);
            if (!mysqli_query($link,$empTypeSql)) {
                echo("Error description: " . mysqli_error($link));
            } else {
                if ($empresult->num_rows === 0) {
                    echo "There are no employee types yet.";
                } else {
            ?>
            <table>
                <thead>
                    <th>Code</th>
                    <th>Name</th>
                    <th>Edit</th>
                    <th>Delete</th>
                </thead>
            <?php 
                    while($row=  mysqli_fetch_assoc($empresult)) {
                        echo "<tr>";
                        echo "<td>".$row['code']."</td>";
                        echo "<td>".$row['name']."</td>";
                        echo '<td><button onClick="window.location.href=`accountSettings.php?id='.$row['id'].'`">E</button>';
                        echo '<td><button onClick="deleteFunction('.$row['id'].')">D</button></td>';
                        echo "</tr>";
                    }
                    echo "</table>";
        ?>
            <div id="updateEmpTypeError" style="color:red">
                <?php 
                    if (isset($_SESSION['updateEmpTypeError'])) {
                        echo $_SESSION['updateEmpTypeError'];
                    }
                ?>
            </div>
            
            <div id="updateEmpTypeSuccess" style="color:green">
                <?php 
                    if (isset($_SESSION['updateEmpTypeSuccess'])) {
                        echo $_SESSION['updateEmpTypeSuccess'];
                    }
                ?>
            </div>
        <?php
                }
            }
        ?>
        <form id='addEmpType' method='post' action='saveAccountSettings.php?add=1'>
            <fieldset >
            <div id="addEmpTypeError" style="color:red">
                <?php 
                    if (isset($_SESSION['addEmpTypeError'])) {
                        echo $_SESSION['addEmpTypeError'];
                    }
                ?>
            </div>
            
            <div id="addEmpTypeSuccess" style="color:green">
                <?php 
                    if (isset($_SESSION['addEmpTypeSuccess'])) {
                        echo $_SESSION['addEmpTypeSuccess'];
                    }
                ?>
            </div>
            <legend>Add/Edit Employee Type</legend>
            <input type='hidden' name='editid' id='editid' value='<?php 
                    if (isset($erow['id'])) {
                        echo $erow['id'];
                    }
                   ?>'/>
            <input type='hidden' name='submitted' id='submitted' value='1'/>
            <label for='code' >Code*:</label>
            <input type='text' name='code' id='code'  maxlength="50" value='<?php 
                    if (isset($erow['code'])) {
                        echo $erow['code'];
                    }
                   ?>'/>
            <br>
            <label for='name' >Name*:</label>
            <input type='text' name='name' id='name'  maxlength="50" value='<?php 
                    if (isset($erow['name'])) {
                        echo $erow['name'];
                    }
                   ?>'/>
            <br>
            <input type='submit' name='submit' value='Submit' />
            </fieldset>
        </form> 
                    
        <form id='accountSettings' action='saveAccountSettings.php?save=1' method='post'>
        <h3>Manage employee restrictions</h3>  
        <div id="accSetSuccess" style='color:green'>
            <?php
                if (isset($_SESSION['updateAccSetSuccess'])) {
                    echo $_SESSION['updateAccSetSuccess'];
                }
            ?>
        </div>
            <table>
                <thead>
                <th>Type</th>
                <th>Access Rights</th>
                </thead>
                <?php 
                    $empTypeResult = mysqli_query($link, $empTypeSql);
                    if (!mysqli_query($link,$empTypeSql)) {
                        echo("Error description: " . mysqli_error($link));
                    } else {
                        if ($empTypeResult->num_rows === 0) {
                            echo "No employee types created yet.";
                        } else {
                            $count = 0;
                            while ($row1 = mysqli_fetch_assoc($empTypeResult)) {
                                $str = $row1['code']."=";
                                $checkArr = explode($str, $valArr[$count]);
                                $count++;
                                $accessArr;
                                if (!empty($checkArr[1])) {
                                    $accessArr = explode(",", $checkArr[1]);
                                }
                                echo "<tr>";
                                echo "<td>".$row1['name']."</td>";
                                
                    ?>
                        <td>
                            <input type="checkbox" name="<?php echo $row1['code']; ?>[]" value='cust'
                                   <?php 
                                    if (in_array("cust", $accessArr)) {
                                        echo " checked";
                                    }
                                   ?>
                                   > Customers
                            <input type="checkbox" name="<?php echo $row1['code']; ?>[]" value='disc' 
                                   <?php 
                                    if (in_array("disc", $accessArr)) {
                                        echo " checked";
                                    }
                                   ?>
                                   > Discounts
                            <input type="checkbox" name="<?php echo $row1['code']; ?>[]" value='gift' 
                                   <?php 
                                    if (in_array("gift", $accessArr)) {
                                        echo " checked";
                                    }
                                   ?>
                                   > Gift Cards<br>
                            <input type="checkbox" name="<?php echo $row1['code']; ?>[]" value='inv' 
                                   <?php 
                                    if (in_array("inv", $accessArr)) {
                                        echo " checked";
                                    }
                                   ?>
                                   > Inventory
                            <input type="checkbox" name="<?php echo $row1['code']; ?>[]" value='loc' 
                                   <?php 
                                    if (in_array("loc", $accessArr)) {
                                        echo " checked";
                                    }
                                   ?>
                                   > Locations
                            <input type="checkbox" name="<?php echo $row1['code']; ?>[]" value='media'
                                   <?php 
                                    if (in_array("media", $accessArr)) {
                                        echo " checked";
                                    }
                                   ?> 
                                   > Media Gallery<br>
                            <input type="checkbox" name="<?php echo $row1['code']; ?>[]" value='orders' 
                                   <?php 
                                    if (in_array("orders", $accessArr)) {
                                        echo " checked";
                                    }
                                   ?>
                                   > Orders
                            <input type="checkbox" name="<?php echo $row1['code']; ?>[]" value='partners' 
                                   <?php 
                                    if (in_array("partners", $accessArr)) {
                                        echo " checked";
                                    }
                                   ?>
                                   > Partners
                            <input type="checkbox" name="<?php echo $row1['code']; ?>[]" value='products' 
                                   <?php 
                                    if (in_array("products", $accessArr)) {
                                        echo " checked";
                                    }
                                   ?>
                                   > Products<br>
                            <input type="checkbox" name="<?php echo $row1['code']; ?>[]" value='settings' 
                                   <?php 
                                    if (in_array("settings", $accessArr)) {
                                        echo " checked";
                                    }
                                   ?>
                                   > Settings
                            <input type="checkbox" name="<?php echo $row1['code']; ?>[]" value='stats' 
                                   <?php 
                                    if (in_array("statistics", $accessArr)) {
                                        echo " checked";
                                    }
                                   ?>
                                   > Statistics
                            <input type="checkbox" name="<?php echo $row1['code']; ?>[]" value='emp' 
                                   <?php 
                                    if (in_array("emp", $accessArr)) {
                                        echo " checked";
                                    }
                                   ?>
                                   > Users
                            <input type="checkbox" name="<?php echo $row1['code']; ?>[]" value='web' 
                                   <?php 
                                    if (in_array("web", $accessArr)) {
                                        echo " checked";
                                    }
                                   ?>
                                   > Web
                        </td>
                    <?php 
                            echo "</tr>";
                            }
                        }
                    }
                ?>
            </table>
            <input type='submit' name='submit' value='Save Changes' />
        </form>
                <h3>All Employees</h3> 
            <?php
                $empSql = "Select * from staff where email <> '".$_SESSION['loggedUserEmail']."'";
                
                $result = mysqli_query($link, $empSql);
                
                if (!mysqli_query($link,$empSql)) {
                    echo("Error description: " . mysqli_error($link));
                } else {
                    if ($result->num_rows === 0) {
                        echo "You have not created any employee accounts yet.<br>";
                        echo "Create an account <a href='users.php'>here</a>";
                    } else {
                    ?>
                    <table>
                        <thead>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Last Login</th>
                        <th>Last Logout</th>
                        </thead>
                    <?php
                        while ($row = mysqli_fetch_assoc($result)) {
                            echo "<tr>";
                            echo "<td>".$row['firstname']." ".$row['lastname']."</td>";
                            echo "<td>".$row['email']."</td>";
                            if (empty($row['lastlogin'])) {
                                echo "<td>-</td>"; 
                            } else {
                                echo "<td>".$row['lastlogin']."</td>";
                            }

                            if (empty($row['lastlogout'])) {
                                echo "<td>-</td>";
                            } else {
                                echo "<td>".$row['lastlogout']."</td>";
                            }
                            echo "</tr>";
                       } 
                    ?>
                        </table><br>
            <?php
                    }
                }
            ?>
        
        </div>
    </div>
    <script>
    function deleteFunction(empId) {
        var r = confirm("Are you sure you wish to delete this employee type?");
        if (r === true) {
            window.location="saveAccountSettings.php?delete=1&id=" + empId;
        } else if (r === false) {
            <?php
                unset($_SESSION['updateEmpTypeSuccess']);
                unset($_SESSION['updateAccSetSuccess']);
                unset($_SESSION['addEmpTypeSuccess']);
                unset($_SESSION['addEmpTypeError']);
                $_SESSION['updateEmpTypeError'] = "Nothing was deleted";
            ?>
            window.location='accountSettings.php';
        }
    }
    </script>
</html>
<?php } ?>