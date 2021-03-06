<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
require_once '../config/db.php';

if (isset($_GET['id'])) {
    $selectSql = "Select * from discounts where id ='" .$_GET['id']."';";
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
            <div class="bc-top bg-white">
                <img src="../icons/admin/discounts16.png" alt="" class="bc-img pull-left"/>
                <div class="pull-left">Discounts
                </div>
                <div class="pull-right">
                    <a href="#addDiscount"><i class="fa fa-fw fa-plus"></i> Add Discount</a>
                </div>
                <div class="clearfix"></div>
            </div>
            
            <div id="page-wrapper">

            <div class="container-fluid">

                <!-- Page Heading -->
                <div class="row">
                    <div class="col-lg-12">
                        <ul class="nav nav-tabs" id="myTabs">
                            <li class="active"><a data-toggle="tab" href="#discounts">Discounts</a></li>
                            <li><a data-toggle="tab" href="#discountHistory">Discount History</a></li>
                        </ul>

                        <div class="tab-content">
                            <div id="discounts" class="tab-pane fade in active">
                            <h3 class="page-header">Manage Discounts</h3>
                            
                            <?php if (isset($_SESSION['updateDiscSuccess'])) { ?>
                            <div id="updateDiscSuccess" class="alert alert-success">
                                <?php echo $_SESSION['updateDiscSuccess']; ?>
                            </div>
                            <?php } ?>
                            
                            <?php if (isset($_SESSION['updateDiscError'])) { ?>
                            <div id="updateDiscError" class="alert alert-danger">
                                <?php echo $_SESSION['updateDiscError']; ?>
                            </div>
                            <?php } ?>

                            <?php 
                                $qry = "Select * from discounts";

                                $result = mysqli_query($link, $qry);

                                if (!mysqli_query($link,$qry))
                                {
                                    echo("Error description: " . mysqli_error($link));
                                } else {
                                    if ($result->num_rows === 0) {
                                        echo "You have not created any discounts yet.";
                                    } else {
                            ?>

                            <div class="pull-left filter-align">Filter: </div>
                            <div style="overflow:hidden">
                                <input type="text" id="filter" class="pull-right" placeholder="Type here to search for discounts">
                            </div>

                            <table id ="example">
                                <thead>
                                    <th>Discount Name</th>
                                    <th>Limit</th>
                                    <th>Recurrence</th>
                                    <th>Usage</th>
                                    <th>Validity</th>
                                    <th>Status</th>
                                    <th>Action</th>                        
                                </thead>
                                <tbody class="searchable">
                                <?php
                                    // output data of each row
                                    while ($row = mysqli_fetch_assoc($result)) {
                                        echo "<tr>";
                                        echo "<td>".$row['name']."<br/>(".$row['code'].")</td>";                            
                                        echo "<td>".$row['disclimit']."</td>";                           
                                        echo "<td>".$row['recurrence']."</td>";                           
                                        echo "<td>".$row['discusage']."</td>";                              
                                        echo "<td>".date("d M Y", strtotime($row['start']))." to ".date("d M Y", strtotime($row['end']))."</td>";                           
                                        echo "<td>".$row['status']."</td>";                        
                                        echo '<td><a onClick="window.location.href=`discounts.php?id='.$row['id'].'`"><i class="fa fa-pencil"></i></a>'
                                                . '<a onClick="deleteFunction('.$row['id'].')"><i class="fa fa-trash-o"></i></a></td>';
                                        echo "</tr>";
                                    }
                                ?>
                                </tbody>
                            </table>

                            <?php
                                } 
                            }
                            ?>
                        </div>
                            
                        <div id="discountHistory" class="tab-pane fade">                            
                            <div class="pull-left filter-align">Filter: </div>
                            <div style="overflow:hidden">
                                <input type="text" id="filterhistory" class="pull-right" placeholder="Type here to search for discounts">
                            </div>

                            <?php 
                                $sql = "Select * from discountarchives;";
                                $res = mysqli_query($link, $sql);
                                
                                if(!mysqli_query($link, $sql)) {
                                    die(mysqli_error($link));
                                } else {
                            ?>
                                <table id ="examplehistory">
                                    <thead>
                                        <th>Discount Name</th>
                                        <th>Use Limit</th>
                                        <th>Recurrence</th>
                                        <th>Usage (C/E)</th>
                                        <th>Validity</th>                      
                                    </thead>
                                    <tbody class="searchableHist">
                                    <?php
                                        // output data of each row
                                        while ($row = mysqli_fetch_assoc($res)) {
                                            echo "<tr>";
                                            echo "<td>".$row['name']."(".$row['code'].")</td>";                            
                                            echo "<td>".$row['disclimit']."</td>";                           
                                            echo "<td>".$row['recurrence']."</td>";                           
                                            echo "<td>".$row['discusage']."</td>";                              
                                            echo "<td>".date("d M Y", strtotime($row['start']))." to ".date("d M Y", strtotime($row['end']))."</td>";
                                            echo "</tr>";
                                        }
                                    ?>
                                    </tbody>
                                </table>
                            <?php
                                }
                            ?>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /.row -->

            <form id='addDiscount' action='processDiscounts.php' method='post'>
            <div class="row">
                <div class="col-lg-8">
                    <div class="vm-container vm-margin-t-30">
                        
                        <?php if (isset($_SESSION['addDiscError'])) { ?>
                        <div id="addDiscError" class="alert alert-danger">
                            <?php echo $_SESSION['addDiscError']; ?>
                        </div>
                        <?php } ?>
                        
                        <p id='nanError' class="alert alert-danger" style="display: none;">Please enter numbers only</p>

                        <?php if (isset($_SESSION['addDiscSuccess'])) { ?>
                        <div id="addDiscSuccess"  class="alert alert-success">
                            <?php echo $_SESSION['addDiscSuccess']; ?>
                        </div>
                        <?php } ?>
                        
                        <h4 class="page-header vm-margin-t-clear">Discount Code</h4>
                        
                        <input type='hidden' name='submitted' id='submitted' value='1'/>
                        <input type='hidden' name='editid' id='editid' 
                               value='<?php if (isset($_GET['id'])) { echo $erow['id']; }?>'/>
                        
                        <div class="row">
                            <div class="col-md-6">
                                Name:
                                    <input type='text' name='name' id='name'  maxlength="50" 
                                           value ="<?php 
                                    if (isset($_SESSION['name'])) {
                                        echo $_SESSION['name'];
                                    } else if (!empty($erow['name'])) {
                                        echo $erow['name'];
                                    } 
                                        ?>"/>
                            </div>
                            <div class="col-md-6">
                                Discount Code*: <br/>
                                <button type="button" onclick="randomString()" class="pull-right">Generate</button>
                                <div style="overflow: hidden;" >
                                    <input type='text' name='code' id='code' value ="<?php 
                                        if(isset($_SESSION['randomString'])) { 
                                            echo $_SESSION['randomString'];
                                        } else if (!empty($erow['code'])) {
                                            echo $erow['code'];
                                        }?>" maxlength="50" />
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <h4 class="page-header">Usage Limit</h4>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <?php
                                    if (isset($_SESSION['usage'])) {
                                        $usageArr = explode(",", $_SESSION['usage']);
                                    } else if (!empty($erow['discusage'])) { 
                                        $usageArr = explode(",", $erow['discusage']);
                                    }  
                                ?>
                                Usage*: <br/>
                                <div class="checkbox">
                                <label class="checkbox-inline">
                                    <input type='checkbox' name='usage[]' value="cust" <?php 
                                            if (!empty($erow['discusage']) || isset($_SESSION['usage'])) {
                                                if (in_array("cust", $usageArr)) {
                                                    echo " checked";
                                                }
                                            }
                                        ?>>Customer
                                </label>
                                <label class="checkbox-inline">
                                    <input type='checkbox' name='usage[]' value='emp' <?php 
                                            if (!empty($erow['discusage']) || isset($_SESSION['usage'])) {
                                                if (in_array("emp", $usageArr)) {
                                                    echo " checked";
                                                }
                                            }
                                        ?>>Employee
                                </label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                Limit Per User*:
                                <input type='text' name='userlimit' id='userlimit'  maxlength="50"  
                                       onkeypress="return isNumber(event)" value ="<?php 
                                        if (isset($_SESSION['userlimit'])) {
                                            echo $_SESSION['userlimit'];
                                        } else if (!empty($erow['userlimit'])) {
                                            echo $erow['userlimit'];
                                        }  
                                    ?>"/>
                            </div>
                        </div>
                        <div class="row vm-margin-t-20">
                            <div class="col-md-6">
                                Track Serial Number?: <br/>
                                <div class="checkbox">
                                    <label>
                                        <input type="checkbox" name="serial" value="yes" <?php 
                                                if (isset($_SESSION['serial'])) {
                                                    if(strcmp("yes", $_SESSION['serial']) === 0) {
                                                        echo " checked";
                                                    }
                                                } else if (!empty($erow['serial'])) {
                                                    if (strcmp("yes", $erow['serial']) === 0) {
                                                        echo " checked";
                                                    }
                                                } 
                                                ?>>Yes
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                Limit*:
                                <input type='text' name='limit' id='limit'  maxlength="50"  
                                       onkeypress="return isNumber(event)" value ="<?php 
                                        if (isset($_SESSION['disclimit'])) {
                                            echo $_SESSION['disclimit'];
                                        } else if (!empty($erow['disclimit'])) {
                                            echo $erow['disclimit'];
                                        } 
                                    ?>"/>
                            </div>
                        </div>
                        <div class="row">
                            <h4 class="page-header">Discount Condition</h4>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div id='conditiontype'>
                                    <?php 

                                    if (isset($_SESSION['condition'])) {
                                        $condition = $_SESSION['condition'];
                                    } else if(!empty($erow['disccondition'])){
                                        if (isset($erow['disccondition'])) {
                                            $condition = $erow['disccondition'];
                                        } else {
                                            $condition = "";
                                        }
                                    } 

                                    if (isset($_SESSION['discterms'])) {
                                        $disctype = $_SESSION['discterms'];
                                    } else if(!empty($erow['disctype'])){
                                        if (isset($erow['disctype'])) {
                                            $disctype = $erow['disctype'];
                                        } else {
                                            $disctype = "";
                                        }

                                        $typeArr = explode(" ", $disctype);
                                    }  

                                    ?>
                                    <select name='condition' id='condition'>
                                        <option value='null' <?php 
                                        if (isset($_SESSION['condition'])) {
                                            if (strcmp($_SESSION['condition'], "null") === 0) {
                                                echo " selected";
                                            }
                                        } else if ((!empty($typeArr)) && (in_array("", $typeArr))) {
                                            $_SESSION['editcondition'] = "";
                                            echo " selected";
                                        }
                                        ?>>Select Discount Type</option>

                                        <option value='upgrade' <?php 
                                        if (isset($_SESSION['condition'])) {
                                            if (strcmp($_SESSION['condition'], "upgrade") === 0) {
                                                echo " selected";
                                            }
                                        } else if ((!empty($typeArr)) && (in_array("Upgrade", $typeArr))) {
                                            $_SESSION['editcondition'] = "upgrade";
                                            echo " selected";
                                        }
                                        ?>>Free Upgrade</option>

                                        <option value='bundleamount' <?php 
                                        if (isset($_SESSION['condition'])) {
                                            if (strcmp($_SESSION['condition'], "bundleamount") === 0) {
                                                echo " selected";
                                            }

                                        } else if ((!empty($typeArr)) && (strcmp($typeArr[2], "For") === 0)) {
                                            $_SESSION['editcondition'] = "bundleamount";
                                            echo " selected";
                                        }
                                        ?>>Bundle Amount</option>

                                        <option value='bundlediscount' <?php 
                                        if (isset($_SESSION['condition'])) {
                                            if (strcmp($_SESSION['condition'], "bundlediscount") === 0) {
                                                echo " selected";
                                            }
                                        } else if ((!empty($typeArr)) && (in_array("Discount", $typeArr))) {
                                            $_SESSION['editcondition'] = "bundlediscount";
                                            echo " selected";
                                        }
                                        ?>>Bundle Discount</option>

                                        <option value='nextfree' <?php 
                                        if (isset($_SESSION['condition'])) {
                                            if (strcmp($_SESSION['condition'], "nextfree") === 0) {
                                                echo " selected";
                                            }
                                        } else if ((!empty($typeArr)) && (in_array("Next", $typeArr))) {
                                            $_SESSION['editcondition'] = "nextfree";
                                            echo " selected";
                                        }
                                        ?>>Next Pair Free</option>

                                        <option value='nextdiscount' <?php
                                        $nextdiscArr = array("Buy", "Next");
                                        if (!empty($typeArr)) { 
                                            $resArr = array_intersect($nextdiscArr, $typeArr);
                                        }

                                        if (isset($_SESSION['condition'])) {
                                            if (strcmp($_SESSION['condition'], "nextdiscount") === 0) {
                                                echo " selected";
                                            }
                                        } else if ((!empty($resArr)) && (!empty($disctype)) && count($resArr) === count($nextdiscArr) && is_numeric(strpos($disctype, "%"))) {
                                            $_SESSION['editcondition'] = "nextdiscount";
                                            echo " selected";
                                        }
                                        ?>>Next Pair Discounted</option>

                                        <option value='fixedpercent' <?php 
                                        $fixedArr = array("Get", "Off");
                                        if ((!empty($fixedArr)) && (!empty($typeArr))) {
                                            $percArr = array_intersect($fixedArr, $typeArr);
                                        }
                                        if (isset($_SESSION['condition'])) {
                                            if (strcmp($_SESSION['condition'], "fixedpercent") === 0) {
                                                echo " selected";
                                            }
                                        } else if ((!empty($percArr)) && (!empty($disctype)) && count($percArr) ===  count($fixedArr) && is_numeric(strpos($disctype, "%"))) {
                                            $_SESSION['editcondition'] = "fixedpercent";
                                            echo " selected";
                                        }
                                        ?>>Fixed Percentage Discount</option>
                                        <option value='fixedamount' <?php 
                                        if ((!empty($fixedArr)) && (!empty($typeArr))) {
                                            $amtArr = array_intersect($fixedArr, $typeArr);
                                        }

                                        if (isset($_SESSION['condition'])) {
                                            if (strcmp($_SESSION['condition'], "fixedamount") === 0) {
                                                echo " selected";
                                            }
                                        } else if ((!empty($percArr)) && (count($percArr) === count($fixedArr) && is_numeric(strpos($disctype, "$")))) {
                                            $_SESSION['editcondition'] = "fixedamount";
                                            echo " selected";
                                        }
                                        ?>>Fixed Amount Discount</option>
                                        <option value='shipping' <?php 
                                        if (isset($_SESSION['condition'])) {
                                            if (strcmp($_SESSION['condition'], "shipping") === 0) {
                                                echo " selected";
                                            }
                                        }
                                        ?>>Free Shipping</option>

                                    </select>
                                    <div id='bundleamount' class="row" style='display:none;'>
                                        <div class="col-md-2 vm-padding-l-clear vm-padding-t-15 pull-left" style="display:inline;">Buy </div>
                                        <div class="col-md-4 vm-padding-l-clear" style="overflow:hidden;">
                                            <input type='text' name='bundleamtqty' 
                                                   value='<?php 
                                                   if (isset($_SESSION['bundleamt']['qty'])) {
                                                       echo $_SESSION['bundleamt']['qty'];
                                                   } else if(isset($_SESSION['editcondition']) && !empty($typeArr[1])) {
                                                    if (strcmp($_SESSION['editcondition'], "bundleamount") === 0) { 
                                                        echo $typeArr[1];
                                                    } 
                                                } ?>'
                                                   onkeypress="return isNumber(event)">
                                        </div>
                                        <div class="col-md-2 vm-padding-r-clear vm-padding-t-15 pull-left" style="display:inline;">For $</div>
                                        <div class="col-md-4 vm-padding-r-clear" style="overflow:hidden;">
                                            <input type='text' name='bundleamtprice' 
                                                   value='<?php if (isset($_SESSION['bundleamt']['price'])) {
                                                       echo $_SESSION['bundleamt']['price'];
                                                   } if(isset($_SESSION['editcondition']) && !empty($typeArr[3])) {
                                                    if (strcmp($_SESSION['editcondition'], "bundleamount") === 0) { 
                                                        echo substr($typeArr[3], 1);
                                                    } 
                                                } ?>'
                                                   onkeypress="return isNumberKey(event)">
                                        </div>
                                    </div>
                                    <div id='bundlediscount' style='display:none;'>
                                        <div class="col-md-2 vm-padding-l-clear vm-padding-t-15 pull-left" style="display:inline;">Buy</div>
                                        <div class="col-md-4 vm-padding-l-clear" style="overflow:hidden;">
                                            <input type='text' name='bundlediscqty' 
                                                   value='<?php if (isset($_SESSION['bundledisc']['qty'])) {
                                                       echo $_SESSION['bundledisc']['qty'];
                                                   } else if(isset($_SESSION['editcondition']) && !empty($typeArr[1])) {
                                                    if (strcmp($_SESSION['editcondition'], "bundlediscount") === 0) { 
                                                        echo $typeArr[1];
                                                    } 
                                                } ?>'
                                                   onkeypress="return isNumber(event)">
                                        </div>
                                        <div class="col-md-4 vm-padding-l-clear" style="overflow:hidden;">
                                            <input type='text' name='bundlediscprice' 
                                                   value='<?php if (isset($_SESSION['bundledisc']['price'])) {
                                                       echo $_SESSION['bundledisc']['price'];
                                                   } else if(isset($_SESSION['editcondition']) && !empty($typeArr[3])) {
                                                    if (strcmp($_SESSION['editcondition'], "bundlediscount") === 0) { 
                                                        echo substr($typeArr[3], 0, -1); 
                                                    } 
                                                } ?>'
                                                   onkeypress="return isNumberKey(event)">
                                        </div>
                                        <div class="col-md-2 vm-padding-r-clear vm-padding-t-15 pull-left" style="display:inline;">% Off</div>
                                    </div>
                                    <div id='nextfree' style='display:none;'>
                                        <div class="col-md-2 vm-padding-l-clear vm-padding-t-15 pull-left" style="display:inline;">Buy</div>
                                        <div class="col-md-4 vm-padding-l-clear" style="overflow:hidden;">
                                            <input type='text' name='nextfreeqty' 
                                                   value='<?php if (isset($_SESSION['nextfree']['qty'])) {
                                                       echo $_SESSION['nextfree']['qty'];
                                                   } else if(isset($_SESSION['editcondition']) && !empty($typeArr[1])) {
                                                    if (strcmp($_SESSION['editcondition'], "nextfree") === 0) { 
                                                        echo $typeArr[1];
                                                    } 
                                                } ?>'
                                                   onkeypress="return isNumber(event)">
                                        </div>
                                        <div class="col-md-2 vm-padding-r-clear vm-padding-t-15 pull-left" style="display:inline;">Free</div>
                                        <div class="col-md-4 vm-padding-r-clear" style="overflow:hidden;">
                                            <input type='text' name='nextfreeamt'
                                                   value='<?php if (isset($_SESSION['nextfree']['amt'])) {
                                                       echo $_SESSION['nextfree']['amt'];
                                                   } else if(isset($_SESSION['editcondition']) && !empty($typeArr[3])) {
                                                    if (strcmp($_SESSION['editcondition'], "nextfree") === 0) { 
                                                        echo $typeArr[3];
                                                    } 
                                                } ?>'
                                                   onkeypress="return isNumber(event)">
                                        </div>
                                    </div>
                                    <div id='nextdiscount' style='display:none;'>
                                        <div class="col-md-2 vm-padding-l-clear vm-padding-t-15 pull-left" style="display:inline;">Buy</div>
                                        <div class="col-md-4 vm-padding-l-clear" style="overflow:hidden;">
                                            <input type='text' name='nextdiscqty' 
                                                   value='<?php if (isset($_SESSION['nextdisc']['qty'])) {
                                                       echo $_SESSION['nextdisc']['qty'];
                                                   } else if(isset($_SESSION['editcondition']) && !empty($typeArr[1])) {
                                                    if (strcmp($_SESSION['editcondition'], "nextdiscount") === 0) { 
                                                        echo $typeArr[1];
                                                    } 
                                                } ?>'
                                                   onkeypress="return isNumber(event)">
                                        </div>
                                        <div class="col-md-4 vm-padding-l-clear" style="overflow:hidden;">
                                            <input type='text' name='nextdiscamt' 
                                                   value='<?php if (isset($_SESSION['nextdisc']['amt'])) {
                                                       echo $_SESSION['nextdisc']['amt'];
                                                   } else if(isset($_SESSION['editcondition']) && !empty($typeArr[3])) {
                                                        if (strcmp($_SESSION['editcondition'], "nextdiscount") === 0) { 
                                                            echo substr($typeArr[4], 0, -1); 
                                                        } 
                                                    } ?>'
                                                   onkeypress="return isNumber(event)">
                                        </div>
                                        <div class="col-md-2 vm-padding-r-clear vm-padding-t-15 pull-left" style="display:inline;">% Off</div>
                                    </div>
                                    <div id='fixedpercent' style='display:none;'>
                                        <div class="col-md-4 vm-padding-l-clear" style="overflow:hidden;">
                                            <input type='text' name='fixedperc'
                                                value='<?php if (isset($_SESSION['fixedperc'])) {
                                                           echo $_SESSION['fixedperc'];
                                                       } else if(isset($_SESSION['editcondition']) && !empty($typeArr[1])) {
                                                        if (strcmp($_SESSION['editcondition'], "fixedpercent") === 0) { 
                                                            echo substr($typeArr[1], 0, -1); 
                                                        } 
                                                    } ?>'
                                                onkeypress="return isNumber(event)">
                                        </div>
                                        <div class="col-md-2 vm-padding-r-clear vm-padding-t-15 pull-left" style="display:inline;">% Off</div>
                                    </div>
                                    <div id='fixedamount' style='display:none;'>
                                        <div class="col-md-2 vm-padding-l-clear vm-padding-t-15 pull-left" style="display:inline;">$</div>
                                        <div class="col-md-4 vm-padding-l-clear" style="overflow:hidden;">
                                            <input type='text' name='fixedamt' 
                                                   value='<?php if (isset($_SESSION['fixedamt'])) {
                                                           echo $_SESSION['fixedamt'];
                                                       } else if(isset($_SESSION['editcondition']) && !empty($typeArr[1])) {
                                                        if (strcmp($_SESSION['editcondition'], "fixedamount") === 0) { 
                                                            echo substr($typeArr[1], 1); 
                                                        } 
                                                    } ?>'
                                                    onkeypress="return isNumberKey(event)">
                                        </div>
                                        <div class="col-md-2 vm-padding-r-clear vm-padding-t-15 pull-left" style="display:inline;">Off</div>
                                    </div>
                                    <div id='shipping' style='display:none;'>
                                        <div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div id='conditions'>
                                    <?php 
                                    if(!empty($condition)) {
                                        $condArr = explode(" ", $condition);
                                    }
                                    ?>
                                    <select name='conditionfor' id='conditionfor'>
                                        <option value='null' <?php 
                                        if (isset($_SESSION['discterms'])) {
                                            if (strcmp($_SESSION['discterms'], "null") === 0) {
                                                echo " selected";
                                            }
                                        } else if ((!empty($condArr)) && (in_array("", $condArr))) {
                                            $_SESSION['editterms'] = "";
                                            echo " selected";
                                        }
                                        ?>>Select an option</option>
                                        <option value='allorders' <?php 
                                        if (isset($_SESSION['discterms'])) {
                                            if (strcmp($_SESSION['discterms'], "allorders") === 0) {
                                                echo " selected";
                                            }
                                        } else if ((!empty($condArr)) && (in_array("All", $condArr))) {
                                            $_SESSION['editterms'] = "allorders";
                                            echo " selected";
                                        }
                                        ?>>All orders</option>
                                        <option value='ordersabove' <?php 
                                        if (isset($_SESSION['discterms'])) {
                                            if (strcmp($_SESSION['discterms'], "ordersabove") === 0) {
                                                echo " selected";
                                            }
                                        } else if ((!empty($condArr)) && (in_array("above", $condArr))) {
                                            $_SESSION['editterms'] = "ordersabove";
                                            echo " selected";
                                        }
                                        ?>>Orders above</option>
                                        <option value='productcat' <?php 
                                        if (isset($_SESSION['discterms'])) {
                                            if (strcmp($_SESSION['discterms'], "productcat") === 0) {
                                                echo " selected";
                                            }
                                        } else if ((!empty($condArr)) && (in_array("categories:", $condArr))) {
                                            $_SESSION['editterms'] = "productcat";
                                            echo " selected";
                                        }
                                        ?>>Product Categories</option>
                                        <option value='specificprod' <?php 
                                        if (isset($_SESSION['discterms'])) {
                                            if (strcmp($_SESSION['discterms'], "specificprod") === 0) {
                                                echo " selected";
                                            }
                                        } else if ((!empty($condArr)) && (in_array("For", $condArr))) {
                                            $_SESSION['editterms'] = "specificprod";
                                            echo " selected";
                                        }
                                        ?>>Specific Product</option>
                                        <option value='customergroup' <?php 
                                        if (isset($_SESSION['discterms'])) {
                                            if (strcmp($_SESSION['discterms'], "customergroup") === 0) {
                                                echo " selected";
                                            }
                                        } else if ((!empty($condArr)) && (in_array("customer", $condArr))) {
                                            $_SESSION['editterms'] = "customergroup";
                                            echo " selected";
                                        }
                                        ?>>Customers in group</option>
                                    </select>
                                    <div id='ordersabove' style='display:none;'>
                                        <div class="col-md-2 vm-padding-l-clear vm-padding-t-15 pull-left" style="display:inline;">$</div>
                                        <div class="col-md-4 vm-padding-l-clear" style="overflow:hidden;">
                                            <input type='text' name='aboveamt' 
                                                   value='<?php if (isset($_SESSION['aboveamt'])) { 
                                                       echo $_SESSION['aboveamt'];
                                                   } else if(isset($_SESSION['editterms']) && !empty($condArr[1])) {
                                                        if (strcmp($_SESSION['editterms'], "ordersabove") === 0) { 
                                                            echo substr($condArr[2], 1);
                                                        } 
                                                    } ?>'
                                                    onkeypress="return isNumberKey(event)">
                                        </div>                                            
                                    </div>
                                    <div id='productcat' style='display:none;'>
                                        <div id='no-tags' stye='display:none;'>
                                            No existing tags found
                                        </div>
                                        <input type='hidden' id='tags' name='tags'>
                                        <select id="select-to" class="contacts" placeholder="Type to select product categories... "></select>
                                    </div>

                                    <div id='specificprod' style='display:none;'>
                                        <?php 
                                            $prodsql = "Select * from products;";
                                            $pres = mysqli_query($link, $prodsql);
                                        ?>
                                        <select name='specificprod'>
                                        <?php
                                            if (!mysqli_query($link, $prodsql)) {
                                                die(mysqli_error($link));
                                            } else {
                                                while ($row = mysqli_fetch_assoc($pres)) {
                                                    echo "<option value='".$row['name']."'"; 
                                                    if(isset($_SESSION['discterms']) && isset($_SESSION['specificprod'])) {
                                                        if (strcmp($_SESSION['discterms'], "specificprod") === 0) { 
                                                            if (strcmp($row['name'], $_SESSION['specificprod']) === 0) {
                                                                echo " selected";
                                                            }
                                                        } 
                                                    } else if(isset($_SESSION['editterms']) && !empty($condArr[2])) {
                                                        if (strcmp($_SESSION['editterms'], "specificprod") === 0) { 
                                                            if (strcmp($row['name'], $condArr[2]) === 0) {
                                                                echo " selected";
                                                            }
                                                        } 
                                                    } 
                                                    echo ">".$row['name']."</option>";
                                                }
                                            }
                                        ?>
                                        </select>
                                    </div>
                                    <div id='customergroup' style='display:none;'>

                                    </div>
                                </div>
<!--                                    Amount*:
                                <input type='text' name='amount' id='amount'  maxlength="50"  
                                       onkeypress="return isNumberKey(event)" value ="<?php 
                                        if (!empty($erow['amount'])) {
//                                                echo $erow['amount'];
                                        }
                                    ?>"/>-->
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="vm-container vm-margin-t-30">
                        <h4 class="page-header vm-margin-t-clear">Status</h4>
                        <div class="row">
                            Recurrence*:
                            <select name='recurrence'>
                                <option value='adhoc' <?php 
                                    if (isset($_SESSION['recurrence'])) { 
                                        if(strcmp($_SESSION['recurrence'], "adhoc") === 0) {
                                            echo " selected";
                                        }
                                    } else if (!empty($erow['recurrence'])) {
                                        if (strcmp($erow['recurrence'], "adhoc") === 0) {
                                            echo " selected";
                                        }
                                    }
                                ?>>Ad-hoc</option>
                                <option value='weekly' <?php 
                                    if (isset($_SESSION['recurrence'])) { 
                                        if(strcmp($_SESSION['recurrence'], "weekly") === 0) {
                                            echo " selected";
                                        }
                                    } else if (!empty($erow['recurrence'])) {
                                        if (strcmp($erow['recurrence'], "weekly") === 0) {
                                            echo " selected";
                                        }
                                    }
                                ?>>Weekly</option>
                                <option value='monthly' <?php 
                                    if (isset($_SESSION['recurrence'])) { 
                                        if(strcmp($_SESSION['recurrence'], "monthly") === 0) {
                                            echo " selected";
                                        }
                                    } else if (!empty($erow['recurrence'])) {
                                        if (strcmp($erow['recurrence'], "monthly") === 0) {
                                            echo " selected";
                                        }
                                    }
                                ?>>Monthly</option>
                                <option value='yearly' <?php 
                                    if (isset($_SESSION['recurrence'])) { 
                                        if(strcmp($_SESSION['recurrence'], "yearly") === 0) {
                                            echo " selected";
                                        }
                                    } else if (!empty($erow['recurrence'])) {
                                        if (strcmp($erow['recurrence'], "yearly") === 0) {
                                            echo " selected";
                                        }
                                    }
                                ?>>Yearly</option>
                            </select>
                        </div>
                        <div class="row">
                            Status*:
                            <select name='status'>
                                <option value='active' <?php 
                                    if (isset($_SESSION['status'])) { 
                                        if(strcmp($_SESSION['status'], "active") === 0) {
                                            echo " selected";
                                        }
                                    } else if (!empty($erow['status'])) {
                                        if (strcmp($erow['status'], "active") === 0) {
                                            echo " selected";
                                        }
                                    }
                                ?>>Active</option>
                                <option value='inactive' <?php 
                                    if (isset($_SESSION['status'])) { 
                                        if(strcmp($_SESSION['status'], "inactive") === 0) {
                                            echo " selected";
                                        }
                                    } else if (!empty($erow['status'])) {
                                        if (strcmp($erow['status'], "inactive") === 0) {
                                            echo " selected";
                                        }
                                    }
                                ?>>Inactive</option>
                            </select>
                        </div>
                        <div class="row">
                            Start date:
                            <input type="text" id="date3" name="date3" 
                                   value='<?php if (isset($_SESSION['start'])) { 
                                        echo date("d M y", strtotime($_SESSION['start']));
                                    } else if (!empty($erow['start'])) { 
                                       echo date("d M y", strtotime($erow['start']));
                                    }?>'>
                        </div>
                        <div class="row">
                            End date:
                            <input type="text" id="date4" name="date4"
                                   value='<?php if (isset($_SESSION['end'])) { 
                                        echo date("d M y", strtotime($_SESSION['end']));
                                    } else if (!empty($erow['end'])) { 
                                       echo date("d M y", strtotime($erow['end']));
                                    }?>'>
                        </div>
                    </div>
                </div>
            </div>
            <div class="bc-bottom vm-margin-t-20">
                <div class="pull-right">
                    <input type='reset' name='cancel' value='Cancel' />
                    <input type='submit' name='submit' value='Save' />
                </div>
                <div class="clearfix"></div>
            </div>
            </form>
        </div>
        </div>
        <!-- /#page-wrapper -->
            
        <?php 
            $tagStr = "";

            $tags = "Select * from tags where type='product';";
            $tres = mysqli_query($link, $tags);

            if (!mysqli_query($link, $tags)) {
                die(mysqli_errno($link));
            } else {
                $i = 0;
                $count = $tres -> num_rows;
                if ($tres -> num_rows !== 0) {
                    while ($row = mysqli_fetch_assoc($tres)) {
                        $tagStr.= "{tag: '".$row['keyword']."'}";
                        if ($i + 1 !== $count) {
                            $tagStr .= ",";
                        }
                        $i++;
                    }
                }
            }
        ?>
        </div>
    </body>
</html>

 <script>
    var myCalendar = new dhtmlXCalendarObject(["date3"]);
    myCalendar.setDateFormat("%d %M %Y");
            myCalendar.hideTime();
    var myCalendar2 = new dhtmlXCalendarObject(["date4"]);
    myCalendar2.setDateFormat("%d %M %Y");
            myCalendar2.hideTime();

    function isNumberKey(evt) {
        var charCode = (evt.which) ? evt.which : event.keyCode
        if (charCode > 31 && (charCode < 48 || charCode > 57) && charCode != 46) {
            document.getElementById('nanError').style.display='block';
            document.getElementById('nanError').style.color='red';
            return false;
       }

        document.getElementById('nanError').style.display='none';
        return true;
    }
    
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
    
    function randomString() {
        var text = "";
        var possible = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz";

        for( var i=0; i < 12; i++ )
            text += possible.charAt(Math.floor(Math.random() * possible.length));

        document.getElementById('code').value = text;
        return false;
    }

    function deleteFunction(locId) {
        var r = confirm("Are you sure you wish to delete this discount?");
        if (r === true) {
            window.location="processDiscounts.php?delete=1&id=" + locId;
        } else if (r === false) {
            <?php
//                unset($_SESSION['addDiscError']);
//                unset($_SESSION['addDiscSuccess']);
//                unset($_SESSION['updateDiscSuccess']);
//                $_SESSION['updateDiscError'] = "Nothing was deleted";
            ?>
            window.location='discounts.php';
        }
    }
    
    $("#filter").keyup(function () {
        var search = $(this).val();
        $(".searchable").children().show();
        $('.noresults').remove();
        if (search) {
            $(".searchable").children().not(":containsNoCase(" + search + ")").hide();
            $(".searchable").each(function () {
                if ($(this).children(':visible').length === 0) 
                    $(this).append('<tr class="noresults"><td colspan="100%">No matching results found</td></tr>');
            });

        }
    });
        
    $.expr[":"].containsNoCase = function (el, i, m) {
        var search = m[3];
        if (!search) return false;
           return new RegExp(search,"i").test($(el).text());
    };
    
    $("#filterhistory").keyup(function () {
        var search = $(this).val();
        $(".searchableHist").children().show();
        $('.noresults').remove();
        if (search) {
            $(".searchableHist").children().not(":containsNoCase(" + search + ")").hide();
            $(".searchableHist").each(function () {
                if ($(this).children(':visible').length === 0) 
                    $(this).append('<tr class="noresults"><td colspan="100%">No matching results found</td></tr>');
            });

        }
    });
        
    $.expr[":"].containsNoCase = function (el, i, m) {
        var search = m[3];
        if (!search) return false;
           return new RegExp(search,"i").test($(el).text());
    };
    
    $(document).ready(function() {
        $('#example').DataTable({
            dom: "<'row'tr>" +
                "<'row'<'col-sm-5'i><'col-sm-7'p>>",
            aoColumnDefs: [{ 'bSortable': false, 'aTargets': [ -1 ] }],
            responsive: true
        });
        $('#examplehistory').DataTable({
            dom: "<'row'tr>" +
                "<'row'<'col-sm-5'i><'col-sm-7'p>>"
        });
    });
    
    $(function() {
        $("#select-to").selectize({
            create: true
        });

        var selectize_tags = $("#select-to")[0].selectize;
        <?php 
        if (isset($_SESSION['tags'])) { 
            $tagsAr = explode(",", $_SESSION['tags']);
            for ($i = 0; $i < count($tagsAr); $i++) {
        ?>
            selectize_tags.addOption({
                
        <?php
                    echo "tag: '".$tagsAr[$i]."'";
        ?>
            });
            selectize_tags.addItem('<?php echo $tagsAr[$i]; ?>');
        <?php
                }
        } else if (isset($_GET['id'])) {
            $tagsql = "Select * from discounts where id='".$_GET['id']."';";
            $tresult = mysqli_query($link, $tagsql);

            if (!mysqli_query($link, $tagsql)) {
                die(mysqli_errno($link));
            } else {
//                while ($row = mysqli_fetch_assoc($tresult)) {
                $row = mysqli_fetch_assoc($tresult);
                $con = explode(" ", $row['disccondition']);
                if (!empty($con[3])) {
                    $tagsAr = explode(",", $con[3]);
                } else {
                    $tagsAr = array();
                }
                for ($i = 0; $i < count($tagsAr); $i++) {
        ?>
            selectize_tags.addOption({
                
        <?php
                    echo "tag: '".$tagsAr[$i]."'";
        ?>
            });
            selectize_tags.addItem('<?php echo $tagsAr[$i]; ?>');
        <?php
                }
//                }
            }
        }
        ?>
    });
    
    <?php if (strcmp($tagStr, "") === 0) { ?>
        document.getElementById('no-tags').style.display = "block";
    <?php } else { ?>
        document.getElementById('no-tags').style.display = "none";        
    <?php } ?>
        
    $('#select-to').selectize({
            persist: false,
            maxItems: null,
            valueField: 'tag',
            labelField: 'tag',
            searchField: ['tag'],
            sortField: [
                    {field: 'tag', direction: 'asc'}
            ],
            options: [<?php echo $tagStr; ?>],
//                    {tag: 'Nikola'},
//                    {tag: 'someone@gmail.com'}
//            ],
            render: {
                    item: function(item, escape) {
                            return '<div>' +
                                    (item.tag ? '<span>' + escape(item.tag) + '</span>' : '') +
                            '</div>';
                    },
                    option: function(item, escape) {
                            var name = item.tag;
                            var label = name || item.tag;
                            var caption = name ? item.tag : null;
                            return '<div>' +
                                    (caption ? '<span class="caption">' + escape(caption) + '</span>' : '') +
                            '</div>';
                    }
            },

            create: function(input) {
                return {tag: input};
            }
    });
    
    function checkValue() {
        var value = document.getElementById('tags').value;
        var isEmpty = false;
        <?php if (strcmp($tagStr, "") === 0) { ?>
                isEmpty = true;
        <?php } ?>
        if (value === "" && isEmpty) {
            document.getElementById('no-tags').style.display = "block";
        } else {
            document.getElementById('no-tags').style.display = "none";
        }
    };    
    
    $('#select-to').change(function() {
        var selectize = $('#select-to').selectize()[0].selectize;
        var val = selectize.getValue();
        document.getElementById('tags').value = val;
        checkValue();
    });
    
    function checkCondition(str) {
        if (str === "bundleamount") {
            document.getElementById('bundleamount').style.display = "block";
            document.getElementById('bundlediscount').style.display = "none";
            document.getElementById('nextfree').style.display = "none"; 
            document.getElementById('nextdiscount').style.display = "none";   
            document.getElementById('fixedpercent').style.display = "none"; 
            document.getElementById('fixedamount').style.display = "none";  
        } else if (str === "bundlediscount") { 
            document.getElementById('bundleamount').style.display = "none";
            document.getElementById('bundlediscount').style.display = "block";
            document.getElementById('nextfree').style.display = "none"; 
            document.getElementById('nextdiscount').style.display = "none";   
            document.getElementById('fixedpercent').style.display = "none"; 
            document.getElementById('fixedamount').style.display = "none";              
        } else if (str === "nextfree") {
            document.getElementById('bundleamount').style.display = "none";
            document.getElementById('bundlediscount').style.display = "none";
            document.getElementById('nextfree').style.display = "block";   
            document.getElementById('nextdiscount').style.display = "none";  
            document.getElementById('fixedpercent').style.display = "none"; 
            document.getElementById('fixedamount').style.display = "none";                 
        } else if (str === "nextdiscount") {
            document.getElementById('bundleamount').style.display = "none";
            document.getElementById('bundlediscount').style.display = "none";
            document.getElementById('nextfree').style.display = "none";  
            document.getElementById('nextdiscount').style.display = "block";   
            document.getElementById('fixedpercent').style.display = "none"; 
            document.getElementById('fixedamount').style.display = "none";  
        } else if (str === "fixedpercent") {
            document.getElementById('bundleamount').style.display = "none";
            document.getElementById('bundlediscount').style.display = "none";
            document.getElementById('nextfree').style.display = "none";  
            document.getElementById('nextdiscount').style.display = "none";  
            document.getElementById('fixedpercent').style.display = "block"; 
            document.getElementById('fixedamount').style.display = "none";                   
        } else if (str === "fixedamount") {
            document.getElementById('bundleamount').style.display = "none";
            document.getElementById('bundlediscount').style.display = "none";
            document.getElementById('nextfree').style.display = "none";  
            document.getElementById('nextdiscount').style.display = "none";  
            document.getElementById('fixedpercent').style.display = "none";  
            document.getElementById('fixedamount').style.display = "block";                 
        } else if (str === "shipping") {
            document.getElementById('bundleamount').style.display = "none";
            document.getElementById('bundlediscount').style.display = "none";
            document.getElementById('nextfree').style.display = "none";  
            document.getElementById('nextdiscount').style.display = "none";  
            document.getElementById('fixedpercent').style.display = "none";  
            document.getElementById('fixedamount').style.display = "none";  
            document.getElementById('shipping').style.display = "block";                  
        } else {
            document.getElementById('bundleamount').style.display = "none";
            document.getElementById('bundlediscount').style.display = "none";
            document.getElementById('nextfree').style.display = "none";  
            document.getElementById('nextdiscount').style.display = "none";  
            document.getElementById('fixedpercent').style.display = "none";  
            document.getElementById('fixedamount').style.display = "none";  
            document.getElementById('shipping').style.display = "none";                   
        }
    }
    
    function checkCondType(str) {
        if (str === "ordersabove") {
            document.getElementById('ordersabove').style.display = "block";
            document.getElementById('productcat').style.display = "none";
            document.getElementById('specificprod').style.display = "none";
            document.getElementById('customergroup').style.display = "none";
        } else if (str === "productcat") {
            document.getElementById('ordersabove').style.display = "none";
            document.getElementById('productcat').style.display = "block";
            document.getElementById('specificprod').style.display = "none";
            document.getElementById('customergroup').style.display = "none";
        } else if (str === "specificprod") {
            document.getElementById('ordersabove').style.display = "none";
            document.getElementById('productcat').style.display = "none";
            document.getElementById('specificprod').style.display = "block";
            document.getElementById('customergroup').style.display = "none";
        } else if (str === "customergroup") {
            document.getElementById('ordersabove').style.display = "none";
            document.getElementById('productcat').style.display = "none";
            document.getElementById('specificprod').style.display = "none";
            document.getElementById('customergroup').style.display = "block";
        } else {
            document.getElementById('ordersabove').style.display = "none";
            document.getElementById('productcat').style.display = "none";
            document.getElementById('specificprod').style.display = "none";
            document.getElementById('customergroup').style.display = "none";                
        }
    }
    
    var obj = document.getElementById('condition');
    
    if (obj !== null) {
        obj.onchange = function() {
            var val = obj.value;
            checkCondition(val);
        };
    }
    
    var conditions = document.getElementById('conditionfor');
    
    if (conditions !== null) {
        conditions.onchange = function() {
            var val = conditions.value;
            checkCondType(val);
        };
    }
    
    <?php if (isset($_SESSION['condition'])) { ?>
        var str = '<?php echo $_SESSION['condition']; ?>';
        checkCondition(str);
    <?php } ?>
        
    <?php if (isset($_SESSION['editcondition'])) { ?>
        var str = '<?php echo $_SESSION['editcondition']; ?>';
        checkCondition(str);
    <?php } ?>
        
    <?php if (isset($_SESSION['discterms'])) { ?>
        var str = '<?php echo $_SESSION['discterms']; ?>';
        checkCondType(str);
    <?php } ?>
        
    <?php if (isset($_SESSION['editterms'])) { ?>
        var str = '<?php echo $_SESSION['editterms']; ?>';
        checkCondType(str);
    <?php } ?>
        checkCondition("");
        checkCondType("");
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