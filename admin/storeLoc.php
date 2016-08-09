<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
require_once '../config/db.php';

if (isset($_POST['submit'])) {
    $loc = $_POST['curStore'];
    $_SESSION['curStore'] = $loc;
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
                                Current Store
                            </li>
                        </ol>
                        
                        <h1 class="page-header">Set POS Store Location</h1>
                        
                        <form method='post' action='storeLoc.php'>
                            <div class='col-lg-12'>
                                <select name="curStore">
                                <?php 
                                    $locs = "Select * from locations where status='active' and name <> 'banner';";
                                    $lres = mysqli_query($link, $locs);
                                    
                                    if (!mysqli_query($link, $locs)) {
                                        die(mysqli_error($link));
                                    } else {
                                        if ($lres -> num_rows === 0) {
                                            echo "<option value='null'>No active stores</option>";
                                        } else {
                                            while($row = mysqli_fetch_assoc($lres)) {
                                                echo "<option value='".$row['code']."'";
                                                
                                                if (isset($_SESSION['curStore'])) {
                                                    if(strcmp($_SESSION['curStore'], $row['code']) === 0) {
                                                        echo " selected";
                                                    }
                                                }
                                                echo ">".$row['name']."</option>";
                                            }
                                        }
                                    }
                                ?>
                                </select>
                                <input type="submit" name="submit" value="SAVE">
                            </div> 
                        </form>
                    </div>
                </div>
                <!-- /.row -->

            </div>
            <!-- /.container-fluid -->
            <div class="modal fade" id="addCartModal" tabindex="-1" 
                role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
               <div class="modal-dialog">
                 <div class="modal-content">
                   <div class="modal-header">
                     <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                     <h4 class="modal-title"></h4>
                   </div>
                   <div class="modal-body">
                   </div>
                   <div class="modal-footer">
                   </div>
                 </div><!-- /.modal-content -->
               </div><!-- /.modal-dialog -->
             </div><!-- /.modal -->
        </div>
        <!-- /#page-wrapper -->
    </div>
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
        
        var num = document.getElementById('numrows').value;
        if (num === "0") {
            document.getElementById('emptyCart').style.display = "block";
            document.getElementById('updateCart').style.display = "none";
        } else {
            <?php unset($_SESSION['order']); ?>
            document.getElementById('emptyCart').style.display = "none";
            document.getElementById('updateCart').style.display = "block";            
        }
    </script>
</html>