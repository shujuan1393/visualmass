<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
require_once '../config/db.php';

if (isset($_GET['id'])) {
    $selectSql = "Select * from partners where id ='" .$_GET['id']."';";
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
                            <li class="active">
                                Partners
                            </li>
                        </ol>
                        
                        <h1 class="page-header">Manage Partners</h1>
                        
                        <div id="updatePartnerSuccess" class="success">
                            <?php 
                                if (isset($_SESSION['updatePartnerSuccess'])) {
                                    echo $_SESSION['updatePartnerSuccess'];
                                }
                            ?>
                        </div>
                        <div id="updatePartnerError" class="error">
                            <?php 
                                if (isset($_SESSION['updatePartnerError'])) {
                                    echo $_SESSION['updatePartnerError'];
                                }
                            ?>
                        </div>
                        
                        <?php 
                            $qry = "Select * from partners";

                            $result = mysqli_query($link, $qry);

                            if (!mysqli_query($link,$qry))
                            {
                                echo("Error description: " . mysqli_error($link));
                            } else {
                                if ($result->num_rows === 0) {
                                    echo "You have not created any partners yet.";
                                } else {
                        ?>
                        
                        <p class="text-right">
                            <a href="#add"><i class="fa fa-fw fa-plus"></i> Add Partner</a>
                        </p>
                                        
                        <div class="pull-left filter-align">Filter: </div>
                        <div style="overflow:hidden">
                            <input type="text" id="filter" class="pull-right" placeholder="Type here to search">
                        </div>

                        <table id ="example">
                            <thead>
                                <th>Company</th>
                                <th>Contact</th>
                                <th>Type</th>
                                <th>Date Added</th>
                                <th>Edit</th>
                                <th>Delete</th>                        
                            </thead>
                            <tbody class="searchable">
                            <?php
                                // output data of each row
                                while ($row = mysqli_fetch_assoc($result)) {
                                    echo "<tr>";
                                    echo "<td>".$row['company'] ."</td>";
                                    echo "<td>".$row['contactname']."</td>";                            
                                    echo "<td>".$row['type']."</td>";                           
                                    echo "<td>".date("d M Y", strtotime($row['dateadded']))."</td>";                         
                                    echo '<td><button onClick="window.location.href=`partners.php?id='.$row['id'].'`">E</button>';
                                    echo '<td><button onClick="deleteFunction('.$row['id'].')">D</button></td>';
                                    echo "</tr>";
                                }
                            ?>
                            </tbody>
                        </table>
                        <?php
                            } 
                        }
                        ?>
        
                        <form id='addPartnerPost' action='processPartners.php' method='post' enctype="multipart/form-data">

                            <div id="addPartnerError" class="error">
                                <?php 
                                    if (isset($_SESSION['addPartnerError'])) {
                                        echo $_SESSION['addPartnerError'];
                                    }

                                    if (isset($_SESSION['uploadPartnerError'])) {
                                        echo $_SESSION['uploadPartnerError'];
                                    }
                                ?>
                            </div>
            
                            <p id='nanError' style="display: none;">Please enter numbers only</p>
                            <div id="addPartnerSuccess" class="success">
                                <?php 
                                    if (isset($_SESSION['addPartnerSuccess'])) {
                                        echo $_SESSION['addPartnerSuccess'];
                                    }
                                ?>
                            </div>
                            
                            <h1 id="add" class="page-header">Add/Edit Partner</h1>
            
                            <input type='hidden' name='submitted' id='submitted' value='1'/>
                            <input type='hidden' name='editid' id='editid' 
                                   value='<?php if (isset($_GET['id'])) { echo $erow['id']; }?>'/>
            
                            <table class="content">
                                <tr>
                                    <td colspan='2'>
                                        <div class="pull-left">Company*:</div>
                                        <input type='text' name='company' id='company'
                                               value='<?php if (!empty($erow['company'])) { echo $erow['company']; }?>'/>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        Contact Name*:
                                        <input type="text" id="contactname" name="contactname" value='<?php if(!empty($erow['contactname'])) {
                                            echo $erow['contactname'];
                                            } ?>'>
                                    </td>
                                    <td>
                                        Phone*:
                                        <input type="text" id="phone" name="phone" value='<?php if(!empty($erow['phone'])) {
                                            echo $erow['phone'];
                                            } ?>' onkeypress="return isNumber(event)">
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2">
                                        Address*:
                                        <input type='text' name='address' id='address'  maxlength="50" value ="<?php 
                                        if (!empty($erow['address'])) { 
                                            echo $erow['address']; }?>"/>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        Apt, suite:
                                        <input type='text' name='apt' id='apt'  maxlength="50" value ="<?php 
                                        if (!empty($erow['apt'])) { 
                                            echo $erow['apt']; }?>"/>
                                    </td>
                                    <td>
                                        ZIP Code*:
                                        <input type='text' name='zip' id='zip'  maxlength="50"  
                                               onkeypress="return isNumber(event)" value ="<?php 
                                        if (!empty($erow['zip'])) { 
                                            echo $erow['zip']; }?>"/>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        City:
                                        <input type='text' name='city' id='city'  maxlength="50" value ="<?php 
                                        if (!empty($erow['city'])) { 
                                            echo $erow['city']; }?>"/>
                                    </td>
                                    <td>
                                        Country*:
                                        <input type='text' name='country' id='country'  maxlength="50" value ="<?php 
                                        if (!empty($erow['country'])) { 
                                            echo $erow['country']; }?>"/>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        Contact Email*:
                                        <input type="text" id="email" name="email" value='<?php if(!empty($erow['email'])) {
                                            echo $erow['email'];
                                            } ?>'>
                                    </td>
                                    <td>
                                        Type*:
                                        <select name="type">
                                            <?php 
                                                $sql = "Select * from categories where type='product';";
                                                $res = mysqli_query($link, $sql);
                                                
                                                if(!mysqli_query($link, $sql)) {
                                                    die(mysqli_error($link));
                                                } else {
                                                    if($res -> num_rows > 0) {
                                                        while($row = mysqli_fetch_assoc($res)) {
                                                            echo "<option value='".$row['name']."'";
                                                            if (!empty($erow['type'])) {
                                                                if (strcmp($erow['type'], $row['name']) === 0) {
                                                                    echo " selected";
                                                                }
                                                            }
                                                            echo ">".$row['name']."</option>";
                                                        }
                                                    } else {
                                                        echo "<option value='null'>No product categories available</option>";
                                                    }
                                                }
                                            ?>
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan='2'>
                                        <input type='submit' name='submit' value='Save' />
                                    </td>
                                </tr>
                            </table>
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

    function deleteFunction(locId) {
        var r = confirm("Are you sure you wish to delete this partner?");
        if (r === true) {
            window.location="processPartners.php?delete=1&id=" + locId;
        } else if (r === false) {
            <?php
                unset($_SESSION['addPartnerError']);
                unset($_SESSION['addPartnerSuccess']);
                unset($_SESSION['updatePartnerSuccess']);
                $_SESSION['updatePartnerError'] = "Nothing was deleted";
            ?>
            window.location='partners.php';
        }
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
