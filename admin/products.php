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
                        
                        <table>
                            <thead>
                                <th>Name</th>
                                <th>Type</th>
                                <th>Price</th>
                                <th>Visibility</th>
                                <th>Availability</th>
                                <th>Edit</th>
                                <th>Delete</th>                        
                            </thead>
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
                        </table>
                        <?php
                            } 
                        }
                        ?> 
                        
                        <div id="updateProdSuccess" style="color:green">
                            <?php 
                                if (isset($_SESSION['updateProdSuccess'])) {
                                    echo $_SESSION['updateProdSuccess'];
                                }
                            ?>
                        </div>
                        <div id="updateProdError" style="color:red">
                            <?php 
                                if (isset($_SESSION['updateProdError'])) {
                                    echo $_SESSION['updateProdError'];
                                }
                            ?>
                        </div>
        
                        <form id='addProduct' action='processProducts.php' method='post' accept-charset='UTF-8' enctype="multipart/form-data">
                            <div id="addProdError" style="color:red">
                                <?php 
                                    if (isset($_SESSION['addProdError'])) {
                                        echo $_SESSION['addProdError'];
                                    }
                                ?>
                            </div>

                            <p id='nanError' style="display: none;">Please enter numbers only</p>
                            <div id="addProdSuccess" style="color:green">
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
                                            <span id='selectExisting'>+ Select from existing products</span>
                                            <input type='text' name='code' id='code' value ="<?php 
                                                if(isset($_SESSION['randomString'])) { 
                                                    echo $_SESSION['randomString']; } 
                                                if (!empty($erow['pid'])) {
                                                    $pos = strpos($erow['pid'], "-");
                                                    if (is_numeric($pos)) {
                                                        $pids = explode("-", $erow['pid']);
                                                        echo $pids[0];
                                                    } else {
                                                        echo $erow['pid'];
                                                    }
                                                }
                                                ?>" maxlength="50" />
                                            <button type='button' onclick="randomString()">Generate</button>
                                            <br>
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
                                                            echo "<option value='".$pid."'>".$pid."</option>";
                                                        }
                                                    }
                                                }
                                            ?>
                                            </select>
                                        </div>
                                    </td>
                                    <td>
                                        Colour*:
                                        <input type='text' name='colourcode' value='<?php 
                                        if (!empty($erow['pid'])) {
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
                                        if (!empty($erow['name'])) {
                                            echo $erow['name'];
                                        }
                                            ?>"/>
                                    </td>
                                    <td>
                                        <?php 
                                            if (!empty($erow['visibility'])) {
                                                $visib = explode(",", $erow['visibility']);
                                            }
                                        ?>
                                        Visibility*: <br/>
                                        <input name='visibility[]' type='checkbox' value='retail' <?php 
                                            if (!empty($erow['visibility'])) {
                                                if (in_array("retail", $visib)) {
                                                    echo " checked";
                                                }
                                            }
                                            ?>><label>Retail</label>
                                        <input name='visibility[]' type='checkbox' value='popup' <?php 
                                            if (!empty($erow['visibility'])) {
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
                                        if (!empty($erow['description'])) {
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
                                    <td colspan="2">
                                        Price*:
                                        <input type='text' name='price' id='price' 
                                               onkeypress="return isNumberKey(event)" value ="<?php 
                                        if (!empty($erow['price'])) {
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
                                        if (!empty($erow['width'])) {
                                            echo $erow['width'];
                                        }
                                            ?>"/>
                                    </td>
                                    <td>
                                        <?php 
                                            if (!empty($erow['measurement'])) {
                                                $measureArr = explode("-", $erow['measurement']);
                                            }
                                        ?>

                                        Measurements*: <br/>
                                        <input type='text' name='measurement1' id='measurement1' 
                                               onkeypress="return isNumber(event)" value ="<?php 
                                        if (!empty($measureArr[0])) {
                                            echo $measureArr[0];
                                        }
                                        ?>"/>-

                                        <input type='text' name='measurement2' id='measurement2' 
                                               onkeypress="return isNumber(event)" value ="<?php 
                                        if (!empty($measureArr[1])) {
                                            echo $measureArr[1];
                                        }
                                        ?>"/>-

                                        <input type='text' name='measurement3' id='measurement3' 
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
                                                    if (!empty($erow['type'])) {
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
                                            if (!empty($erow['gender'])){
                                                $genArr = explode(",", $erow['gender']);
                                            }
                                        ?>
                                        Gender*: <br/>
                                        <input name='gender[]' type='checkbox' value='men' <?php 
                                            if (!empty($erow['gender'])) {
                                                if (in_array("men", $genArr)) {
                                                    echo " checked";
                                                }
                                            }
                                            ?>><label>Men</label>
                                        <input name='gender[]' type='checkbox' value='women' <?php 
                                            if (!empty($erow['gender'])) {
                                                if (in_array("women", $genArr)) {
                                                    echo " checked";
                                                }
                                            }
                                            ?>><label>Women</label>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan='2'>
                                        <?php 
                                            if (!empty($erow['availability'])) {
                                                $avai = explode(",", $erow['availability']);
                                            }
                                        ?>
                                        Availability*: <br/>
                                        <input name='availability[]' type='checkbox' value='sale' <?php 
                                            if (!empty($erow['availability'])) {
                                                if (in_array("sale", $avai)) {
                                                    echo " checked";
                                                }
                                            }
                                            ?>><label>For Sale</label>
                                        <input name='availability[]' type='checkbox' value='tryon' <?php 
                                            if (!empty($erow['availability'])) {
                                                if (in_array("tryon", $avai)) {
                                                    echo " checked";
                                                }
                                            }
                                            ?>><label>Home Try-on</label>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2">
                                        Tags:
<!--                                        <input type='text' name="tags" id='tags' value ="<?php 
//                                        if (!empty($erow['tags'])) {
//                                            echo $erow['tags'];
//                                        }
                                            ?>">-->
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
                                                    echo "<img src='".$featArr[$i]."' width=200>";
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
                                                    echo "<img src='".$imgArr[$i]."' width=200>";
                                                }
                                                echo "<br><input type='hidden' name='oldImage' value='".$erow['images']."'>";
                                            }
                                        ?>

                                        Image(s): 
                                        <input type="file" name="images[]" id='images' multiple accept='image/*'/>
                                    </td>
                                </tr>
                                <tr id='locationLinks'>
                                    <td>
                                        <?php 
                                            if(!empty($erow['locations'])) {
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
                                        </div><br><br></td>
                                    <td></td>
                                </tr>
                        <?php 
                            if (!empty($slocs)) {
                                for ($i = 0; $i < count($slocs); $i++) {
                                    if (!empty($slocs[$i])) {
                        ?>
                                <tr><td>
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
                                }
                            } else {
                        ?>
                                <tr><td>
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
<!--                                <tr>
                                    <td>-->
                                        <input type='hidden' name='submitted' value='1' />
                                        <input type='submit' name='submit' value='Submit' />
<!--                                    </td>
                                </tr>-->
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
    
    
    
    $(function() {
        $("#select-to").selectize({
            create: true
        });

        var selectize_tags = $("#select-to")[0].selectize;
        <?php 
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
</script>

