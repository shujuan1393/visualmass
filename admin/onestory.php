<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
require_once '../config/db.php';

if (isset($_GET['id'])) {
    unset($_SESSION['addOneSuccess']);
    unset($_SESSION['addOneError']);
    unset($_SESSION['updateOneError']);
    unset($_SESSION['updateOneSuccess']);
    unset($_SESSION['uploadOneError']);
    $selectSql = "Select * from ourstory where id ='" .$_GET['id']."';";
    
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
        <h2>Our Story - Gift Initiative</h2>
        <br>
        <?php 
            $getBanner = "Select * from ourstory where page='one' and type='banner';";
            $bresult = mysqli_query($link, $getBanner);
            
            if (!mysqli_query($link, $getBanner)) {
                echo "Error description: ". mysqli_error($link);
            } else {
                if ($bresult -> num_rows == 0 ) {
                    echo "You have not uploaded a banner image yet.<br><br>";
                } else {
                    $brow = mysqli_fetch_assoc($bresult);
                    $browArr = explode(".", $brow['html']);
                    $ext = $browArr[count($browArr)-1];
                    
                    $imgArr = array("jpg", "jpeg", "png", "gif");
                    $vidArr = array("mp3", "mp4", "wma");
                    
                    if (in_array($ext, $imgArr)) {
                        echo "<img src='".$brow['html']."' width=450>";
                    } else {
                        echo '<video width="500" height="400" controls>
                        <source src="'.$brow['html'].'" type="video/mp4">
                        Your browser does not support the video tag.
                        </video>';
                    }
                }
            }
        ?>
        <form id='addOneBanner' action='processOne.php?banner=1' method='post' enctype="multipart/form-data">
            <fieldset >
            <div id="addOneBannerError" style="color:red">
                <?php 
                    if (isset($_SESSION['addOneBannerError'])) {
                        echo $_SESSION['addOneBannerError'];
                    }
                ?>
            </div>
            
            <div id="addOneBannerSuccess" style="color:green">
                <?php 
                    if (isset($_SESSION['addOneBannerSuccess'])) {
                        echo $_SESSION['addOneBannerSuccess'];
                    }
                ?>
            </div>
            <legend>Update Our Story - Gift Initiative Banner</legend>
            <input type='hidden' name='submitted' id='submitted' value='1'/>
            <input type='hidden' name='oldImage' id='oldImage' value='<?php echo $brow['html']; ?>'/>
            <label for='image' >Image:</label>
            <input type="file" name="image" id='image'/>
            <br>
            <input type='submit' name='submit' value='Submit' />
            </fieldset>
        </form>
        <br>
        <?php 
            $qry = "Select * from ourstory where page='one' and type='section'";
            
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
                    <th>Order</th>
                    <th>Status</th>
                    <th>Edit</th>
                    <th>Delete</th>                        
                </thead>
            <?php
                $rowCount = 0;
                // output data of each row
                while ($row = mysqli_fetch_assoc($result)) {
                    $rowCount++;
                    echo "<tr>";
                    echo "<td>".$row['title'] ."</td>";
                    echo "<td>".$row['fieldorder']."</td>";  
                    echo "<td>".$row['status']."</td>";                          
                    echo '<td><button onClick="window.location.href=`onestory.php?id='.$row['id'].'`">E</button>';
                    echo '<td><button onClick="deleteFunction('.$row['id'].')">D</button></td>';
                    echo "</tr>";
                }
            ?>
            </table>
            <?php
                } 
            }
            ?>
            <div id="updateOneSuccess" style="color:green">
                <?php 
                    if (isset($_SESSION['updateOneSuccess'])) {
                        echo $_SESSION['updateOneSuccess'];
                    }
                ?>
            </div>
            <div id="updateOneError" style="color:red">
                <?php 
                    if (isset($_SESSION['updateOneError'])) {
                        echo $_SESSION['updateOneError'];
                    }
                ?>
            </div>
        <hr><br>
        
        <form id='addOne' action='processOne.php' method='post'>
            <fieldset >
            <div id="addOneError" style="color:red">
                <?php 
                    if (isset($_SESSION['addOneError'])) {
                        echo $_SESSION['addOneError'];
                    }
                ?>
            </div>
            <p id='nanError' style="display: none;">Please enter numbers only</p>
            <div id="addOneSuccess" style="color:green">
                <?php 
                    if (isset($_SESSION['addOneSuccess'])) {
                        echo $_SESSION['addOneSuccess'];
                    }
                ?>
            </div>
            <legend>Add/Edit Our Story - Gift Initiative Section</legend>
            <input type='hidden' name='submitted' id='submitted' value='1'/>
            <input type='hidden' name='editid' id='editid' 
                   value='<?php if (isset($_GET['id'])) { echo $erow['id']; }?>'/>
            <label for='title' >Title*:</label>
            <input type='text' name='title' id='title'
                   value='<?php if (!empty($erow['title'])) { echo $erow['title']; }?>'/>
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
            Content*: 
            <textarea name="html"><?php 
            if(!empty($erow['html'])) { echo $erow['html']; }?></textarea>
            <script type="text/javascript">
                CKEDITOR.replace('html');
            </script>
            <br>
            <input type='submit' name='submit' value='Submit' />
            
            </fieldset>
        </form>
        </div>
    </div>
    <script>
        function deleteFunction(locId) {
            var r = confirm("Are you sure you wish to delete this section?");
            if (r === true) {
                window.location="processOne.php?delete=1&id=" + locId;
            } else if (r === false) {
                <?php
                    unset($_SESSION['addOneError']);
                    unset($_SESSION['addOneSuccess']);
                    unset($_SESSION['updateOneSuccess']);
                    $_SESSION['updateOneError'] = "Nothing was deleted";
                ?>
                window.location='onestory.php';
            }
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
    </script>
</html>
