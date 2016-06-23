<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
require_once '../config/db.php';

if (isset($_GET['id'])) {
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
                            <li>
                                Web
                            </li>
                            <li class="active">
                                Design
                            </li>
                        </ol>
                        
                        <ul class="nav nav-tabs">
                            <li class="active"><a data-toggle="tab" href="#designb">Design Banner</a></li>
                            <li><a data-toggle="tab" href="#menu1">Design Sections</a></li>
                        </ul>
                        
                        <div class="tab-content">
                            <div id="designb" class="tab-pane fade in active">
                                <h1 class="page-header">Update Design Banner</h1>
                                <p>
                                    <?php 
                                        $getBanner = "Select * from ourstory where page='design' and type='banner';";
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
                                                    echo '<video width="500" height="400" autoplay>
                                                    <source src="'.$brow['html'].'" type="video/mp4">
                                                    Your browser does not support the video tag.
                                                    </video>';
                                                }
                                            }
                                        }
                                    ?>
                                    
                                    <form id='addDesBanner' action='processDesign.php?banner=1' method='post' enctype="multipart/form-data">
                                        <div id="addDesBannerError" class="error">
                                            <?php 
                                                if (isset($_SESSION['addDesBannerError'])) {
                                                    echo $_SESSION['addDesBannerError'];
                                                }
                                            ?>
                                        </div>

                                        <div id="addDesBannerSuccess" class="success">
                                            <?php 
                                                if (isset($_SESSION['addDesBannerSuccess'])) {
                                                    echo $_SESSION['addDesBannerSuccess'];
                                                }
                                            ?>
                                        </div>

                                        <input type='hidden' name='submitted' id='submitted' value='1'/>
                                        <input type='hidden' name='oldImage' id='oldImage' value='<?php if(!empty($brow['html'])) echo $brow['html']; ?>'/>

                                        Image:
                                        <input type="file" name="image" id='image'/>
                                        <br>
                                        <input type='submit' name='submit' value='Submit' />
                                    </form>
                                </p>
                            </div>
                            <div id="menu1" class="tab-pane fade">
                                <h1 class="page-header">Manage Design Sections</h1>
                                
                                <div id="updateDesSuccess" class="success">
                                    <?php 
                                        if (isset($_SESSION['updateDesSuccess'])) {
                                            echo $_SESSION['updateDesSuccess'];
                                        }
                                    ?>
                                </div>
                                <div id="updateDesError" class="error">
                                    <?php 
                                        if (isset($_SESSION['updateDesError'])) {
                                            echo $_SESSION['updateDesError'];
                                        }
                                    ?>
                                </div>
                                
                                <p>
                                    <?php 
                                        $qry = "Select * from ourstory where page='design' and type='section'";

                                        $result = mysqli_query($link, $qry);

                                        $rowCount = 0;

                                        if (!mysqli_query($link,$qry))
                                        {
                                            echo("Error description: " . mysqli_error($link));
                                        } else {
                                            if ($result->num_rows === 0) {
                                                echo "You have not created any sections yet.";
                                            } else {
                                    ?>
                                    
                                    <p class="text-right">
                                        <a href="#add"><i class="fa fa-fw fa-plus"></i> Add Section</a>
                                    </p>
                                    
                                    <table>
                                        <thead>
                                            <th>Title</th>
                                            <th>Order</th>
                                            <th>Status</th>
                                            <th>Edit</th>
                                            <th>Delete</th>                        
                                        </thead>
                                        <?php
                                            // output data of each row
                                            while ($row = mysqli_fetch_assoc($result)) {
                                                $rowCount++;
                                                echo "<tr>";
                                                echo "<td>".$row['title'] ."</td>";
                                                echo "<td>".$row['fieldorder']."</td>";  
                                                echo "<td>".$row['status']."</td>";                          
                                                echo '<td><button onClick="window.location.href=`designstory.php?id='.$row['id'].'#menu1`">E</button>';
                                                echo '<td><button onClick="deleteFunction('.$row['id'].')">D</button></td>';
                                                echo "</tr>";
                                            }
                                        ?>
                                    </table>
                                    <?php
                                        } 
                                    }
                                    ?>
                                    
                                    <form id='addDes' action='processDesign.php' method='post'>

                                        <div id="addDesError" class="error">
                                            <?php 
                                                if (isset($_SESSION['addDesError'])) {
                                                    echo $_SESSION['addDesError'];
                                                }
                                            ?>
                                        </div>
                                        <p id='nanError' style="display: none;">Please enter numbers only</p>
                                        <div id="addDesSuccess" class="success">
                                            <?php 
                                                if (isset($_SESSION['addDesSuccess'])) {
                                                    echo $_SESSION['addDesSuccess'];
                                                }
                                            ?>
                                        </div>

                                        <h1 class="page-header">Add/Edit Design Section</h1>

                                        <input type='hidden' name='submitted' id='submitted' value='1'/>
                                        <input type='hidden' name='editid' id='editid' 
                                               value='<?php if (isset($_GET['id'])) { echo $erow['id']; }?>'/>

                                        <table class="content">
                                            <tr>
                                                <td colspan="2">
                                                    Title*:</label>
                                                    <input type='text' name='title' id='title'
                                                           value='<?php if (!empty($erow['title'])) { echo $erow['title']; }?>'/>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    Order*:
                                                    <input type='text' name='order' id='order'  
                                                       onkeypress="return isNumber(event)" 
                                                           value="<?php if (isset($erow['fieldorder'])) { echo $erow['fieldorder']; } else { echo $rowCount+1; } ?>"/>
                                                </td>
                                                <td>
                                                    Status*:
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
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="2">
                                                    Content*: 
                                                    <textarea name="html"><?php 
                                                        if(!empty($erow['html'])) { echo $erow['html']; }?></textarea>
                                                    <script type="text/javascript">
                                                        CKEDITOR.replace('html');
                                                    </script>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="2">
                                                    <input type='submit' name='submit' value='Submit' />
                                                </td>
                                            </tr>
                                        </table>
                                    </form>
                                </p>
                            </div>
                        </div>
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
    function deleteFunction(locId) {
        var r = confirm("Are you sure you wish to delete this section?");
        if (r === true) {
            window.location="processDesign.php?delete=1&id=" + locId;
        } else if (r === false) {
            <?php
                unset($_SESSION['addDesError']);
                unset($_SESSION['addDesSuccess']);
                unset($_SESSION['updateDesSuccess']);
                $_SESSION['updateDesError'] = "Nothing was deleted";
            ?>
            window.location='designstory.php#menu1';
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
    
    $(document).ready(function() {
        if(location.hash) {
            $('a[href=' + location.hash + ']').tab('show');
        }
        $(document.body).on("click", "a[data-toggle]", function(event) {
            location.hash = this.getAttribute("href");
        });
    });
    $(window).on('popstate', function() {
        var anchor = location.hash || $("a[data-toggle=tab]").first().attr("href");
        $('a[href=' + anchor + ']').tab('show');
    });
</script>