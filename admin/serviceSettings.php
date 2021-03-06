<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
require_once '../config/db.php';

if (isset($_GET['id'])) {
    $selectSql = "Select * from services where id ='" .$_GET['id']."';";
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
                                Service
                            </li>
                        </ol>
            
                        <h1 class="page-header">Manage Services</h1>
                        
                        <div id="updateServSuccess" class="success">
                            <?php 
                                if (isset($_SESSION['updateServSuccess'])) {
                                    echo $_SESSION['updateServSuccess'];
                                }
                            ?>
                        </div>
                        <div id="updateServError" class="error">
                            <?php 
                                if (isset($_SESSION['updateServError'])) {
                                    echo $_SESSION['updateServError'];
                                }
                            ?>
                        </div>
        
                        <?php 
                            $qry = "Select * from services";

                            $result = mysqli_query($link, $qry);

                            if (!mysqli_query($link,$qry))
                            {
                                echo("Error description: " . mysqli_error($link));
                            } else {
                                if ($result->num_rows === 0) {
                                    echo "You have not created any services yet.";
                                } else {
                        ?>
                        
                        <p class="text-right">
                            <a href="#add"><i class="fa fa-fw fa-plus"></i> Add Service</a>
                        </p>
                        
                        <table>
                            <thead>
                                <th>Service Code</th>
                                <th>Name</th>
                                <th>Edit</th>
                                <th>Delete</th>                        
                            </thead>
                            <?php
                                // output data of each row
                                while ($row = mysqli_fetch_assoc($result)) {
                                    echo "<tr>";
                                    echo "<td>".$row['servicecode']."</td>";
                                    echo "<td>".$row['servicename']."</td>";                          
                                    echo '<td><button onClick="window.location.href=`serviceSettings.php?id='.$row['id'].'`">E</button>';
                                    echo '<td><button onClick="deleteFunction('.$row['id'].')">D</button></td>';
                                    echo "</tr>";
                                }
                            ?>
                        </table>
                        <?php
                            } 
                        }
                        ?>
        
                        <form id='addService' action='processServices.php' method='post' accept-charset='UTF-8'>
                
                            <div id="addServError" class="error">
                                <?php 
                                    if (isset($_SESSION['addServError'])) {
                                        echo $_SESSION['addServError'];
                                    }
                                ?>
                            </div>

                            <div id="addServSuccess" class="success">
                                <?php 
                                    if (isset($_SESSION['addServSuccess'])) {
                                        echo $_SESSION['addServSuccess'];
                                    }
                                ?>
                            </div>
            
                            <h1 id="add" class="page-header">Add/Edit Service</h1>
                            
                            <input type='hidden' name='submitted' id='submitted' value='1'/>
                            <input type='hidden' name='editid' id='editid' 
                                   value='<?php if (isset($_GET['id'])) { echo $erow['id']; }?>'/>
            
                            <table class="content">
                                <tr>
                                    <td>
                                    Name*:
                                    <input type='text' name='name' id='name'  maxlength="50" 
                                           value='<?php if (isset($_SESSION['name'])) {
                                                   echo $_SESSION['name'];
                                               } else if (!empty($erow['servicename'])) 
                                               { echo $erow['servicename']; 
                                               }  
?>'/>
                                    </td>
                                    <td>
                                    Service Code*:
                                    <input type='text' name='code' id='code'  maxlength="50" 
                                           value='<?php if (isset($_SESSION['code'])) {
                                                   echo $_SESSION['code'];
                                               } else if (!empty($erow['servicecode'])) 
                                               { echo $erow['servicecode']; 
                                               } ?>'/>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2">
                                        Description:
                                        <textarea name="desc" id='desc'><?php 
                                            if (isset($_SESSION['desc'])) {
                                                echo $_SESSION['desc'];
                                            } else if(!empty($erow['description'])) { 
                                                echo $erow['description']; 
                                            } ?></textarea>
                                        <script type="text/javascript">
                                            CKEDITOR.replace('desc');
                                        </script>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2">
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
    function deleteFunction(empId) {
        var r = confirm("Are you sure you wish to delete this service?");
        if (r === true) {
            window.location="processServices.php?delete=1&id=" + empId;
        } else if (r === false) {
            <?php
                unset($_SESSION['updateServSuccess']);
                $_SESSION['updateServError'] = "Nothing was deleted";
            ?>
            window.location='serviceSettings.php';
        }
    }
</script>
