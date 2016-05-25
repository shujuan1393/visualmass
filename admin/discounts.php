<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
require_once '../config/db.php';

if (isset($_GET['id'])) {
    $selectSql = "Select * from discounts where id ='" .$_GET['id']."';";
    $eresult = mysqli_query($link, $selectSql);

    if (!mysqli_query($link,$selectSql))
    {
        echo("Error description: " . mysqli_error($link));
    } else {
        $erow = mysqli_fetch_assoc($eresult);
    }
}
?>
<!DOCTYPE html>
<html>
    <div id="frameheader">
        <?php
            require '../nav/adminHeader.php';
        ?>
    </div>
    <div id="framecontent">
        <?php
            require '../nav/adminSidebar.php';
        ?>
    </div>
    <div id="maincontent">
        <div class="innertube">
        <h2>Manage Discounts</h2>
        
        <?php 
            $qry = "Select * from discounts";
            
            $result = mysqli_query($link, $qry);

            if (!mysqli_query($link,$qry))
            {
                echo("Error description: " . mysqli_error($link));
            } else {
                if ($result->num_rows === 0) {
                    echo "You have not created any discounts yet.";
                } else {
            ?>
        <div style='float: right;'><a href='#addDiscount'>+ Add Discount</a></div>
            <table>
                <thead>
                    <th>Discount Name</th>
                    <th>Use Limit</th>
                    <th>Recurrence</th>
                    <th>Usage (C/E)</th>
                    <th>Validity</th>
                    <th>Status</th>
                    <th>Edit</th>
                    <th>Delete</th>                        
                </thead>
            <?php
                // output data of each row
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<tr>";
                    echo "<td>".$row['name']."(".$row['code'].")</td>";                            
                    echo "<td>".$row['disclimit']."</td>";                           
                    echo "<td>".$row['recurrence']."</td>";                           
                    echo "<td>".$row['discusage']."</td>";                              
                    echo "<td>".date("d M Y", strtotime($row['start']))." to ".date("d M Y", strtotime($row['end']))."</td>";                           
                    echo "<td>".$row['status']."</td>";                        
                    echo '<td><button onClick="window.location.href=`discounts.php?id='.$row['id'].'`">E</button>';
                    echo '<td><button onClick="deleteFunction('.$row['id'].')">D</button></td>';
                    echo "</tr>";
                }
            ?>
            </table>
            <?php
                } 
            }
            ?>
            <div id="updateDiscSuccess" style="color:green">
                <?php 
                    if (isset($_SESSION['updateDiscSuccess'])) {
                        echo $_SESSION['updateDiscSuccess'];
                    }
                ?>
            </div>
            <div id="updateDiscError" style="color:red">
                <?php 
                    if (isset($_SESSION['updateDiscError'])) {
                        echo $_SESSION['updateDiscError'];
                    }
                ?>
            </div>
        
        <div class="content_container-2">
        <table class='content'>
            <tr>
                <td colspan='2'><div class="form_header">Add/Edit Discount</div></td>
            </tr>
            <tr>
                <td colspan='2'>
                <form id='addDiscount' action='processDiscounts.php' method='post'>
                    <div id="addDiscError" style="color:red">
                        <?php 
                            if (isset($_SESSION['addDiscError'])) {
                                echo $_SESSION['addDiscError'];
                            }
                        ?>
                    </div>
                    <p id='nanError' style="display: none;">Please enter numbers only</p>

                    <div id="addDiscSuccess" style="color:green">
                        <?php 
                            if (isset($_SESSION['addDiscSuccess'])) {
                                echo $_SESSION['addDiscSuccess'];
                            }
                        ?>
                    </div>
                </td>
            </tr>
            <tr>
                <td colspan='2'>
                    <input type='hidden' name='submitted' id='submitted' value='1'/>
                    <input type='hidden' name='editid' id='editid' 
                           value='<?php if (isset($_GET['id'])) { echo $erow['id']; }?>'/>
                    <label for='name' >Name:</label>
                    <input type='text' name='name' id='name'  maxlength="50" 
                           value ="<?php 
                    if (!empty($erow['name'])) {
                        echo $erow['name'];
                    }
                        ?>"/>
                </td>
            </tr>
            <tr>
                <td colspan='2'>
                    <label for='code' >Discount Code*:</label>
                    <input type='text' name='code' id='code' value ="<?php 
                    if(isset($_SESSION['randomString'])) { 
                        echo $_SESSION['randomString']; } 
                    if (!empty($erow['code'])) {
                        echo $erow['code'];
                    }
                        ?>" maxlength="50" />
                    <button type='button' onclick="randomString()">Generate</button>
                </td>
            </tr>
            <tr>
                <td>
                    <label for='limit' >Limit*:</label>
                    <input type='text' name='limit' id='limit'  maxlength="50"  
                           onkeypress="return isNumber(event)" value ="<?php 
                            if (!empty($erow['disclimit'])) {
                                echo $erow['disclimit'];
                            }
                        ?>"/>
                </td>
                <td>
                    <?php
                        if (!empty($erow['discusage'])) { 
                            $usageArr = explode(",", $erow['discusage']);
                        }
                    ?>
                    <label for='usage' >Usage*:</label>
                    <input type='checkbox' name='usage[]' value="cust" <?php 
                            if (!empty($erow['discusage'])) {
                                if (in_array("cust", $usageArr)) {
                                    echo " checked";
                                }
                            }
                        ?>>Customer 
                    <input type='checkbox' name='usage[]' value='emp' <?php 
                            if (!empty($erow['discusage'])) {
                                if (in_array("emp", $usageArr)) {
                                    echo " checked";
                                }
                            }
                        ?>>Employee
                </td>
            </tr>
            <tr>
                <td>
                    <label for='recurrence' >Recurrence*:</label>
                    <select name='recurrence'>
                        <option value='adhoc' <?php 
                            if (!empty($erow['recurrence'])) {
                                if (strcmp($erow['recurrence'], "adhoc") === 0) {
                                    echo " selected";
                                }
                            }
                        ?>>Ad-hoc</option>
                        <option value='weekly' <?php 
                            if (!empty($erow['recurrence'])) {
                                if (strcmp($erow['recurrence'], "weekly") === 0) {
                                    echo " selected";
                                }
                            }
                        ?>>Weekly</option>
                        <option value='monthly' <?php 
                            if (!empty($erow['recurrence'])) {
                                if (strcmp($erow['recurrence'], "monthly") === 0) {
                                    echo " selected";
                                }
                            }
                        ?>>Monthly</option>
                        <option value='yearly' <?php 
                            if (!empty($erow['recurrence'])) {
                                if (strcmp($erow['recurrence'], "yearly") === 0) {
                                    echo " selected";
                                }
                            }
                        ?>>Yearly</option>
                    </select>
                </td>
                <td>
                    <label for='status' >Status*:</label>
                    <select name='status'>
                        <option value='active' <?php 
                            if (!empty($erow['status'])) {
                                if (strcmp($erow['status'], "active") === 0) {
                                    echo " selected";
                                }
                            }
                        ?>>Active</option>
                        <option value='inactive' <?php 
                            if (!empty($erow['status'])) {
                                if (strcmp($erow['status'], "inactive") === 0) {
                                    echo " selected";
                                }
                            }
                        ?>>Inactive</option>
                    </select>
                </td>
            </tr>
            <tr>
                <td>
                    Start date:
                    <input type="text" id="date3" name="date3">
                </td>
                <td>
                    End date:
                    <input type="text" id="date4" name="date4">
                </td>
            </tr>
            <tr>
                <td colspan='2'><input type='submit' name='submit' value='Submit' /></td>
            </tr>
        </table>
        </div>
        </form>
        </div>
    </div>
    <script>
        var myCalendar = new dhtmlXCalendarObject(["date3"]);
                myCalendar.hideTime();
        var myCalendar2 = new dhtmlXCalendarObject(["date4"]);
                myCalendar2.hideTime();
                
        function isNumber(evt) {
            evt = (evt) ? evt : window.event;
            var charCode = (evt.which) ? evt.which : evt.keyCode;
            if (charCode > 31 && (charCode < 48 || charCode > 57)) {
                document.getElementById('nanError').style.display='block';
                document.getElementById('nanError').style.color='red';
                return false;
            }
            document.getElementById('nanError').style.display='none';
            return true;
        }
        function randomString() {
            var text = "";
            var possible = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";

            for( var i=0; i < 10; i++ )
                text += possible.charAt(Math.floor(Math.random() * possible.length));

            document.getElementById('code').value = text;
            return false;
        }
        
        function deleteFunction(locId) {
            var r = confirm("Are you sure you wish to delete this discount?");
            if (r === true) {
                window.location="processDiscounts.php?delete=1&id=" + locId;
            } else if (r === false) {
                <?php
                    unset($_SESSION['addDiscError']);
                    unset($_SESSION['addDiscSuccess']);
                    unset($_SESSION['updateDiscSuccess']);
                    $_SESSION['updateDiscError'] = "Nothing was deleted";
                ?>
                window.location='discounts.php';
            }
        }
    </script>
    
</html>

