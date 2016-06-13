<?php
    require_once 'config/db.php';
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
                <h3>ORDER HISTORY</h3>
                <table>
                    <tr>
                        <td width='20%'>Order #</td>
                        <td width='50%'>Details</td>
                        <td width='15%'>Status</td>
                        <td width='15%'>Cost ($)</td>
                    </tr>
                <?php
                    $orders = "Select * from orders where orderedby='".$_SESSION['loggedUserEmail']."' group by datepaid order by datepaid, orderid;";
                    $ores = mysqli_query($link, $orders);
                    
                    if (!mysqli_query($link, $orders)) {
                        die(mysqli_error($link));
                    } else {
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
                ?>
                <?php
                    }
                ?>
                </table>
            </div>
            
            <div id="footer"><?php require_once 'nav/footer.php';?></div>
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
    </script>
</html>