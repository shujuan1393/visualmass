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
                                ORDERS
                            </li>
                        </ol>
                        <div id='allorders'>
                            <h1 class="page-header">View Orders</h1>

                            <?php
                               $sql = "Select * from orders group by orderid order by datepaid desc";
                               $result = mysqli_query($link, $sql);

                               if (!mysqli_query($link,$sql))
                               {
                                   echo("Error description: " . mysqli_error($link));
                               } else {
                                   if ($result->num_rows === 0) {
                                       echo "There are no orders yet.";
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
                                        echo '<td><button onClick="window.location.href=`orders.php?id='.$row['orderid'].'`">View</button>';
                                        echo "</tr>";
                                    }

                                ?>
                            </table>
                            <?php
                                } 
                            }
                            ?>
                        </div>
                        <div id='orderdetails' style='display:none;'>
                            <?php
                                if (isset($_GET['id'])) {
                                    $orders = "Select * from orders where orderid='".$_GET['id']."';";
                                    $ores = mysqli_query($link, $orders);

                                    if (!mysqli_query($link, $orders)) {
                                        die(mysqli_error($link));
                                    } else {
                                        $orow = mysqli_fetch_assoc($ores);
                                   
                            ?>
                                <h1 id="add" class="page-header">View Order Details</h1>
                                <h4>Order <span class='caps'><?php echo $orow['orderid'] . " (".$orow['status'].")"; ?></span></h4>
                                Purchased on <?php echo date("d M Y", strtotime($orow['datepaid'])); ?><br>
                                Customer: <?php 
                                    $cust = "Select * from user where email ='".$orow['orderedby']."';";
                                    $cres = mysqli_query($link, $cust);

                                    if (!mysqli_query($link, $cust)) {
                                        die(mysqli_errno($link));
                                    } else {
                                        $crow = mysqli_fetch_assoc($cres);
                                        echo "<a href='customers.php?id=".$crow['id']."'>".$crow['firstname']." ".$crow['lastname']."</a>";
                                    }
                                    ?><br>
                                <table>
                                    <tr>
                                        <td>Product Name</td>
                                        <td>Price</td>
                                        <td>Quantity</td>
                                        <td>Product Type</td>
                                        <td>Additional Details</td>
                                    </tr>
                                    
                                    <?php 
                                        $res = mysqli_query($link, $orders);
                                        while($r1 = mysqli_fetch_assoc($res)) {
                                            echo "<tr>";
                                            if (is_numeric(strpos($r1['type'], "@"))) {
                                                $prod = "Select * from giftcards where code='".$r1['pid']."';";
                                            } else {
                                                $prod = "Select * from products where pid='".$r1['pid']."';";
                                            }
                                            $pres = mysqli_query($link, $prod);
                                            
                                            if (!mysqli_query($link, $prod)) {
                                                die(mysqli_error($link));
                                            } else {
                                                $prow = mysqli_fetch_assoc($pres);
                                                echo "<td>".$prow['name']."</td>";
                                            }
                                            if (strcmp($r1['type'], "hometry") === 0) {
                                                echo "<td>-</td>";
                                            } else {
                                                echo "<td>$".$r1['price']."</td>";
                                            }
                                            echo "<td>".$r1['quantity']."</td>";
                                            if (is_numeric(strpos($r1['type'], "@"))) {
                                                $typeArr = explode("@", $r1['type']);
                                                echo "<td>".$typeArr[0]." ".$typeArr[1]."</td>";
                                            } else {
                                                echo "<td>".$r1['type']."</td>";
                                            }
                                            if (is_numeric(strpos($r1['type'], "@"))) {
                                                $detArr = explode(",", $r1['details']);
                                                echo "<td class='text-left'>";
                                                echo "To: " .$detArr[0]."<br>";
                                                echo "From: " .$detArr[1]."<br>";
                                                echo "Send to: " .$detArr[2]."<br>";
                                                echo "Note: " .$detArr[3]."<br>";
                                                echo "</td>";
                                            } else {
                                                echo "<td>Lens: ";
                                                if (empty($r1['details'])) {
                                                    echo "-";
                                                } else {
                                                    $lens = "Select * from products where pid ='".$r1['details']."';";
                                                    $lres = mysqli_query($link, $lens);
                                                    
                                                    if(!mysqli_query($link, $lens)) {
                                                        die(mysqli_error($link));
                                                    } else {
                                                        $lrow = mysqli_fetch_assoc($lres);
                                                        echo $lrow['name'];
                                                    }
                                                }                                                
                                                echo "</td>";
                                            }
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
                </div>
                <!-- /.row -->

            </div>
            <!-- /.container-fluid -->

        </div>
        <!-- /#page-wrapper -->
    </div>
</html>

<script>
    <?php if (isset($_GET['id'])) { ?>
            document.getElementById('orderdetails').style.display = "block";
            document.getElementById('allorders').style.display = "none";
    <?php } ?>
</script>

