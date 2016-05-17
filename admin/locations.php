<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
require_once '../config/db.php';

if (isset($_GET['id'])) {
    unset($_SESSION['addLocSuccess']);
    unset($_SESSION['addLocError']);
    unset($_SESSION['updateLocSuccess']);
    unset($_SESSION['updateLocError']);
    unset($_SESSION['uploadLocError']);
    unset($_SESSION['randomString']);
    $selectSql = "Select * from locations where id ='" .$_GET['id']."';";
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
        <h2>Manage Locations</h2>
        
        <?php 
            $qry = "Select * from locations";
            
            $result = mysqli_query($link, $qry);

            if (!mysqli_query($link,$qry))
            {
                echo("Error description: " . mysqli_error($link));
            } else {
                if ($result->num_rows === 0) {
                    echo "You have not created any locations yet.";
                } else {
            ?>
            <table>
                <thead>
                    <th>Name</th>
                    <th>Address</th>
                    <th>Phone</th>
                    <th>Type</th>
                    <th>Services</th>
                    <th>Edit</th>
                    <th>Delete</th>                        
                </thead>
            <?php
                // output data of each row
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<tr>";
                    echo "<td>".$row['name'] ."</td>";
                    echo "<td>".$row['address'].", ".$row['city'].", ".$row['country']."</td>";                            
                    echo "<td>".$row['phone']."</td>";                           
                    echo "<td>".$row['type']."</td>";                           
                    echo "<td>".$row['services']."</td>";                         
                    echo '<td><button onClick="window.location.href=`locations.php?id='.$row['id'].'`">E</button>';
                    echo '<td><button onClick="deleteFunction('.$row['id'].')">D</button></td>';
                    echo "</tr>";
                }
            ?>
            </table>
            <?php
                } 
            }
            ?>
            <div id="updateLocSuccess" style="color:green">
                <?php 
                    if (isset($_SESSION['updateLocSuccess'])) {
                        echo $_SESSION['updateLocSuccess'];
                    }
                ?>
            </div>
            <div id="updateLocError" style="color:red">
                <?php 
                    if (isset($_SESSION['updateLocError'])) {
                        echo $_SESSION['updateLocError'];
                    }
                ?>
            </div>
        <hr><br>
        
        <form id='addLocation' action='processLocations.php' method='post' accept-charset='UTF-8' enctype="multipart/form-data">
            <fieldset >
            <div id="addLocError" style="color:red">
                <?php 
                    if (isset($_SESSION['addLocError'])) {
                        echo $_SESSION['addLocError'];
                    }
                    
                    if (isset($_SESSION['uploadLocError'])) {
                        echo $_SESSION['uploadLocError'];
                    }
                ?>
            </div>
            <p id='nanError' style="display: none;">Please enter numbers only</p>
            
            <div id="addLocSuccess" style="color:green">
                <?php 
                    if (isset($_SESSION['addLocSuccess'])) {
                        echo $_SESSION['addLocSuccess'];
                    }
                ?>
            </div>
            <legend>Add/Edit Location</legend>
            <input type='hidden' name='submitted' id='submitted' value='1'/>
            <input type='hidden' name='editid' id='editid' 
                   value='<?php if (isset($_GET['id'])) { echo $erow['id']; }?>'/>
            <label for='code' >Location Code*:</label>
            <input type='text' name='code' id='code' value ="<?php 
            if(isset($_SESSION['randomString'])) { 
                echo $_SESSION['randomString']; } 
            if (!empty($erow['code'])) { 
                echo $erow['code']; }?>" maxlength="50" />
            <button type='button' onclick="randomString()">Generate</button>
            <br>
            <label for='name' >Name*:</label>
            <input type='text' name='name' id='name'  maxlength="50" value ="<?php 
            if (!empty($erow['name'])) { 
                echo $erow['name']; }?>"/>
            <br>
            <label for='address' >Address*:</label>
            <input type='text' name='address' id='address'  maxlength="50" value ="<?php 
            if (!empty($erow['address'])) { 
                echo $erow['address']; }?>"/>
            <br>
            <label for='phone' >Phone*:</label>
            <input type='text' name='phone' id='phone'  maxlength="50"  
                   onkeypress="return isNumber(event)" value ="<?php 
            if (!empty($erow['phone'])) { 
                echo $erow['phone']; }?>"/>
            <br>
            <label for='apt' >Apt, suite:</label>
            <input type='text' name='apt' id='apt'  maxlength="50" value ="<?php 
            if (!empty($erow['apt'])) { 
                echo $erow['apt']; }?>"/>
            <br>
            <label for='city' >City*:</label>
            <input type='text' name='city' id='city'  maxlength="50" value ="<?php 
            if (!empty($erow['city'])) { 
                echo $erow['city']; }?>"/>
            <br>
            <label for='zip' >ZIP Code*:</label>
            <input type='text' name='zip' id='zip'  maxlength="50"  
                   onkeypress="return isNumber(event)" value ="<?php 
            if (!empty($erow['zip'])) { 
                echo $erow['zip']; }?>"/>
            <br>
            <label for='country' >Country*:</label>
            <input type='text' name='country' id='country'  maxlength="50" value ="<?php 
            if (!empty($erow['country'])) { 
                echo $erow['country']; }?>"/>
            <br>
            <?php 
                if (!empty($erow['image'])) {
                    echo "<img src='".$erow['image']."' width=200><br>";
                    echo "<input type='hidden' name='oldImage' id='oldImage' value='".$erow['image']."'>";
                }
            ?>
            <label for='image' >Image:</label>
            <input type="file" name="image" id='image' accept="image/*" />
<!--             Button trigger modal 
            <button type="button" id='showModal' class="btn btn-primary btn-lg" data-toggle="modal" data-target="#popupModal">
              Select from existing images
            </button>
            <br>
            
             Modal 
            <div class="modal modal-default" id="popupModal">
            <div class="modal-dialog">
              <div class="modal-content">
                <div class="modal-header" style="background-color: #00A388; color: white;" align="center">
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                  <h4 class="modal-title"><strong>Welcome!</strong></h4>
                </div>
                <div class="modal-body">
                  //<?php
//                        $directory = "../uploads/*/";
//
//                        //get all image files with a .jpg extension.
//                        $images = glob("" . $directory . "*.*");
//
//                        $imgs = '';
//                        // create array
//                        foreach($images as $image){ $imgs[] = "$image"; }
//
//                        //shuffle array
//                        shuffle($imgs);
//
//                        //display images
//                        foreach ($imgs as $img) {
//                            echo "<input id='selectedImg' type='hidden' value='$img'><img src='$img' width=200/>";
//                        }
//                    ?>
                </div>
                <div class="modal-footer" style="background-color: #ECF0F1">
                  <button type="button" class="btn btn-outline pull-left" data-dismiss="modal">Close</button>
                  <button type="button" class="btn btn-primary" id="profileInfo">Submit</button>
                </div>
              </div> /.modal-content 
            </div> /.modal-dialog 
          </div> /.modal -->
<br>
            Type*:
            <select name="type">
                <option value="retail" <?php 
                    if (!empty($erow['type'])) {
                        if (strcmp($erow['type'], "retail") === 0) {
                            echo " selected";
                        }
                    }
                ?>>Retail</option>
                <option value="popup" <?php 
                    if (!empty($erow['type'])) {
                        if (strcmp($erow['type'], "popup") === 0) {
                            echo " selected";
                        }
                    }
                ?>>Pop-up</option>
            </select>
            <br>
            Services*:
            <table width='500px' style='margin-left: 70px; margin-top:-20px'>
                <tr><td>
            <?php
                if(!empty($erow['services'])){
                    $serviceArr = explode(",", $erow['services']);
                }
                $serviceSql = "Select * from services";
                $serviceResult = mysqli_query($link, $serviceSql);

            if (!mysqli_query($link,$serviceSql))
            {
                echo("Error description: " . mysqli_error($link));
            } else {
                if ($serviceResult->num_rows === 0) {
            ?>
                <input type="checkbox" name="services[]" value="nil"><label>No services</label>
            <?php
                } else {
                    $count = 0;
                    while($row = mysqli_fetch_assoc($serviceResult)) {
                        echo '<input type="checkbox" name="services[]" 
                    value="'.$row['servicecode'].'"';
                        if (!empty($erow['services'])) {
                            if (in_array($row['servicecode'], $serviceArr)) {
                                echo " checked";
                            }
                        }        
                        echo '><label>'.
                                $row['servicename'].'</label>';
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
            <br>
            <input type='submit' name='submit' value='Submit' />
            </fieldset>
        </form>
        </div>
    </div>
    <script>
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
        
        function deleteFunction(locId) {
            var r = confirm("Are you sure you wish to delete this location?");
            if (r === true) {
                window.location="processLocations.php?delete=1&id=" + locId;
            } else if (r === false) {
                <?php
                    unset($_SESSION['updateLocSuccess']);
                    $_SESSION['updateLocError'] = "Nothing was deleted";
                ?>
                window.location='locations.php';
            }
        }
    </script>
</html>

