<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
require_once '../config/db.php';
require_once('../nav/adminHeader.php');

if (isset($_GET['id'])) {
    unset($_SESSION['addTermError']);
    unset($_SESSION['addTermSuccess']);
    unset($_SESSION['updateTermError']);
    unset($_SESSION['updateTermSuccess']);
    $selectSql = "Select * from terms where id ='" .$_GET['id']."';";
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
        <h2>Terms</h2>
        <br>
        <?php 
            $qry = "Select * from terms order by fieldorder asc";
            $result = mysqli_query($link, $qry);

            if (!mysqli_query($link,$qry))
            {
                echo("Error description: " . mysqli_error($link));
            } else {
                if ($result->num_rows === 0) {
                    echo "You have not created any terms yet.";
                } else {
            ?>
            <table>
                <thead>
                    <th>Order</th>
                    <th>Title</th>
                    <th>Edit</th>
                    <th>Delete</th>                        
                </thead>
            <?php
            $rowCount = 0;
                // output data of each row
                while ($row = mysqli_fetch_assoc($result)) {
                    $rowCount++;
                    echo "<tr>";
                    echo "<td>".$row['fieldorder'] ."</td>";  
                    echo "<td>".$row['title'] ."</td>";                        
                    echo '<td><button onClick="window.location.href=`terms.php?id='.$row['id'].'`">E</button>';
                    echo '<td><button onClick="deleteFunction('.$row['id'].')">D</button></td>';
                    echo "</tr>";
                }
            ?>
            </table>
            <?php
                } 
            }
            ?>
            <div id="updateTermSuccess" style="color:green">
                <?php 
                    if (isset($_SESSION['updateTermSuccess'])) {
                        echo $_SESSION['updateTermSuccess'];
                    }
                ?>
            </div>
            <div id="updateTermError" style="color:red">
                <?php 
                    if (isset($_SESSION['updateTermError'])) {
                        echo $_SESSION['updateTermError'];
                    }
                ?>
            </div>
        <hr><br>
        
        <form id='addTermSection' action='processTerms.php' method='post'>
            <fieldset >
            <div id="addTermError" style="color:red">
                <?php 
                    if (isset($_SESSION['addTermError'])) {
                        echo $_SESSION['addTermError'];
                    }
                ?>
            </div>
            
            <p id='nanError' style="display: none;">Please enter numbers only</p>
            <div id="addTermSuccess" style="color:green">
                <?php 
                    if (isset($_SESSION['addTermSuccess'])) {
                        echo $_SESSION['addTermSuccess'];
                    }
                ?>
            </div>
            <legend>Add/Edit Terms Section</legend>
            <input type='hidden' name='submitted' id='submitted' value='1'/>
            <input type='hidden' name='editid' id='editid' 
                   value='<?php if (isset($_GET['id'])) { echo $erow['id']; }?>'/>
            <label for='title' >Title*:</label>
            <input type='text' name='title' id='title' 
                   value='<?php 
                   if (!empty($erow['title'])) {
                       echo $erow['title'];
                   }
                   ?>'/>
            <br>
            <label for='order' >Order*:</label>
            <input type='text' name='order' id='order'  
               onkeypress="return isNumber(event)" 
                   value="<?php if (isset($erow['fieldorder'])) { echo $erow['fieldorder']; } else { echo $rowCount+1; } ?>"/>
            <br>
            Content*: 
            <textarea name="html"><?php 
                   if (!empty($erow['html'])) {
                       echo $erow['html'];
                   }
                   ?></textarea>
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
            var r = confirm("Are you sure you wish to delete this Terms section?");
            if (r === true) {
                window.location="processTerms.php?delete=1&id=" + locId;
            } else if (r === false) {
                <?php
                    unset($_SESSION['addTermError']);
                    unset($_SESSION['addTermSuccess']);
                    unset($_SESSION['updateTermSuccess']);
                    $_SESSION['updateTermError'] = "Nothing was deleted";
                ?>
                window.location='terms.php';
            }
        }
    </script>
</html>
