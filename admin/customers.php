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
                                <a href="admin.php"><i class="fa fa-home"></i></a>
                            </li>
                            <li class="active">
                                CUSTOMERS
                            </li>
                        </ol>
                        
                        <?php
                            if (isset($_GET['id'])) {
                        ?>
                            <h1 class="page-header">Customer Details</h1>
                        
                        <?php
                                $sql = "Select * from user where id='".$_GET['id']."';";
                            
                                $result = mysqli_query($link, $sql);

                                if (!mysqli_query($link,$sql))
                                {
                                    echo("Error description: " . mysqli_error($link));
                                } else {
                                    if ($result->num_rows === 0) {
                                        echo "There are no customers yet.";
                                    } else {
                                        $crow = mysqli_fetch_assoc($result);
                             ?>

                             <div class='col-md-12'>
                                <div class='col-md-4'>
                                    <h4><?php echo $crow['firstname']." ".$crow['lastname']; ?></h4>
                                    <?php echo $crow['country']; ?><br>
                                    Customer since <?php echo date("M d, Y", strtotime($crow['datejoined'])); ?>
                                </div>
                                 <div class='col-md-3'>
                                     Contact
                                     <?php 
                                         echo "<ul class='nolist'>";
                                         echo "<li><a href='mailto:".$crow['email']."'>".$crow['email']."</a></li>";
                                         echo "<li>";
                                         if (strcmp($crow['marketing'], "yes") === 0) {
                                             echo "Accepts marketing";
                                         } else {
                                             echo "Does not accept marketing";
                                         }
                                         echo "</li>";
                                         echo "<li>";
                                         if (empty($crow['password'])) {
                                             echo "Does not have an account";
                                         } else {
                                             echo "Has an account";
                                         }
                                         echo "</li>";
                                         echo "</ul>";
                                     ?>
                                 </div>
                                 <div class='col-md-4'>
                                     Default Address
                                     <p>
                                     <?php 
                                         echo $crow['firstname']." ".$crow['lastname']."<br>";
                                         echo $crow['address'];
                                     ?>
                                     </p>
                                 </div>
                             </div>

                             <div class='col-md-12'>
                                 <?php 
                                     if (!empty($crow['prescription'])) {
                                         echo $crow['prescription'];
                                     } else {
                                         echo "<p class='padded text-center'><strong>This customer has no stored prescription</strong></p>";
                                     }
                                 ?>
                             </div>

                             <div class='col-md-12'>
                                 <div class='col-md-4'>
                                     Last Order: 
                                     <?php 
                                         $recent = "Select * from orders where orderedby ='".$crow['email']."' order by datepaid desc limit 1";
                                         $res = mysqli_query($link, $recent);

                                         if (!mysqli_query($link, $recent)) {
                                             die(mysqli_error($link));
                                         } else {
                                             $row = mysqli_fetch_assoc($res);
                                             $datepaid = date("d M Y H:i", strtotime($row['datepaid']));                                        
                                     ?>
                                     <script>
                                         var date1 = new Date();
                                         var date2 = new Date("<?php echo date("d M Y H:i:s", strtotime($row['datepaid'])); ?>");
                                         var hours = Math.abs(date1.getTime() - date2) / 3600000;
                                         document.write("<h4>About " + Math.floor(hours) + " hours ago</h4>");
                                     </script>
                                     <?php
                                             $orderid = $row['orderid'];
                                             $idArr = explode("-", $orderid);
                                             if (in_array("ON", $idArr)) {
                                                 echo "<p>Online Store</p>";
                                             } else {
                                                 $loc = "Select * from locations where code ='".$idArr[0]."';";
                                                 $lres = mysqli_query($link, $loc);

                                                 if (!mysqli_query($link, $loc)) {
                                                     die(mysqli_error($link));
                                                 } else {
                                                     $row = mysqli_fetch_assoc($lres);

                                                     echo "Point of Sale - ".$row['name'];
                                                 }
                                             }
                                         }
                                     ?>
                                 </div>
                                 <div class='col-md-3'>
                                     Total Spent:
                                     <?php 
                                         $total = "Select SUM(totalcost) as total, count(distinct orderid) as count from orders where orderedby ='".$crow['email']."'";
                                         $tres = mysqli_query($link, $total);

                                         if (!mysqli_query($link, $total)) {
                                             die(mysqli_error($link));
                                         } else {
                                             $row = mysqli_fetch_assoc($tres);
                                             echo "<h4>$".$row['total']."</h4>";
                                             echo "<p>".$row['count']." orders</p>";
                                     ?>
                                 </div>
                                 <div class='col-md-4'>
                                     Average per order:
                                     <?php 
                                             $avg = $row['total'] / $row['count'];
                                             echo "<h4>$$avg</h4>";
                                         }
                                     ?>
                                 </div>
                             </div>
                             <?php
                                 } 
                             }
                             ?>
                             <br>
                             <div id='recentorders'>
                                 <h4>Recent Orders</h4>
                                 <div class='padded'>
                                 <?php 
                                     $recent = "Select * from orders where orderedby ='".$crow['email']."' group by orderid order by datepaid desc";
                                     $res = mysqli_query($link, $recent);

                                     if (!mysqli_query($link, $recent)) {
                                         die(mysqli_error($link));
                                     } else {
                                         while ($row = mysqli_fetch_assoc($res)) {
                                             echo "<div class='accordion'><h5>".$row['orderid']. " on ".date("d M Y H:i", strtotime($row['datepaid']));
                                             $ordertotal = "Select SUM(totalcost) as total from orders where orderid='".$row['orderid']."' group by orderid;";
                                             $result = mysqli_query($link, $ordertotal);

                                             if (!mysqli_query($link, $ordertotal)) {
                                                 die(mysqli_error($link));
                                             } else {
                                                 $one = mysqli_fetch_assoc($result);
                                                 echo "<div class='pull-right'><span>$".$one['total']."</span></div>"."</h5></div>";
                                             }

                                             $products = "Select * from orders where orderid='".$row['orderid']."';";
                                             $pres = mysqli_query($link, $products);
                                             if (!mysqli_query($link, $products)) {
                                                 die(mysqli_error($link));
                                             } else {
                                                 echo "<div class='panel'>";
                                                 while ($prow = mysqli_fetch_assoc($pres)) {
                                                     if (is_numeric(strpos($prow['type'], "@"))) {
                                                         $gift = "Select * from giftcards where code = '".$prow['pid']."';";
                                                         $gres = mysqli_query($link, $gift);

                                                         if (!mysqli_query($link, $gift)) {
                                                             die(mysqli_error($link));
                                                         } else {
                                                             $grow = mysqli_fetch_assoc($gres);
                                                             echo "<a href='giftcards.php?id=".$grow['id']."#add'>".$grow['name']."</a><br>";
                                                             echo "<a href='giftcards.php?id=".$grow['id']."#add'>GIFTCARD IMAGE<img src='' width='300'></a>";

                                                             echo "<div class='pull-right'><span>".$prow['quantity']." x $".$grow['amount']."</span></div><br>";
                                                         }
                                                     } else {
                                                         $getname = "Select * from products where pid = '".$prow['pid']."';";
                                                         $nres = mysqli_query($link, $getname);

                                                         if (!mysqli_query($link, $getname)) {
                                                             die(mysqli_error($link));
                                                         } else {
                                                             $nrow = mysqli_fetch_assoc($nres);
                                                             echo "<a href='products.php?id=".$nrow['pid']."#add'>".$nrow['name']."</a><br>";
                                                             if (!empty($nrow['featured'])) {
                                                                 $images = explode(",", $nrow['featured']);
                                                                 echo "<a href='products.php?id=".$nrow['pid']."#add'><img src='".$images[0]."' width='300'></a>";
                                                             }
                                                             echo "<div class='pull-right'><span>".$prow['quantity']." x $".$nrow['price']."</span></div><br>";
                                                         }
                                                     }
                                                 }
                                                 echo "</div>";
                                             }
                                         }
                                     }
                                 ?>
                                 </div>
                             </div>
                        <?php } else { ?>
                            <h1 class="page-header">View Customers</h1>
                            
                            <table>
                                <thead>
                                    <th>Name</th>
                                    <th>Location</th>
                                    <th>Orders</th>
                                    <th>Last Order</th>
                                    <th>Total Spent</th>
                                </thead>
                                <?php 
                                    $users = "select * from user";
                                    $ures = mysqli_query($link, $users);
                                    
                                    if (!mysqli_query($link, $users)) {
                                        die(mysqli_error($link));
                                    } else {
                                        while ($urow = mysqli_fetch_assoc($ures)) {
                                            echo "<tr class='padded'>";
                                            echo "<td><a href='customers.php?id=".$urow['id']."'>".$urow['firstname']." ".$urow['lastname']."</a></td>";
                                            echo "<td>".$urow['country']."</td>";
                                            $noorders = "Select count(distinct orderid) as num, SUM(totalcost) as total from orders where orderedby='".$urow['email']."';";
                                            $ores = mysqli_query($link, $noorders);
                                            
                                            if (!mysqli_query($link, $noorders)) {
                                                die(mysqli_error($link));
                                            } else {
                                                $orow = mysqli_fetch_assoc($ores);
                                                echo "<td>".$orow['num']."</td>";  
                                                $lastorder = "Select * from orders where orderedby ='".$urow['email']."' order by datepaid desc limit 1";
                                                $lastres = mysqli_query($link, $lastorder);
                                                
                                                if (!mysqli_query($link, $lastorder)) {
                                                    die(mysqli_error($link));
                                                } else {
                                                    $lastrow = mysqli_fetch_assoc($lastres);
                                                    echo "<td><a href='orders.php?id=".$lastrow['orderid']."'>".$lastrow['orderid']."</a></td>";
                                                }
                                                echo "<td>$".$orow['total']."</td>";                                                 
                                            }
                                            echo "</tr>";
                                        }
                                    }
                                ?>
                            </table>
                             
                        <?php } ?>
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
        var acc = document.getElementsByClassName("accordion");
        var panel = document.getElementsByClassName('panel');
     
        for (var i = 0; i < acc.length; i++) {
            acc[i].onclick = function() {
                var setClasses = !this.classList.contains('active');
                setClass(acc, 'active', 'remove');
                setClass(panel, 'show', 'remove');

                if (setClasses) {
                    this.classList.toggle("active");
                    this.nextElementSibling.classList.toggle("show");
                }
            };
        }

        function setClass(els, className, fnName) {
            for (var i = 0; i < els.length; i++) {
                els[i].classList[fnName](className);
            }
        }
    </script>

