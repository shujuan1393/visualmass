<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
require_once '../config/db.php';
require_once '../PHPExcel/PHPExcel.php';

//unset($_SESSION['exportSetError']);
//unset($_SESSION['exportSetSuccess']);

if (isset($_POST['submit'])) {
    $statsArr = $_POST['statistics'];
    
    if (empty($statsArr)) {
        unset($_SESSION['exportSetSuccess']);
        $_SESSION['exportSetError'] = "No valid selection for data export";
    } else {
        unset($_SESSION['exportSetError']);
        $printArr = array();
        $nameArr = array();

        for($i = 0; $i < count($statsArr); $i++) {
            $stat = $statsArr[$i];

            if (strcmp($stat, "favouriteProd") === 0) {
                $sql = "Select COUNT(pid) as count, pid from productstatistics where type = 'favourite' group by pid order by count desc LIMIT 1;";
                $result = mysqli_query($link, $sql);
                array_push($printArr, $result);
                array_push($nameArr, "Most Favourited Product");
            } else if (strcmp($stat, "viewedProd") === 0) {
                $sql = "Select COUNT(pid) as count, pid from productstatistics where type = 'viewproduct' group by pid order by count desc LIMIT 1;";
                $result = mysqli_query($link, $sql);
                array_push($printArr, $result);
                array_push($nameArr, "Most Viewed Product");
            } else if (strcmp($stat, "todayOrders") === 0) {
                $sql = "Select SUM(totalcost) as total, location from orders where DATE(datepaid)=CURDATE() group by location;";
                $result = mysqli_query($link, $sql);
                array_push($printArr, $result); 
                array_push($nameArr, "Today's Orders");           
            } else if (strcmp($stat, "topSearched") === 0) {
                $sql = "Select count(distinct keyword) as count, keyword from searchstatistics where type='product' order by count desc LIMIT 5;";
                $result = mysqli_query($link, $sql);
                array_push($printArr, $result);      
                array_push($nameArr, "Top 5 Most Searched Items");                               
            } else if (strcmp($stat, "topSold") === 0) {
                $sql = "Select count(pid) as count, pid from productstatistics where type = 'purchase' or type='giftcard' group by pid order by count desc LIMIT 5";
                $result = mysqli_query($link, $sql);
                array_push($printArr, $result);             
                array_push($nameArr, "Top 5 Most Purchased Items");                         
            } else if (strcmp($stat, "purchaseCartedProd") === 0) {
                $sql = "Select COUNT(pid) as count, pid from productstatistics where type = 'cartpurchase' group by pid order by count desc LIMIT 1;";
                $result = mysqli_query($link, $sql);
                array_push($printArr, $result);    
                array_push($nameArr, "Most Added to Cart (Purchase)");                                       
            } else if (strcmp($stat, "hometryCartedProd") === 0) {
                $sql = "Select COUNT(pid) as count, pid from productstatistics where type = 'carttry' group by pid order by count desc LIMIT 1;";
                $result = mysqli_query($link, $sql);
                array_push($nameArr, "Most Added to Cart (Try-on)");
                array_push($printArr, $result);                                         
            } else if (strcmp($stat, "locationBreakdown") === 0) {
                $sql = "Select SUM(totalcost) as total, location from orders group by location order by total desc;";
                $result = mysqli_query($link, $sql);
                array_push($printArr, $result);     
                array_push($nameArr, "Sales Breakdown (Location)");                               
            } else if (strcmp($stat, "staffLogin") === 0) {
                $sql = "Select firstname, lastname, lastlogin, lastlogout from staff order by lastlogin desc LIMIT 5";
                $result = mysqli_query($link, $sql);
                array_push($nameArr, "Recent Staff Logins");
                array_push($printArr, $result);                             
            } else if (strcmp($stat, "staffBreakdown") === 0) {
                $sql = "Select SUM(totalcost) as total, staff from orders group by staff order by total desc;";
                $result = mysqli_query($link, $sql);
                array_push($printArr, $result);   
                array_push($nameArr, "Sales Breakdown (Staff)");                         
            } else if (strcmp($stat, "onlinePurchaseBreakdown") === 0) {
                $sql = "Select SUM(totalcost) as total, pid from orders where location = 'online' and (type='purchase' or type='giftcard') group by pid order by total desc;";
                $result = mysqli_query($link, $sql);
                array_push($printArr, $result); 
                array_push($nameArr, "Online Sales (Purchase)");                                 
            } else if (strcmp($stat, "onlineHometryBreakdown") === 0) {
                $sql = "Select COUNT(pid) as count, pid from orders where location ='online' and type = 'hometry' group by pid order by count desc;";
                $result = mysqli_query($link, $sql);
                array_push($nameArr, "Online Sales (Try-on)");
                array_push($printArr, $result);      
            } else if (strcmp($stat, "offlinePurchaseBreakdown") === 0) {
                $sql = "Select SUM(totalcost) as total, pid from orders where location <> 'online' and (type='purchase' or type='giftcard') group by pid order by total desc;";
                $result = mysqli_query($link, $sql);
                array_push($printArr, $result);    
                array_push($nameArr, "Offline Sales (Purchase)");                            
            } else if (strcmp($stat, "offlineHometryBreakdown") === 0) {
                $sql = "Select COUNT(pid) as count, pid from orders where location <> 'online' and type = 'hometry' group by pid order by count desc;";
                $result = mysqli_query($link, $sql);
                array_push($printArr, $result);   
                array_push($nameArr, "Offline Sales (Try-on)");                           
            }
        }

        $filename = "statistics(".date("d-m-Y").")";

        // Create new PHPExcel object
        $objPHPExcel = new PHPExcel();

        // Set document properties
        $objPHPExcel->getProperties()->setLastModifiedBy("Visual Mass")
                                    ->setTitle("Visual Mass Statistics ".date('d-m-Y'));

        $alphas = range('A', 'Z');
        
        $objPHPExcel->getDefaultStyle()
            ->getAlignment()
            ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

        for($i = 0; $i < count($printArr); $i++) {
            $res = $printArr[$i];

            if ($i +1 !== count($printArr)) {
                //Setting index when creating
                $objPHPExcel->createSheet($i); 
            }

            if ($res -> num_rows === 0) {
                $objPHPExcel ->setActiveSheetIndex($i)
                            -> setCellValue("A1", "No data");
            } else {
                $valCount = 2;
                while ($row = mysqli_fetch_assoc($res)) {
                    $keys = array_keys($row);
                    for ($k = 0; $k < count($keys); $k++) {
                        $objPHPExcel ->setActiveSheetIndex($i)
                                -> setCellValue($alphas[$k]."1", $keys[$k]);
                        $objPHPExcel->getActiveSheet()
                                ->getColumnDimension($alphas[$k])
                                ->setWidth(20);
                        $objPHPExcel -> getActiveSheet()
                                ->getStyle($alphas[$k]."1")
                                ->getAlignment()
                                ->setWrapText(true);
                        //set bold
                        $objPHPExcel ->getActiveSheet()
                                ->getStyle($alphas[$k]."1")
                                ->getFont()
                                ->setBold(true);
                    }
                    $vals = array_values($row);
                    for($v = 0; $v < count($vals); $v++) {
                        if (empty($vals[$v])) {
                            $val = "NULL";
                        } else {
                            $val = $vals[$v];
                        }
                        $objPHPExcel ->setActiveSheetIndex($i)
                                -> setCellValue($alphas[$v].$valCount, $val);
                        $objPHPExcel->getActiveSheet()
                                ->getColumnDimension($alphas[$v])
                                ->setWidth(20);
                        $objPHPExcel -> getActiveSheet()
                                ->getStyle($alphas[$v].$valCount)
                                ->getAlignment()
                                ->setWrapText(true);
                    }
                    $valCount++;
                } 
            }
            // Rename worksheet
            $objPHPExcel->getActiveSheet()->setTitle($nameArr[$i]);
        }
        $filename = "VMstatistics_".date('dmY');
        $_SESSION['exportSetSuccess'] = "Data successfully exported";
        // Redirect output to a client’s web browser (Excel5)
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="'.$filename.'.xls"');
        header('Cache-Control: max-age=0');
        // If you're serving to IE 9, then the following may be needed
        header('Cache-Control: max-age=1');

        // If you're serving to IE over SSL, then the following may be needed
        //header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
        //header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
        //header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
        //header ('Pragma: public'); // HTTP/1.0

        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        $objWriter->save('php://output');
        exit;
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
                            <li>
                                Export
                            </li>
                        </ol>
                        
                        <h1 class="page-header">Export Statistics</h1>
        
                        <table class="content">
                            <form method='post' action='exportSettings.php'>
                                <div id="exportSetError" class="error">
                                    <?php
                                        if (isset($_SESSION['exportSetError'])) {
                                            echo $_SESSION['exportSetError'];
                                        }
                                    ?>
                                </div>

                                <div id="exportSetSuccess" class="success">
                                    <?php
                                        if (isset($_SESSION['exportSetSuccess'])) {
                                            echo $_SESSION['exportSetSuccess'];
                                        }
                                    ?>
                                </div>
                                <tr>
                                    <td>
                                        Select statistics to export:
                                        <br>
                                        <input type='checkbox' name='statistics[]' value='favouriteProd'>Most Favourited Product<br>
                                        <input type='checkbox' name='statistics[]' value='viewedProd'>Most Viewed Product<br>
                                        <input type='checkbox' name='statistics[]' value='todayOrders'>Total Orders Today (<?php echo date('d M Y'); ?>)<br>
                                        <input type='checkbox' name='statistics[]' value='topSearched'>Top 5 Searched Items<br>
                                        <input type='checkbox' name='statistics[]' value='topSold'>Top 5 Sold Items<br>
                                        <input type='checkbox' name='statistics[]' value='purchaseCartedProd'>Product Most Added to Cart For Purchase<br>
                                        <input type='checkbox' name='statistics[]' value='hometryCartedProd'>Product Most Added to Cart For Home Try-on<br>
                                        <input type='checkbox' name='statistics[]' value='locationBreakdown'>Sales Breakdown (Location)<br>
                                        <input type='checkbox' name='statistics[]' value='staffLogin'>Recent Staff Login<br>
                                        <input type='checkbox' name='statistics[]' value='staffBreakdown'>Sales Breakdown (Staff)<br>
                                        <input type='checkbox' name='statistics[]' value='onlinePurchaseBreakdown'>Online Sales Breakdown (Purchase)<br>
                                        <input type='checkbox' name='statistics[]' value='onlineHometryBreakdown'>Online Sales Breakdown (Home Try-on)<br>
                                        <input type='checkbox' name='statistics[]' value='offlinePurchaseBreakdown'>Offline Sales Breakdown (Purchase)<br>
                                        <input type='checkbox' name='statistics[]' value='offlineHometryBreakdown'>Offline Sales Breakdown (Home Try-on)<br>
                                    </td>
                                    <td class='pull-right'>
                                        <button id='markExports' class='button' onClick="selectAll()">MARK ALL</button>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2">
                                        <input type='submit' name='submit' id='exportButton' value='Export'/>
                                    </td>
                                </tr>
                            </form>
                        </table>
                    </div>
                </div>
                <!-- /.row -->

            </div>
            <!-- /.container-fluid -->

        </div>
        <!-- /#page-wrapper -->
    </div>
        
    <script>
        function selectAll() {
            event.preventDefault();
            var checkboxes = document.getElementsByName('statistics[]');
            for(var i in checkboxes) {
                if (checkboxes[i].checked === false) {
                    checkboxes[i].checked = true;
                } else {
                    checkboxes[i].checked = false;
                }
            }
	}
    </script>
</html>