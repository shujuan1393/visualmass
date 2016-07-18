<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
require_once '../config/db.php';

if (isset($_GET['id'])) {
    $selectSql = "Select * from jobs where id ='" .$_GET['id']."';";
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
                                Jobs
                            </li>
                        </ol>
                        
                        <h1 class="page-header">Manage Jobs</h1>
                        
                        <div id="updateJobSuccess" class="success">
                            <?php 
                                if (isset($_SESSION['updateJobSuccess'])) {
                                    echo $_SESSION['updateJobSuccess'];
                                }
                            ?>
                        </div>
                        <div id="updateJobError" class="error">
                            <?php 
                                if (isset($_SESSION['updateJobError'])) {
                                    echo $_SESSION['updateJobError'];
                                }
                            ?>
                        </div>
                       
                        <?php
                           $sql = "Select * from jobs";
                           $result = mysqli_query($link, $sql);

                           if (!mysqli_query($link,$sql))
                           {
                               echo("Error description: " . mysqli_error($link));
                           } else {
                               if ($result->num_rows === 0) {
                                   echo "You have not created any jobs yet.";
                               } else {
                        ?>
                        
                        <p class="text-right">
                            <a href="#add"><i class="fa fa-fw fa-plus"></i> Add Jobs</a>
                        </p>
                        
                        <div class="pull-left filter-align">Filter: </div>
                        <div style="overflow:hidden">
                            <input type="text" id="filter" class="pull-right" placeholder="Type here to search">
                        </div>
                        
                        <table id ="example">
                            <thead>
                                <th>Title</th>
                                <th>Status</th>
                                <th>Type</th>
                                <th>Featured</th>
                                <th>Edit</th>
                                <th>Delete</th>                        
                            </thead>
                            <tbody class="searchable">
                            <?php
                                // output data of each row
                                while ($row = mysqli_fetch_assoc($result)) {
                                    echo "<tr>";
                                    echo "<td>".$row['title']."</td>";
                                    echo "<td>".$row['status']."</td>";                            
                                    echo "<td>".$row['type']."</td>";                           
                                    echo "<td>".$row['featured']."</td>";                          
                                    echo '<td><button onClick="window.location.href=`jobs.php?id='.$row['id'].'`">E</button>';
                                    echo '<td><button onClick="deleteFunction(\''.$row['id'].'\')">D</button></td>';
                                    echo "</tr>";
                                }

                            ?>
                            </tbody>
                        </table>
                        <?php
                            } 
                        }
                        ?>
                        
                        <h1 id="add" class="page-header">Add/Edit Job</h1>
                        
                        <form id='addJob' action='processJobs.php' method='post' accept-charset='UTF-8' enctype="multipart/form-data">
                        <div id="addJobError" class="error">
                            <?php 
                                if (isset($_SESSION['addJobError'])) {
                                    echo $_SESSION['addJobError'];
                                }
                            ?>
                        </div>
                        <div id="addJobSuccess" class="success">
                            <?php 
                                if (isset($_SESSION['addJobSuccess'])) {
                                    echo $_SESSION['addJobSuccess'];
                                }
                            ?>
                        </div>
                        <input type='hidden' name='submitted' id='submitted' value='1'/>
                        <input type='hidden' name='editid' id='editid' 
                               value='<?php if (isset($_GET['id'])) { echo $erow['id']; }?>'/>
                        
                        <table class='content'>
                            <tr>
                                <td>
                                    Title*:
                                    <input type='text' name='title' id='title'  maxlength="50" value ="<?php 
                                    if (isset($_SESSION['title'])) { 
                                        echo $_SESSION['title'];
                                    } else if (!empty($erow['title'])) {
                                        echo $erow['title'];
                                    }
                                        ?>"/>
                                </td>
                                <td>
                                    Featured?
                                    <input type='checkbox' name='featured' id='featured' value='yes' <?php 
                                    if (isset($_SESSION['featured'])) { 
                                        if (strcmp($_SESSION['featured'], "yes") === 0) {
                                            echo " checked";
                                        }
                                    } else if (!empty($erow['featured'])) {
                                        if (strcmp($erow['featured'], "yes") === 0) {
                                            echo " checked";
                                        }
                                    }
                                        ?>/>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    Type*:
                                    <select name="type">
                                        <option value="hq" <?php 
                                        if (isset($_SESSION['type'])) { 
                                            if (strcmp($_SESSION['type'], "hq") === 0) {
                                                echo " selected";
                                            }
                                        } else if (!empty($erow['type'])) {
                                            if (strcmp($erow['type'], "hq") === 0) {
                                                echo " selected";
                                            }
                                        }
                                        ?>>Headquarters</option>
                                        <option value="retail" <?php 
                                        if (isset($_SESSION['type'])) { 
                                            if (strcmp($_SESSION['type'], "retail") === 0) {
                                                echo " selected";
                                            }
                                        } else if (!empty($erow['type'])) {
                                            if (strcmp($erow['type'], "retail") === 0) {
                                                echo " selected";
                                            }
                                        }
                                        ?>>Retail</option>
                                    </select>
                                </td>
                                <td>
                                    Status*:
                                    <select name="status" id='status'>
                                        <option value="active" <?php 
                                        if (isset($_SESSION['status'])) { 
                                            if (strcmp($_SESSION['status'], "active") === 0) {
                                                echo " selected";
                                            }
                                        } else if (!empty($erow['status'])) {
                                            if (strcmp($erow['status'], "active") === 0) {
                                                echo " selected";
                                            }
                                        }
                                        ?>>Active</option>
                                        <option value="inactive" <?php 
                                        if (isset($_SESSION['status'])) { 
                                            if (strcmp($_SESSION['status'], "inactive") === 0) {
                                                echo " selected";
                                            }
                                        } else if (!empty($erow['status'])) {
                                            if (strcmp($erow['status'], "inactive") === 0) {
                                                echo " selected";
                                            }
                                        }
                                        ?>>Inactive</option>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td colspan='2' id='scheduledposts' style='display:none;'>
                                    Scheduled Date/Time:<br>
                                    <input style='width:38%!important;' type="text" placeholder="DATE" id="date4" name="date4" value='<?php if (isset($_SESSION['scheduledate'])) {
                                            echo $_SESSION['scheduledate'];
                                        } else if(!empty($erow['scheduled'])) {
                                        echo date('Y-m-d', strtotime($erow['scheduled']));
                                        } ?>'>
                                    <input style='width:38%!important;' id="setTimeExample" name='scheduledtime' placeholder="TIME" 
                                           type="text" class="time" value='<?php if (isset($_SESSION['time'])) {
                                            echo $_SESSION['time'];
                                        } else if(!empty($erow['scheduled'])) {
                                        echo date('H.i.s', strtotime($erow['scheduled']));
                                        }?>'/><br>
                                    <button id="setTimeButton">Set current time</button>
                                </td>
                            </tr>
                            <tr>
                                <td colspan='2'>
                                    Description*:
                                    <textarea name='html' id='html'><?php 
                                    if (isset($_SESSION['html'])) { 
                                        echo $_SESSION['html'];
                                    } else if (!empty($erow['html'])) {
                                        echo $erow['html'];
                                    }
                                        ?></textarea>
                                    <script type="text/javascript">
                                        CKEDITOR.replace('html');
                                    </script>
                                </td>
                            </tr>
                            <tr>
                                <td colspan='2'>
                                    <input type='submit' name='submit' value='Save' />
                                </td>
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
    function deleteFunction(prodId) {
        var r = confirm("Are you sure you wish to delete this job?");
        if (r === true) {
            window.location="processJobs.php?delete=1&id=" + prodId;
        } else if (r === false) {
            <?php
//                unset($_SESSION['updateJobSuccess']);
//                $_SESSION['updateJobError'] = "Nothing was deleted";
            ?>
            window.location='jobs.php';
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
    
    var myCalendar2 = new dhtmlXCalendarObject(["date4"]);
    myCalendar2.hideTime();
    
    $(function() {
        $('#setTimeExample').timepicker();
        $('#setTimeButton').on('click', function (event){
            event.preventDefault();
            $('#setTimeExample').timepicker('setTime', new Date());
        });
        
        
    });
    var status = document.getElementById('status').value;
        
    if (status === "inactive") {
        document.getElementById('scheduledposts').style.display = "block";
    } else {
        document.getElementById('scheduledposts').style.display = "none";
    }
    
    document.getElementById('status').onclick = function() {
        var val = document.getElementById('status').value;
        
        if (val === "inactive") {
            document.getElementById('scheduledposts').style.display = "block";
        } else {
            document.getElementById('scheduledposts').style.display = "none";
        }
    };
</script>

