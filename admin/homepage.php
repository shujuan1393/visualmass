<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
require_once '../config/db.php';
require_once('../calendar/classes/tc_calendar.php');
require_once('../nav/adminHeader.php');

if (isset($_GET['id'])) {
    unset($_SESSION['updateHomepageSuccess']);    
    unset($_SESSION['updateHomepageError']);
    unset($_SESSION['addHomepageSuccess']);
    unset($_SESSION['uploadHomepageError']);
    $selectSql = "Select * from homepage where id ='" .$_GET['id']."';";
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
        <div class='innertube'>
        <?php
            require '../nav/adminSidebar.php';
        ?>
        </div>
    </div>
    <div id="maincontent">
        <div class="innertube">
        <h2>Manage Homepage Sections</h2>
        
        <?php 
            $qry = "Select * from homepage";
            
            $result = mysqli_query($link, $qry);

            if (!mysqli_query($link,$qry))
            {
                echo("Error description: " . mysqli_error($link));
            } else {
                if ($result->num_rows === 0) {
                    echo "You have not created any sections yet.";
                } else {
            ?>
            <table>
                <thead>
                    <th>Title</th> 
                    <th>Status</th>
                    <th>Edit</th>
                    <th>Delete</th>                        
                </thead>
            <?php
                // output data of each row
                $rowCount = 0;
                
                while ($row = mysqli_fetch_assoc($result)) {
                    $rowCount++;
                    echo "<tr>";
                    echo "<td>".$row['title'] ."</td>";   
                    echo "<td>".$row['status']."</td>";                        
                    echo '<td><button onClick="window.location.href=`homepage.php?id='.$row['id'].'`">E</button>';
                    echo '<td><button onClick="deleteFunction('.$row['id'].')">D</button></td>';
                    echo "</tr>";
                }
            ?>
            </table>
            <?php
                } 
            }
            ?>
            <div id="updateHomepageSuccess" style="color:green">
                <?php 
                    if (isset($_SESSION['updateHomepageSuccess'])) {
                        echo $_SESSION['updateHomepageSuccess'];
                    }
                ?>
            </div>
            <div id="updateHomepageError" style="color:red">
                <?php 
                    if (isset($_SESSION['updateHomepageError'])) {
                        echo $_SESSION['updateHomepageError'];
                    }
                ?>
            </div>
        <hr><br>
        
        <form id='addAdvertisement' action='processHomepage.php' method='post' enctype="multipart/form-data">
            <fieldset >
            <div id="addHomepageError" style="color:red">
                <?php 
                    if (isset($_SESSION['addHomepageError'])) {
                        echo $_SESSION['addHomepageError'];
                    }
                    
                    if (isset($_SESSION['uploadHomepageError'])) {
                        echo $_SESSION['uploadHomepageError'];
                    }
                ?>
            </div>
            <div id="addHomepageSuccess" style="color:green">
                <?php 
                    if (isset($_SESSION['addHomepageSuccess'])) {
                        echo $_SESSION['addHomepageSuccess'];
                    }
                ?>
            </div>
            <legend>Add/Edit Homepage Section</legend>
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
            <label for='order' >Order*:</label>
            <input type='text' name='order' id='order'  
               onkeypress="return isNumber(event)" 
                   value="<?php if (isset($erow['fieldorder'])) { echo $erow['fieldorder']; } else { echo $rowCount+1; } ?>"/>
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
            var r = confirm("Are you sure you wish to delete this section?");
            if (r === true) {
                window.location="processHomepage.php?delete=1&id=" + locId;
            } else if (r === false) {
                <?php
                    unset($_SESSION['addHomepageError']);
                    unset($_SESSION['addHomepageSuccess']);
                    unset($_SESSION['updateHomepageSuccess']);
                    $_SESSION['updateHomepageError'] = "Nothing was deleted";
                ?>
                window.location='homepage.php';
            }
        }
        
        function addButton() {
            var count = document.getElementById('buttonno').value;
            count++;
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
 
            document.getElementById('buttonlinks').appendChild(node); 
            document.getElementById('buttonno').value = count;
        }
    </script>
    
</html>

