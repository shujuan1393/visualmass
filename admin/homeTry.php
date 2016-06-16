<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
require_once '../config/db.php';

if (isset($_GET['id'])) {
    $selectSql = "Select * from hometry where id ='" .$_GET['id']."';";
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
                                Home Try-on
                            </li>
                        </ol>
                        
                        <ul class="nav nav-tabs">
                            <li class="active"><a data-toggle="tab" href="#homeb">Home Try-on Banner</a></li>
                            <li><a data-toggle="tab" href="#menu1">Home Try-on Sections</a></li>
                        </ul>
                        
                        <div class="tab-content">
                            <div id="homeb" class="tab-pane fade in active">
                                <h1 class="page-header">Update Home Try-on Banner</h1>
                                
                                <div id="updateHomeSuccess" style="color:green">
                                    <?php 
                                        if (isset($_SESSION['updateHomeSuccess'])) {
                                            echo $_SESSION['updateHomeSuccess'];
                                        }
                                    ?>
                                </div>
                                <div id="updateHomeError" style="color:red">
                                    <?php 
                                        if (isset($_SESSION['updateHomeError'])) {
                                            echo $_SESSION['updateHomeError'];
                                        }
                                    ?>
                                </div>
                                
                                <p>
                                    <?php 
                                        $getBanner = "Select * from hometry where type='banner';";
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

                                    <form id='addHomeBanner' action='processHomeTry.php?banner=1' method='post' enctype="multipart/form-data">

                                        <div id="addHomeBannerError" style="color:red">
                                            <?php 
                                                if (isset($_SESSION['addHomeBannerError'])) {
                                                    echo $_SESSION['addHomeBannerError'];
                                                }
                                            ?>
                                        </div>

                                        <div id="addHomeBannerSuccess" style="color:green">
                                            <?php 
                                                if (isset($_SESSION['addHomeBannerSuccess'])) {
                                                    echo $_SESSION['addHomeBannerSuccess'];
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
                                <h1 class="page-header">Manage Home Try-on Sections</h3>
                                <p>
                                    <?php 
                                        $qry = "Select * from hometry where type='section' ORDER BY fieldorder asc";

                                        $result = mysqli_query($link, $qry);

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
                                            <th>Order</th> 
                                            <th>Title</th> 
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
                                                echo "<td>".$row['fieldorder'] ."</td>";   
                                                echo "<td>".$row['title'] ."</td>";   
                                                echo "<td>".$row['status']."</td>";                        
                                                echo '<td><button onClick="window.location.href=`homeTry.php?id='.$row['id'].'`">E</button>';
                                                echo '<td><button onClick="deleteFunction('.$row['id'].')">D</button></td>';
                                                echo "</tr>";
                                            }
                                        ?>
                                    </table>
                                    <?php
                                        } 
                                    }
                                    ?>

                                    <form id='addAdvertisement' action='processHomeTry.php' method='post' enctype="multipart/form-data">

                                        <div id="addHomeError" style="color:red">
                                            <?php 
                                                if (isset($_SESSION['addHomeError'])) {
                                                    echo $_SESSION['addHomeError'];
                                                }

                                                if (isset($_SESSION['uploadHomeError'])) {
                                                    echo $_SESSION['uploadHomeError'];
                                                }
                                            ?>
                                        </div>

                                        <p id='nanError' style="display: none;">Please enter numbers only</p>
                                        <div id="addHomeSuccess" style="color:green">
                                            <?php 
                                                if (isset($_SESSION['addHomeSuccess'])) {
                                                    echo $_SESSION['addHomeSuccess'];
                                                }
                                            ?>
                                        </div>

                                        <h1 id="add" class="page-header">Add/Edit Home Try-On Section</h1>

                                        <input type='hidden' name='submitted' id='submitted' value='1'/>
                                        <input type='hidden' name='editid' id='editid' 
                                               value='<?php if (isset($_GET['id'])) { echo $erow['id']; } ?>'/>

                                        <table class="content">
                                            <tr>
                                                <td>
                                                    Title*:
                                                    <input type='text' name='title' id='title' maxlength="50" 
                                                           value='<?php if (!empty($erow['title'])) { echo $erow['title']; } ?>'/>
                                                </td>
                                                <td>
                                                    Order*:
                                                    <input type='text' name='order' id='order'  
                                                       onkeypress="return isNumber(event)" 
                                                           value="<?php 
                                                                if(!empty($erow['fieldorder'])){
                                                                    if (isset($erow['fieldorder'])) { 
                                                                        echo $erow['fieldorder']; 
                                                                    } else { 
                                                                        echo $rowCount+1;
                                                                    } 
                                                                } ?>"/>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <?php 
                                                        if (!empty($erow['image'])) {
                                                            echo "<img src='".$erow['image']."' width=200><br>";
                                                            echo "<input type='hidden' name='oldImage' id='oldImage' value='".$erow['image']."'>";
                                                        }
                                                    ?>

                                                    Image*:
                                                    <input type="file" name="image" id='image' accept="image/*" />
                                                </td>
                                                <td>
                                                    Image Position*: <br/>
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
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="2">
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
                                                    Content (optional): 
                                                    <textarea name="html"><?php 
                                                        if(!empty($erow['html'])) { echo $erow['html']; }?></textarea>
                                                    <script type="text/javascript">
                                                        CKEDITOR.replace('html');
                                                    </script>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    Content Position: <br/>
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
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="2">
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

                                                    <h4 class="pull-left">Button</h4>
                                                    <p onclick="addButton()" class="text-right addMore">
                                                        <i class="fa fa-fw fa-plus"></i> Add Button
                                                    </p>

                                                    <div id='buttonlinks'>
                                                        <?php 
                                                            if (!empty($buttontexts)) {
                                                                for ($i = 0; $i < count($buttontexts); $i++) {
                                                        ?>

                                                        <h5 class="page-header">Button <?php echo $i+1; ?></h5>
                                                            <table class="content-sub">
                                                                <tr>
                                                                    <td>
                                                                        Text:
                                                                        <input type='text' name='buttontext<?php echo $i+1; ?>' 
                                                                               id='buttontext<?php echo $i+1; ?>'  maxlength="50" 
                                                                               value='<?php if (!empty($buttontexts[$i])) { echo $buttontexts[$i]; } ?>'/>
                                                                    </td>
                                                                    <td>
                                                                        Position:
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
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td colspan="2">
                                                                        Link:
                                                                        <input type='text' name='link<?php echo $i+1; ?>' 
                                                                               id='link<?php echo $i+1; ?>'  maxlength="50" 
                                                                               value='<?php if (!empty($links[$i])) { echo $links[$i]; } ?>'/>
                                                                    </td>
                                                                </tr>
                                                            </table>
                                                            <?php
                                                                    }
                                                                } else {
                                                            ?>

                                                            <h5 class="page-header">Button 1 (optional)</h5>
                                                            <table class="content-sub">
                                                                <tr>
                                                                    <td>
                                                                        Text:
                                                                        <input type="text" name="buttontext1" id="buttontext1" maxlength="50">
                                                                    </td>
                                                                    <td>
                                                                        Position: <br/>
                                                                        <input type="radio" name="linkpos1" value="left">Left 
                                                                        <input type="radio" name="linkpos1" value="center">Center 
                                                                        <input type="radio" name="linkpos1" value="right">Right 
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td colspan="2">
                                                                        Link:
                                                                        <input type="text" name="link1" id="link1" maxlength="50">
                                                                    </td>
                                                                </tr>
                                                            </table>
                                                            <?php
                                                                }
                                                            ?>
                                                    </div>
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
            window.location="processHomeTry.php?delete=1&id=" + locId;
        } else if (r === false) {
            <?php
                unset($_SESSION['addHomeError']);
                unset($_SESSION['addHomeSuccess']);
                unset($_SESSION['updateHomeSuccess']);
                $_SESSION['updateHomeError'] = "Nothing was deleted";
            ?>
            window.location='homeTry.php';
        }
    }

    function addButton() {
        var count = document.getElementById('buttonno').value;
        count++;
        var node = document.createElement('fieldset');  
        node.innerHTML = "<h5 class='page-header'>Button " + count + " (optional)</h5>" +
                "<table class='content-sub'><tr>" +
                "<td>Text: <input type='text' name='buttontext" + count +
                    "' id='buttontext" + count + "' maxlength='50' /></td>" +
                "<td>Position: <br/>" +
                    "<input type='radio' name='linkpos"+count+"' value='left'>Left " +
                    "<input type='radio' name='linkpos"+count+"' value='center'>Center " + 
                    "<input type='radio' name='linkpos"+count+"' value='right'>Right </td></tr>" +
                "<tr><td colspan='2'>Link: <input type='text' name='link" + count + 
                    "' id='link" + count + "' maxlength='50' /></td></tr></table>";

        document.getElementById('buttonlinks').appendChild(node); 
        document.getElementById('buttonno').value = count;
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

