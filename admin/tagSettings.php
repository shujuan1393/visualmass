<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
require_once '../config/db.php';

if (isset($_GET['id']) && !isset($_GET['delete'])) {
    $selectSql = "Select * from tags where id ='" .$_GET['id']."';";
    $eresult = mysqli_query($link, $selectSql);

    if (!mysqli_query($link,$selectSql))
    {
        echo("Error description: " . mysqli_error($link));
    } else {
        unset($_SESSION['updateTagSuccess']);
        unset($_SESSION['addTagSuccess']);
        $erow = mysqli_fetch_assoc($eresult);
    }
} else if (isset($_GET['id']) && isset($_GET['delete'])) {
    $delete = "DELETE FROM tags where id='".$_GET['id']."';";
    mysqli_query($link, $delete);
    unset($_SESSION['updateTagError']);
    unset($_SESSION['addTagError']);
    unset($_SESSION['addTagSuccess']);
    $_SESSION['updateTagSuccess'] = "Record deleted successfully";
} else if (isset($_POST['submit'])) {
    if (empty($_POST['keyword']) || empty($_POST['type'])) {
        unset($_SESSION['updateTagError']);
        unset($_SESSION['updateTagSuccess']);
        unset($_SESSION['addTagSuccess']);
        $_SESSION['addTagError'] = "Empty field(s)";
    } else {
        $keyword = $_POST['keyword'];
        $type = $_POST['type'];

        $check = "Select * from tags where type = '$type' and keyword='$keyword';";
        $cres = mysqli_query($link, $check);

        if (!mysqli_query($link, $check)) {
            die(mysqli_error($link));
        } else {
            if (!empty($_POST['editid'])) {
                $editid = $_POST['editid'];
                $sql = "UPDATE tags set keyword = '$keyword', type='$type' where id='$editid';";
                unset($_SESSION['updateTagError']);
                unset($_SESSION['addTagError']);
                unset($_SESSION['addTagSuccess']);
                $_SESSION['updateTagSuccess'] = "Record successfully updated";
                
                mysqli_query($link, $sql);
            } else {
                if ($cres -> num_rows === 0) {
                    $sql = "INSERT INTO tags (keyword, type) VALUES ('$keyword', '$type');";
                    unset($_SESSION['updateTagError']);
                    unset($_SESSION['addTagError']);
                    unset($_SESSION['updateTagSuccess']);
                    $_SESSION['addTagSuccess'] = "Record successfully added";
                    mysqli_query($link, $sql);
                } else {
                    unset($_SESSION['updateTagError']);
                    unset($_SESSION['updateTagSuccess']);
                    unset($_SESSION['addTagSuccess']);
                    $_SESSION['addTagError'] = "Record already exists";
                }
            }
        }
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
                                Tags
                            </li>
                        </ol>
            
                        <h1 class="page-header">Manage Tags</h1>
                        
                        <div id="updateTagSuccess" class="success">
                            <?php 
                                if (isset($_SESSION['updateTagSuccess'])) {
                                    echo $_SESSION['updateTagSuccess'];
                                }
                            ?>
                        </div>
                        <div id="updateTagError" class="error">
                            <?php 
                                if (isset($_SESSION['updateTagError'])) {
                                    echo $_SESSION['updateTagError'];
                                }
                            ?>
                        </div>
        
                        <?php 
                            $qry = "Select * from tags order by type asc";

                            $result = mysqli_query($link, $qry);

                            if (!mysqli_query($link,$qry))
                            {
                                echo("Error description: " . mysqli_error($link));
                            } else {
                                if ($result->num_rows === 0) {
                                    echo "You have not added any tags yet.";
                                } else {
                        ?>
                        
                        <p class="text-right">
                            <a href="#add"><i class="fa fa-fw fa-plus"></i> Add Tag</a>
                        </p>
                        
                        <table>
                            <thead>
                                <th>Keyword</th>
                                <th>Type</th>
                                <th>Date Added</th>
                                <th>Edit</th>
                                <th>Delete</th>                        
                            </thead>
                            <?php
                                // output data of each row
                                while ($row = mysqli_fetch_assoc($result)) {
                                    echo "<tr>";
                                    echo "<td>".$row['keyword']."</td>";
                                    echo "<td>".$row['type']."</td>";  
                                    echo "<td>".date("d M Y", strtotime($row['dateadded']))."</td>";                          
                                    echo '<td><button onClick="window.location.href=`tagSettings.php?id='.$row['id'].'`">E</button>';
                                    echo '<td><button onClick="deleteFunction('.$row['id'].')">D</button></td>';
                                    echo "</tr>";
                                }
                            ?>
                        </table>
                        <?php
                            } 
                        }
                        ?>
        
                        <form id='addTag' action='tagSettings.php' method='post' accept-charset='UTF-8'>
                
                            <div id="addTagError" class="error">
                                <?php 
                                    if (isset($_SESSION['addTagError'])) {
                                        echo $_SESSION['addTagError'];
                                    }
                                ?>
                            </div>

                            <div id="addTagSuccess" class="success">
                                <?php 
                                    if (isset($_SESSION['addTagSuccess'])) {
                                        echo $_SESSION['addTagSuccess'];
                                    }
                                ?>
                            </div>
            
                            <h1 id="add" class="page-header">Add/Edit Tag</h1>
                            
                            <input type='hidden' name='submitted' id='submitted' value='1'/>
                            <input type='hidden' name='editid' id='editid' 
                                   value='<?php if (isset($_GET['id'])) { echo $erow['id']; }?>'/>
            
                            Keyword*:
                            <input type='text' name='keyword' id='keyword'  maxlength="50" 
                                   value='<?php if (!empty($erow['keyword'])) 
                                       { echo $erow['keyword']; }?>'/>
                            
                            Type*:
                            <select name='type'>
                                <option value='blog' <?php 
                                    if (!empty($erow['type'])) {
                                        if (strcmp($erow['type'], "blog") === 0) {
                                            echo " selected";
                                        }
                                    }
                                ?>>Blog</option>
                                <option value='product' <?php 
                                    if (!empty($erow['type'])) {
                                        if (strcmp($erow['type'], "product") === 0) {
                                            echo " selected";
                                        }
                                    }
                                ?>>Product</option>
                            </select>
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
        var r = confirm("Are you sure you wish to delete this keyword?");
        if (r === true) {
            window.location="tagSettings.php?delete=1&id=" + empId;
        } else if (r === false) {
            <?php
                unset($_SESSION['updateTagSuccess']);
                $_SESSION['updateTagError'] = "Nothing was deleted";
            ?>
            window.location='tagSettings.php';
        }
    }
</script>
