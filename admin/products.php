<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
require_once '../config/db.php';

if (isset($_GET['id'])) {
    $selectSql = "Select * from products where pid ='" .$_GET['id']."';";
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
                                <a href="index.php"><i class="fa fa-home"></i></a>
                            </li>
                            <li class="active">
                                Products
                            </li>
                        </ol>
                        
                        <h1 class="page-header">Manage Products</h1>
                        
                        <?php
                            $sql = "Select * from products";
                            $result = mysqli_query($link, $sql);

                            if (!mysqli_query($link,$sql))
                            {
                                echo("Error description: " . mysqli_error($link));
                            } else {
                                if ($result->num_rows === 0) {
                                    echo "You have not created any products yet.";
                                } else {
                        ?>
                        
                        <p class="text-right">
                            <a href="#add"><i class="fa fa-fw fa-plus"></i> Add Product</a>
                        </p>
                        
                        <div class="pull-left filter-align">Filter: </div>
                        <div style="overflow:hidden">
                            <input type="text" id="filter" class="pull-right" placeholder="Type here to search">
                        </div>
                        
                        <table id ="example">
                            <thead>
                                <th>Name</th>
                                <th>Type</th>
                                <th>Price</th>
                                <th>Visibility</th>
                                <th>Availability</th>
                                <th>Edit</th>
                                <th>Delete</th>                        
                            </thead>
                            <tbody class="searchable">
                             <?php
                                // output data of each row
                                while ($row = mysqli_fetch_assoc($result)) {
                                    echo "<tr>";
                                    echo "<td>".$row['name']." (".$row['pid'].")</td>";
                                    echo "<td>".$row['type']."</td>";                            
                                    echo "<td>".$row['price']."</td>";                             
                                    echo "<td>".$row['visibility']."</td>";                          
                                    echo "<td>".$row['availability']."</td>";                         
                                    echo '<td><button onClick="window.location.href=`products.php?id='.$row['pid'].'`">E</button>';
                                    echo '<td><button onClick="deleteFunction(\''.$row['pid'].'\')">D</button></td>';
                                    echo "</tr>";
                                }
                            ?>
                            </tbody>
                        </table>
                        <?php
                            } 
                        }
                        ?> 
                        
                        <div id="updateProdSuccess" class="success">
                            <?php 
                                if (isset($_SESSION['updateProdSuccess'])) {
                                    echo $_SESSION['updateProdSuccess'];
                                }
                            ?>
                        </div>
                        <div id="updateProdError" class="error">
                            <?php 
                                if (isset($_SESSION['updateProdError'])) {
                                    echo $_SESSION['updateProdError'];
                                }
                            ?>
                        </div>
        
                        <form id='addProduct' action='processProducts.php' method='post' accept-charset='UTF-8' enctype="multipart/form-data">
                            <div id="addProdError" class="error">
                                <?php 
                                    if (isset($_SESSION['addProdError'])) {
                                        echo $_SESSION['addProdError'];
                                    }
                                ?>
                            </div>

                            <p id='nanError' style="display: none;">Please enter numbers only</p>
                            <div id="addProdSuccess" class="success">
                                <?php 
                                    if (isset($_SESSION['addProdSuccess'])) {
                                        echo $_SESSION['addProdSuccess'];
                                    }
                                ?>
                            </div>
                            
                            <h1 id="add" class="page-header">Add/Edit Product</h1>
                            
                            <input type='hidden' name='submitted' id='submitted' value='1'/>
                            <input type='hidden' name='editid' id='editid' 
                                   value='<?php if (isset($_GET['id'])) { echo $erow['pid']; }?>'/>
                                       
                            <input type='hidden' id='addExisting' name='addExisting'>
                            <table id='prodForm' class="content">
                                <tr>
                                    <td>
                                        <div id='newProduct'>
                                            Product Code*:
                                            <span id='selectExisting'>+ Select from existing products</span><br/>
                                            <button type='button' onclick="randomString()" class="pull-right">Generate</button>
                                            <div style="overflow: hidden;" >
                                            <input type='text' name='code' id='code' value ="<?php 
                                                if(isset($_SESSION['randomString'])) { 
                                                    echo $_SESSION['randomString']; } 
                                                else if (!empty($erow['pid'])) {
                                                    $pos = strpos($erow['pid'], "-");
                                                    if (is_numeric($pos)) {
                                                        $pids = explode("-", $erow['pid']);
                                                        echo $pids[0];
                                                    } else {
                                                        echo $erow['pid'];
                                                    }
                                                } else if (isset($_SESSION['code'])) {
                                                    echo $_SESSION['code'];
                                                }
                                                ?>" maxlength="50" />
                                            </div>
                                        </div>

                                        <div id='existingProd' style='display:none;'>
                                            <span id='selectNew'>+ Create new product</span>
                                            Product Code*:
                                            <select name='existing'>
                                            <?php 
                                                $prodSql = "Select * from products;";
                                                $pres = mysqli_query($link, $prodSql);

                                                if(!mysqli_query($link, $prodSql)){
                                                    die(mysqli_error($link));
                                                } else {
                                                    while($row = mysqli_fetch_assoc($pres)) {
                                                        $pid = $row['pid'];
                                                        //get related products
                                                        $relpos = strpos($pid, '-');
                                                        if (!is_numeric($relpos)) {
                                                            echo "<option value='".$pid."'";
                                                            if (isset($_SESSION['code'])) {
                                                                if(strcmp($_SESSION['code'], $pid) === 0) {
                                                                    echo " selected";
                                                                }
                                                            } else if (!empty($erow['pid'])) {
                                                                $pos = strpos($erow['pid'], "-");
                                                                if (is_numeric($pos)) {
                                                                    $pids = explode("-", $erow['pid']);
                                                                    $code = $pids[0];
                                                                } else {
                                                                    $code = $erow['pid'];
                                                                }
                                                                if(strcmp($code, $pid) === 0) {
                                                                    echo " selected";
                                                                }
                                                            } 
                                                            echo ">".$pid."</option>";
                                                        }
                                                    }
                                                }
                                            ?>
                                            </select>
                                        </div>
                                    </td>
                                    <td width='45%'>
                                        Colour*:
                                        <input type='text' name='colourcode' value='<?php 
                                            if (isset($_SESSION['colourcode'])) {
                                                echo $_SESSION['colourcode'];
                                            } else if (!empty($erow['pid'])){
                                                $pos = strpos($erow['pid'], "-");
                                                if (is_numeric($pos)) {
                                                    $pids = explode("-", $erow['pid']);
                                                    echo $pids[1];
                                                } 
                                            } 
                                        ?>'>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        Name*:
                                        <input type='text' name='name' id='name'  maxlength="50" value ="<?php 
                                        if (isset($_SESSION['name'])) {
                                            echo $_SESSION['name'];
                                        } else if (!empty($erow['name'])) {
                                            echo $erow['name'];
                                        }  
                                            ?>"/>
                                    </td>
                                    <td>
                                        <?php 
                                            if (isset($_SESSION['visibility'])) {
                                                $visib = explode(",", $_SESSION['visibility']);
                                            } else if (!empty($erow['visibility'])) {
                                                $visib = explode(",", $erow['visibility']);
                                            }  
                                        ?>
                                        Visibility*: <br/>
                                        <input name='visibility[]' type='checkbox' value='retail' <?php 
                                            if (!empty($erow['visibility']) || isset($_SESSION['visibility'])) {
                                                if (in_array("retail", $visib)) {
                                                    echo " checked";
                                                }
                                            }
                                            ?>><label>Retail</label>
                                        <input name='visibility[]' type='checkbox' value='popup' <?php 
                                            if (!empty($erow['visibility']) || isset($_SESSION['visibility'])) {
                                                if (in_array("popup", $visib)) {
                                                    echo " checked";
                                                }
                                            }
                                            ?>><label>Pop-up</label>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2">
                                        Description*:
                                        <textarea name='desc' id='desc'><?php 
                                        if (isset($_SESSION['desc'])) {
                                            echo $_SESSION['desc'];
                                        } else if (!empty($erow['description'])) {
                                            echo $erow['description'];
                                        }  
                                            ?></textarea>
                                        <script type="text/javascript">
                                            CKEDITOR.replace('desc');
                                        </script>
                                        <br>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        Status*:
                                        <select name='status' id='status'>
                                            <option value='active' <?php 
                                                if (isset($_SESSION['status'])) {
                                                    if (strcmp($_SESSION['status'], "active") === 0) {
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
                                                    if (strcmp($_SESSION['status'], "inactive") === 0) {
                                                        echo " selected";
                                                    }
                                                } else if (!empty($erow['status'])) {
                                                    if (strcmp($erow['status'], "inactive") === 0) {
                                                        echo " selected";
                                                    }
                                                } 
                                            ?>>Inactive</option>
                                        </select>
                                    </td>
                                    <td id='scheduledposts' style='display:none;'>
                                        Scheduled Date/Time:<br>
                                        <input style='width:45%!important;' type="text" placeholder="DATE" id="date4" name="date4" value='<?php if (isset($_SESSION['date4'])) {
                                                echo $_SESSION['date4'];
                                            } else if(!empty($erow['scheduled'])) {
                                            echo date('Y-m-d', strtotime($erow['scheduled']));
                                            } ?>'>
                                        <input style='width:45%!important;' id="setTimeExample" name='scheduledtime' placeholder="TIME" 
                                               type="text" class="time" value='<?php if (isset($_SESSION['time'])) {
                                                echo $_SESSION['time'];
                                            } else if(!empty($erow['scheduled'])) {
                                            echo date('H.i.s', strtotime($erow['scheduled']));
                                            } ?>'/><br>
                                        <button id="setTimeButton">Set current time</button>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2">
                                        Price*:
                                        <input type='text' name='price' id='price' 
                                               onkeypress="return isNumberKey(event)" value ="<?php 
                                        if (isset($_SESSION['price'])) {
                                            echo $_SESSION['price'];
                                        } else if (!empty($erow['price'])) {
                                            echo $erow['price'];
                                        }
                                            ?>"/>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        Width*:
                                        <input type='text' name='width' id='width' 
                                               onkeypress="return isNumber(event)" value ="<?php 
                                        if (isset($_SESSION['width'])) {
                                            echo $_SESSION['width'];
                                        } else if (!empty($erow['width'])) {
                                            echo $erow['width'];
                                        }
                                            ?>"/>
                                    </td>
                                    <td>
                                        <?php 
                                            if (isset($_SESSION['measurement'])) {
                                                $measureArr = explode("-", $_SESSION['measurement']);
                                            } else if (!empty($erow['measurement'])) {
                                                $measureArr = explode("-", $erow['measurement']);
                                            }
                                        ?>

                                        Measurements*: <br/>
<!--                                        <input type='text' name='measurement' id ='mment'
                                               onkeypress='return isNumber(event)'/>-->
                                        <input type='text' style='width:30%!important;' name='measurement1' id='measurement1' 
                                               onkeypress="return isNumber(event)" value ="<?php 
                                        if (!empty($measureArr[0])) {
                                            echo $measureArr[0];
                                        }
                                        ?>"/>
                                        <span class='padded-input'>-</span>

                                        <input type='text' style='width:30%!important;' name='measurement2' id='measurement2' 
                                               onkeypress="return isNumber(event)" value ="<?php 
                                        if (!empty($measureArr[1])) {
                                            echo $measureArr[1];
                                        }
                                        ?>"/>
                                        <span class='padded-input'>-</span>

                                        <input type='text' style='width:30%!important;' name='measurement3' id='measurement3' 
                                               onkeypress="return isNumber(event)" value ="<?php 
                                        if (!empty($measureArr[2])) {
                                            echo $measureArr[2];
                                        }
                                        ?>"/>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        Type*: 
                                        <select name="type">
                                            <?php 
                                                $types = "Select * from categories where type='product';";
                                                $res = mysqli_query($link, $types);
                                                while($trow = mysqli_fetch_assoc($res)) {
                                                    echo "<option value='".$trow['name']."'";
                                                    if (isset($_SESSION['type'])) {
                                                        if (strcmp($_SESSION['type'], $trow['name']) === 0) {
                                                            echo " selected";
                                                        }
                                                    } else if (!empty($erow['type'])) {
                                                        if (strcmp($erow['type'], $trow['name']) === 0) {
                                                            echo " selected";
                                                        }
                                                    } 
                                                    echo ">".$trow['name']."</option>";
                                                }
                                            ?>
                                        </select>
                                    </td>
                                    <td>
                                        <?php 
                                            if (isset($_SESSION['gender'])) {
                                                $genArr = explode(",", $_SESSION['gender']);
                                            } else if (!empty($erow['gender'])){
                                                $genArr = explode(",", $erow['gender']);
                                            } 
                                        ?>
                                        Gender*: <br/>
                                        <input name='gender[]' type='checkbox' value='men' <?php 
                                            if (!empty($erow['gender']) || isset($_SESSION['gender'])) {
                                                if (in_array("men", $genArr)) {
                                                    echo " checked";
                                                }
                                            }
                                            ?>>Men
                                        <input name='gender[]' type='checkbox' value='women' <?php 
                                            if (!empty($erow['gender']) || isset($_SESSION['gender'])) {
                                                if (in_array("women", $genArr)) {
                                                    echo " checked";
                                                }
                                            }
                                            ?>>Women
                                    </td>
                                </tr>
                                <?php 
                                    //get home try on settings
                                    $hometry = "Select * from settings where type='homeTryon';";
                                    $hres = mysqli_query($link, $hometry);
                                    
                                    if (!mysqli_query($link, $hometry)) {
                                        die(mysqli_error($link));
                                    } else {
                                        $row = mysqli_fetch_assoc($hres);
                                        $valArr = explode("&", $row['value']);
                                        $permission = explode("visibility=", $valArr[0]);
                                    }
                                ?>
                                <tr>
                                    <td colspan='2'>
                                        <?php 
                                            if (isset($_SESSION['availability'])) {
                                                $avai = explode(",", $_SESSION['availability']);
                                            } else if (!empty($erow['availability'])) {
                                                $avai = explode(",", $erow['availability']);
                                            }  
                                        ?>
                                        Availability*: <br/>
                                        <input name='availability[]' type='checkbox' value='sale' <?php 
                                            if (!empty($erow['availability']) || isset($_SESSION['availability'])) {
                                                if (in_array("sale", $avai)) {
                                                    echo " checked";
                                                }
                                            } else if ((!empty($permission[1])) && (strcmp($permission[1], "off") === 0)) {
                                                echo " checked";
                                            }
                                            ?>>For Sale
                                        <?php if ((!empty($permission[1])) && (strcmp($permission[1], "on") === 0)) { ?>
                                            <input name='availability[]' type='checkbox' value='tryon' <?php 
                                                if (!empty($erow['availability']) || isset($_SESSION['availability'])) {
                                                    if (in_array("tryon", $avai)) {
                                                        echo " checked";
                                                    }
                                                }
                                                ?>>Home Try-on
                                        <?php } ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2">
                                        Tags:
                                        <div id='no-tags' stye='display:none;'>
                                            No existing tags found
                                        </div>
                                        <input type='hidden' id='tags' name='tags'>
                                        <div class="control-group">
                                                <select id="select-to" class="contacts" placeholder="Add some tags..."></select>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <?php 
                                            if (!empty($erow['featured'])) {
                                                $featArr = explode(",", $erow['featured']);
                                                for($i =0; $i < count($featArr); $i++) {
                                                    if (!empty($featArr[$i])) {
                                                        echo '<div class="button addMore" onClick="unlinkFeatImg(\''.$featArr[$i].'\')"><img src="'.$featArr[$i].'" width=200></div><br>';
                                                    }
//                                                    echo "<img src='".$featArr[$i]."' width=200>";
                                                }
                                                echo "<br><input type='hidden' name='oldFeaturedImages' value='".$erow['featured']."'>";
                                            }
                                        ?>

                                        Featured Image(s): 
                                        <input type="file" name="featured[]" id='featured' multiple accept='image/*'/>
                                    </td>
                                    <td>
                                        <?php 
                                            if (!empty($erow['images'])) {
                                                $imgArr = explode(",", $erow['images']);
                                                for($i =0; $i < count($imgArr); $i++) {
                                                    if (!empty($imgArr[$i])) {
                                                        echo '<div class="button addMore" onClick="unlinkImg(\''.$imgArr[$i].'\')"><img src="'.$imgArr[$i].'" width=200></div><br>';
                                                    }
                                                }
                                                echo "<br><input type='hidden' name='oldImage' value='".$erow['images']."'>";
                                            }
                                        ?>

                                        Image(s): 
                                        <input type="file" name="images[]" id='images' multiple accept='image/*'/>
                                    </td>
                                </tr>
                                <tr id='locationLinks'>
                                    <td colspan="2">
                                        <?php 
                                            if (isset($_SESSION['locations']) && isset($_SESSION['tracks']) && isset($_SESSION['locqty'])) {
                                                $slocs = $_SESSION['locations'];
                                                $slocqty = $_SESSION['locqty'];
                                            } else if(!empty($erow['locations'])) {
                                                $slocs = explode(",", $erow['locations']);
                                                $slocqty = explode(",", $erow['locationqty']);
                                            }  
                                        ?>
                                        <input type='hidden' name='locno' id='locno' value='<?php 
                                                if(!empty($slocs)) {
                                                    echo count($slocs);
                                                } else {
                                                    echo '1';
                                                }
                                        ?>'>
                                        
                                        <h4 class="pull-left">Location
                                        <div id='nanLocError' style="display: none;"><h6 class="pull-left">Please enter numbers only</h6></div>
                                        </h4>
                                        <div onclick="addLocation()" class="addMore text-right">
                                            <i class="fa fa-fw fa-plus"></i> Add Location
                                        </div>
                                    </td>
                                </tr>
                                <?php 
                                    if (!empty($slocs)) {
                                        for ($i = 0; $i < count($slocs); $i++) {
                                            if (!empty($slocs[$i])) {
                                ?>
                                <tr>
                                    <td>
                                        <?php
                                            $locSql = "Select * from locations where name <> 'banner'";
                                            $locResult = mysqli_query($link, $locSql);

                                            if (!mysqli_query($link,$locSql))
                                            {
                                                echo("Error description: " . mysqli_error($link));
                                            } else {
                                                if ($locResult->num_rows !== 0) {
                                                    $index = $i +1;
                                                    $locNames = "";
                                                    $locCodes = "";
                                                echo '<h5 class="page-header">Location '.$index.'</h5>';
                                                echo "<select name='locations$index'>";
                                                while($row = mysqli_fetch_assoc($locResult)) {
                                                    $locCodes .= $row['code'].",";
                                                    $locNames .= $row['name'].",";
                                                    echo '<option value="'.$row['code'].'"';
                                                    if (strcmp($row['code'], $slocs[$i])) {
                                                        echo " selected";
                                                    }
                                                    echo '><label>'.$row['name'].'</label>';
                //
                //                                    if ($i % 2 === 0) {
                //                                        echo "<br>";
                //                                    }
                                                }
                                                echo "</select>";
                                                echo "<input type='hidden' name='allLocNames' id='allLocNames' value='".$locNames."'>";
                                                echo "<input type='hidden' name='allLocCodes' id='allLocCodes' value='".$locCodes."'>";
                                        ?>
                                    </td>
                                    <td>
                                        Track Inventory for Location <?php echo $index; ?>?*:
                                        <input type='checkbox' name='track<?php echo $index; ?>' id='track<?php echo $index; ?>' value ="yes" <?php 
                                        if (!empty($slocqty[$i])) {
                                            echo " checked";
                                        }
                                            ?>/>
                                        <br>

                                        <div id='showQty<?php echo $index; ?>' style='display: none;'>
                                            Quantity*:
                                            <input type='text' name='qty<?php echo $index; ?>' id='qty<?php echo $index; ?>' 
                                                   onkeypress="return isLocNumber(event)" value ="<?php 
                                            if (!empty($slocqty[$i])) {
                                                echo $slocqty[$i];
                                            }
                                                ?>"/>
                                        </div>
                                    </td>
                                </tr>
                                <?php   
                                        }
                                    }
                                ?>
                                <?php
                                            }   
                                        }
                                    } else {
                                ?>
                                <tr>
                                    <td>
                                      <?php
                                            $locSql = "Select * from locations where name <> 'banner'";
                                            $locResult = mysqli_query($link, $locSql);

                                            if (!mysqli_query($link,$locSql))
                                            {
                                                echo("Error description: " . mysqli_error($link));
                                            } else {
                                                if ($locResult->num_rows !== 0) {
                                                $count = 1;
                                                $locNames = "";
                                                $locCodes = "";
                                                echo '<h5 class="page-header">Location '.$count.'</h5>';
                                                echo "<select name='locations$count'>";
                                                while($row = mysqli_fetch_assoc($locResult)) {
                                                    $locCodes .= $row['code'].",";
                                                    $locNames .= $row['name'].",";
                                                    echo '<option value="'.$row['code'].'"';

                                                    if (!empty($erow['locations'])) {
                                                        if (in_array($row['code'], $locs)) {
                                                            echo " selected";
                                                        }
                                                    }
                                                    echo '><label>'.$row['name'].'</label>';

                                                    if ($count % 2 === 0) {
                                                        echo "<br>";
                                                    }
                                                }
                                                echo "</select>";
                                                echo "<input type='hidden' name='allLocNames' id='allLocNames' value='".$locNames."'>";
                                                echo "<input type='hidden' name='allLocCodes' id='allLocCodes' value='".$locCodes."'>";
                                    ?>
                                    </td>
                                    <td>
                                        Track Inventory for Location 1?*:
                                        <input type='checkbox' name='track<?php echo $count; ?>' id='track<?php echo $count; ?>' value ="yes" <?php 
                                        if (!empty($erow['track'])) {
                                            if (strcmp($erow['track'], "yes") === 0) {
                                                echo " checked";
                                            }
                                        }
                                            ?>/>
                                        <br>

                                        <div id='showQty<?php echo $count; ?>' style='display: none;'>
                                            Quantity*:
                                            <input type='text' name='qty<?php echo $count; ?>' id='qty<?php echo $count; ?>' 
                                                   onkeypress="return isLocNumber(event)" value ="<?php 
                                            if (!empty($erow['quantity'])) {
                                                echo $erow['quantity'];
                                            }
                                                ?>"/>
                                            <br>
                                        </div>
                                    </td>
                                </tr>
                                    <?php   
                                            }
                                        }
                                    ?>
                                <?php
                                    }
                                ?>
                            </table>
                            <input type='hidden' name='submitted' value='1' />
                            <input type='submit' name='submit' value='Save' />
                        </form>
                    </div>
                </div>
                <!-- /.row -->

            </div>
            <!-- /.container-fluid -->

        </div>
        <!-- /#page-wrapper -->
    </div>
</html>
           
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

<script>  
    var myCalendar2 = new dhtmlXCalendarObject(["date4"]);
    myCalendar2.hideTime();

    $(function() {
        $('#setTimeExample').timepicker();
        $('#setTimeButton').on('click', function (event){
            event.preventDefault();
            $('#setTimeExample').timepicker('setTime', new Date());
        });
        
        var status = document.getElementById('status').value;
        
        if (status === "inactive") {
            document.getElementById('scheduledposts').style.display = "block";
        } else {
            document.getElementById('scheduledposts').style.display = "none";
        }
    });
    
    document.getElementById('status').onclick = function() {
        var val = this.value;
        
        if (val === "inactive") {
            document.getElementById('scheduledposts').style.display = "block";
        } else {
            document.getElementById('scheduledposts').style.display = "none";
        }
    };
    
    function unlinkImg(img) {
        var id;
        <?php if (isset($_GET['id'])) { ?> 
                id = <?php echo "'".$_GET['id']."'"; ?>;                
        <?php } else { ?>;
                id = "";
        <?php } ?>
        window.location="processMedia.php?type=products&feat=no&id="+id+"&file=" + img;
    }    
    
    function unlinkFeatImg(img) {
        var id;
        <?php if (isset($_GET['id'])) { ?> 
                id = <?php echo "'".$_GET['id']."'"; ?>;
        <?php } else { ?>;
                id = "";
        <?php } ?>
        window.location="processMedia.php?type=products&feat=yes&id="+id+"&file=" + img;
    }    
    
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
            $tagsql = "Select * from products where pid='".$_GET['id']."';";
            $tresult = mysqli_query($link, $tagsql);

            if (!mysqli_query($link, $tagsql)) {
                die(mysqli_errno($link));
            } else {
//                while ($row = mysqli_fetch_assoc($tresult)) {
                $row = mysqli_fetch_assoc($tresult);
                $tagsAr = explode(",", $row['tags']);
                
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
    
    function checkTrack(num) {
        var track = "track" + num;
        var qty = "showQty" + num;
        document.getElementById(track).onclick = function(){  
            var el = document.getElementById(qty);
            if (el.style.display === "none") {
                el.style.display = "block";
            } else {
                el.style.display = "none";
            }
        };
    }
    
    function attachTrack(num) {
        for (var i = 1; i <= num; i++) {
            checkTrack(i);
            var track = "track" + i;
            var qty = 'showQty' + i;
            if (document.getElementById(track).checked) {
                document.getElementById(qty).style.display = "block";            
            }   
        }
    }
    
    window.onload = function () {
        var num = document.getElementById('locno').value;
        attachTrack(num);
    };
    
    <?php if (strcmp($tagStr, "") === 0) { ?>
        document.getElementById('no-tags').style.display = "block";
    <?php } else { ?>
        document.getElementById('no-tags').style.display = "none";        
    <?php } ?>
    
    var locNames = document.getElementById('allLocNames').value;
    var locCodes = document.getElementById('allLocCodes').value;

    function addLocation() {
        var count = document.getElementById('locno').value;
        count++;
        document.getElementById('locno').value = count;
        
        var x=document.getElementById('prodForm');
        // deep clone the targeted row
        var new_row = x.rows[1].cloneNode(true);
           // get the total number of rows
        var len = x.rows.length;
           // set the innerHTML of the first row 
           
        new_row.cells[0].innerHTML = "<h5 class='page-header'>Location " + count + "</h5>";

        var select = document.createElement( 'select' );
        select.name = "locations" +count;
        var nameArr = locNames.split(",");
        var codeArr = locCodes.split(",");
        var option;

        for (var i = 0; i < nameArr.length; i++) {

            if (nameArr[i] !== "" && codeArr[i] !== "") {
                option = document.createElement( 'option' );
                option.value = codeArr[i];
                option.textContent = nameArr[i];
                select.appendChild( option );
            }
         };
        new_row.cells[0].appendChild(select);
        new_row.cells[1].innerHTML = "Track Inventory for Location "+count+"? *:" +
            "<input type='checkbox' name='track" + count +
                    "' id='track" + count + "' value='yes' /><br>" +
                    "<div id='showQty" + count + "' style='display:none;'>" + 
                    "Quantity*:<input type='text' name='qty" + count +"' id='qty" + count + "'" +
                    " onkeypress='return isLocNumber(event)'><br></div>";
        
           // append the new row to the table
        x.appendChild( new_row );
        attachTrack(count);
    }
    
    document.getElementById('selectExisting').onclick = function() {
        document.getElementById('existingProd').style.display = "block";
        document.getElementById('newProduct').style.display = "none";
        document.getElementById('addExisting').value = "yes";
    };

    document.getElementById('selectNew').onclick = function() {
        document.getElementById('existingProd').style.display = "none";
        document.getElementById('newProduct').style.display = "block";
        document.getElementById('addExisting').value = "no";
    };

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
    
    
    function isLocNumber(evt) {
        evt = (evt) ? evt : window.event;
        var charCode = (evt.which) ? evt.which : evt.keyCode;
        if (charCode > 31 && (charCode < 48 || charCode > 57)) {
            document.getElementById('nanLocError').style.display='block';
            document.getElementById('nanLocError').style.color='red';
            return false;
        }
        document.getElementById('nanLocError').style.display='none';
        return true;
    }
    
    function randomString() {
        var text = "";
        var possible = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";

        for( var i=0; i < 5; i++ )
            text += possible.charAt(Math.floor(Math.random() * possible.length));

        document.getElementById('code').value = text;
        return false;
    }

    function deleteFunction(prodId) {
        var r = confirm("Are you sure you wish to delete this product?");
        if (r === true) {
            window.location="processProducts.php?delete=1&pid=" + prodId;
        } else if (r === false) {
            <?php
                unset($_SESSION['updateProdSuccess']);
                $_SESSION['updateProdError'] = "Nothing was deleted";
            ?>
            window.location='products.php';
        }
    }
    
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
</script>

