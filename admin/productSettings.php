<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
require_once '../config/db.php';

if (isset($_GET['id']) && strcmp($_GET['type'], "category") === 0) {
    $selectSql = "Select * from categories where id ='" .$_GET['id']."';";
    $eresult = mysqli_query($link, $selectSql);

    if (!mysqli_query($link,$selectSql))
    {
        echo("Error description: " . mysqli_error($link));
    } else {
        $erow = mysqli_fetch_assoc($eresult);
    }
} else if (isset($_GET['id']) && strcmp($_GET['type'], "material") === 0) {
    $selectSql = "Select * from materials where id ='" .$_GET['id']."';";
    $mresult = mysqli_query($link, $selectSql);

    if (!mysqli_query($link,$selectSql))
    {
        echo("Error description: " . mysqli_error($link));
    } else {
        $mrow = mysqli_fetch_assoc($mresult);
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
            
                        <ul class="nav nav-tabs" id="myTabs">
                            <li id='newCat' class="active"><a data-toggle="tab" href="#prodcat">Product Categories</a></li>
                            <li id="newMat"><a data-toggle="tab" href="#prodMat">Product Materials</a></li>
                        </ul>
                        
                        <div class="tab-content">
                            <div id="prodcat" class="tab-pane fade in active">
                            <h1 class="page-header">Manage Product Categories</h1>

                            <div id="updateProdCatSuccess" class="success">
                                <?php 
                                    if (isset($_SESSION['updateProdCatSuccess'])) {
                                        echo $_SESSION['updateProdCatSuccess'];
                                    }
                                ?>
                            </div>
                            <div id="updateProdCatError" class="error">
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
                                        echo '<td><button onClick="window.location.href=`productSettings.php?type=category&id='.$row['id'].'#add`">E</button>';
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

                                <div id="addProdCatError" class="error">
                                    <?php 
                                        if (isset($_SESSION['addProdCatError'])) {
                                            echo $_SESSION['addProdCatError'];
                                        }
                                    ?>
                                </div>

                                <div id="addProdCatSuccess" class="success">
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
                            <div id="prodMat" class="tab-pane fade">
                            <h1 class="page-header">Manage Product Materials</h1>
                            
                                <div id="updateProdMatSuccess" class="success">
                                    <?php 
                                        if (isset($_SESSION['updateProdMatSuccess'])) {
                                            echo $_SESSION['updateProdMatSuccess'];
                                        }
                                    ?>
                                </div>
                                <div id="updateProdMatError" class="error">
                                    <?php 
                                        if (isset($_SESSION['updateProdMatError'])) {
                                            echo $_SESSION['updateProdMatError'];
                                        }
                                    ?>
                                </div>

                                <?php 
                                    $matqry = "Select * from materials";

                                    $mresult = mysqli_query($link, $matqry);

                                    if (!mysqli_query($link,$matqry))
                                    {
                                        echo("Error description: " . mysqli_error($link));
                                    } else {
                                        if ($mresult->num_rows === 0) {
                                            echo "You have not created any product materials yet.";
                                        } else {
                                ?>

                                <p class="text-right">
                                    <a href="#addMat"><i class="fa fa-fw fa-plus"></i> Add Material</a>
                                </p>

                                <table>
                                    <thead>
                                        <th>Name</th>
                                        <th>Edit</th>
                                        <th>Delete</th>                        
                                    </thead>
                                    <?php
                                        // output data of each row
                                        while ($row = mysqli_fetch_assoc($mresult)) {
                                            echo "<tr>";
                                            echo "<td>".$row['name']."</td>";                          
                                            echo '<td><button onClick="window.location.href=`productSettings.php?type=material&id='.$row['id'].'#prodMat`">E</button>';
                                            echo '<td><button onClick="deleteMatFunction('.$row['id'].')">D</button></td>';
                                            echo "</tr>";
                                        }
                                    ?>
                                </table>
                                <?php
                                    } 
                                }
                                ?>  

                                <form id='addProdMat' action='processProdMat.php' method='post' accept-charset='UTF-8' enctype="multipart/form-data">

                                    <div id="addProdMatError" class="error">
                                        <?php 
                                            if (isset($_SESSION['addProdMatError'])) {
                                                echo $_SESSION['addProdMatError'];
                                            }
                                        ?>
                                    </div>

                                    <div id="addProdMatSuccess" class="success">
                                        <?php 
                                            if (isset($_SESSION['addProdMatSuccess'])) {
                                                echo $_SESSION['addProdMatSuccess'];
                                            }
                                        ?>
                                    </div>

                                    <h1 id="addMat" class="page-header">Add/Edit Product Material</h1>

                                    <input type='hidden' name='submitted' id='submitted' value='1'/>
                                    <input type='hidden' name='editid' id='editid' 
                                           value='<?php if (isset($_GET['id'])) { echo $mrow['id']; }?>'/>

                                    Name*:
                                    <input type='text' name='name' id='name'  maxlength="50" 
                                           value='<?php if (!empty($mrow['name'])) 
                                               { echo $mrow['name']; }?>'/>
                                    <br>
                                    
                                    Details*:
                                    <textarea name="details" id="details"><?php if(!empty($mrow['details'])) {
                                        echo $mrow['details']; }?></textarea>
                                    <script>
                                        CKEDITOR.replace('details');
                                    </script>
                                    <br>
                                    <?php 
                                        if (!empty($mrow['image'])) {
                                            echo "<img src='".$mrow['image']."' width='150'>";
                                            echo "<input type='hidden' name='oldImage' value='".$mrow['image']."'>";
                                        }
                                        ?><br>
                                    Image:
                                    <input type="file" name="image" id='image' accept="image/*" />
                                    <br>
                                    
                                    <input type='submit' name='submit' value='Submit' />
                                </form>
                            
                            </div>
                        </div>
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
            window.location='productSettings.php';
        }
    }
    function deleteMatFunction(empId) {
        var r = confirm("Are you sure you wish to delete this product material?");
        if (r === true) {
            window.location="processProdMat.php?delete=1&id=" + empId;
        } else if (r === false) {
            <?php
                unset($_SESSION['updateProdMatSuccess']);
                $_SESSION['updateProdMatError'] = "Nothing was deleted";
            ?>
            window.location='productSettings.php#prodMat';
        }
    }
    
    $(document).ready(function() {
        if(location.hash) {
            $('a[href=' + location.hash + ']').tab('show');
        }
        $(document.body).on("click", "a[data-toggle]", function(event) {
            location.hash = this.getAttribute("href");
        });
    });
    $(window).on('popstate', function() {
        var anchor = location.hash || $("a[data-toggle=tab]").first().attr("href");
        $('a[href=' + anchor + ']').tab('show');
    });
</script>