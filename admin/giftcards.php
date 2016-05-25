<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
require_once '../config/db.php';

if (isset($_GET['id'])) {
    unset($_SESSION['addGiftSuccess']);
    unset($_SESSION['updateGiftError']);
    unset($_SESSION['updateGiftSuccess']);
    $selectSql = "Select * from giftcards where id ='" .$_GET['id']."';";
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
        <h2>Manage Gift Cards</h2>
        
        <?php 
            $qry = "Select * from giftcards";
            
            $result = mysqli_query($link, $qry);

            if (!mysqli_query($link,$qry))
            {
                echo("Error description: " . mysqli_error($link));
            } else {
                if ($result->num_rows === 0) {
                    echo "You have not created any gift cards yet.";
                } else {
            ?>
            <table>
                <thead>
                    <th>Name</th>
                    <th>Type</th>
                    <th>Customisable</th>
                    <th>Amount</th>
                    <th>Status</th>
                    <th>Edit</th>
                    <th>Delete</th>                        
                </thead>
            <?php
                // output data of each row
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<tr>";
                    echo "<td>".$row['name'] ."</td>";                          
                    echo "<td>".$row['type']."</td>";                            
                    echo "<td>".$row['customise']."</td>"; 
                    echo "<td>";
                    if (strcmp($row['customise'], "yes") === 0) {
                        echo " -";
                    } else {
                        echo "$".$row['amount'];                        
                    }
                    
                    echo "</td>";                           
                    echo "<td>".$row['status']."</td>";                        
                    echo '<td><button onClick="window.location.href=`giftcards.php?id='.$row['id'].'`">E</button>';
                    echo '<td><button onClick="deleteFunction('.$row['id'].')">D</button></td>';
                    echo "</tr>";
                }
            ?>
            </table>
            <?php
                } 
            }
            ?>
            <div id="updateGiftSuccess" style="color:green">
                <?php 
                    if (isset($_SESSION['updateGiftSuccess'])) {
                        echo $_SESSION['updateGiftSuccess'];
                    }
                ?>
            </div>
            <div id="updateGiftError" style="color:red">
                <?php 
                    if (isset($_SESSION['updateGiftError'])) {
                        echo $_SESSION['updateGiftError'];
                    }
                ?>
            </div>
        <hr><br>
        
        <form id='addGift' action='processGiftcards.php' method='post'>
            <fieldset >
            <div id="addGiftError" style="color:red">
                <?php 
                    if (isset($_SESSION['addGiftError'])) {
                        echo $_SESSION['addGiftError'];
                    }
                ?>
            </div>
            <p id='nanError' style="display: none;">Please enter numbers only</p>
            
            <div id="addGiftSuccess" style="color:green">
                <?php 
                    if (isset($_SESSION['addGiftSuccess'])) {
                        echo $_SESSION['addGiftSuccess'];
                    }
                ?>
            </div>
            <legend>Add/Edit Gift Card</legend>
            <input type='hidden' name='submitted' id='submitted' value='1'/>
            <input type='hidden' name='editid' id='editid' 
                   value='<?php if (isset($_GET['id'])) { echo $erow['id']; }?>'/>
            
            <label for='name' >Name*:</label>
            <input type='text' name='name' id='name'  maxlength="50" 
                   value='<?php if (!empty($erow['name'])) { echo $erow['name']; }?>'/>
            <br>
            <label for='desc' >Description* (100 characters max):</label>
            <input type='text' name='desc' id='desc'  maxlength="100"
                   value='<?php if (!empty($erow['description'])) { echo $erow['description']; }?>'/>
            <br>
            <label for='customise' >Customisable?</label>
            <input type="radio" name='customise' id="customise" value='yes' 
                   <?php 
                    if (!empty($erow['customise'])) {
                        if (strcmp("yes", $erow['customise'])===0) {
                            echo " checked";
                            $_SESSION['customiseOn'] = "yes";
                        }
                    } else {
                        echo " checked";
                        $_SESSION['customiseOn'] = "yes";
                    }
                   ?>
                   onclick="toggleTextbox(false);">Yes
            <input type="radio" name='customise' value='no' 
                   <?php 
                    if (!empty($erow['customise'])) {
                        if (strcmp("no", $erow['customise'])===0) {
                            echo " checked";
                            $_SESSION['customiseOn'] = "no";
                        }
                    }
                   ?>
                   onclick="toggleTextbox(true);">No
            <br>
            <label for='amount' >Amount: </label>
            <span id='currency' style='display:none'>$</span>
            <input type='text' name='amount' id='amount'  maxlength="50"  onkeypress="return isNumberKey(event)"
                   value='<?php if (!empty($erow['amount'])) { echo $erow['amount']; }?>'/>
            <br>
            <br>
            <label for='type' >Type*:</label>
            <input type='radio' name='type' id='type' value='physical' <?php 
                        if(!empty($erow['type'])) {
                            if (strcmp($erow['type'], "physical") === 0) {
                                echo " checked";
                            }
                        } else {
                            echo " checked";
                        }
                    ?>>Physical 
            <input type='radio' name='type' id='type' value='ecard' <?php 
                        if(!empty($erow['type'])) {
                            if (strcmp($erow['type'], "ecard") === 0) {
                                echo " checked";
                            }
                        }
                    ?>>E-card 
            <br>
            <label for='status' >Status*:</label>
            <select name='status'>
                <option value='active' 
                        <?php
                            if (!empty($erow['status'])) {
                                if (strcmp($erow['status'], "active") === 0) {
                                    echo "selected";
                                }
                            }
                        ?>
                        >Active</option>
                <option value='inactive'
                        <?php
                            if (!empty($erow['status'])) {
                                if (strcmp($erow['status'], "inactive") === 0) {
                                    echo "selected";
                                }
                            }
                        ?>
                        >Inactive</option>
            </select>
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
        
        function deleteFunction(locId) {
            var r = confirm("Are you sure you wish to delete this gift card?");
            if (r === true) {
                window.location="processGiftcards.php?delete=1&id=" + locId;
            } else if (r === false) {
                <?php
                    unset($_SESSION['addGiftError']);
                    unset($_SESSION['addGiftSuccess']);
                    unset($_SESSION['updateGiftSuccess']);
                    $_SESSION['updateGiftError'] = "Nothing was deleted";
                ?>
                window.location='giftcards.php';
            }
        }
        
        
        function toggleTextbox(rdo) {
            document.getElementById("amount").disabled = !rdo;
            if (rdo) {
                document.getElementById("currency").style.display = "inline";
            } else {
                document.getElementById("currency").style.display = "none";
                document.getElementById("amount").value = "";
            }
        }
        
        window.onload = function() {
            <?php 
                if ($_SESSION['customiseOn'] === "no") {
            ?>
                toggleTextbox(true);
            <?php 
                } else {
            ?>
                toggleTextbox(false);                    
            <?php } ?>
        };
    </script>
    
</html>

