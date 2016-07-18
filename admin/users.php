<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
require_once '../config/db.php';

if (isset($_GET['id'])) {
    $selectSql = "Select * from staff where id ='" .$_GET['id']."';";
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
                                Users
                            </li>
                        </ol>
                        
                        <ul class="nav nav-tabs" id="myTabs">
                            <li class="active"><a data-toggle="tab" href="#allemp">All Employees</a></li>
                            <li><a data-toggle="tab" href="#menu1">Manage Employees</a></li>
                        </ul>

                        <div class="tab-content">
                            <div id="allemp" class="tab-pane fade in active">
                                <h1 class="page-header">All Employees</h1>
                        
                                <div id="updateEmpSuccess" class="success">
                                    <?php 
                                        if (isset($_SESSION['updateSuccess'])) {
                                            echo $_SESSION['updateSuccess'];
                                        }
                                    ?>
                                </div>
                                <div id="updateEmpError" class="error">
                                    <?php 
                                        if (isset($_SESSION['updateError'])) {
                                            echo $_SESSION['updateError'];
                                        }
                                    ?>
                                </div>
                                
                                <p>
                                    <?php
                                        $empSql = "Select * from staff ";

                                        $result = mysqli_query($link, $empSql);

                                        if (!mysqli_query($link,$empSql)) {
                                            echo("Error description: " . mysqli_error($link));
                                        } else {
                                            if ($result->num_rows === 0) {
                                                echo "You have not created any employee accounts yet.<br>";
                                                echo "Create an account <a href='users.php'>here</a>";
                                            } else {
                                            ?>

                                            <div class="pull-left filter-align">Filter: </div>
                                            <div style="overflow:hidden">
                                                <input type="text" id="filter" class="pull-right" placeholder="Type here to search">
                                            </div>

                                            <table id ="example">
                                                <thead>
                                                    <th>Name</th>
                                                    <th>Email</th>
                                                    <th>Last Login</th>
                                                    <th>Last Logout</th>
                                                </thead>
                                                <tbody class="searchable">
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
                                                </tbody>
                                            </table>
                                    <?php
                                            }
                                        }
                                    ?>
                                </p>
                            </div>
                            
                            <div id="menu1" class="tab-pane fade">
                                
                                <h1 class="page-header">Manage Employees</h1>
                                <p>
                                    <?php 
                                        $qry = "Select * from staff where email <> '".$_SESSION['loggedUserEmail']."'";

                                        $result = mysqli_query($link, $qry);

                                        if (!mysqli_query($link,$qry))
                                        {
                                            echo("Error description: " . mysqli_error($link));
                                        } else {
                                            if ($result->num_rows === 0) {
                                                echo "You have not created any employees accounts yet.";
                                            } else {
                                        ?>
                                        
                                        <p class="text-right">
                                            <a href="#add"><i class="fa fa-fw fa-plus"></i> Add Employee</a>
                                        </p>
                                        
                                        <div class="pull-left filter-align">Filter: </div>
                                        <div style="overflow:hidden">
                                            <input type="text" id="filter2" class="pull-right" placeholder="Type here to search">
                                        </div>

                                        <table id ="example2">
                                            <thead>
                                                <th>Name</th>
                                                <th>Email</th>
                                                <th>Employee Type</th>
                                                <th>Edit</th>
                                                <th>Delete</th>                        
                                            </thead>
                                            <tbody class="searchable">
                                            <?php
                                                // output data of each row
                                                while ($row = mysqli_fetch_assoc($result)) {
                                                    echo "<tr>";
                                                    echo "<td>".$row['firstname'] . " " . $row['lastname']."</td>";
                                                    echo "<td>".$row['email']."</td>";                           
                                                    echo "<td>".$row['type']."</td>";                         
                                                    echo '<td><button onClick="window.location.href=`users.php?id='.$row['id'].'#menu1`">E</button>';
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

                                    <form id='addUser' action='processUsers.php' method='post' accept-charset='UTF-8'>
                                        
                                        <h1 id="add" class="page-header">Add/Edit Employee Account</h1>
                                        
                                        <div id="addUserError" class="error">
                                            <?php 
                                                if (isset($_SESSION['addEmpError'])) {
                                                    echo $_SESSION['addEmpError'];
                                                }
                                            ?>
                                        </div>

                                        <div id="addEmpSuccess" class="success">
                                            <?php 
                                                if (isset($_SESSION['addEmpSuccess'])) {
                                                    echo $_SESSION['addEmpSuccess'];
                                                }
                                            ?>
                                        </div>
                                        
                                        <input type='hidden' name='submitted' id='submitted' value='1'/>
                                        <input type='hidden' name='editid' id='editid' 
                                               value='<?php if (isset($_GET['id'])) { echo $erow['id']; }?>'/>
                                        
                                        <table class="content">
                                            <tr>
                                                <td>
                                                    First Name*:
                                                    <input type='text' name='firstName' id='firstName'  maxlength="50" 
                                                           value='<?php 
                                                           if (isset($_SESSION['firstname'])) {
                                                               echo $_SESSION['firstname'];
                                                           } else if (!empty($erow['firstname'])) {
                                                               echo $erow['firstname'];
                                                           }  
                                                           ?>'/>
                                                </td>
                                                <td>
                                                    Last Name*:
                                                    <input type='text' name='lastName' id='lastName'  maxlength="50" 
                                                           value='<?php 
                                                           if (isset($_SESSION['lastname'])) {
                                                               echo $_SESSION['lastname'];
                                                           } else if (!empty($erow['lastname'])) {
                                                               echo $erow['lastname'];
                                                           }  
                                                           ?>'/>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    Email*:
                                                    <input type='text' name='email' id='email'  maxlength="50" 
                                                           value='<?php 
                                                           if (isset($_SESSION['email'])) {
                                                               echo $_SESSION['email'];
                                                           } else if (!empty($erow['email'])) {
                                                               echo $erow['email'];
                                                           }  
                                                           ?>'/>
                                                </td>
                                                <td>
                                                    Type*:
                                                    <select name="type">
                                                        <?php 
                                                            $emptypeSql = "Select * from employeeTypes";
                                                            $typeresult = mysqli_query($link, $emptypeSql);

                                                            if (!mysqli_query($link,$emptypeSql)) {
                                                                echo("Error description: " . mysqli_error($link));
                                                            } else {
                                                                if ($typeresult->num_rows === 0) {
                                                                    echo "You have not created any employee types yet.";
                                                                } else {
                                                                    while ($row1 = mysqli_fetch_assoc($typeresult)) {
                                                                        echo "<option value='".$row1['code']."'";
                                                                        if (isset($_SESSION['type'])) {
                                                                            if(strcmp($_SESSION['type'], $row1['code']) === 0) {
                                                                                echo " selected";
                                                                            }
                                                                        } else if (!empty($erow['type'])) {
                                                                            if (strcmp($erow['type'], $row1['code']) === 0) {
                                                                                echo " selected";
                                                                            } 
                                                                        }  
                                                                        echo ">".$row1['name']."</option>";
                                                                    }
                                                                }
                                                            }
                                                        ?>
                                                    </select>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="2">
                                                    <input type='submit' name='submit' value='Save' />
                                                </td>
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

<script>
    function deleteFunction(empId) {
        var r = confirm("Are you sure you wish to delete this employee's account?");
        if (r === true) {
            window.location="processUsers.php?delete=1&id=" + empId;
        } else if (r === false) {
            <?php
//                unset($_SESSION['updateSuccess']);
//                unset($_SESSION['addEmpSuccess']);
//                unset($_SESSION['addEmpError']);
//                $_SESSION['updateError'] = "Nothing was deleted";
            ?>
            window.location='users.php#menu1';
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

