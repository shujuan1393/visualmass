<?php
    require_once 'config/db.php';
    require_once 'config/zyllem.php';
?>
<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<html>
    <head>
        <meta charset="UTF-8">
    </head>
    <body>
        
        <div id="wrapper">
            <div id="header"><?php require_once 'nav/header.php';?></div>
            
            <div id="content">
                <h3>ORDER HISTORY</h3><br>
                <table>
                    <tr>
                        <td width='20%'>Order #</td>
                        <td width='50%'>Details</td>
                        <td width='15%'>Status</td>
                        <td width='15%'>Cost ($)</td>
                    </tr>
                <?php
                    $orders = "Select * from orders where orderedby='".$_SESSION['loggedUserEmail']."' group by orderid order by datepaid, orderid;";
                    $ores = mysqli_query($link, $orders);
                    
                    if (!mysqli_query($link, $orders)) {
                        die(mysqli_error($link));
                    } else {
                        if ($ores -> num_rows > 0) {
                            $count = 0;
                            while($row = mysqli_fetch_assoc($ores)) { 
                ?>
                            <tr>
                            <td>
                                <?php echo $row['orderid']; ?>
                            </td>
                            <td>
                                <?php 
                                    $date = date("M d, Y", strtotime($row['datepaid']));
                                    echo "<div class='accordion' id='".$count."'> Purchased on ".$date
                                            ." (CLICK TO VIEW MORE DETAILS) <i class='fa fa-caret-down' aria-hidden='true'></i></div>";
                                    echo "<div class='panel' id='panel".$count."'>";
                                    $relorders = "Select * from orders where orderid = '".$row['orderid']."';";
                                    $relres = mysqli_query($link, $relorders);

                                    if (!mysqli_query($link, $relorders)) {
                                        die(mysqli_error($link));
                                    } else {
                                        while ($r1 = mysqli_fetch_assoc($relres)) {
                                            if (is_numeric(strpos($r1['type'], "@"))) {
                                                $getprod = "Select * from giftcards where code ='".$r1['pid']."';";
                                            } else {
                                                $getprod = "Select * from products where pid ='".$r1['pid']."';";
                                            }

                                            $pres = mysqli_query($link, $getprod);
                                            $prow = mysqli_fetch_assoc($pres);
                                            $price = $r1['price'] * $r1['quantity'];
                                            echo $r1['quantity'] ." ".$prow['name'];
                                            if (strcmp($r1['type'], "hometry") === 0) {
                                                echo " for home try-on";
                                               //get delivery tracking information
                                                $del = "Select * from deliveries where orderid='".$row['orderid']."';";
                                                $dres = mysqli_query($link, $del);

                                                if (!mysqli_query($link, $del)) {
                                                    die(mysqli_error($link));
                                                } else {
                                                    if ($dres -> num_rows > 0) {
                                                        $drow = mysqli_fetch_assoc($dres);
                                                        echo "<br><br>Delivery Details";
                                                        echo "<br>ID: <a href='orders.php?did=".$drow['deliveryid']."'>".$drow['deliveryid']."</a><br>";
                                                        echo "Tracking Number: ".$drow['trackingnumber']."<br>";
                                                        echo "Click <a href='".$drow['trackingurl']."' target='_blank'>here</a> to track your delivery";
                                                        echo "<div class='caps'>".$drow['statename']."</div>";
                                                    }
                                                }
                                            } else {
                                                echo " - $".$price;
                                            }
                                            echo "<br>";
                                        }
                                    }
                                    echo "</div>";
                                ?>
                            </td>
                            <td class='caps'>
                                <?php echo $row['status']; ?>
                            </td>
                            <td>
                                <?php echo $row['totalcost']; ?>
                            </td>
                            </tr>
                <?php   
                                $count++;
                            }
                        } else {
                            echo "<tr><td colspan='4'>";
                            echo "<h4>Sorry, you do not have any past orders yet :( </h4>";
                            echo "<h5>Start shopping now!</h5>";
                            echo "</td></tr>";
                        }
                ?>
                <?php
                    }
                ?>
                </table>
                <?php if(isset($_GET['did'])) { 
                    $did = $_GET['did'];
                ?>
                <h4 class='caps'>Delivery Details For ID: <?php echo $did; ?></h4>
                
                <?php 
                    //get delivery information from zyllem
                    $options = array(
                        'http' => array(
                            'header' => "Authorization: bearer ".$access."\r\n".
                                        "Content-Type: application/json\r\n",
                            'method'  => "GET"
                        ),
                    );

                    $delcontext = stream_context_create($options);
                    $url = 'https://api.zyllem.org/api/v2/deliveries/'.$did;
                    $result = file_get_contents($url, false, $delcontext, -1, 40000);
                    $arr = json_decode($result, true);
//                    print_r($arr);
//                    
                    $orderid = $arr['eOrderId'];
                    $deliveryid = $arr['deliveryId'];
                    $trackingNum = $arr['trackingNumber'];
                    $trackingUrl = $arr['trackingUrl'];
                    $cost = $arr['cost']['currency']." ".$arr['cost']['value'];
                    $comments = $arr['comments'];
                    $status = $arr['status'];
                    $stateName = $arr['stateName'];
                    $service = $arr['service'];
                    $pickup = $arr['pickupTime'];
                    $pickupArr = explode("T", $pickup);
                    $time = explode("+", $pickupArr[1]);
                    
                    //get delivery parcels
                    $parcels = $arr['parcels'];
                    
                    //update delivery status
//                    $sql = "UPDATE deliveries set status = '$status', statename='$stateName' "
//                            . "where trackingnumber = '$trackingNum';";
//                    mysqli_query($link, $sql);
                    
                ?>
                <div class='col-md-10 col-md-offset-2 full'>
                    <div class='col-md-5'>
                        Tracking Number: <?php echo $trackingNum;?> <br>
                        Click <a href='<?php echo $trackingUrl; ?>' target='_blank'>here</a> to track your delivery status<br>
                    </div>
                    <div class='col-md-5'>
                        Cost: <?php echo $cost; ?><br>
                        State Name: <?php echo $stateName; ?><br>
                        <?php if(strcmp($stateName, "Cancelled") !== 0) { ?>
                            <a data-toggle="modal" data-target="#cancelModal" id='cancelModalBtn' class='button'>CANCEL</a>
                        <?php } ?>
                    </div>
                    <div class='col-md-10'>
                        Service: <?php echo $service; ?><br>
                        Pickup Time: <?php echo date("d M Y", strtotime($pickupArr[0]))." ".$time[0]; ?><br>
                    </div>
                    <div class="col-md-5">
                        <h5>Parcels</h5>
                        <?php 
                            for ($i = 0; $i < count($parcels); $i++) {
                                $desc = $parcels[$i]['description'];
                                
                                echo "- ".$desc."<br>";
                            }
                        ?>
                    </div>
                    <div class='col-md-5'>
                        <h5>Comments</h5>
                        <?php echo $comments; ?>
                    </div>
                </div>
                <?php } ?>
            </div>
            
            <div id="footer"><?php require_once 'nav/footer.php';?></div>
            
            <div class="modal fade" id="cancelModal" tabindex="-1" 
             role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog">
              <div class="modal-content">
                <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                  <h4 class="modal-title">Why do you want to cancel this delivery?</h4>
                </div>
                <div class="modal-body">
                    <input type='text' name='reason' id='reason' placeholder="REASON">
                </div>
                <div class="modal-footer">
                    <input type='submit' name='submit' onclick="cancelDelivery('<?php echo $did; ?>')" value='Cancel Now'>
<!--                  <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                  <button type="button" class="btn btn-primary">Save changes</button>-->
                </div>
              </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
          </div><!-- /.modal -->
          
        </div>
    </body>
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
        
        function cancelDelivery(deliveryid) {
            var reason = document.getElementById('reason').value;
            document.getElementById('reason').value = "";
            window.location = "cancel.php?did="+deliveryid+"&reason="+reason;
        }
        
        <?php 
            if (isset($_SESSION['cancelledDelivery'])) {
                if (strcmp($_SESSION['cancelledDelivery'], "successful") === 0) {
        ?>
                document.getElementById('cancelModalBtn').style.display = "none";
        <?php
                } else {
        ?>
                document.getElementById('cancelModalBtn').style.display = "block";
        <?php
                }
            }
        ?>
    </script>
</html>