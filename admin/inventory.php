<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
session_start();
require '../config/db.php';
if (!isset($_SESSION['loggedUser'])) {
    header("Location: login.php");
} 

if (isset($_GET['id'])) {
    unset($_SESSION['updateInvError']);
    unset($_SESSION['updateInvSuccess']);
    $getSql = "Select * from inventory where id =". $_GET['id'];
    $res = mysqli_query($link, $getSql);
    $resrow = mysqli_fetch_assoc($res);    
}
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
        <h2>Inventory</h2>
        <br>
        <?php
            $sql = "Select * from inventory";
            $result = mysqli_query($link, $sql);
            
            if (!mysqli_query($link,$sql))
            {
                echo("Error description: " . mysqli_error($link));
            } else {
                if ($result->num_rows === 0) {
                    echo "There is no inventory.";
                } else {
            ?>
            <table>
                <thead>
                    <th>Product ID</th>
                    <th>Type</th>
                    <th>Price</th>
                    <th>Quantity</th>
                    <th>Update</th>                       
                </thead>
            <?php
                // output data of each row
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<tr>";
                    echo "<td>".$row['pid']."</td>";
                    echo "<td>".$row['type']."</td>";                            
                    echo "<td>".$row['price']."</td>";                           
                    echo "<td>".$row['quantity']."</td>";                        
                    echo '<td><button onClick="window.location.href=`inventory.php?id='.$row['id'].'`">E</button>';
//                    echo '<td><button onClick="window.location.href=`editInventory.php?id='.$row['id'].'`">E</button>';
                    echo "</tr>";
                }

            ?>
            </table>
            <?php
                } 
            }
            ?> 
        <br><hr>
        <form id='addInventory' action='processInventory.php' method='post' accept-charset='UTF-8'>
            <fieldset >
            <legend>Add Inventory</legend>
            <input type='hidden' name='submitted' id='submitted' value='1'/>
            
            <label for='code' >Product*:</label>
            <?php 
                $prodSql = "Select * from products";
                $prodResult = mysqli_query($link, $prodSql);
                
                echo "<select name='product'>";
                
                while ($row = mysqli_fetch_assoc($prodResult)) {
                    echo "<option value='".$row['pid']."' ";
                    if (isset($_GET['id'])) {
                        if (strcmp($resrow['pid'], $row['pid']) === 0) {
                            echo "selected";
                        }
                    }
                    
                    echo ">";
                    echo $row['name']." (".$row['pid'].")</option>";
                }
                echo "</select>";
            ?>
            <br>
            <?php 
                if (isset($_GET['id'])) {
            ?>
            <label for='price' >Price*:</label>
            <input type='text' name='price' id='price' 
                   <?php 
                    if (isset($_GET['id'])) {
                        echo "value='".$resrow['price']."'";
                    }
                   ?>
                   onkeypress="return isNumberKey(event)" />
            <br>
            <?php 
                }
            ?>
            <label for='qty' >Quantity*:</label>
            <input type='text' name='qty' id='qty' 
                   <?php 
                    if (isset($_GET['id'])) {
                        echo "value='".$resrow['quantity']."'";
                    }
                   ?>
                   onkeypress="return isNumber(event)" />
            <br>
            <p id='nanError' style="display: none;">Please enter numbers only</p>
            <input type='submit' name='submit' value='Submit' />
            <div id="updateInvSuccess" style="color:green">
                <?php 
                    if (isset($_SESSION['updateInvSuccess'])) {
                        echo $_SESSION['updateInvSuccess'];
                    }
                ?>
            </div>
            <div id="updateInvError" style="color:red">
                <?php 
                    if (isset($_SESSION['updateInvError'])) {
                        echo $_SESSION['updateInvError'];
                    }
                ?>
            </div>
            </fieldset>
        </form>
        </div>
    </div>
    <script>
        
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
    </script>
</html>

