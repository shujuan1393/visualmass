<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
require_once '../config/db.php';

if (isset($_GET['id'])) {
    $selectSql = "Select * from terms where id ='" .$_GET['id']."';";
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
                                Web
                            </li>
                            <li class="active">
                                Terms
                            </li>
                        </ol>
                        
                        <h1 class="page-header">Manage Terms</h1>
                        
                        <?php 
                            $qry = "Select * from terms order by fieldorder asc";
                            $result = mysqli_query($link, $qry);

                            if (!mysqli_query($link,$qry))
                            {
                                echo("Error description: " . mysqli_error($link));
                            } else {
                                if ($result->num_rows === 0) {
                                    echo "You have not created any terms yet.";
                                } else {
                        ?>
                        
                        <p class="text-right">
                            <a href="#add"><i class="fa fa-fw fa-plus"></i> Add Section</a>
                        </p>
                        
                        <table>
                            <thead>
                                <th>Order</th>
                                <th>Title</th>
                                <th>Edit</th>
                                <th>Delete</th>                        
                            </thead>
                            <?php
                                $rowCount = 0;
                                // output data of each row
                                while ($row = mysqli_fetch_assoc($result)) {
                                    $rowCount++;
                                    echo "<tr>";
                                    echo "<td>".$row['fieldorder'] ."</td>";  
                                    echo "<td>".$row['title'] ."</td>";                        
                                    echo '<td><button onClick="window.location.href=`terms.php?id='.$row['id'].'`">E</button>';
                                    echo '<td><button onClick="deleteFunction('.$row['id'].')">D</button></td>';
                                    echo "</tr>";
                                }
                            ?>
                        </table>
                        <?php
                            } 
                        }
                        ?>
                        
                        <div id="updateTermSuccess" class="success">
                            <?php 
                                if (isset($_SESSION['updateTermSuccess'])) {
                                    echo $_SESSION['updateTermSuccess'];
                                }
                            ?>
                        </div>
                        <div id="updateTermError" class="error">
                            <?php 
                                if (isset($_SESSION['updateTermError'])) {
                                    echo $_SESSION['updateTermError'];
                                }
                            ?>
                        </div>
        
                        <form id='addTermSection' action='processTerms.php' method='post'>
                            <div id="addTermError" class="error">
                                <?php 
                                    if (isset($_SESSION['addTermError'])) {
                                        echo $_SESSION['addTermError'];
                                    }
                                ?>
                            </div>

                            <p id='nanError' style="display: none;">Please enter numbers only</p>
                            <div id="addTermSuccess" class="success">
                                <?php 
                                    if (isset($_SESSION['addTermSuccess'])) {
                                        echo $_SESSION['addTermSuccess'];
                                    }
                                ?>
                            </div>
                            
                            <h1 id="add" class="page-header">Add/Edit Terms Section</h1>

                            <input type='hidden' name='submitted' id='submitted' value='1'/>
                            <input type='hidden' name='editid' id='editid' 
                                   value='<?php if (isset($_GET['id'])) { echo $_GET['id']; }?>'/>
            
                            <table class="content">
                                <tr>
                                    <td>
                                    Title*:
                                    <input type='text' name='title' id='title' 
                                           value='<?php 
                                           if (isset($_SESSION['title'])) {
                                               echo $_SESSION['title'];
                                           } else if (!empty($erow['title'])) {
                                               echo $erow['title'];
                                           } 
                                           ?>'/>
                                    </td>
                                    <td>
                                    Order*:
                                    <input type='text' name='order' id='order'  
                                       onkeypress="return isNumber(event)" 
                                           value="<?php 
                                                if (isset($_SESSION['order'])) {
                                                    echo $_SESSION['order'];
                                                } else if(!empty($erow['fieldorder'])){
                                                    if (isset($erow['fieldorder'])) { 
                                                        echo $erow['fieldorder']; 
                                                    } else { 
                                                        echo $rowCount+1; 
                                                    }
                                                } ?>"/>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2">
                                        Content*: 
                                        <textarea name="html"><?php 
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
                                    <td colspan="2">
                                        <input type='submit' name='submit' value='Submit' />
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
        var r = confirm("Are you sure you wish to delete this Terms section?");
        if (r === true) {
            window.location="processTerms.php?delete=1&id=" + locId;
        } else if (r === false) {
            <?php
//                unset($_SESSION['addTermError']);
//                unset($_SESSION['addTermSuccess']);
//                unset($_SESSION['updateTermSuccess']);
//                $_SESSION['updateTermError'] = "Nothing was deleted";
            ?>
            window.location='terms.php';
        }
    }
</script>