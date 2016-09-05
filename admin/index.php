<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
require_once '../config/db.php';

unset($_SESSION['updateError']);
unset($_SESSION['updateSuccess']);
unset($_SESSION['addEmpSuccess']);
unset($_SESSION['addEmpError']);

unset($_SESSION['updateEmpTypeError']);
unset($_SESSION['updateEmpTypeSuccess']);
unset($_SESSION['addEmpTypeSuccess']);
unset($_SESSION['addEmpTypeError']);

unset($_SESSION['randomString']);
unset($_SESSION['addLocError']);
unset($_SESSION['addLocSuccess']);
unset($_SESSION['uploadLocError']);
unset($_SESSION['updateLocSuccess']);
unset($_SESSION['updateLocError']);
unset($_SESSION['editUpdateLocError']);

unset($_SESSION['editUpdateProdError']);
unset($_SESSION['updateProdError']);
unset($_SESSION['updateProdSuccess']);
unset($_SESSION['addProdError']);
unset($_SESSION['addProdSuccess']);

unset($_SESSION['editUpdateDiscError']);
unset($_SESSION['updateDiscError']);
unset($_SESSION['updateDiscSuccess']);
unset($_SESSION['addDiscError']);
unset($_SESSION['addDiscSuccess']);

unset($_SESSION['updateMediaError']);
unset($_SESSION['updateMediaSuccess']);

unset($_SESSION['updateServSuccess']);
unset($_SESSION['addServSuccess']);
unset($_SESSION['updateServError']);
unset($_SESSION['addServError']);

unset($_SESSION['gensetError']);
unset($_SESSION['updateGenSetSuccess']);
unset($_SESSION['updateGcsetError']);
unset($_SESSION['updateGcSetSuccess']);
unset($_SESSION['updateHTSetSuccess']);
unset($_SESSION['updateAccSetSuccess']);
unset($_SESSION['updateNotiSetSuccess']);
unset($_SESSION['updateCheckSetSuccess']);
unset($_SESSION['updateCheckSetError']);

unset($_SESSION['addAdvError']);
unset($_SESSION['addAdvSuccess']);
unset($_SESSION['uploadAdvError']);
unset($_SESSION['updateAdvSuccess']);
unset($_SESSION['updateAdvError']);
unset($_SESSION['editUpdateAdvError']);

unset($_SESSION['addBlogError']);
unset($_SESSION['addBlogSuccess']);
unset($_SESSION['uploadBlogError']);
unset($_SESSION['updateBlogSuccess']);
unset($_SESSION['updateBlogError']);
unset($_SESSION['editUpdateBlogError']);

unset($_SESSION['addFaqError']);
unset($_SESSION['addFaqSuccess']);
unset($_SESSION['addFaqBannerError']);
unset($_SESSION['addFaqBannerSuccess']);
unset($_SESSION['updateFaqSuccess']);
unset($_SESSION['updateFaqError']);

unset($_SESSION['addTermError']);
unset($_SESSION['addTermSuccess']);
unset($_SESSION['updateTermSuccess']);
unset($_SESSION['updateTermError']);

unset($_SESSION['updateInvError']);
unset($_SESSION['updateInvSuccess']);
unset($_SESSION['profileError']);
unset($_SESSION['profileSuccess']);
unset($_SESSION['updateContactSuccess']);
unset($_SESSION['updateContactError']);
unset($_SESSION['addContactSuccess']);
unset($_SESSION['addContactError']);
unset($_SESSION['setContactDetailsError']);
unset($_SESSION['setContactDetailsSuccess']);

unset($_SESSION['addProdBannerSuccess']);
unset($_SESSION['addProdBannerError']);
?>

<script>
    var date = new Date();
    var hrs = date.getHours();
    var welcome = "";
    
    if(hrs < 12) {
        welcome = "Good morning, ";
    }else if(hrs < 17) {
        welcome = "Good afternoon, ";
    }else if(hrs < 21) {
        welcome = "Good evening, ";
    }else {
        welcome = "Good night, ";
    }
</script>

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
                        <div class="vm-center">
                            <h2>
                                <?php echo $welcome = "<script>document.write(welcome)</script>" .$_SESSION['loggedUser']; ?>
                            </h2>
                            <h5>Today is <?php echo date('l, d F Y'); ?></h5>
                        </div>
                        <div id="today">
                            <?php 
                                $sales = "Select SUM(totalcost) as total, location from orders where DATE(datepaid)=CURDATE() group by location;";
                                
                                $salesres = mysqli_query($link, $sales);
                                $chartArr = array(
                                    "caption" => "Overall Sales", 
                                    "paletteColors" => "#0075c2,#1aaf5d,#f2c500,#f45b00,#8e0000",
                                    "bgColor" => "#ffffff",
                                    "showBorder" => "0",
                                    "use3DLighting" => "0",
                                    "showShadow" => "0",
                                    "enableSmartLabels" => "1",
                                    "startingAngle" => "0",
                                    "showPercentValues" => "1",
                                    "showPercentInTooltip" => "0",
                                    "decimals" => "3",
                                    "captionFontSize" => "14",
                                    "subcaptionFontSize" => "14",
                                    "subcaptionFontBold" => "0",
                                    "toolTipColor" => "#ffffff",
                                    "toolTipBorderThickness" => "0",
                                    "toolTipBgColor" => "#ccc",
                                    "toolTipBgAlpha" => "80",
                                    "toolTipBorderRadius" => "2",
                                    "toolTipPadding" => "5",
//                                    "showHoverEffect" => "0.7",
                                    "showLegend" => "1",
                                    "legendBgColor" => "#ffffff",
                                    "legendBorderAlpha" => "0",
                                    "legendShadow" => "0",
                                    "legendItemFontSize" => "10",
                                    "legendItemFontColor" => "#666666",
                                    "useDataPlotColorForLabels" => "1");
                                
                                $data = array("chart" => $chartArr, "data" => "");
//                                
                                if (!mysqli_query($link, $sales)) {
                                    die(mysqli_error($link));
                                } else {
                                    $result = array();
                                    $count = 0;
                                    if($salesres -> num_rows > 0) {
                                        while($row = mysqli_fetch_assoc($salesres)) {
                                            $result[$count] = array($row['location'] => $row['total']);
                                            $count++;
                                        }
                                    } 
                                }
                                
                                $data['data'] = array();
                                // Iterate through the data in `$actualData` and insert in to the `$arrData` array.
                                foreach ($result as $key => $value) {
                                    foreach($value as $k => $v) {
                                        array_push($data['data'],
                                            array(
                                                'label' => $k,
                                                'value' => $v
                                            )
                                        );
                                    }
                                }
//                                print_r($data['data']);
                                $arr = json_encode($data);
                                
//                                print_r($arr);
                                $columnChart = new FusionCharts(
                                    "pie2D", 
                                    "sales" , 
                                    "420", 
                                    "400", 
                                    "salesChart", 
                                    "json", 
                                    $arr);
                                
                                $columnChart->render();
                                
                                $visitChart = $chartArr;
                                $visitChart['caption'] = "Sales Per Visitor";
                                unset($visitChart['paletteColors']);
                                $visitors = array("chart" => $visitChart, "data" => "");
                                
                                $cust = "Select SUM(totalcost) as total, user.email as email from user join orders where user.email = orders.orderedby and DATE(orders.datepaid) = CURDATE() group by user.email;";
                                $cres = mysqli_query($link, $cust);
                                
                                if(!mysqli_query($link, $cust)) {
                                    die(mysqli_error($link));
                                } else {
                                    $result = array();
                                    $count = 0;
                                    while($row = mysqli_fetch_array($cres)) {
                                        $result[$count] = array($row['email'] => $row['total']);
                                        $count++;
                                    }
                                }
                                
                                $visitors['data'] = array();
                                // Iterate through the data in `$actualData` and insert in to the `$arrData` array.
                                foreach ($result as $key => $value) {
                                    foreach($value as $k => $v) {
                                        array_push($visitors['data'],
                                            array(
                                                'label' => $k,
                                                'value' => $v
                                            )
                                        );
                                    }
                                }
                                $visitorArr = json_encode($visitors);
                                
//                                print_r($visitors);
                                $visitorChart = new FusionCharts(
                                    "pie2D", 
                                    "visitors" , 
                                    "420", 
                                    "400", 
                                    "visitorChart", 
                                    "json", 
                                    $visitorArr);
                                
                                $visitorChart->render();
                                
                            ?>
                            <table>
                                <tr>
                                    <td id="salesChart" width="40%"></td>
                                    <td id="visitorChart" width="40%"></td>
                                </tr>
                            </table>
                        </div>
                        <div id="updates">
                            <h4>Recently logged in</h4>
                            <?php 
                                $users = "Select * from staff order by lastlogin desc LIMIT 5";
                                $ures = mysqli_query($link, $users);
                                
                                if(!mysqli_query($link, $users)) {
                                    die(mysqli_error($link));
                                } else {
                                    if($ures -> num_rows > 0) {
                            ?>
                                <table>
                                    <thead>
                                        <th>Name</th>
                                        <th>Last Login</th>
                                    </thead>
                                    <?php while ($row = mysqli_fetch_assoc($ures)) { 
                                            echo "<tr>";
                                            echo "<td>".$row['firstname']." ".$row['lastname']."</td>";
                                            echo "<td>".date('d M Y H:i:s', strtotime($row['lastlogin']))."</td>";
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
    </body>
</html>