<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
require_once '../config/db.php';

if (isset($_GET['id'])) {
    unset($_SESSION['updateJobSuccess']);
    unset($_SESSION['updateJobError']);
    unset($_SESSION['addJobSuccess']);
    unset($_SESSION['addJobError']);
    $selectSql = "Select * from jobs where id ='" .$_GET['id']."';";
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
        <h2>Manage Jobs</h2>
        <br>
        <div class="table_container">
<!--            <table class='content_table'>
                <tr>
                    <td>-->
                        <div id="updateJobSuccess" style="color:green">
                            <?php 
                                if (isset($_SESSION['updateJobSuccess'])) {
                                    echo $_SESSION['updateJobSuccess'];
                                }
                            ?>
                        </div>
                        <div id="updateJobError" style="color:red">
                            <?php 
                                if (isset($_SESSION['updateJobError'])) {
                                    echo $_SESSION['updateJobError'];
                                }
                            ?>
                        </div>
                         <?php
                            $sql = "Select * from jobs";
                            $result = mysqli_query($link, $sql);

                            if (!mysqli_query($link,$sql))
                            {
                                echo("Error description: " . mysqli_error($link));
                            } else {
                                if ($result->num_rows === 0) {
                                    echo "You have not created any jobs yet.";
                                } else {
                            ?>
                            <table>
                                <thead>
                                    <th>Title</th>
                                    <th>Status</th>
                                    <th>Type</th>
                                    <th>Featured</th>
                                    <th>Edit</th>
                                    <th>Delete</th>                        
                                </thead>
                            <?php
                                // output data of each row
                                while ($row = mysqli_fetch_assoc($result)) {
                                    echo "<tr>";
                                    echo "<td>".$row['title']."</td>";
                                    echo "<td>".$row['status']."</td>";                            
                                    echo "<td>".$row['type']."</td>";                           
                                    echo "<td>".$row['featured']."</td>";                          
                                    echo '<td><button onClick="window.location.href=`jobs.php?id='.$row['id'].'`">E</button>';
                                    echo '<td><button onClick="deleteFunction(\''.$row['id'].'\')">D</button></td>';
                                    echo "</tr>";
                                }

                            ?>
                        </table>
                                    <?php
                            } 
                        }
                        ?> 
<!--                    </td>
                    <td>-->
        </div>
            <div class="content_container">
            <table class='content'>
                <tr>
                    <td colspan='2'><div class="form_header">Add/Edit Job</div></td>
                </tr>
                <tr>
                    <td colspan='2'>
                        <form id='addJob' action='processJobs.php' method='post' accept-charset='UTF-8' enctype="multipart/form-data">
                        <div id="addJobError" style="color:red">
                            <?php 
                                if (isset($_SESSION['addJobError'])) {
                                    echo $_SESSION['addJobError'];
                                }
                            ?>
                        </div>
                        <div id="addJobSuccess" style="color:green">
                            <?php 
                                if (isset($_SESSION['addJobSuccess'])) {
                                    echo $_SESSION['addJobSuccess'];
                                }
                            ?>
                        </div>
                        <input type='hidden' name='submitted' id='submitted' value='1'/>
                        <input type='hidden' name='editid' id='editid' 
                               value='<?php if (isset($_GET['id'])) { echo $erow['id']; }?>'/>
                    </td>
                </tr>
                <tr>
                    <td colspan='2'>
                        <label for='title' >Title*:</label>
                        <input type='text' name='title' id='title'  maxlength="50" value ="<?php 
                        if (!empty($erow['title'])) {
                            echo $erow['title'];
                        }
                            ?>"/>
                    </td>
                </tr>
                <tr>
                    <td colspan='2'>
                        Type*:
                        <select name="type">
                            <option value="hq" <?php 
                            if (!empty($erow['type'])) {
                                if (strcmp($erow['type'], "hq") === 0) {
                                    echo " selected";
                                }
                            }
                            ?>>Headquarters</option>
                            <option value="retail" <?php 
                            if (!empty($erow['type'])) {
                                if (strcmp($erow['type'], "retail") === 0) {
                                    echo " selected";
                                }
                            }
                            ?>>Retail</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>
                        Status*:
                        <select name="status">
                            <option value="active" <?php 
                            if (!empty($erow['status'])) {
                                if (strcmp($erow['status'], "active") === 0) {
                                    echo " selected";
                                }
                            }
                            ?>>Active</option>
                            <option value="inactive" <?php 
                            if (!empty($erow['status'])) {
                                if (strcmp($erow['status'], "inactive") === 0) {
                                    echo " selected";
                                }
                            }
                            ?>>Inactive</option>
                        </select>
                    </td>
                    <td>
                        <label for='featured' >Featured?</label>
                        <input type='checkbox' name='featured' id='featured' value='yes' <?php 
                        if (!empty($erow['featured'])) {
                            if (strcmp($erow['featured'], "yes") === 0) {
                                echo " checked";
                            }
                        }
                            ?>/>
                    </td>
                </tr>
                <tr>
                    <td colspan='2'>
                        <label for='html' >Description*:</label>
                        <textarea name='html' id='html'><?php 
                        if (!empty($erow['html'])) {
                            echo $erow['html'];
                        }
                            ?></textarea>
                        <script type="text/javascript">
                            CKEDITOR.replace('html');
                        </script>
                    </td>
                </tr>
                <tr>
                    <td colspan='2'><input type='submit' name='submit' value='Save' /></td>
                </tr>
            </form>
            </table>
            </div>
<!--                        
                </tr>
            </table>-->
        </div>
    </div>
    <script>
        function deleteFunction(prodId) {
            var r = confirm("Are you sure you wish to delete this job?");
            if (r === true) {
                window.location="processJobs.php?delete=1&id=" + prodId;
            } else if (r === false) {
                <?php
                    unset($_SESSION['updateJobSuccess']);
                    $_SESSION['updateJobError'] = "Nothing was deleted";
                ?>
                window.location='jobs.php';
            }
        }
    </script>
</html>

