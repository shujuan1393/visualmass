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
            <div id="page-wrapper">

            <div class="container-fluid">

                <!-- Page Heading -->
                <div class="row">
                    <div class="col-lg-12">
                        <ol class="breadcrumb">
                            <li>
                                <a href="home.php"><i class="fa fa-home"></i></a>
                            </li>
                            <li class="active">
                                Discounts
                            </li>
                        </ol>
                        
                        <h1 class="page-header">Manage Discounts</h1>
                        
                        <div id="updateDiscSuccess" class='success'>
                            <?php 
                                if (isset($_SESSION['updateDiscSuccess'])) {
                                    echo $_SESSION['updateDiscSuccess'];
                                }
                            ?>
                        </div>
                        <div id="updateDiscError" class='error'>
                            <?php 
                                if (isset($_SESSION['updateDiscError'])) {
                                    echo $_SESSION['updateDiscError'];
                                }
                            ?>
                        </div>

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

                        <p class="text-right">
                            <a href="#add"><i class="fa fa-fw fa-plus"></i> Add Discount</a>
                        </p>

                        <div class="pull-left filter-align">Filter: </div>
                        <div style="overflow:hidden">
                            <input type="text" id="filter" class="pull-right" placeholder="Type here to search">
                        </div>
                        
                        <table id ="example">
                            <thead>
                                <th>Discount Name</th>
                                <th>Use Limit</th>
                                <th>Recurrence</th>
                                <th>Usage (C/E)</th>
                                <th>Validity</th>
                                <th>Status</th>
                                <th>Edit</th>
                                <th>Delete</th>                        
                            </thead>
                            <tbody class="searchable">
                            <?php
                                // output data of each row
                                while ($row = mysqli_fetch_assoc($result)) {
                                    echo "<tr>";
                                    echo "<td>".$row['name']."(".$row['code'].")</td>";                            
                                    echo "<td>".$row['disclimit']."</td>";                           
                                    echo "<td>".$row['recurrence']."</td>";                           
                                    echo "<td>".$row['discusage']."</td>";                              
                                    echo "<td>".date("d M Y", strtotime($row['start']))." to ".date("d M Y", strtotime($row['end']))."</td>";                           
                                    echo "<td>".$row['status']."</td>";                        
                                    echo '<td><button onClick="window.location.href=`discounts.php?id='.$row['id'].'`">E</button>';
                                    echo '<td><button onClick="deleteFunction('.$row['id'].')">D</button></td>';
                                    echo "</tr>";
                                }
                            ?>
                            </tbody>
                        </table>

                        <?php
                            } 
                        }
                        ?>
                        
                        <h1 id="add" class="page-header">Add/Edit Discount</h1>
                        
                        <form id='addDiscount' action='processDiscounts.php' method='post'>
                            <div id="addDiscError" class='error'>
                                <?php 
                                    if (isset($_SESSION['addDiscError'])) {
                                        echo $_SESSION['addDiscError'];
                                    }
                                ?>
                            </div>
                            <p id='nanError' class='error' style="display: none;">Please enter numbers only</p>

                            <div id="addDiscSuccess"  class='success'>
                                <?php 
                                    if (isset($_SESSION['addDiscSuccess'])) {
                                        echo $_SESSION['addDiscSuccess'];
                                    }
                                ?>
                            </div>
                            
                        <table class='content'>
                            <tr>
                                <td>
                                    <input type='hidden' name='submitted' id='submitted' value='1'/>
                                    <input type='hidden' name='editid' id='editid' 
                                           value='<?php if (isset($_GET['id'])) { echo $erow['id']; }?>'/>
                                    Name:
                                    <input type='text' name='name' id='name'  maxlength="50" 
                                           value ="<?php 
                                    if (!empty($erow['name'])) {
                                        echo $erow['name'];
                                    }
                                        ?>"/>
                                </td>
                                <td>
                                    Discount Code*: <br/>
                                    <button type="button" onclick="randomString()" class="pull-right">Generate</button>
                                    <div style="overflow: hidden;" >
                                        <input type='text' name='code' id='code' value ="<?php 
                                            if(isset($_SESSION['randomString'])) { 
                                                echo $_SESSION['randomString']; 
                                                
                                            } else if (!empty($erow['code'])) {
                                                echo $erow['code'];
                                            } ?>" maxlength="50" />
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <?php
                                        if (!empty($erow['discusage'])) { 
                                            $usageArr = explode(",", $erow['discusage']);
                                        }
                                    ?>
                                    Usage*: <br/>
                                    <input type='checkbox' name='usage[]' value="cust" <?php 
                                            if (!empty($erow['discusage'])) {
                                                if (in_array("cust", $usageArr)) {
                                                    echo " checked";
                                                }
                                            }
                                        ?>>Customer 
                                    <input type='checkbox' name='usage[]' value='emp' <?php 
                                            if (!empty($erow['discusage'])) {
                                                if (in_array("emp", $usageArr)) {
                                                    echo " checked";
                                                }
                                            }
                                        ?>>Employee
                                </td>
                                <td>
                                    Limit Per User*:
                                    <input type='text' name='userlimit' id='userlimit'  maxlength="50"  
                                           onkeypress="return isNumber(event)" value ="<?php 
                                            if (!empty($erow['userlimit'])) {
                                                echo $erow['userlimit'];
                                            }
                                        ?>"/>
                                </td>                                
                            </tr>
                            <tr>
                                <td>
                                    Track Serial Number?: <br/>
                                    <input type='checkbox' name='serial' value="yes" <?php 
                                            if (!empty($erow['serial'])) {
                                                if (strcmp("yes", $erow['serial']) === 0) {
                                                    echo " checked";
                                                }
                                            }
                                        ?>>Yes 
                                </td>
                                <td>
                                    Limit*:
                                    <input type='text' name='limit' id='limit'  maxlength="50"  
                                           onkeypress="return isNumber(event)" value ="<?php 
                                            if (!empty($erow['disclimit'])) {
                                                echo $erow['disclimit'];
                                            }
                                        ?>"/>
                                </td> 
                            </tr>
                            <tr>
                                <td colspan="2">
                                        <h5>Discount Condition</h5>
                                </td>
                            </tr>
                            <tr>
                                <td width='40%'>
                                    <div id='conditiontype'>
                                        <?php 
                                        if (isset($erow['disctype'])) {
                                            $disctype = $erow['disctype'];
                                        } else {
                                            $disctype = "";
                                        }
                                        
                                        if (isset($erow['disccondition'])) {
                                            $condition = $erow['disccondition'];
                                        } else {
                                            $condition = "";
                                        }
                                        
                                            $typeArr = explode(" ", $disctype);
                                        ?>
                                        <select name='condition' id='condition'>
                                            <option value='null' <?php 
                                            if (isset($_SESSION['condition'])) {
                                                if (strcmp($_SESSION['condition'], "null") === 0) {
                                                    echo " selected";
                                                }
                                            } else if (in_array("", $typeArr)) {
                                                $_SESSION['editcondition'] = "";
                                                echo " selected";
                                            }
                                            ?>>Select Discount Type</option>
                                            <option value='upgrade' <?php 
                                            if (isset($_SESSION['condition'])) {
                                                if (strcmp($_SESSION['condition'], "upgrade") === 0) {
                                                    echo " selected";
                                                }
                                            } else if (in_array("Upgrade", $typeArr)) {
                                                $_SESSION['editcondition'] = "upgrade";
                                                echo " selected";
                                            }
                                            ?>>Free Upgrade</option>
                                            <option value='bundleamount' <?php 
                                            if (isset($_SESSION['condition'])) {
                                                if (strcmp($_SESSION['condition'], "bundleamount") === 0) {
                                                    echo " selected";
                                                }
                                            } else if (in_array("For", $typeArr)) {
                                                $_SESSION['editcondition'] = "bundleamount";
                                                echo " selected";
                                            }
                                            ?>>Bundle Amount</option>
                                            <option value='bundlediscount' <?php 
                                            if (isset($_SESSION['condition'])) {
                                                if (strcmp($_SESSION['condition'], "bundlediscount") === 0) {
                                                    echo " selected";
                                                }
                                            } else if (in_array("Discount", $typeArr)) {
                                                $_SESSION['editcondition'] = "bundlediscount";
                                                echo " selected";
                                            }
                                            ?>>Bundle Discount</option>
                                            <option value='nextfree' <?php 
                                            if (isset($_SESSION['condition'])) {
                                                if (strcmp($_SESSION['condition'], "nextfree") === 0) {
                                                    echo " selected";
                                                }
                                            } else if (in_array("Next", $typeArr)) {
                                                $_SESSION['editcondition'] = "nextfree";
                                                echo " selected";
                                            }
                                            ?>>Next Pair Free</option>
                                            <option value='nextdiscount' <?php 
                                            $nextdiscArr = array("Buy", "Next");
                                            $resArr = array_intersect($nextdiscArr, $typeArr);
                                            if (isset($_SESSION['condition'])) {
                                                if (strcmp($_SESSION['condition'], "nextdiscount") === 0) {
                                                    echo " selected";
                                                }
                                            } else if (count($resArr) === count($nextdiscArr) && is_numeric(strpos($disctype, "%"))) {
                                                $_SESSION['editcondition'] = "nextdiscount";
                                                echo " selected";
                                            }
                                            ?>>Next Pair Discounted</option>
                                            <option value='fixedpercent' <?php 
                                            $fixedArr = array("Get", "Off");
                                            $percArr = array_intersect($fixedArr, $typeArr);
                                            if (isset($_SESSION['condition'])) {
                                                if (strcmp($_SESSION['condition'], "fixedpercent") === 0) {
                                                    echo " selected";
                                                }
                                            } else if (count($percArr) ===  count($fixedArr) && is_numeric(strpos($disctype, "%"))) {
                                                $_SESSION['editcondition'] = "fixedpercent";
                                                echo " selected";
                                            }
                                            ?>>Fixed Percentage Discount</option>
                                            <option value='fixedamount' <?php 
                                            $amtArr = array_intersect($fixedArr, $typeArr);
                                            if (isset($_SESSION['condition'])) {
                                                if (strcmp($_SESSION['condition'], "fixedamount") === 0) {
                                                    echo " selected";
                                                }
                                            } else if (count($amtArr) === count($amtArr) && is_numeric(strpos($disctype, "$"))) {
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
                                        <div id='bundleamount' style='display:none;'>
                                            <div class='pull-left' style='width: 45%!important;'>
                                                <span class='pull-left padded-input'>Buy&nbsp; </span> 
                                                <span class='pull-right' style='width: 80%!important;'>
                                                    <input type='text' name='bundleamtqty' 
                                                           value='<?php if(isset($_SESSION['editcondition']) && !empty($typeArr[1])) {
                                                            if (strcmp($_SESSION['editcondition'], "bundleamount") === 0) { 
                                                                echo $typeArr[1];
                                                            } 
                                                        } ?>'
                                                           onkeypress="return isNumber(event)">
                                                </span>
                                            </div>
                                            <div class='pull-right' style='width: 48%!important;'>
                                                <span class='pull-left padded-input'>For $</span>
                                                <span class='pull-right' style='width: 80%!important;'>
                                                    <input type='text' name='bundleamtprice' 
                                                           value='<?php if(isset($_SESSION['editcondition']) && !empty($typeArr[3])) {
                                                            if (strcmp($_SESSION['editcondition'], "bundleamount") === 0) { 
                                                                echo substr($typeArr[3], 1);
                                                            } 
                                                        } ?>'
                                                           onkeypress="return isNumberKey(event)">
                                                </span>
                                            </div>
                                        </div>
                                        <div id='bundlediscount' style='display:none;'>
                                            <div class='pull-left' style='width: 45%!important;'>
                                                <span class='pull-left padded-input'>Buy&nbsp; </span> 
                                                <span class='pull-right' style='width: 80%!important;'>
                                                    <input type='text' name='bundlediscqty' 
                                                           value='<?php if(isset($_SESSION['editcondition']) && !empty($typeArr[1])) {
                                                            if (strcmp($_SESSION['editcondition'], "bundlediscount") === 0) { 
                                                                echo $typeArr[1];
                                                            } 
                                                        } ?>'
                                                           onkeypress="return isNumber(event)">
                                                </span>
                                            </div>
                                            <div class='pull-right' style='width: 48%!important;'>
                                                <span class='pull-left' style='width: 80%!important;'>
                                                    <input type='text' name='bundlediscprice' 
                                                           value='<?php if(isset($_SESSION['editcondition']) && !empty($typeArr[3])) {
                                                            if (strcmp($_SESSION['editcondition'], "bundlediscount") === 0) { 
                                                                echo substr($typeArr[3], 0, -1); 
                                                            } 
                                                        } ?>'
                                                           onkeypress="return isNumberKey(event)">
                                                </span>
                                                <span class='pull-right padded-input'>% Off</span>
                                            </div>
                                        </div>
                                        <div id='nextfree' style='display:none;'>
                                            <div class='pull-left' style='width: 45%!important;'>
                                                <span class='pull-left padded-input'>Buy&nbsp; </span> 
                                                <span class='pull-right' style='width: 80%!important;'>
                                                    <input type='text' name='nextfreeqty' 
                                                           value='<?php if(isset($_SESSION['editcondition']) && !empty($typeArr[1])) {
                                                            if (strcmp($_SESSION['editcondition'], "nextfree") === 0) { 
                                                                echo $typeArr[1];
                                                            } 
                                                        } ?>'
                                                           onkeypress="return isNumber(event)">
                                                </span>
                                            </div>
                                            <div class='pull-right' style='width: 45%!important;'>
                                                <span class='pull-left padded-input'>Free  </span>
                                                <span class='pull-right' style='width: 80%!important;'>
                                                    <input type='text' name='nextfreeamt'
                                                           value='<?php if(isset($_SESSION['editcondition']) && !empty($typeArr[3])) {
                                                            if (strcmp($_SESSION['editcondition'], "nextfree") === 0) { 
                                                                echo $typeArr[3];
                                                            } 
                                                        } ?>'
                                                           onkeypress="return isNumber(event)">
                                                </span>
                                            </div>
                                        </div>
                                        <div id='nextdiscount' style='display:none;'>
                                            <div class='pull-left' style='width: 45%!important;'>
                                                <span class='pull-left padded-input'>Buy&nbsp; </span> 
                                                <span class='pull-right' style='width: 80%!important;'>
                                                    <input type='text' name='nextdiscqty' 
                                                           value='<?php if(isset($_SESSION['editcondition']) && !empty($typeArr[1])) {
                                                            if (strcmp($_SESSION['editcondition'], "nextdiscount") === 0) { 
                                                                echo $typeArr[1];
                                                            } 
                                                        } ?>'
                                                           onkeypress="return isNumber(event)">
                                                </span>
                                            </div>
                                            <div class='pull-right' style='width: 48%!important;'>
                                                <span class='pull-left' style='width: 80%!important;'>
                                                    <input type='text' name='nextdiscamt' 
                                                           value='<?php if(isset($_SESSION['editcondition']) && !empty($typeArr[3])) {
                                                                if (strcmp($_SESSION['editcondition'], "nextdiscount") === 0) { 
                                                                    echo substr($typeArr[3], 0, -1); 
                                                                } 
                                                            } ?>'
                                                           onkeypress="return isNumber(event)">
                                                </span>
                                                <span class='pull-right padded-input'> % Off </span>
                                            </div>
                                        </div>
                                        <div id='fixedpercent' style='display:none;'>
                                            <span class='pull-left' style='width: 50%!important;'>
                                                <input type='text' name='fixedperc'
                                                    value='<?php if(isset($_SESSION['editcondition']) && !empty($typeArr[1])) {
                                                            if (strcmp($_SESSION['editcondition'], "fixedpercent") === 0) { 
                                                                echo substr($typeArr[1], 0, -1); 
                                                            } 
                                                        } ?>'
                                                    onkeypress="return isNumber(event)">
                                            </span>
                                            <span class='pull-left padded-input'>&nbsp; % Off </span>
                                        </div>
                                        <div id='fixedamount' style='display:none;'>
                                            <span class='pull-left padded-input'>$ &nbsp; </span>
                                            <span class='pull-left' style='width: 50%!important;'>
                                                <input type='text' name='fixedamt' 
                                                       value='<?php if(isset($_SESSION['editcondition']) && !empty($typeArr[1])) {
                                                            if (strcmp($_SESSION['editcondition'], "fixedamount") === 0) { 
                                                                echo substr($typeArr[1], 1); 
                                                            } 
                                                        } ?>'
                                                        onkeypress="return isNumberKey(event)">
                                            </span>
                                            <span class='pull-left padded-input'>&nbsp; Off </span>
                                        </div>
                                        <div id='shipping' style='display:none;'>
                                            <div>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div id='conditions'>
                                        <?php 
                                            $condArr = explode(" ", $condition);
                                        ?>
                                        <select name='conditionfor' id='conditionfor'>
                                            <option value='null' <?php 
                                            if (isset($_SESSION['discterms'])) {
                                                if (strcmp($_SESSION['discterms'], "null") === 0) {
                                                    echo " selected";
                                                }
                                            } else if (in_array("", $condArr)) {
                                                $_SESSION['editterms'] = "";
                                                echo " selected";
                                            }
                                            ?>>Select an option</option>
                                            <option value='allorders' <?php 
                                            if (isset($_SESSION['discterms'])) {
                                                if (strcmp($_SESSION['discterms'], "allorders") === 0) {
                                                    echo " selected";
                                                }
                                            } else if (in_array("All", $condArr)) {
                                                $_SESSION['editterms'] = "allorders";
                                                echo " selected";
                                            }
                                            ?>>All orders</option>
                                            <option value='ordersabove' <?php 
                                            if (isset($_SESSION['discterms'])) {
                                                if (strcmp($_SESSION['discterms'], "ordersabove") === 0) {
                                                    echo " selected";
                                                }
                                            } else if (in_array("above", $condArr)) {
                                                $_SESSION['editterms'] = "ordersabove";
                                                echo " selected";
                                            }
                                            ?>>Orders above</option>
                                            <option value='productcat' <?php 
                                            if (isset($_SESSION['discterms'])) {
                                                if (strcmp($_SESSION['discterms'], "productcat") === 0) {
                                                    echo " selected";
                                                }
                                            } else if (in_array("categories:", $condArr)) {
                                                $_SESSION['editterms'] = "productcat";
                                                echo " selected";
                                            }
                                            ?>>Product Categories</option>
                                            <option value='specificprod' <?php 
                                            if (isset($_SESSION['discterms'])) {
                                                if (strcmp($_SESSION['discterms'], "specificprod") === 0) {
                                                    echo " selected";
                                                }
                                            } else if (in_array("For", $condArr)) {
                                                $_SESSION['editterms'] = "specificprod";
                                                echo " selected";
                                            }
                                            ?>>Specific Product</option>
                                            <option value='customergroup' <?php 
                                            if (isset($_SESSION['discterms'])) {
                                                if (strcmp($_SESSION['discterms'], "customergroup") === 0) {
                                                    echo " selected";
                                                }
                                            } else if (in_array("customer", $condArr)) {
                                                $_SESSION['editterms'] = "customergroup";
                                                echo " selected";
                                            }
                                            ?>>Customers in group</option>
                                        </select>
                                        <div id='ordersabove' style='display:none;'>
                                            <span class='pull-left padded-input'>$ &nbsp; </span>
                                            <span class='pull-left' style='width: 50%!important;'>
                                                <input type='text' name='aboveamt' 
                                                       value='<?php if(isset($_SESSION['editterms']) && !empty($condArr[1])) {
                                                            if (strcmp($_SESSION['editterms'], "ordersabove") === 0) { 
                                                                echo substr($condArr[2], 1);
                                                            } 
                                                        } ?>'
                                                        onkeypress="return isNumberKey(event)">
                                            </span>                                            
                                        </div>
                                        <div id='productcat' style='display:none;'>
                                            <div id='no-tags' stye='display:none;'>
                                                No existing tags found
                                            </div>
                                            <input type='hidden' id='tags' name='tags'>
                                            <div class="control-group">
                                                    <select id="select-to" class="contacts" placeholder="Type to select product categories... "></select>
                                            </div>
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
                                                        if(isset($_SESSION['editterms']) && !empty($condArr[2])) {
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
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    Recurrence*:
                                    <select name='recurrence'>
                                        <option value='adhoc' <?php 
                                            if (!empty($erow['recurrence'])) {
                                                if (strcmp($erow['recurrence'], "adhoc") === 0) {
                                                    echo " selected";
                                                }
                                            }
                                        ?>>Ad-hoc</option>
                                        <option value='weekly' <?php 
                                            if (!empty($erow['recurrence'])) {
                                                if (strcmp($erow['recurrence'], "weekly") === 0) {
                                                    echo " selected";
                                                }
                                            }
                                        ?>>Weekly</option>
                                        <option value='monthly' <?php 
                                            if (!empty($erow['recurrence'])) {
                                                if (strcmp($erow['recurrence'], "monthly") === 0) {
                                                    echo " selected";
                                                }
                                            }
                                        ?>>Monthly</option>
                                        <option value='yearly' <?php 
                                            if (!empty($erow['recurrence'])) {
                                                if (strcmp($erow['recurrence'], "yearly") === 0) {
                                                    echo " selected";
                                                }
                                            }
                                        ?>>Yearly</option>
                                    </select>
                                </td>
                                <td>
                                    Status*:
                                    <select name='status'>
                                        <option value='active' <?php 
                                            if (!empty($erow['status'])) {
                                                if (strcmp($erow['status'], "active") === 0) {
                                                    echo " selected";
                                                }
                                            }
                                        ?>>Active</option>
                                        <option value='inactive' <?php 
                                            if (!empty($erow['status'])) {
                                                if (strcmp($erow['status'], "inactive") === 0) {
                                                    echo " selected";
                                                }
                                            }
                                        ?>>Inactive</option>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    Start date:
                                    <input type="text" id="date3" name="date3" 
                                           value='<?php if (!empty($erow['start'])) { 
                                               echo $erow['start'];
                                            }?>'>
                                </td>
                                <td>
                                    End date:
                                    <input type="text" id="date4" name="date4"
                                           value='<?php if (!empty($erow['end'])) { 
                                               echo $erow['end'];
                                            }?>'>
                                </td>
                            </tr>
                                <td colspan='2'><input type='submit' name='submit' value='Submit' /></td>
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
</html>

 <script>
    var myCalendar = new dhtmlXCalendarObject(["date3"]);
            myCalendar.hideTime();
    var myCalendar2 = new dhtmlXCalendarObject(["date4"]);
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
                unset($_SESSION['addDiscError']);
                unset($_SESSION['addDiscSuccess']);
                unset($_SESSION['updateDiscSuccess']);
                $_SESSION['updateDiscError'] = "Nothing was deleted";
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
    
    $(document).ready(function() {
        $('#example').DataTable({
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
        if (isset($_GET['id'])) {
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
</script>