<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
require_once '../config/db.php';

if (isset($_GET['id'])) {
    unset($_SESSION['updateJobSuccess']);
    unset($_SESSION['updateJobError']);
    unset($_SESSION['addJobSuccess']);
    unset($_SESSION['addJobError']);
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
                        
                        <div id="updateJobSuccess" style="color:green">
                            <?php 
                                if (isset($_SESSION['updateJobSuccess'])) {
                                    echo $_SESSION['updateJobSuccess'];
                                }
                            ?>
                        </div>
                        <div id="updateJobError" style="color:red">
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
                        
                        <table>
                            <thead>
                                <th>Title</th>
                                <th>Status</th>
                                <th>Type</th>
                                <th>Featured</th>
                                <th>Edit</th>
                                <th>Delete</th>                        
                            </thead>
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
                        </table>
                        <?php
                            } 
                        }
                        ?>
                        
                        <h1 id="add" class="page-header">Add/Edit Job</h1>
                        
                        <form id='addJob' action='processJobs.php' method='post' accept-charset='UTF-8' enctype="multipart/form-data">
                        <div id="addJobError" style="color:red">
                            <?php 
                                if (isset($_SESSION['addJobError'])) {
                                    echo $_SESSION['addJobError'];
                                }
                            ?>
                        </div>
                        <div id="addJobSuccess" style="color:green">
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
                                    if (!empty($erow['title'])) {
                                        echo $erow['title'];
                                    }
                                        ?>"/>
                                </td>
                                <td>
                                    Featured?
                                    <input type='checkbox' name='featured' id='featured' value='yes' <?php 
                                    if (!empty($erow['featured'])) {
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
                                        if (!empty($erow['type'])) {
                                            if (strcmp($erow['type'], "hq") === 0) {
                                                echo " selected";
                                            }
                                        }
                                        ?>>Headquarters</option>
                                        <option value="retail" <?php 
                                        if (!empty($erow['type'])) {
                                            if (strcmp($erow['type'], "retail") === 0) {
                                                echo " selected";
                                            }
                                        }
                                        ?>>Retail</option>
                                    </select>
                                </td>
                                <td>
                                    Status*:
                                    <select name="status">
                                        <option value="active" <?php 
                                        if (!empty($erow['status'])) {
                                            if (strcmp($erow['status'], "active") === 0) {
                                                echo " selected";
                                            }
                                        }
                                        ?>>Active</option>
                                        <option value="inactive" <?php 
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
                                <td colspan='2'>
                                    Description*:
                                    <textarea name='html' id='html'><?php 
                                    if (!empty($erow['html'])) {
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
                unset($_SESSION['updateJobSuccess']);
                $_SESSION['updateJobError'] = "Nothing was deleted";
            ?>
            window.location='jobs.php';
        }
    }
</script>

