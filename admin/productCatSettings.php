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
                                <a href="index.php"><i class="fa fa-home"></i></a>
                            </li>
                            <li>
                                Settings
                            </li>
                            <li class="active">
                                Product Categories
                            </li>
                        </ol>
            
                        <h1 class="page-header">Manage Product Categories</h1>
                        
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
                        
                        <p class="text-right">
                            <a href="#add"><i class="fa fa-fw fa-plus"></i> Add Category</a>
                        </p>
                        
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
                        
                        <form id='addProdCat' action='processProdCat.php' method='post' accept-charset='UTF-8'>

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

                            <h1 id="add" class="page-header">Add/Edit Product Category</h1>

                            <input type='hidden' name='submitted' id='submitted' value='1'/>
                            <input type='hidden' name='editid' id='editid' 
                                   value='<?php if (isset($_GET['id'])) { echo $erow['id']; }?>'/>
                            
                            Name*:
                            <input type='text' name='name' id='name'  maxlength="50" 
                                   value='<?php if (!empty($erow['name'])) 
                                       { echo $erow['name']; }?>'/>
                            
                            <input type='submit' name='submit' value='Submit' />
                        </form>
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