<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
require_once '../config/db.php';
require_once('../nav/adminHeader.php'); 
if (!isset($_GET['delete']) && isset($_GET['fid'])) { 
    $sql = "Select * from forms where id ='".$_GET['fid']."';";
    $result = mysqli_query($link, $sql);
    
    $frow = mysqli_fetch_assoc($result);
} else if (!isset($_GET['delete']) && isset($_GET['id'])) { 
    $sql = "Select * from forms where id ='".$_GET['id']."';";
    $result = mysqli_query($link, $sql);
    
    $editrow = mysqli_fetch_assoc($result);
} else if (isset($_GET['add'])) {
    if (empty($_POST['name'])) {
        unset($_SESSION['updateFormError']);
        unset($_SESSION['addFormSuccess']);
        unset($_SESSION['addFormError']);

        unset($_SESSION['updateFormFieldError']);
        unset($_SESSION['updateFormFieldSuccess']);
        unset($_SESSION['addFormFieldSuccess']);
        unset($_SESSION['addFormFieldError']);
        $_SESSION['addFormError'] = "Empty field(s)";
    } else {
        unset($_SESSION['addFormError']);
        $name = $_POST['name'];
        $status = $_POST['status'];
        
        if (!empty($_POST['editid'])) {
            $sql = "UPDATE forms set name='$name', status='$status', type='form' where id='".$_POST['editid']."';";
            mysqli_query($link, $sql);
            
            unset($_SESSION['updateFormError']);
            unset($_SESSION['addFormSuccess']);
            unset($_SESSION['addFormError']);
            
            unset($_SESSION['updateFormFieldError']);
            unset($_SESSION['updateFormFieldSuccess']);
            unset($_SESSION['addFormFieldSuccess']);
            unset($_SESSION['addFormFieldError']);
            $_SESSION['updateFormSuccess'] = "Form updated successfully";
        } else {
            $sql = "INSERT INTO forms (name, status, type) VALUES ('$name', '$status', 'form');";
            mysqli_query($link, $sql);
            
            unset($_SESSION['updateFormError']);
            unset($_SESSION['updateFormSuccess']);
            unset($_SESSION['addFormError']);

            unset($_SESSION['updateFormFieldError']);
            unset($_SESSION['updateFormFieldSuccess']);
            unset($_SESSION['addFormFieldSuccess']);
            unset($_SESSION['addFormFieldError']);
            $_SESSION['addFormSuccess'] = "Form added successfully";
        }
    }
} else if (isset($_GET['delete']) && isset($_GET['fid'])) {
    $deletesql = "DELETE FROM forms where id ='". $_GET['fid']."'";
    if (mysqli_query($link, $deletesql)) {
        unset($_SESSION['updateFormError']);
        unset($_SESSION['addFormSuccess']);
        unset($_SESSION['addFormError']);
        unset($_SESSION['updateFormFieldError']);
        unset($_SESSION['updateFormFieldSuccess']);
        unset($_SESSION['addFormFieldSuccess']);
        unset($_SESSION['addFormFieldError']);
        $_SESSION['updateFormSuccess'] = "Form deleted successfully";
    } 
} else if (isset($_GET['delete']) && isset($_GET['id'])) {
    $deletesql = "DELETE FROM forms where id ='". $_GET['id']."'";
    if (mysqli_query($link, $deletesql)) {
        unset($_SESSION['updateFormError']);
        unset($_SESSION['addFormSuccess']);
        unset($_SESSION['addFormError']);
        unset($_SESSION['updateFormFieldError']);
        unset($_SESSION['updateFormSuccess']);
        unset($_SESSION['addFormFieldSuccess']);
        unset($_SESSION['addFormFieldError']);
        $_SESSION['updateFormFieldSuccess'] = "Form field deleted successfully";
    } 
} else if (isset($_GET['update'])) {
    if(empty($_POST['name']) || 
            (strcmp($_POST['type'], "dropdown") === 0 && empty($_POST['options'])) || 
            (strcmp($_POST['type'], "checkbox") === 0 && empty($_POST['options']))) {
        unset($_SESSION['updateFormFieldError']);
        unset($_SESSION['updateFormFieldSuccess']);
        unset($_SESSION['addFormFieldSuccess']);
        unset($_SESSION['updateFormError']);
        unset($_SESSION['updateFormSuccess']);
        unset($_SESSION['addFormSuccess']);
        unset($_SESSION['addFormError']);
        $_SESSION['addFormFieldError'] = "Empty field(s)";
    } else {
        unset($_SESSION['updateFormSuccess']);
        unset($_SESSION['updateFormError']);
        unset($_SESSION['addFormSuccess']);
        unset($_SESSION['addFormError']);

        unset($_SESSION['updateFormFieldError']);
        unset($_SESSION['updateFormFieldSuccess']);
        unset($_SESSION['addFormFieldSuccess']);
        unset($_SESSION['addFormFieldError']);
        $name = $_POST['name'];
        $type = $_POST['type'];
        $fieldorder = $_POST['order'];
        $form = $_POST['form'];
        $options = $_POST['options'];
        
        if (!empty($_POST['editfieldid'])) {
            $sql = "UPDATE forms set name='$name', type='field', field='$type', options='$options',"
                    . "form='$form', fieldorder='$fieldorder' where id='".$_POST['editfieldid']."';";
            
            mysqli_query($link, $sql);
            unset($_SESSION['addFormFieldError']);
            $_SESSION['addFormFieldSuccess'] = "Form field updated successfully";
        } else {
            $sql = "INSERT INTO forms (name, type, field, options, form, fieldorder) VALUES (".
                    "'$name', 'field', '$type', '$options', '$form', '$fieldorder');";
            mysqli_query($link, $sql);
            unset($_SESSION['addFormFieldError']);
            $_SESSION['addFormFieldSuccess'] = "Form field added successfully";
        }
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
        <h2>Settings - Forms</h2>
        
        <h3>Manage Forms</h3>
        <?php 
            $getDetails = "Select * from forms where type='form';";
            $dresult = mysqli_query($link, $getDetails);
            $drow;
            if (!mysqli_query($link, $getDetails)) {
                echo "Error description: ". mysqli_error($link);
            } else {
                if ($dresult -> num_rows == 0 ) {
                    echo "You have not created any forms yet.<br><br>";
                } else {
        ?>
            <table>
                <thead>
                    <th>Name</th>
                    <th>Status</th>
                    <th>Edit</th>
                    <th>Delete</th>                        
                </thead>
            <?php
                // output data of each row
                while ($row = mysqli_fetch_assoc($dresult)) {
                    echo "<tr>";
                    echo "<td>".$row['name'] ."</td>";    
                    echo "<td>".$row['status'] ."</td>";                       
                    echo '<td><button onClick="window.location.href=`formSettings.php?fid='.$row['id'].'`">E</button>';
                    echo '<td><button onClick="deleteFormFunction('.$row['id'].')">D</button></td>';
                    echo "</tr>";
                }
            ?>
            </table>
        <?php 
                }
            }
        ?>
        <div id="updateFormError" style="color:red">
            <?php 
                if (isset($_SESSION['updateFormError'])) {
                    echo $_SESSION['updateFormError'];
                }
            ?>
        </div>

        <div id="updateFormSuccess" style="color:green">
            <?php 
                if (isset($_SESSION['updateFormSuccess'])) {
                    echo $_SESSION['updateFormSuccess'];
                }
            ?>
        </div>
        <form id='addForms' action='formSettings.php?add=1' method='post'>
            <fieldset >
            <div id="addFormError" style="color:red">
                <?php 
                    if (isset($_SESSION['addFormError'])) {
                        echo $_SESSION['addFormError'];
                    }
                ?>
            </div>
            
            <div id="addFormSuccess" style="color:green">
                <?php 
                    if (isset($_SESSION['addFormSuccess'])) {
                        echo $_SESSION['addFormSuccess'];
                    }
                ?>
            </div>
            <legend>Add/Edit Forms</legend>
            <input type='hidden' name='submitted' id='submitted' value='1'/>
            <input type='hidden' name='editid' id='editid' value='<?php 
                if (!isset($_GET['delete']) && isset($_GET['fid'])) {
                    echo $_GET['fid'];
                }
            ?>'/>
            <label for='name' >Name*:</label>
            <input type='text' name='name' id='name' 
                   value="<?php if (isset($frow['name'])) { echo $frow['name']; } ?>"/>
            <br>
            <label for='status' >Status:</label>
            <select name="status">
                <option value="active" <?php 
                    if (!empty($frow['status'])) {
                        if(strcmp("active", $frow['status']) === 0) {
                            echo " selected";
                        }
                    }
                    ?>>Active</option>
                <option value="inactive" <?php 
                    if (!empty($frow['status'])) {
                        if(strcmp("inactive", $frow['status']) === 0) {
                            echo " selected";
                        }
                    }
                    ?>>Inactive</option>
            </select>
            <br>
            <input type='submit' name='submit' value='Submit' />
            </fieldset>
        </form>
        <br>
        <h3>Manage Form Fields</h3>
        <?php 
            $qry = "Select * from forms where type <> 'form' ORDER BY form, fieldorder asc";
           
            $result = mysqli_query($link, $qry);

            if (!mysqli_query($link,$qry)) 
            {
                echo("Error description: " . mysqli_error($link));
            } else {
                if ($result->num_rows === 0) {
                    echo "You have not created any form fields yet.";
                } else {
            ?>
            <table>
                <thead>
                    <th>Form</th>
                    <th>Order</th>
                    <th>Name</th>
                    <th>Field Type</th>
                    <th>Edit</th>
                    <th>Delete</th>                        
                </thead>
            <?php
                // output data of each row
                        
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<tr>"; 
                    echo "<td>".$row['form'] ."</td>"; 
                    echo "<td>".$row['fieldorder'] ."</td>";  
                    echo "<td>".$row['name'] ."</td>";  
                    echo "<td>".$row['field'] ."</td>";                        
                    echo '<td><button onClick="window.location.href=`formSettings.php?id='.$row['id'].'`">E</button>';
                    echo '<td><button onClick="deleteFunction('.$row['id'].')">D</button></td>';
                    echo "</tr>";
                }
            ?>
            </table>
            <?php
                } 
            }
            
            $formsQry = "Select * from forms where type='form';";
            $formResult = mysqli_query($link, $formsQry);

            while($formRow = mysqli_fetch_assoc($formResult)) {
                $fieldQry = "Select * from forms where type='field' and form='".$formRow['name']."';";
                $fieldResult = mysqli_query($link, $fieldQry);
                $fieldRows = $fieldResult -> num_rows;
                echo "<input type='hidden' id='".$formRow['name']."Value' value='".$fieldRows."'>";
            }
            ?>
            <div id="updateFormFieldSuccess" style="color:green">
                <?php 
                    if (isset($_SESSION['updateFormFieldSuccess'])) {
                        echo $_SESSION['updateFormFieldSuccess'];
                    }
                ?>
            </div>
        
            <div id="updateFormFieldError" style="color:red">
                <?php 
                    if (isset($_SESSION['updateFormFieldError'])) {
                        echo $_SESSION['updateFormFieldError'];
                    }
                ?>
            </div>
        <hr><br>
        
        <?php 
            $noForm = "Select * from forms where type='form'";
            $forResult = mysqli_query($link, $noForm);
            
            if ($forResult -> num_rows > 0) {
        ?>
            <form id='addFormField' action='formSettings.php?update=1' method='post'>
                <fieldset >
                <div id="addFormFieldError" style="color:red">
                    <?php 
                        if (isset($_SESSION['addFormFieldError'])) {
                            echo $_SESSION['addFormFieldError'];
                        }
                    ?>
                </div>
                <p id='nanError' style="display: none;">Please enter numbers only</p>
                <div id="addFormFieldSuccess" style="color:green">
                    <?php 
                        if (isset($_SESSION['addFormFieldSuccess'])) {
                            echo $_SESSION['addFormFieldSuccess'];
                        }
                    ?>
                </div>
                <legend>Add/Edit Form Field</legend>
                <input type="hidden" name="editfieldid" id="editfieldid" 
                       value="<?php if(!isset($_GET['delete']) && isset($_GET['id'])) { echo $_GET['id']; } ?>"
                <input type='hidden' name='submitted' id='submitted' value='1'/>
                <label for='name' >Name*:</label>
                <input type='text' name='name' id='name' 
                       value="<?php if (isset($editrow['name'])) { echo $editrow['name']; } ?>"/>
                <label for='order' >Order*:</label>
                <input type='text' name='order' id='order'  
                   onkeypress="return isNumber(event)" 
                       value="<?php if (isset($editrow['fieldorder'])) { echo $editrow['fieldorder']; } ?>"/>
                <br>
                <label for='form' >Form*:</label>
                <select name="form" id="form">
                <?php 
                    $formSql = "Select * from forms where type='form';";
                    $fresult = mysqli_query($link, $formSql);
                    while ($row1 = mysqli_fetch_assoc($fresult)) {
                ?>
                    <option value="<?php echo $row1['name'];?>" <?php 
                            if(isset($editrow['form'])) {
                                if (strcmp($editrow['form'], $row1['name']) === 0) {
                                    echo " selected";
                                }
                            }
                            ?>><?php echo $row1['name'];?></option>
                <?php 
                    }
                ?>
                </select>
                <label for='type' >Type*:</label>
                <select name='type'>
                    <option value='textbox'
                            <?php
                                if (isset($editrow['field'])) {
                                    if (strcmp($editrow['field'], "textbox") === 0) {
                                        echo " selected";
                                    }
                                }
                            ?>
                            >Single-line Textbox</option>
                    <option value='dropdown'
                            <?php
                                if (isset($editrow['field'])) {
                                    if (strcmp($editrow['field'], "dropdown") === 0) {
                                        echo " selected";
                                    }
                                }
                            ?>>Dropdown List</option>
                    <option value='checkbox'
                            <?php
                                if (isset($editrow['field'])) {
                                    if (strcmp($editrow['field'], "checkbox") === 0) {
                                        echo " selected";
                                    }
                                }
                            ?>>Checkbox</option>
                    <option value='textarea'
                            <?php
                                if (isset($editrow['field'])) {
                                    if (strcmp($editrow['field'], "textarea") === 0) {
                                        echo " selected";
                                    }
                                }
                            ?>>Multi-line Textbox</option>
                </select>
                <br>
                Options (only for dropdown & checkbox types): 
                <p class='setting-tooltips'>Please place a comma (,) after each option</p>
                <textarea name="options" cols='50' rows='10'><?php if (isset($editrow['options'])) { echo $editrow['options']; } ?></textarea>
                <br>
                <input type='submit' name='submit' value='Submit' />
                </fieldset>
            </form>
        <?php 
            }
        ?>
        </div>
    </div>
    <script>
        function deleteFormFunction(locId) {
            var r = confirm("Are you sure you wish to delete this form?");
            if (r === true) {
                window.location="formSettings.php?delete=1&fid=" + locId;
            } else if (r === false) {
                <?php
                    unset($_SESSION['addFormError']);
                    unset($_SESSION['addFormSuccess']);
                    unset($_SESSION['updateFormSuccess']);
                    unset($_SESSION['addFormFieldError']);
                    unset($_SESSION['addFormFieldSuccess']);
                    unset($_SESSION['updateFormFieldSuccess']);
                    unset($_SESSION['updateFormFieldError']);
                    $_SESSION['updateFormError'] = "Nothing was deleted";
                ?>
                window.location='formSettings.php';
            }
        }
        function deleteFunction(locId) {
            var r = confirm("Are you sure you wish to delete this form field?");
            if (r === true) {
                window.location="formSettings.php?delete=1&id=" + locId;
            } else if (r === false) {
                <?php
                    unset($_SESSION['addFormError']);
                    unset($_SESSION['updateFormError']);
                    unset($_SESSION['addFormSuccess']);
                    unset($_SESSION['updateFormSuccess']);
                    unset($_SESSION['addFormFieldError']);
                    unset($_SESSION['addFormFieldSuccess']);
                    unset($_SESSION['updateFormFieldSuccess']);
                    $_SESSION['updateFormFieldError'] = "Nothing was deleted";
                ?>
                window.location='formSettings.php';
            }
        }
        
        document.getElementById('form').onchange = function() {
            var index = this.selectedIndex;
            var inputText = this.children[index].innerHTML.trim();
            var count = document.getElementById(inputText+ "Value").value;
            <?php
            if(!isset($_GET['id'])) {
            ?>
                document.getElementById('order').value = Number(count)+1;
            <?php 
            }
            ?>
        }
        
        window.onload = function() {
            var index = document.getElementById('form').selectedIndex;
            var inputText = document.getElementById('form').children[index].innerHTML.trim();
            var count = document.getElementById(inputText+ "Value").value;
            <?php
            if(!isset($_GET['id'])) {
            ?>
                document.getElementById('order').value = Number(count)+1;
            <?php 
            }
            ?>
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
    </script>
</html>
