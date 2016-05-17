<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
require_once '../config/db.php';
require_once('../nav/adminHeader.php');

if (isset($_GET['id'])) {
    unset($_SESSION['updateProdSuccess']);
    unset($_SESSION['updateProdError']);
    unset($_SESSION['addProdSuccess']);
    unset($_SESSION['addProdError']);
    $selectSql = "Select * from products where id ='" .$_GET['id']."';";
    $eresult = mysqli_query($link, $selectSql);

    if (!mysqli_query($link,$selectSql))
    {
        echo("Error description: " . mysqli_error($link));
    } else {
        $erow = mysqli_fetch_assoc($eresult);
    }
}
?>
<html>    
    <div id="framecontent">
        <div class='innertube'>
        <?php
            require '../nav/adminSidebar.php';
        ?>
        </div>
    </div>
    <div id="maincontent">
        <div class="innertube">
        <h2>Manage Products</h2>
        <br>
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
            <table>
                <thead>
                    <th>Name</th>
                    <th>Type</th>
                    <th>Price</th>
                    <th>Quantity</th>
                    <th>Visibility</th>
                    <th>Availability</th>
                    <th>Edit</th>
                    <th>Delete</th>                        
                </thead>
            <?php
                // output data of each row
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<tr>";
                    echo "<td>".$row['name']."</td>";
                    echo "<td>".$row['type']."</td>";                            
                    echo "<td>".$row['price']."</td>";                           
                    echo "<td>".$row['quantity']."</td>";                           
                    echo "<td>".$row['visibility']."</td>";                          
                    echo "<td>".$row['availability']."</td>";                         
                    echo '<td><button onClick="window.location.href=`products.php?id='.$row['id'].'`">E</button>';
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
        <br><hr>
        
        <form id='addProduct' action='processProducts.php' method='post' accept-charset='UTF-8' enctype="multipart/form-data">
            <fieldset >
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
            <legend>Add/Edit Product</legend>
            <input type='hidden' name='submitted' id='submitted' value='1'/>
            <input type='hidden' name='editid' id='editid' 
                   value='<?php if (isset($_GET['id'])) { echo $erow['pid']; }?>'/>
            
            <label for='code' >Product Code*:</label>
            <input type='text' name='code' id='code' value ="<?php 
            if(isset($_SESSION['randomString'])) { 
                echo $_SESSION['randomString']; } 
            if (!empty($erow['pid'])) {
                echo $erow['pid'];
            }
                ?>" maxlength="50" />
            <button type='button' onclick="randomString()">Generate</button>
            <br>
            
            <label for='name' >Name*:</label>
            <input type='text' name='name' id='name'  maxlength="50" value ="<?php 
            if (!empty($erow['name'])) {
                echo $erow['name'];
            }
                ?>"/>
            <br>
            <label for='desc' >Description*:</label>
            <textarea name='desc' id='desc'><?php 
            if (!empty($erow['description'])) {
                echo $erow['description'];
            }
                ?></textarea>
            <script type="text/javascript">
                CKEDITOR.replace('desc');
            </script>
            <br>
            <label for='price' >Price*:</label>
            <input type='text' name='price' id='price' 
                   onkeypress="return isNumberKey(event)" value ="<?php 
            if (!empty($erow['price'])) {
                echo $erow['price'];
            }
                ?>"/>
            <label for='qty' >Quantity*:</label>
            <input type='text' name='qty' id='qty' 
                   onkeypress="return isNumber(event)" value ="<?php 
            if (!empty($erow['quantity'])) {
                echo $erow['quantity'];
            }
                ?>"/>
            <br>
            Type*:
            <select name="type">
                <option value="sunglasses" <?php 
                if (!empty($erow['type'])) {
                    if (strcmp($erow['type'], "sunglasses") === 0) {
                        echo " selected";
                    }
                }
                ?>>Sunglasses</option>
                <option value="frames" <?php 
                if (!empty($erow['type'])) {
                    if (strcmp($erow['type'], "frames") === 0) {
                        echo " selected";
                    }
                }
                ?>>Frames</option>
            </select>
            <br>
            <?php 
                $visib = explode(",", $erow['visibility']);
            ?>
            Visibility*: 
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
            <br>
            <?php 
                $avai = explode(",", $erow['availability']);
            ?>
            Availability*: 
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
            <br>
            <?php 
                $locs = explode(",", $erow['locations']);
            ?>
            Locations*:
            <table width='500px' style='margin-left: 80px; margin-top:-22px'>
                <tr><td>
            <?php
                $locSql = "Select * from locations";
                $locResult = mysqli_query($link, $locSql);

            if (!mysqli_query($link,$locSql))
            {
                echo("Error description: " . mysqli_error($link));
            } else {
                if ($locResult->num_rows === 0) {
            ?>
                <input type="checkbox" name="locations[]" value="nil"><label>No locations</label>
            <?php
                } else {
                    $count = 0;
                    while($row = mysqli_fetch_assoc($locResult)) {
                        echo '<input type="checkbox" name="locations[]" 
                    value="'.$row['code'].'"';
                
                        if (!empty($erow['locations'])) {
                            if (in_array($row['code'], $locs)) {
                                echo " checked";
                            }
                        }
                        echo '><label>'.$row['name'].'</label>';
                        $count++;
                        
                        if ($count % 2 === 0) {
                            echo "<br>";
                        }
                    }
                }
            }
            ?>
                    </td></tr>
            </table>
            Tags:
            <input type='text' name="tags" id='tags' value ="<?php 
            if (!empty($erow['tags'])) {
                echo $erow['tags'];
            }
                ?>">
            <br>
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
            <br>
            <input type='submit' name='submit' value='Submit' />
            </fieldset>
        </form>
        </div>
    </div>
    <script>
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
    </script>
</html>

