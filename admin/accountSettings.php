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
                            <li>
                                Accounts
                            </li>
                        </ol>
                        
                        <ul class="nav nav-tabs" id="myTabs">
                            <li class="active"><a data-toggle="tab" href="#emprest">Employee Restrictions</a></li>
                            <li><a data-toggle="tab" href="#menu1">Employee Types</a></li>
                        </ul>

                        <div class="tab-content">
                            <div id="emprest" class="tab-pane fade in active">
                                <h1 class="page-header">Manage Employee Restrictions</h1>
                                <p>
                                    <form id='accountSettings' action='saveAccountSettings.php?save=1' method='post'>
                                        <div id="accSetSuccess" style='color:green'>
                                            <?php
                                                if (isset($_SESSION['updateAccSetSuccess'])) {
                                                    echo $_SESSION['updateAccSetSuccess'];
                                                }
                                            ?>
                                        </div>
                                        
                                        <?php 
                                            $empTypeSql = "select * from employeeTypes";
                                            $empTypeResult = mysqli_query($link, $empTypeSql);
                                            if (!mysqli_query($link,$empTypeSql)) {
                                                echo("Error description: " . mysqli_error($link));
                                            } else {
                                                if ($empTypeResult->num_rows === 0) {
                                                    echo "No employee types created yet.";
                                                } else {
                                        ?>
                                        <table class="content-table">
                                            <thead>
                                            <th>Roles</th>
                                            <th>Access Rights</th>
                                            </thead>
                                            <?php
                                                $count = 0;
                                                while ($row1 = mysqli_fetch_assoc($empTypeResult)) {
                                                    $str = $row1['code']."=";
                                                    if (stripos($savedrow['value'], $str) !== FALSE) {
                                                        $checkArr = explode($str, $valArr[$count]);
                                                        $count++;
                                                        $accessArr;
                                                        if (!empty($checkArr[1])) {
                                                            $accessArr = explode(",", $checkArr[1]);
                                                        }else{
                                                            $accessArr = array();
                                                        }
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
                                                       > Gift Cards
                                                <input type="checkbox" name="<?php echo $row1['code']; ?>[]" value='inv' 
                                                       <?php 
                                                        if (in_array("inv", $accessArr)) {
                                                            echo " checked";
                                                        }
                                                       ?>
                                                       > Inventory
                                                <input type="checkbox" name="<?php echo $row1['code']; ?>[]" value='career' 
                                                       <?php 
                                                        if (in_array("career", $accessArr)) {
                                                            echo " checked";
                                                        }
                                                       ?>
                                                       > Jobs
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
                                                       > Products
                                                <input type="checkbox" name="<?php echo $row1['code']; ?>[]" value='settings' 
                                                       <?php 
                                                        if (in_array("settings", $accessArr)) {
                                                            echo " checked";
                                                        }
                                                       ?>
                                                       > Settings
                                                <input type="checkbox" name="<?php echo $row1['code']; ?>[]" value='stats' 
                                                       <?php 
                                                        if (in_array("stats", $accessArr)) {
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
                                            ?>
                                            <tr>
                                                <td colspan="2">
                                                    <input type='submit' name='submit' value='Save' />
                                                </td>
                                            </tr>
                                        </table>
                                        <?php
                                                }
                                            }
                                        ?>
                                    </form>
                                </p>
                            </div>
                            
                            <div id="menu1" class="tab-pane fade">
                                
                                <h1 class="page-header">Manage Employee Types</h1>
                                
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
                                
                                <p>
                                    <?php 
                                        $empresult = mysqli_query($link, $empTypeSql);
                                        if (!mysqli_query($link,$empTypeSql)) {
                                            echo("Error description: " . mysqli_error($link));
                                        } else {
                                            if ($empresult->num_rows === 0) {
                                                echo "There are no employee types yet.";
                                            } else {
                                    ?>
                                    
                                    <p class="text-right">
                                        <a href="#add"><i class="fa fa-fw fa-plus"></i> Add Types</a>
                                    </p>

                                    <div class="pull-left filter-align">Filter: </div>
                                    <div style="overflow:hidden">
                                        <input type="text" id="filter" class="pull-right" placeholder="Type here to search">
                                    </div>

                                    <table id ="example">
                                        <thead>
                                            <th>Code</th>
                                            <th>Name</th>
                                            <th>Edit</th>
                                            <th>Delete</th>
                                        </thead>
                                        <tbody class="searchable">
                                        <?php 
                                            while($row=  mysqli_fetch_assoc($empresult)) {
                                                echo "<tr>";
                                                echo "<td>".$row['code']."</td>";
                                                echo "<td>".$row['name']."</td>";
                                                echo '<td><button onClick="window.location.href=`accountSettings.php?id='.$row['id'].'#menu1`">E</button>';
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
                                        
                                    <form id='addEmpType' method='post' action='saveAccountSettings.php?add=1'>
                                        
                                        <h1 id="add" class="page-header">Add/Edit Employee Type</h1>
                                        
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
                                        
                                        <table class="content">
                                            <input type='hidden' name='editid' id='editid' value='<?php 
                                                    if (isset($erow['id'])) {
                                                        echo $erow['id'];
                                                    }
                                                   ?>'/>
                                            <input type='hidden' name='submitted' id='submitted' value='1'/>
                                            <tr>
                                                <td>
                                                    Code*:
                                                    <input type='text' name='code' id='code'  maxlength="50" value='<?php 
                                                            if (isset($erow['code'])) {
                                                                echo $erow['code'];
                                                            }
                                                           ?>'/>
                                                </td>
                                                <td>
                                                    Name*:
                                                    <input type='text' name='name' id='name'  maxlength="50" value='<?php 
                                                            if (isset($erow['name'])) {
                                                                echo $erow['name'];
                                                            }
                                                           ?>'/>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="2"><input type='submit' name='submit' value='Save' /></td>
                                            </tr>
                                        </table>
                                    </form> 
                                </p>
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

<?php } ?>

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