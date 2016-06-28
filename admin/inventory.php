<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
require_once '../config/db.php';
if (!isset($_SESSION['loggedUser'])) {
    header("Location: login.php");
} 

if (isset($_GET['id'])) {
    $getSql = "Select * from inventory where id =". $_GET['id'];
    $res = mysqli_query($link, $getSql);
    $resrow = mysqli_fetch_assoc($res);    
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
                            <li class="active">
                                Inventory
                            </li>
                        </ol>
                        
                        <h1 class="page-header">Manage Inventory</h1>
                        
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
                        
                        <p class="text-right">
                            <a href="#add"><i class="fa fa-fw fa-plus"></i> Add Inventory</a>
                        </p>
                        
                        <div class="pull-left filter-align">Filter: </div>
                        <div style="overflow:hidden">
                            <input type="text" id="filter" class="pull-right" placeholder="Type here to search">
                        </div>
                        
                        <table id ="example">
                            <thead>
                                <th>Product ID</th>
                                <th>Location</th>
                                <th>Type</th>
                                <th>Price</th>
                                <th>Quantity</th>
                                <th>Update</th>                       
                            </thead>
                            <tbody class="searchable">
                            <?php
                                // output data of each row
                                while ($row = mysqli_fetch_assoc($result)) {
                                    echo "<tr>";
                                    echo "<td>";
                                    $getprod = "select * from products where pid='".$row['pid']."';";
                                    $prodres = mysqli_query($link, $getprod);
                                    
                                    if (!mysqli_query($link, $getprod)) {
                                        die(mysqli_error($link));
                                    } else {
                                        if ($prodres -> num_rows === 0) {
                                            echo "-";
                                        } else {
                                            $prow = mysqli_fetch_assoc($prodres);
                                            echo $prow['name']." (".$prow['pid'].")";
                                        }
                                    }
                                    echo "</td>";
                                    
                                    
                                    echo "<td>";
                                    if (!empty($row['location'])){
                                        $getloc = "select * from locations where code='".$row['location']."';";
                                        $locres = mysqli_query($link, $getloc);
                                    
                                        if (!mysqli_query($link, $getloc)) {
                                            die(mysqli_error($link));
                                        } else {
                                            if ($locres -> num_rows === 0) {
                                                echo "-";
                                            } else {
                                                $lrow = mysqli_fetch_assoc($locres);
                                                echo $lrow['name']." (".$lrow['code'].")";
                                            }
                                        }
                                    }
                                    echo "</td>"; 
                                    
                                    echo "<td>".$row['type']."</td>";                            
                                    echo "<td>".$row['price']."</td>";                           
                                    echo "<td>".$row['quantity']."</td>";                        
                                    echo '<td><button onClick="window.location.href=`inventory.php?id='.$row['id'].'`">E</button>';
                //                    echo '<td><button onClick="window.location.href=`editInventory.php?id='.$row['id'].'`">E</button>';
                                    echo "</tr>";
                                }

                            ?>
                            </tbody>
                        </table>
                        
                        <?php
                            } 
                        }
                        ?>
                        
                        <form id='addInventory' action='processInventory.php' method='post' accept-charset='UTF-8'>
                            
                            <div id="updateInvSuccess" class="success">
                                <?php 
                                    if (isset($_SESSION['updateInvSuccess'])) {
                                        echo $_SESSION['updateInvSuccess'];
                                    }
                                ?>
                            </div>
                            <div id="updateInvError" class="error">
                                <?php 
                                    if (isset($_SESSION['updateInvError'])) {
                                        echo $_SESSION['updateInvError'];
                                    }
                                ?>
                            </div>
                            
                            
                            
                            <input type='hidden' name='submitted' id='submitted' value='1'/>
                            
                            <h1 id="add" class="page-header">Add/Edit Inventory</h1>
                            
                            <table class="content">
                                <tr>
                                    <td colspan="2">
                                        Product*:
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
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2">
                                        Location*:
                                        <?php 
                                            $locSql = "Select * from locations where name <> 'banner'";
                                            $locres = mysqli_query($link, $locSql);

                                            echo "<select name='location'>";

                                            while ($row = mysqli_fetch_assoc($locres)) {
                                                echo "<option value='".$row['code']."' ";
                                                if (isset($_GET['id'])) {
                                                    if (strcmp($resrow['location'], $row['code']) === 0) {
                                                        echo "selected";
                                                    }
                                                }

                                                echo ">";
                                                echo $row['name']."</option>";
                                            }
                                            echo "</select>";
                                        ?>
                                    </td>
                                </tr>
                                <tr>
                                <tr>
                                <?php 
//                                    if (isset($_GET['id'])) {
                                ?>
                                    <td>

                                    Price*:
                                    <input type='text' name='price' id='price' 
                                           <?php 
                                            if (isset($_GET['id'])) {
                                                echo "value='".$resrow['price']."'";
                                            }
                                           ?>
                                           onkeypress="return isNumberKey(event)" />

                                    </td>
                                    <td>
                                <?php 
//                                    }
//                                    else {
//                                        echo "<td colspan='2'>";
//                                    }
                                ?>
                                        Quantity*:
                                        <input type='text' name='qty' id='qty' 
                                               <?php 
                                                if (isset($_GET['id'])) {
                                                    echo "value='".$resrow['quantity']."'";
                                                }
                                               ?>
                                               onkeypress="return isNumber(event)" />
                                        <p id='nanError' style="display: none;">Please enter numbers only</p>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <input type='submit' name='submit' value='Save Changes' />
                                    </td>
                                </tr>
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
    
    $("#filter").keyup(function () {
        var search = $(this).val();
        $(".searchable").children().show();
        $('.noresults').remove();
        if (search) {
            $(".searchable").children().not(":containsNoCase(" + search + ")").hide();
            $(".searchable").each(function () {
                if ($(this).children(':visible').length === 0) 
                    $(this).append('<tr class="noresults"><td colspan="100%">No matching results found</td></tr>');
            });

        }
    });
    
    $.expr[":"].containsNoCase = function (el, i, m) {
        var search = m[3];
        if (!search) return false;
           return new RegExp(search,"i").test($(el).text());
    };
    
    $(document).ready(function() {
        $('#example').DataTable({
            dom: "<'row'tr>" +
                "<'row'<'col-sm-5'i><'col-sm-7'p>>"
        });
    });
</script>