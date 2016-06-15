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
                                Gift Cards
                            </li>
                        </ol>
                        
                        <h1 class="page-header">Manage Gift Cards</h1>
                        
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
                        
                        <p class="text-right">
                            <a href="#add"><i class="fa fa-fw fa-plus"></i> Add Gift Card</a>
                        </p>
                        
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
                        
                        <form id='addGift' action='processGiftcards.php' method='post'>
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
                            
                            <h1 id="add" class="page-header">Add/Edit Gift Card</h1>
                            
                            <input type='hidden' name='submitted' id='submitted' value='1'/>
                            <input type='hidden' name='editid' id='editid' 
                                   value='<?php if (isset($_GET['id'])) { echo $erow['id']; }?>'/>

                            <table class="content">
                                <tr>
                                    <td>
                                        Name*:
                                        <input type='text' name='name' id='name'  maxlength="50" 
                                               value='<?php if (!empty($erow['name'])) { echo $erow['name']; }?>'/>
                                    </td>
                                    <td>
                                        Code*: <br/>
                                        <button type="button" onclick="randomString()" class="pull-right">Generate</button>
                                        <div style="overflow: hidden;" >
                                            <input type='text' name='code' id='code' value ="<?php 
                                                if(isset($_SESSION['randomString'])) { 
                                                    echo $_SESSION['randomString']; } 
                                                if (!empty($erow['code'])) {
                                                    echo $erow['code'];
                                                } ?>" maxlength="50" />
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        Amount: 
                                        <span id='currency' style='display:none'>$</span>
                                        <input type='text' name='amount' id='amount'  maxlength="50"  onkeypress="return isNumberKey(event)"
                                               value='<?php if (!empty($erow['amount'])) { echo $erow['amount']; }?>'/>
                                    </td>
                                    <td>
                                        Customisable? <br/>
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
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        Status*:
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
                                    </td>
                                    <td>
                                        Type*: <br/>
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
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2">
                                        Description* (100 characters max):
                                        <input type='text' name='desc' id='desc'  maxlength="100"
                                               value='<?php if (!empty($erow['description'])) { echo $erow['description']; }?>'/>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2">
                                        <input type='submit' name='submit' value='Save' />
                                    </td>
                                </tr>
                            </table>
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

    function randomString() {
        var text = "";
        var possible = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";

        for( var i=0; i < 20; i++ )
            text += possible.charAt(Math.floor(Math.random() * possible.length));

        document.getElementById('code').value = text;
        return false;
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


