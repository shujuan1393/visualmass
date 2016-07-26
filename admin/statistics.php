<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

require_once '../config/db.php';

$selectSql = "SELECT value from settings WHERE type='account'";
$savedresult = mysqli_query($link, $selectSql);

if (isset($_GET['id'])) {
    $getEmpType = "Select * from employeetypes where id='".$_GET['id']."';";
    $eresult = mysqli_query($link, $getEmpType);
    $erow = mysqli_fetch_assoc($eresult);
}

if (!mysqli_query($link,$selectSql)) {
    echo("Error description: " . mysqli_error($link));
} else {
    $savedrow = mysqli_fetch_assoc($savedresult);
    $valArr = explode("&", $savedrow['value']);
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
                                Statistics
                            </li>
                        </ol>
                        
                        <ul class="nav nav-tabs" id="myTabs">
                            <li class="active"><a data-toggle="tab" href="#sales">Sales</a></li>
                            <li><a data-toggle="tab" href="#menu1">Staff</a></li>
                            <li><a data-toggle="tab" href="#stores">Stores</a></li>
                        </ul>

                        <div class="tab-content">
                            <div id="sales" class="tab-pane fade in active">
                                <h1 class="page-header">Sales Statistics</h1>
                                <table>
                                    <tr>
                                        <td>
                                            <div>
                                                <h4>Most Popular Product</h4>
                                                <div class='col-md-5 text-center'>
                                                <?php 
                                                    $viewed = "Select COUNT(pid) as count, pid from productstatistics where type = 'viewproduct' group by pid order by count desc LIMIT 1;";
                                                    $viewedprod = mysqli_query($link, $viewed);

                                                    if (!mysqli_query($link, $viewed)) {
                                                        die(mysqli_error($link));
                                                    } else {
                                                        if($viewedprod -> num_rows > 0) {
                                                            $row = mysqli_fetch_assoc($viewedprod);
                                                            $prod = "Select * from products where pid='".$row['pid']."';";
                                                            $pres = mysqli_query($link, $prod);

                                                            if(!mysqli_query($link, $prod)) {
                                                                die(mysqli_error($link));
                                                            } else {
                                                                if($pres -> num_rows > 0) {
                                                                    $prow = mysqli_fetch_assoc($pres);
                                                                    $imgArr = explode(",", $prow['featured']);
                                                                    $imgpos = strpos($imgArr[0], '/');
                                                                    $img = substr($imgArr[0], $imgpos+1);
                                                                    echo "<img src='".$img."' width='200'><br>";
                                                                    echo "<div class='text-center'>".$prow['name']."</div>";
                                                                    echo "<span>".$row['count']." views</span>";
                                                                }
                                                            }
                                                        }
                                                    }
                                                ?>
                                                </div>
                                                <div class='col-md-5 text-center'>
                                                <?php 
                                                    $favourite = "Select COUNT(pid) as count, pid from productstatistics where type = 'favourite' group by pid order by count desc LIMIT 1;";
                                                    $favprod = mysqli_query($link, $favourite);

                                                    if (!mysqli_query($link, $favourite)) {
                                                        die(mysqli_error($link));
                                                    } else {
                                                        if($favprod -> num_rows > 0) {
                                                            $row = mysqli_fetch_assoc($favprod);
                                                            $prod = "Select * from products where pid='".$row['pid']."';";
                                                            $pres = mysqli_query($link, $prod);

                                                            if(!mysqli_query($link, $prod)) {
                                                                die(mysqli_error($link));
                                                            } else {
                                                                if($pres -> num_rows > 0) {
                                                                    $prow = mysqli_fetch_assoc($pres);
                                                                    $imgArr = explode(",", $prow['featured']);
                                                                    $imgpos = strpos($imgArr[0], '/');
                                                                    $img = substr($imgArr[0], $imgpos+1);
                                                                    echo "<img src='".$img."' width='200'><br>";
                                                                    echo "<div class='text-center'>".$prow['name']."</div>";
                                                                    echo "<span>".$row['count']." favourites</span>";
                                                                }
                                                            }
                                                        }
                                                    }
                                                ?>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div>
                                                <h4>Product Most Added to Cart</h4>
                                                <div class='col-md-5 text-center'>
                                                    <h5>Purchase</h5>
                                                <?php 
                                                    $addCart = "Select COUNT(pid) as count, pid from productstatistics where type = 'cartpurchase' group by pid order by count desc LIMIT 1;";
                                                    $addpurchase = mysqli_query($link, $addCart);
                                                    
                                                    if (!mysqli_query($link, $addCart)) {
                                                        die(mysqli_error($link));
                                                    } else {
                                                        if($addpurchase -> num_rows > 0) {
                                                            $row = mysqli_fetch_assoc($addpurchase);
                                                            $prod = "Select * from products where pid='".$row['pid']."';";
                                                            $pres = mysqli_query($link, $prod);
                                                            
                                                            if(!mysqli_query($link, $prod)) {
                                                                die(mysqli_error($link));
                                                            } else {
                                                                if($pres -> num_rows > 0) {
                                                                    $prow = mysqli_fetch_assoc($pres);
                                                                    $imgArr = explode(",", $prow['featured']);
                                                                    $imgpos = strpos($imgArr[0], '/');
                                                                    $img = substr($imgArr[0], $imgpos+1);
                                                                    echo "<img src='".$img."' width='200'><br>";
                                                                    echo "<div class='text-center'>".$prow['name']."</div>";
                                                                    echo "<span>".$row['count']." times</span>";
                                                                }
                                                            }
                                                        }
                                                    }
                                                ?>
                                                </div>
                                                <div class='col-md-5 text-center'>
                                                    <h5>Home Try-on</h5>
                                                <?php 
                                                    $addTry = "Select COUNT(pid) as count, pid from productstatistics where type = 'carttry' group by pid order by count desc LIMIT 1;";
                                                    $addHometry = mysqli_query($link, $addTry);
                                                    
                                                    if (!mysqli_query($link, $addTry)) {
                                                        die(mysqli_error($link));
                                                    } else {
                                                        if($addHometry -> num_rows > 0) {
                                                            $row = mysqli_fetch_assoc($addHometry);
                                                            $prod = "Select * from products where pid='".$row['pid']."';";
                                                            $pres = mysqli_query($link, $prod);
                                                            
                                                            if(!mysqli_query($link, $prod)) {
                                                                die(mysqli_error($link));
                                                            } else {
                                                                if($pres -> num_rows > 0) {
                                                                    $prow = mysqli_fetch_assoc($pres);
                                                                    $imgArr = explode(",", $prow['featured']);
                                                                    $imgpos = strpos($imgArr[0], '/');
                                                                    $img = substr($imgArr[0], $imgpos+1);
                                                                    echo "<img src='".$img."' width='200'><br>";
                                                                    echo "<div class='text-center'>".$prow['name']."</div>";
                                                                    echo "<span>".$row['count']." times</span>";
                                                                }
                                                            }
                                                        }
                                                    }
                                                ?>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <?php 
                                                $chartArr = array(
                                                    "caption" => "Top 5 Searched Items", 
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
                                                    "captionFontSize" => "13",
                                                    "subcaptionFontSize" => "13",
                                                    "subcaptionFontBold" => "0",
                                                    "toolTipColor" => "#ffffff",
                                                    "toolTipBorderThickness" => "0",
                                                    "toolTipBgColor" => "#ccc",
                                                    "toolTipBgAlpha" => "80",
                                                    "toolTipBorderRadius" => "2",
                                                    "toolTipPadding" => "5",
                                                    "showLegend" => "1",
                                                    "legendBgColor" => "#ffffff",
                                                    "legendBorderAlpha" => "0",
                                                    "legendShadow" => "0",
                                                    "legendItemFontSize" => "10",
                                                    "legendItemFontColor" => "#666666",
                                                    "useDataPlotColorForLabels" => "1");

                                                $data = array("chart" => $chartArr, "data" => "");
                //                                
                                                $search = "Select count(distinct keyword) as count, keyword from searchstatistics where type='product' order by count desc LIMIT 5;";
                                                $searchres = mysqli_query($link, $search);
                                                
                                                if (!mysqli_query($link, $search)) {
                                                    die(mysqli_error($link));
                                                } else {
                                                    $result = array();
                                                    $count = 0;
                                                    if($searchres -> num_rows > 0) {
                                                        while($row = mysqli_fetch_assoc($searchres)) {
                                                            $result[$count] = array($row['keyword'] => $row['count']);
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
                                                    "column2D", 
                                                    "search" , 
                                                    "420", 
                                                    "300", 
                                                    "searchChart", 
                                                    "json", 
                                                    $arr);

                                                $columnChart->render();
                                            ?>
                                            <div id="searchChart"></div>
                                        </td>
                                        <td>
                                            <?php 
                                                $chartArr['caption'] = "Top 5 Sold Items";
                                                $solddata = array("chart" => $chartArr, "data" => "");
                //                                
                                                $sold = "Select count(pid) as count, pid from productstatistics where type = 'purchase' or type='giftcard' group by pid order by count desc LIMIT 5";
                                                $soldres = mysqli_query($link, $sold);
                                                
                                                if (!mysqli_query($link, $sold)) {
                                                    die(mysqli_error($link));
                                                } else {
                                                    $result = array();
                                                    $count = 0;
                                                    if($soldres -> num_rows > 0) {
                                                        while($row = mysqli_fetch_assoc($soldres)) {
                                                            //change pid to product name
                                                            $pid = $row['pid'];
                                                            $psql = "Select * from products where pid='$pid';";
                                                            $res = mysqli_query($link, $psql);
                                                            $name;
                                                            
                                                            if (!mysqli_query($link, $psql)) {
                                                                die(mysqli_error($link));
                                                            } else {
                                                                if ($res -> num_rows > 0) {
                                                                    $prow = mysqli_fetch_assoc($res);
                                                                    $name = $prow['name'];
                                                                } else {
//                                                                    $sql = "Select * from giftcards where code='$pid';";
//                                                                    $cardres = mysqli_query($link, $sql);
//                                                                    
//                                                                    if (!mysqli_query($link, $sql)) {
//                                                                        die(mysqli_error($link));
//                                                                    } else {
//                                                                        if ($cardres -> num_rows > 0) {
//                                                                            $cardrow = mysqli_fetch_assoc($cardres);
//                                                                            $name = "$".$cardrow['amount']." giftcard";
//                                                                        }
//                                                                    }
                                                                    $name = "giftcard";
                                                                }
                                                            }
                                                            
                                                            $result[$count] = array($name => $row['count']);
                                                            $count++;
                                                        }
                                                    } 
                                                }
                                                
                                                $solddata['data'] = array();
                                                // Iterate through the data in `$actualData` and insert in to the `$arrData` array.
                                                foreach ($result as $key => $value) {
                                                    foreach($value as $k => $v) {
                                                        array_push($solddata['data'],
                                                            array(
                                                                'label' => $k,
                                                                'value' => $v
                                                            )
                                                        );
                                                    }
                                                }
                //                                print_r($data['data']);
                                                $soldarr = json_encode($solddata);

                //                                print_r($arr);
                                                $soldChart = new FusionCharts(
                                                    "doughnut3D", 
                                                    "sold" , 
                                                    "420", 
                                                    "300", 
                                                    "soldChart", 
                                                    "json", 
                                                    $soldarr);

                                                $soldChart->render();
                                            ?>
                                            <div id="soldChart"></div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan='2'>
                                            <?php 
                                                $breakdownChartArr = array(
                                                    "caption" => "Sales Breakdown",
                                                    "subCaption" => "By Location",
                                                    "xAxisName" => "Source",
                                                    "yAxisName" => "Sales (In SGD)",
                                                    "numberPrefix" => "$",
                                                    "paletteColors" => "#0075c2",
                                                    "bgColor" => "#ffffff",
                                                    "showBorder" => "0",
                                                    "showCanvasBorder" => "0",
                                                    "plotBorderAlpha" => "10",
                                                    "usePlotGradientColor" => "0",
                                                    "plotFillAlpha" => "50",
                                                    "showXAxisLine" => "1",
                                                    "axisLineAlpha" => "25",
                                                    "divLineAlpha" => "10",
                                                    "showValues" => "1",
                                                    "showAlternateHGridColor" => "0",
                                                    "captionFontSize" => "14",
                                                    "subcaptionFontSize" => "14",
                                                    "subcaptionFontBold" => "0",
                                                    "toolTipColor" => "#ffffff",
                                                    "toolTipBorderThickness" => "0",
                                                    "toolTipBgColor" => "#cccccc",
                                                    "toolTipBgAlpha" => "80",
                                                    "toolTipBorderRadius" => "2",
                                                    "toolTipPadding" => "5"
                                                );
                                                $breakdowndata = array("chart" => $breakdownChartArr, "data" => "");
                //                                
                                                $sales = "Select SUM(totalcost) as total, location from orders group by location order by total desc;";
                                                $sres = mysqli_query($link, $sales);
                                                
                                                if(!mysqli_query($link, $sales)) {
                                                    die(mysqli_error($link));
                                                } else {
                                                    $result = array();
                                                    $count = 0;
                                                    if ($sres -> num_rows > 0) {
                                                        while($row = mysqli_fetch_assoc($sres)) {
                                                            $result[$count] = array($row['location'] => $row['total']);
                                                            $count++;
                                                        }
                                                    }
                                                }
                                                
                                                $breakdowndata['data'] = array();
                                                // Iterate through the data in `$actualData` and insert in to the `$arrData` array.
                                                foreach ($result as $key => $value) {
                                                    foreach($value as $k => $v) {
                                                        array_push($breakdowndata['data'],
                                                            array(
                                                                'label' => $k,
                                                                'value' => $v
                                                            )
                                                        );
                                                    }
                                                }
                //                                print_r($data['data']);
                                                $breakdownarr = json_encode($breakdowndata);

                //                                print_r($arr);
                                                $breakdownChart = new FusionCharts(
                                                    "column2d", 
                                                    "breakdown" , 
                                                    "420", 
                                                    "300", 
                                                    "salesBreakdown", 
                                                    "json", 
                                                    $breakdownarr);

                                                $breakdownChart->render();
                                            ?>
                                            <div id="salesBreakdown"></div>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                            
                            <div id="menu1" class="tab-pane fade">
                                <h1 class="page-header">Staff Statistics</h1>
                                <div id="updates">
                                    <h4>Recent Logins</h4>
                                    <?php 
                                        $users = "Select firstname, lastname, lastlogin, lastlogout from staff order by lastlogin desc LIMIT 5";
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
                                <div>
                                    <h4>Sales Breakdown</h4>
                                    <?php 
                                        $staffChartArr = array(
                                            "caption" => "Sales Breakdown",
                                            "subCaption" => "By Staff",
                                            "xAxisName" => "Staff",
                                            "yAxisName" => "Sales (In SGD)",
                                            "numberPrefix" => "$",
                                            "paletteColors" => "#0075c2",
                                            "bgColor" => "#ffffff",
                                            "showBorder" => "0",
                                            "showCanvasBorder" => "0",
                                            "plotBorderAlpha" => "10",
                                            "usePlotGradientColor" => "0",
                                            "plotFillAlpha" => "50",
                                            "showXAxisLine" => "1",
                                            "axisLineAlpha" => "25",
                                            "divLineAlpha" => "10",
                                            "showValues" => "1",
                                            "showAlternateHGridColor" => "0",
                                            "captionFontSize" => "14",
                                            "subcaptionFontSize" => "14",
                                            "subcaptionFontBold" => "0",
                                            "toolTipColor" => "#ffffff",
                                            "toolTipBorderThickness" => "0",
                                            "toolTipBgColor" => "#cccccc",
                                            "toolTipBgAlpha" => "80",
                                            "toolTipBorderRadius" => "2",
                                            "toolTipPadding" => "5"
                                        );
                                        $staffdata = array("chart" => $staffChartArr, "data" => "");
        //                                
                                        $staff = "Select SUM(totalcost) as total, staff from orders group by staff order by total desc;";
                                        $staffres = mysqli_query($link, $staff);

                                        if(!mysqli_query($link, $staff)) {
                                            die(mysqli_error($link));
                                        } else {
                                            $result = array();
                                            $count = 0;
                                            if ($staffres -> num_rows > 0) {
                                                while($row = mysqli_fetch_assoc($staffres)) {
                                                    $email = $row['staff'];
                                                    
                                                    $emp = "Select * from staff where email='$email';";
                                                    $eres = mysqli_query($link, $emp);
                                                    
                                                    if(!mysqli_query($link, $emp)) {
                                                        die(mysqli_error($link));
                                                    } else {
                                                        if($eres -> num_rows > 0) {
                                                            $erow = mysqli_fetch_assoc($eres);
                                                            $staffname = $erow['firstname']." ".$erow['lastname'];
                                                            $result[$count] = array($staffname => $row['total']);
                                                            $count++;
                                                        }
                                                    }
                                                }
                                            }
                                        }

                                        $staffdata['data'] = array();
                                        // Iterate through the data in `$actualData` and insert in to the `$arrData` array.
                                        foreach ($result as $key => $value) {
                                            foreach($value as $k => $v) {
                                                array_push($staffdata['data'],
                                                    array(
                                                        'label' => $k,
                                                        'value' => $v
                                                    )
                                                );
                                            }
                                        }
        //                                print_r($data['data']);
                                        $staffarr = json_encode($staffdata);

        //                                print_r($arr);
                                        $staffChart = new FusionCharts(
                                            "column2d", 
                                            "staff" , 
                                            "620", 
                                            "450", 
                                            "staffBreakdown", 
                                            "json", 
                                            $staffarr);

                                        $staffChart->render();
                                    ?>
                                    <div id="staffBreakdown"></div>
                                </div>
                            </div>
                            <div id="stores" class="tab-pane fade">
                                <h1 class="page-header">Store Statistics</h1>
                                <h3 class='text-center'>Online Sales Breakdown</h3>
                                <div class='col-md-5'>
                                    <?php 
                                        $chartArr['caption'] = "Purchases";

                                        $onlinedata = array("chart" => $chartArr, "data" => "");
        //                                
                                        $online = "Select SUM(totalcost) as total, pid from orders where location='online' and (type='purchase' or type='giftcard') group by pid order by total desc;";
                                        $onlineres = mysqli_query($link, $online);

                                        if(!mysqli_query($link, $online)) {
                                            die(mysqli_error($link));
                                        } else {
                                            $result = array();
                                            $count = 0;
                                            if ($onlineres -> num_rows > 0) {
                                                while($row = mysqli_fetch_assoc($onlineres)) {
                                                    $pid = $row['pid'];
                                                    
                                                    $prod = "Select * from products where pid ='$pid';";
                                                    $pres = mysqli_query($link, $prod);
                                                    
                                                    if(!mysqli_query($link, $prod)) {
                                                        die(mysqli_error($link));
                                                    } else {
                                                        if ($pres -> num_rows > 0) {
                                                            $prow = mysqli_fetch_assoc($pres);
                                                            $name = $prow['name'];
                                                        } else {
                                                            $name = "giftcard";
                                                        }
                                                        
                                                        $result[$count] = array($prow['name'] => $row['total']);
                                                        $count++;
                                                    }
                                                }
                                            }
                                        }

                                        $onlinedata['data'] = array();
                                        // Iterate through the data in `$actualData` and insert in to the `$arrData` array.
                                        foreach ($result as $key => $value) {
                                            foreach($value as $k => $v) {
                                                array_push($onlinedata['data'],
                                                    array(
                                                        'label' => $k,
                                                        'value' => $v
                                                    )
                                                );
                                            }
                                        }
        //                                print_r($data['data']);
                                        $onlinearr = json_encode($onlinedata);

        //                                print_r($arr);
                                        $onlineChart = new FusionCharts(
                                            "column2d", 
                                            "online" , 
                                            "420", 
                                            "300", 
                                            "onlineBreakdown", 
                                            "json", 
                                            $onlinearr);

                                        $onlineChart->render();
                                    ?>
                                    <div id="onlineBreakdown"></div>
                                </div>
                                <div class='col-md-5 col-md-offset-1'>
                                    <?php 
                                        $chartArr['caption'] = "Home Try-on";
                                        $onlineHomedata = array("chart" => $chartArr, "data" => "");
        //                                
                                        $onlineHome = "Select COUNT(pid) as count, pid from orders where location='online' and type = 'hometry' group by pid order by count desc;";
                                        $onlineHomeres = mysqli_query($link, $onlineHome);

                                        if(!mysqli_query($link, $onlineHome)) {
                                            die(mysqli_error($link));
                                        } else {
                                            $result = array();
                                            $count = 0;
                                            if ($onlineHomeres -> num_rows > 0) {
                                                while($crow = mysqli_fetch_assoc($onlineHomeres)) {
                                                    $pid = $crow['pid'];
                                                    
                                                    $prod = "Select * from products where pid ='$pid';";
                                                    $pres = mysqli_query($link, $prod);
                                                    
                                                    if(!mysqli_query($link, $prod)) {
                                                        die(mysqli_error($link));
                                                    } else {
                                                        if ($pres -> num_rows > 0) {
                                                            $prow = mysqli_fetch_assoc($pres);
                                                            $result[$count] = array($prow['name'] => $crow['count']);
                                                            $count++;
                                                        }
                                                    }
                                                }
                                            }
                                        }

                                        $onlineHomedata['data'] = array();
                                        // Iterate through the data in `$actualData` and insert in to the `$arrData` array.
                                        foreach ($result as $key => $value) {
                                            foreach($value as $k => $v) {
                                                array_push($onlineHomedata['data'],
                                                    array(
                                                        'label' => $k,
                                                        'value' => $v
                                                    )
                                                );
                                            }
                                        }
        //                                print_r($data['data']);
                                        $onlineHomearr = json_encode($onlineHomedata);

        //                                print_r($arr);
                                        $onlineHomeChart = new FusionCharts(
                                            "doughnut3d", 
                                            "onlineHome" , 
                                            "420", 
                                            "300", 
                                            "onlineHometry", 
                                            "json", 
                                            $onlineHomearr);

                                        $onlineHomeChart->render();
                                    ?>
                                    <div id="onlineHometry"></div>
                                </div>
                                
                                <h3 class='text-center'>Offline Sales Breakdown</h3>
                                <div class='col-md-5'>
                                    <?php 
                                        $chartArr['caption'] = "Purchases";

                                        $offlinedata = array("chart" => $chartArr, "data" => "");
        //                                
                                        $offline = "Select SUM(totalcost) as total, pid from orders where location<>'online' and (type='purchase' or type='giftcard') group by pid order by total desc;";
                                        $offlineres = mysqli_query($link, $offline);

                                        if(!mysqli_query($link, $offline)) {
                                            die(mysqli_error($link));
                                        } else {
                                            $result = array();
                                            $count = 0;
                                            if ($offlineres -> num_rows > 0) {
                                                while($row = mysqli_fetch_assoc($offlineres)) {
                                                    $pid = $row['pid'];
                                                    
                                                    $prod = "Select * from products where pid ='$pid';";
                                                    $pres = mysqli_query($link, $prod);
                                                    
                                                    if(!mysqli_query($link, $prod)) {
                                                        die(mysqli_error($link));
                                                    } else {
                                                        if ($pres -> num_rows > 0) {
                                                            $prow = mysqli_fetch_assoc($pres);
                                                            $name = $prow['name'];
                                                        } else {
                                                            $name = "giftcard";
                                                        }
                                                        
                                                        $result[$count] = array($prow['name'] => $row['total']);
                                                        $count++;
                                                    }
                                                }
                                            }
                                        }

                                        $offlinedata['data'] = array();
                                        // Iterate through the data in `$actualData` and insert in to the `$arrData` array.
                                        foreach ($result as $key => $value) {
                                            foreach($value as $k => $v) {
                                                array_push($offlinedata['data'],
                                                    array(
                                                        'label' => $k,
                                                        'value' => $v
                                                    )
                                                );
                                            }
                                        }
        //                                print_r($data['data']);
                                        $offlinearr = json_encode($offlinedata);

        //                                print_r($arr);
                                        $offlineChart = new FusionCharts(
                                            "column2d", 
                                            "offline" , 
                                            "420", 
                                            "300", 
                                            "offlineBreakdown", 
                                            "json", 
                                            $offlinearr);

                                        $offlineChart->render();
                                    ?>
                                    <div id="offlineBreakdown"></div>
                                </div>
                                <div class='col-md-5 col-md-offset-1'>
                                    <?php 
                                        $chartArr['caption'] = "Home Try-on";
                                        $offlineHomedata = array("chart" => $chartArr, "data" => "");
        //                                
                                        $offlineHome = "Select COUNT(pid) as count, pid from orders where location <> 'online' and type = 'hometry' group by pid order by count desc;";
                                        $offlineHomeres = mysqli_query($link, $offlineHome);

                                        if(!mysqli_query($link, $offlineHome)) {
                                            die(mysqli_error($link));
                                        } else {
                                            $result = array();
                                            $count = 0;
                                            if ($offlineHomeres -> num_rows > 0) {
                                                while($crow = mysqli_fetch_assoc($offlineHomeres)) {
                                                    $pid = $crow['pid'];
                                                    
                                                    $prod = "Select * from products where pid ='$pid';";
                                                    $pres = mysqli_query($link, $prod);
                                                    
                                                    if(!mysqli_query($link, $prod)) {
                                                        die(mysqli_error($link));
                                                    } else {
                                                        if ($pres -> num_rows > 0) {
                                                            $prow = mysqli_fetch_assoc($pres);
                                                            $result[$count] = array($prow['name'] => $crow['count']);
                                                            $count++;
                                                        }
                                                    }
                                                }
                                            }
                                        }

                                        $offlineHomedata['data'] = array();
                                        // Iterate through the data in `$actualData` and insert in to the `$arrData` array.
                                        foreach ($result as $key => $value) {
                                            foreach($value as $k => $v) {
                                                array_push($offlineHomedata['data'],
                                                    array(
                                                        'label' => $k,
                                                        'value' => $v
                                                    )
                                                );
                                            }
                                        }
        //                                print_r($data['data']);
                                        $offlineHomearr = json_encode($offlineHomedata);

        //                                print_r($arr);
                                        $offlineHomeChart = new FusionCharts(
                                            "doughnut3d", 
                                            "offlineHome" , 
                                            "420", 
                                            "300", 
                                            "offlineHometry", 
                                            "json", 
                                            $offlineHomearr);

                                        $offlineHomeChart->render();
                                    ?>
                                    <div id="offlineHometry"></div>
                                </div>
                            </div>
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

<?php } ?>

<script>
    $(document).ready(function() {
        if(location.hash) {
            $('a[href=' + location.hash + ']').tab('show');
        }
        $(document.body).on("click", "a[data-toggle]", function(event) {
            location.hash = this.getAttribute("href");
        });
    });
    
    $(window).on('popstate', function() {
        var anchor = location.hash || $("a[data-toggle=tab]").first().attr("href");
        $('a[href=' + anchor + ']').tab('show');
    });
</script>