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
<html lang="en">
    <?php require '../nav/adminHeader.php'; ?>
    <body>
        <div id="wrapper">
            <?php require '../nav/adminMenubar.php'; ?>
            
            <!-- Content -->
            <div id="page-wrapper">

            <div class="container-fluid">

                <!-- Page Heading -->
                <div class="row">
                    <div class="col-lg-12">
                        <ol class="breadcrumb">
                            <li>
                                <a href="home.php"><i class="fa fa-home"></i></a>
                            </li>
                            <li class="active">
                                Discounts
                            </li>
                        </ol>
                        
                        <h1 class="page-header">Manage Discounts</h1>
                        
                        <div id="updateDiscSuccess" class='success'>
                            <?php 
                                if (isset($_SESSION['updateDiscSuccess'])) {
                                    echo $_SESSION['updateDiscSuccess'];
                                }
                            ?>
                        </div>
                        <div id="updateDiscError" class='error'>
                            <?php 
                                if (isset($_SESSION['updateDiscError'])) {
                                    echo $_SESSION['updateDiscError'];
                                }
                            ?>
                        </div>

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

                        <p class="text-right">
                            <a href="#add"><i class="fa fa-fw fa-plus"></i> Add Discount</a>
                        </p>

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
                        
                        <h1 id="add" class="page-header">Add/Edit Discount</h1>
                        
                        <form id='addDiscount' action='processDiscounts.php' method='post'>
                            <div id="addDiscError" class='error'>
                                <?php 
                                    if (isset($_SESSION['addDiscError'])) {
                                        echo $_SESSION['addDiscError'];
                                    }
                                ?>
                            </div>
                            <p id='nanError' class='error' style="display: none;">Please enter numbers only</p>

                            <div id="addDiscSuccess"  class='success'>
                                <?php 
                                    if (isset($_SESSION['addDiscSuccess'])) {
                                        echo $_SESSION['addDiscSuccess'];
                                    }
                                ?>
                            </div>
                            
                        <table class='content'>
                            <tr>
                                <td>
                                    <input type='hidden' name='submitted' id='submitted' value='1'/>
                                    <input type='hidden' name='editid' id='editid' 
                                           value='<?php if (isset($_GET['id'])) { echo $erow['id']; }?>'/>
                                    Name:
                                    <input type='text' name='name' id='name'  maxlength="50" 
                                           value ="<?php 
                                    if (!empty($erow['name'])) {
                                        echo $erow['name'];
                                    }
                                        ?>"/>
                                </td>
                                <td>
                                    Discount Code*: <br/>
                                    <button type="button" onclick="randomString()" class="pull-right">Generate</button>
                                    <div style="overflow: hidden;" >
                                        <input type='text' name='code' id='code' value ="<?php 
                                            if(isset($_SESSION['randomString'])) { 
                                                echo $_SESSION['randomString']; } 
                                            if (!empty($erow['code'])) {
                                                echo $erow['code'];
                                            } ?>" maxlength="50" />
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    Amount*:
                                    <input type='text' name='amount' id='amount'  maxlength="50"  
                                           onkeypress="return isNumberKey(event)" value ="<?php 
                                            if (!empty($erow['amount'])) {
                                                echo $erow['amount'];
                                            }
                                        ?>"/>
                                </td>
                                <td>
                                    Limit*:
                                    <input type='text' name='limit' id='limit'  maxlength="50"  
                                           onkeypress="return isNumber(event)" value ="<?php 
                                            if (!empty($erow['disclimit'])) {
                                                echo $erow['disclimit'];
                                            }
                                        ?>"/>
                                </td>                                
                            </tr>
                            
                            <tr>
                                <td colspan="2">
                                    <?php
                                        if (!empty($erow['discusage'])) { 
                                            $usageArr = explode(",", $erow['discusage']);
                                        }
                                    ?>
                                    Usage*: <br/>
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
                                    Recurrence*:
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
                                    Status*:
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
                                    <input type="text" id="date3" name="date3" 
                                           value='<?php if (!empty($erow['start'])) { 
                                               echo $erow['start'];
                                            }?>'>
                                </td>
                                <td>
                                    End date:
                                    <input type="text" id="date4" name="date4"
                                           value='<?php if (!empty($erow['end'])) { 
                                               echo $erow['end'];
                                            }?>'>
                                </td>
                            </tr>
                                <td colspan='2'><input type='submit' name='submit' value='Submit' /></td>
                            </tr>
                            </form>
                        </table>
                    </div>
                </div>
                <!-- /.row -->

            </div>
            <!-- /.container-fluid -->

        </div>
        <!-- /#page-wrapper -->
    </div>
</html>

 <script>
    var myCalendar = new dhtmlXCalendarObject(["date3"]);
            myCalendar.hideTime();
    var myCalendar2 = new dhtmlXCalendarObject(["date4"]);
            myCalendar2.hideTime();

    function isNumberKey(evt) {
        var charCode = (evt.which) ? evt.which : event.keyCode
        if (charCode > 31 && (charCode < 48 || charCode > 57) && charCode != 46) {
            document.getElementById('nanError').style.display='block';
            document.getElementById('nanError').style.color='red';
            return false;
       }

        document.getElementById('nanError').style.display='none';
        return true;
    }
    
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