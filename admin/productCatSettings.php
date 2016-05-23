<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
require_once '../config/db.php';

if (isset($_GET['id'])) {
    unset($_SESSION['addProdCatError']);
    unset($_SESSION['addProdCatSuccess']);
    unset($_SESSION['updateProdCatError']);
    unset($_SESSION['updateProdCatSuccess']);
    $selectSql = "Select * from categories where id ='" .$_GET['id']."';";
    $eresult = mysqli_query($link, $selectSql);

    if (!mysqli_query($link,$selectSql))
    {
        echo("Error description: " . mysqli_error($link));
    } else {
        $erow = mysqli_fetch_assoc($eresult);
    }
}
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
            <h2>Settings - Product Categories</h2><br>
        <?php 
            $qry = "Select * from categories where type='product'";

            $result = mysqli_query($link, $qry);

            if (!mysqli_query($link,$qry))
            {
                echo("Error description: " . mysqli_error($link));
            } else {
                if ($result->num_rows === 0) {
                    echo "You have not created any product categories yet.";
                } else {
            ?>
            <table>
                <thead>
                    <th>Name</th>
                    <th>Edit</th>
                    <th>Delete</th>                        
                </thead>
            <?php
                // output data of each row
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<tr>";
                    echo "<td>".$row['name']."</td>";                          
                    echo '<td><button onClick="window.location.href=`productCatSettings.php?id='.$row['id'].'`">E</button>';
                    echo '<td><button onClick="deleteFunction('.$row['id'].')">D</button></td>';
                    echo "</tr>";
                }
            ?>
            </table>
            <?php
                } 
            }
            ?>  
            <div id="updateProdCatSuccess" style="color:green">
                <?php 
                    if (isset($_SESSION['updateProdCatSuccess'])) {
                        echo $_SESSION['updateProdCatSuccess'];
                    }
                ?>
            </div>
            <div id="updateProdCatError" style="color:red">
                <?php 
                    if (isset($_SESSION['updateProdCatError'])) {
                        echo $_SESSION['updateProdCatError'];
                    }
                ?>
            </div>
        
        <hr><br>
        
        <form id='addProdCat' action='processProdCat.php' method='post' accept-charset='UTF-8'>
            <fieldset >
                
            <div id="addProdCatError" style="color:red">
                <?php 
                    if (isset($_SESSION['addProdCatError'])) {
                        echo $_SESSION['addProdCatError'];
                    }
                ?>
            </div>
            
            <div id="addProdCatSuccess" style="color:green">
                <?php 
                    if (isset($_SESSION['addProdCatSuccess'])) {
                        echo $_SESSION['addProdCatSuccess'];
                    }
                ?>
            </div>
            <legend>Add/Edit Product Category</legend>
            <input type='hidden' name='submitted' id='submitted' value='1'/>
            <input type='hidden' name='editid' id='editid' 
                   value='<?php if (isset($_GET['id'])) { echo $erow['id']; }?>'/>
            <label for='name' >Name*:</label>
            <input type='text' name='name' id='name'  maxlength="50" 
                   value='<?php if (!empty($erow['name'])) 
                       { echo $erow['name']; }?>'/>
            <br>
            <input type='submit' name='submit' value='Submit' />
            </fieldset>
        </form>
        </div>
    </div>
    <script>
    function deleteFunction(empId) {
        var r = confirm("Are you sure you wish to delete this product category?");
        if (r === true) {
            window.location="processProdCat.php?delete=1&id=" + empId;
        } else if (r === false) {
            <?php
                unset($_SESSION['updateProdCatSuccess']);
                $_SESSION['updateProdCatError'] = "Nothing was deleted";
            ?>
            window.location='productCatSettings.php';
        }
    }
    </script>
</html>

