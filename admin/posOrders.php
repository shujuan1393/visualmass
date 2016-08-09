<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
require_once '../config/db.php';

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
                                POS Orders
                            </li>
                        </ol>
                        
                        <h1 class="page-header">Orders at Current Store</h1>
                        
                        <?php
                               $sql = "Select * from orders where location='".$_SESSION['curStore']."' group by orderid order by datepaid desc";
                               $result = mysqli_query($link, $sql);

                               if (!mysqli_query($link,$sql))
                               {
                                   echo("Error description: " . mysqli_error($link));
                               } else {
                                   if ($result->num_rows === 0) {
                                       echo "There are no orders at this location yet.";
                                   } else {
                            ?>

                            <table>
                                <thead>
                                    <th>Order #</th>
                                    <th>Customer</th>
                                    <th>Date</th>
                                    <th>Status</th>
                                    <th>View</th>                     
                                </thead>
                                <?php
                                    // output data of each row
                                    while ($row = mysqli_fetch_assoc($result)) {
                                        echo "<tr>";
                                        echo "<td>".$row['orderid']."</td>";
                                        echo "<td>".$row['orderedby']."</td>";                            
                                        echo "<td>".date("d M Y", strtotime($row['datepaid']))."</td>";                           
                                        echo "<td class='caps'>".$row['status']."</td>";                          
                                        echo '<td><a class="addcart" href="posOrder.php?id='.$row['orderid'].
                                                    '" data-toggle="modal" data-target="#orderModal">View</a>';
//                                        echo '<button onClick="window.location.href=`orders.php?id='.$row['orderid'].'`">View</button>';
                                        echo "</tr>";
                                    }
                                ?>
                            </table>
                            <?php
                                } 
                            }
                            ?>
                    </div>
                </div>
                <!-- /.row -->

            </div>
            <!-- /.container-fluid -->
            <div class="modal fade" id="orderModal" tabindex="-1" 
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
        $('#orderModal').appendTo("body");
        
        setInterval(function() {
            // method to be executed;
            $('#orderModal').removeData('bs.modal');
        }, 500);
    </script>
</html>