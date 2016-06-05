<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
require_once '../config/db.php';

if (isset($_GET['id'])) {
//    unset($_SESSION['updateAdvSuccess']);    
//    unset($_SESSION['updateAdvError']);
//    unset($_SESSION['addAdvSuccess']);
    $selectSql = "Select * from advertisements where id ='" .$_GET['id']."';";
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
    <div id="frameheader">
        <?php
            require '../nav/adminHeader.php';
        ?>
    </div>
    <div id="framecontent">
        <?php
            require '../nav/adminSidebar.php';
        ?>
    </div>
    <div id="maincontent">
        <div class="innertube">
        <h2>Manage Advertisements</h2>
        
        <?php 
            $qry = "Select * from advertisements";
            
            $result = mysqli_query($link, $qry);

            if (!mysqli_query($link,$qry))
            {
                echo("Error description: " . mysqli_error($link));
            } else {
                if ($result->num_rows === 0) {
                    echo "You have not created any advertisements yet.";
                } else {
            ?>
            <table>
                <thead>
                    <th>Title</th>
                    <th>Expiry</th>   
                    <th>Status</th>
                    <th>Visibility</th>
                    <th>Edit</th>
                    <th>Delete</th>                        
                </thead>
            <?php
                // output data of each row
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<tr>";
                    $oDate = new DateTime($row['start']);
                    $sDate = $oDate->format("d-m-Y");
                    
                    
                    $mDate = new DateTime($row['end']);
                    $eDate = $mDate->format("d-m-Y");
                    
                    echo "<td>".$row['title'] ."</td>";     
                    echo "<td>".$row['expiry'];
                    if (strcmp($row['expiry'], "yes")===0) {
                          echo " (".$sDate." to ".$eDate.")";
                    }
                    echo "</td>";  
                    
                    echo "<td>".$row['status']."</td>";                           
                    echo "<td>".$row['visibility']."</td>";                       
                    echo '<td><button onClick="window.location.href=`advertisements.php?id='.$row['id'].'`">E</button>';
                    echo '<td><button onClick="deleteFunction('.$row['id'].')">D</button></td>';
                    echo "</tr>";
                }
            ?>
            </table>
            <?php
                } 
            }
            ?>
            <div id="updateAdvSuccess" style="color:green">
                <?php 
                    if (isset($_SESSION['updateAdvSuccess'])) {
                        echo $_SESSION['updateAdvSuccess'];
                    }
                ?>
            </div>
            <div id="updateAdvError" style="color:red">
                <?php 
                    if (isset($_SESSION['updateAdvError'])) {
                        echo $_SESSION['updateAdvError'];
                    }
                ?>
            </div>
        <hr><br>
        
        <form id='addAdvertisement' action='processAdvertisements.php' method='post' enctype="multipart/form-data">
            <fieldset >
            <div id="addAdvError" style="color:red">
                <?php 
                    if (isset($_SESSION['addAdvError'])) {
                        echo $_SESSION['addAdvError'];
                    }
                    
                    if (isset($_SESSION['uploadAdvError'])) {
                        echo $_SESSION['uploadAdvError'];
                    }
                ?>
            </div>
            <div id="addAdvSuccess" style="color:green">
                <?php 
                    if (isset($_SESSION['addAdvSuccess'])) {
                        echo $_SESSION['addAdvSuccess'];
                    }
                ?>
            </div>
            <legend>Add/Edit Advertisement</legend>
            <input type='hidden' name='submitted' id='submitted' value='1'/>
            <input type='hidden' name='editid' id='editid' 
                   value='<?php if (isset($_GET['id'])) { echo $erow['id']; } ?>'/>
            <label for='title' >Title*:</label>
            <input type='text' name='title' id='title' maxlength="50" 
                   value='<?php if (!empty($erow['title'])) { echo $erow['title']; } ?>'/>
            <br>
            <?php 
                if (!empty($erow['image'])) {
                    echo "<img src='".$erow['image']."' width=200><br>";
                    echo "<input type='hidden' name='oldImage' id='oldImage' value='".$erow['image']."'>";
                }
            ?>
            <label for='image' >Image*:</label>
            <input type="file" name="image" id='image' accept="image/*" />
            <br>
            <label for='imagepos' >Image Position*:</label>
            <input type='radio' name='imagepos' value='left' <?php 
                if (!empty($erow['imagepos'])) {
                    if (strcmp($erow['imagepos'], "left") === 0) {
                        echo " checked";
                    }
                }
            ?>>Left 
            <input type='radio' name='imagepos' value='background' <?php 
                if (!empty($erow['imagepos'])) {
                    if (strcmp($erow['imagepos'], "background") === 0) {
                        echo " checked";
                    }
                }
            ?>>Background 
            <input type='radio' name='imagepos' value='right' <?php 
                if (!empty($erow['imagepos'])) {
                    if (strcmp($erow['imagepos'], "right") === 0) {
                        echo " checked";
                    }
                }
            ?>>Right 
            <br>
            <label for='status' >Status*:</label>
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
            <br>
            
            <label for='expiry' >Expiry*:</label>
            <input type='radio' name='expiry' id='checkYes' value='yes' <?php 
                    if (!empty($erow['expiry'])) {
                        if (strcmp($erow['expiry'], "yes") === 0) {
                            echo " checked";
                        }
                    }
                ?>> Yes
            <input type='radio' name='expiry' id='checkNo' value='no' <?php 
                    if (!empty($erow['expiry'])) {
                        if (strcmp($erow['expiry'], "no") === 0) {
                            echo " checked";
                        } 
                    } else {
                        echo " checked";
                    }
                ?>> No
            <br>
            <div id='expiryDate' style='display:none'>
                    Start date:
                    <input type="text" id="date3" name="date3">
                    End date:
                    <input type="text" id="date4" name="date4">
            <br>
            </div>
            Content (optional): 
            <textarea name="html"><?php 
            if(!empty($erow['html'])) { echo $erow['html']; }?></textarea>
            <script type="text/javascript">
                CKEDITOR.replace('html');
            </script>
            <br>
            <label for='htmlpos' >Content Position:</label>
            <input type='radio' name='htmlpos' value='left' <?php 
                if (!empty($erow['htmlpos'])) {
                    if (strcmp($erow['htmlpos'], "left") === 0) {
                        echo " checked";
                    }
                }
            ?>>Left 
            <input type='radio' name='htmlpos' value='center' <?php 
                if (!empty($erow['htmlpos'])) {
                    if (strcmp($erow['htmlpos'], "center") === 0) {
                        echo " checked";
                    }
                }
            ?>>Center 
            <input type='radio' name='htmlpos' value='right' <?php 
                if (!empty($erow['htmlpos'])) {
                    if (strcmp($erow['htmlpos'], "right") === 0) {
                        echo " checked";
                    }
                }
            ?>>Right 
            <br>
            Visibility*: 
            <?php 
                if(!empty($erow['visibility'])) {
                    $visib = explode(",", $erow['visibility']);
                }
            ?>
            <div class='checkboxAlign'>
            <input name='visibility[]' type='checkbox' value='homepage' <?php 
                if (!empty($erow['visibility'])) {
                    if (in_array("homepage", $visib)) {
                        echo " checked";
                    }
                }
                ?>><label>Homepage</label>
            <input name='visibility[]' type='checkbox' value='catalogue' <?php 
                if (!empty($erow['visibility'])) {
                    if (in_array("catalogue", $visib)) {
                        echo " checked";
                    }
                }
                ?>><label>Product Catalogue</label>
            <input name='visibility[]' type='checkbox' value='prodDetails' <?php 
                if (!empty($erow['visibility'])) {
                    if (in_array("prodDetails", $visib)) {
                        echo " checked";
                    }
                }
                ?>><label>Product Details</label>
            
            <input name='visibility[]' type='checkbox' value='locations' <?php 
                if (!empty($erow['visibility'])) {
                    if (in_array("locations", $visib)) {
                        echo " checked";
                    }
                }
                ?>><label>Locations</label>
            <br>
            <input name='visibility[]' type='checkbox' value='story' <?php 
                if (!empty($erow['visibility'])) {
                    if (in_array("story", $visib)) {
                        echo " checked";
                    }
                }
                ?>><label>Our Story</label>
            
            <input name='visibility[]' type='checkbox' value='culture' <?php 
                if (!empty($erow['visibility'])) {
                    if (in_array("culture", $visib)) {
                        echo " checked";
                    }
                }
                ?>><label>Culture</label>
            
            <input name='visibility[]' type='checkbox' value='design' <?php 
                if (!empty($erow['visibility'])) {
                    if (in_array("design", $visib)) {
                        echo " checked";
                    }
                }
                ?>><label>Design</label>
            
            <input name='visibility[]' type='checkbox' value='one' <?php 
                if (!empty($erow['visibility'])) {
                    if (in_array("one", $visib)) {
                        echo " checked";
                    }
                }
                ?>><label>One for You, One for Them</label>
            <br>
            <input name='visibility[]' type='checkbox' value='blog' <?php 
                if (!empty($erow['visibility'])) {
                    if (in_array("blog", $visib)) {
                        echo " checked";
                    }
                }
                ?>><label>Blog</label>
            
            <input name='visibility[]' type='checkbox' value='hometry' <?php 
                if (!empty($erow['visibility'])) {
                    if (in_array("hometry", $visib)) {
                        echo " checked";
                    }
                }
                ?>><label>Home Try-on</label>
            </div>
            <br>
            <label for='minheight' >Min height*: <span class='setting-tooltips'>(for the advertisement to appear on each page)</span></label>
                        
            <p id='nanError' style="display: none;">Please enter numbers only</p>
            <input type='text' name='minheight' id='minheight' maxlength="50" 
                   onkeypress="return isNumber(event)" 
                   value='<?php if (!empty($erow['minheight'])) { echo $erow['minheight']; } ?>'/>
            <br>
            <?php 
                if (!empty($erow['buttontext'])) {
                    $buttontexts = explode(",", $erow['buttontext']);
                }
                if (!empty($erow['link'])) {
                    $links = explode(",", $erow['link']);
                }
                
                if (!empty($erow['linkpos'])) {
                    $linkposArr = explode(",", $erow['linkpos']);
                }
            ?>
            <input type='hidden' name='buttonno' id='buttonno' value='<?php 
                    if(!empty($buttontexts)) {
                        echo count($buttontexts);
                    } else {
                        echo '1';
                    }
            ?>'>
            <span onclick="addButton()">Add Button</span>
            <div id='buttonlinks'>
                <?php 
                    if (!empty($buttontexts)) {
                        for ($i = 0; $i < count($buttontexts); $i++) {
                ?>
                        <fieldset>
                            <legend>Add Button <?php echo $i+1; ?></legend>
                            <label for='buttontext1' >Button Text <?php echo $i+1; ?>(optional):</label>
                            <input type='text' name='buttontext<?php echo $i+1; ?>' 
                                   id='buttontext<?php echo $i+1; ?>'  maxlength="50" 
                                   value='<?php if (!empty($buttontexts[$i])) { echo $buttontexts[$i]; } ?>'/>
                            <br>
                            <label for='link1' >Link  <?php echo $i+1; ?> (optional):</label>
                            <input type='text' name='link<?php echo $i+1; ?>' 
                                   id='link<?php echo $i+1; ?>'  maxlength="50" 
                                   value='<?php if (!empty($links[$i])) { echo $links[$i]; } ?>'/>
                            <br>
                            <label for='linkpos1' >Link Position  <?php echo $i+1; ?>:</label>
                            <input type='radio' name='linkpos<?php echo $i+1; ?>' value='left' <?php 
                                if (!empty($linkposArr[$i])) {
                                    if (strcmp($linkposArr[$i], "left") === 0) {
                                        echo " checked";
                                    }
                                }
                            ?>>Left 
                            <input type='radio' name='linkpos<?php echo $i+1; ?>' value='center' <?php 
                                if (!empty($linkposArr[$i])) {
                                    if (strcmp($linkposArr[$i], "center") === 0) {
                                        echo " checked";
                                    }
                                }
                            ?>>Center 
                            <input type='radio' name='linkpos<?php echo $i+1; ?>' value='right' <?php 
                                if (!empty($linkposArr[$i])) {
                                    if (strcmp($linkposArr[$i], "right") === 0) {
                                        echo " checked";
                                    }
                                }
                            ?>>Right 
                        </fieldset>
                <?php
                        }
                    } else {
                ?>
                    <fieldset>
                        <legend>Add Button 1</legend>
                        <label for="buttontext1">Button Text 1(optional):</label>
                        <input type="text" name="buttontext1" id="buttontext1" maxlength="50">
                        <br>
                        <label for="link1">Link  1 (optional):</label>
                        <input type="text" name="link1" id="link1" maxlength="50">
                        <br>
                        <label for="linkpos1">Link Position 1:</label>
                        <input type="radio" name="linkpos1" value="left">Left 
                        <input type="radio" name="linkpos1" value="center">Center 
                        <input type="radio" name="linkpos1" value="right">Right 
                    </fieldset>
                <?php
                    }
                ?>
            </div>
            <br>
            <input type='submit' name='submit' value='Submit' />
            </fieldset>
        </form>
        </div>
    </div>
    <script> 
        var myCalendar = new dhtmlXCalendarObject(["date3"]);
                myCalendar.hideTime();
        var myCalendar2 = new dhtmlXCalendarObject(["date4"]);
                myCalendar2.hideTime();
                
        if (document.getElementById('checkYes').checked) {
           document.getElementById('expiryDate').style.display = "block";            
        }
        document.getElementById('checkYes').onclick = function(){  
           document.getElementById('expiryDate').style.display = "block";
        };
        
        document.getElementById('checkNo').onclick = function(){  
           document.getElementById('expiryDate').style.display = "none";
        };
        
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
        
        function deleteFunction(locId) {
            var r = confirm("Are you sure you wish to delete this advertisement?");
            if (r === true) {
                window.location="processAdvertisements.php?delete=1&id=" + locId;
            } else if (r === false) {
                <?php
                    unset($_SESSION['addAdvError']);
                    unset($_SESSION['addAdvSuccess']);
                    unset($_SESSION['updateAdvSuccess']);
                    $_SESSION['updateAdvError'] = "Nothing was deleted";
                ?>
                window.location='advertisements.php';
            }
        }
        var count=1;
        
        function addButton() {
            count++;
            document.getElementById('buttonno').value = count;
            var node = document.createElement('fieldset');  
            node.innerHTML = "<legend>Add Button "+ count + 
                    "</legend><label for='buttontext"+count+"' >Button Text " +count+ 
                    " (optional):</label><input type='text' name='buttontext"+count+
                    "' id='buttontext"+count+"' maxlength='50' /><br>" +
                    "<label for='link"+count+"' >Link " +count+ " (optional):</label>"
                    +"<input type='text' name='link"+count+"' id='link"+count+"'  maxlength='50' />"+
                    "<br><label for='linkpos"+count+"' >Link Position "+count+ " :</label>"+
                    "<input type='radio' name='linkpos"+count+"' value='left'>Left "+
                    "<input type='radio' name='linkpos"+count+"' value='center'>Center"+ 
                    "<input type='radio' name='linkpos"+count+"' value='right'>Right";
//            node.innerHTML = 'Button Text ' + count + ' : <input type="text" name="buttontext'+count+'">';
            
            document.getElementById('buttonlinks').appendChild(node); 
        }
    </script>
    
</html>

