<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
session_start();
require '../config/db.php';

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
                    echo '<td><button onClick="window.location.href=`editProduct.php?id='.$row['id'].'`">E</button>';
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
            <legend>Add Product</legend>
            <input type='hidden' name='submitted' id='submitted' value='1'/>
            
            <label for='code' >Product Code*:</label>
            <input type='text' name='code' id='code' value ="<?php 
            if(isset($_SESSION['randomString'])) { 
                echo $_SESSION['randomString']; } ?>" maxlength="50" />
            <button type='button' onclick="randomString()">Generate</button>
            <br>
            
            <label for='name' >Name*:</label>
            <input type='text' name='name' id='name'  maxlength="50" />
            <br>
            <label for='desc' >Description*:</label>
            <textarea name='desc' id='desc'></textarea>
            <br>
            <label for='price' >Price*:</label>
            <input type='text' name='price' id='price' onkeypress="return isNumberKey(event)" />
            <label for='qty' >Quantity*:</label>
            <input type='text' name='qty' id='qty' onkeypress="return isNumber(event)"/>
            <br>
            Type*:
            <select name="type">
                <option value="sunglasses">Sunglasses</option>
                <option value="frames">Frames</option>
            </select>
            <br>
            Visibility*: 
            <input name='visibility[]' type='checkbox' value='retail'><label>Retail</label>
            <input name='visibility[]' type='checkbox' value='popup'><label>Pop-up</label>
            <br>
            Availability*: 
            <input name='availability[]' type='checkbox' value='sale'><label>For Sale</label>
            <input name='availability[]' type='checkbox' value='tryon'><label>Home Try-on</label>
            <br>
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
                    value="'.$row['code'].'"><label>'.
                                $row['name'].'</label>';
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
            <input type='text' name="tags" id='tags'>
            <br>
            Image(s): 
            <input type="file" name="images[]" id='images' multiple accept='image/*'/>
            <br>
            <input type='submit' name='submit' value='Submit' />
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

