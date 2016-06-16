<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
require_once '../config/db.php';

if (isset($_GET['id'])) {
    $selectSql = "Select * from productdescription where id ='" .$_GET['id']."';";
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
                                Product Description
                            </li>
                        </ol>
                        
                        <ul class="nav nav-tabs">
                            <li class="active"><a data-toggle="tab" href="#prodb">Product Description Banner</a></li>
                            <li><a data-toggle="tab" href="#menu1">Product Description Sections</a></li>
                        </ul>

                        <div class="tab-content">
                            <div id="prodb" class="tab-pane fade in active">
                                <h1 class="page-header">Manage Product Description Banners</h1>
                                <div id='bannerPreview' style='display: none;'>
                                    You have not uploaded a banner image yet.
                                </div>
                                <?php 
                                    $getBanner = "Select * from productbanner where gender='all'";
                                    $bresult = mysqli_query($link, $getBanner);

                                    if (!mysqli_query($link, $getBanner)) {
                                        echo "Error description: ". mysqli_error($link);
                                    } else {
                                        if ($bresult -> num_rows !== 0 ) {
                                            while ($brow = mysqli_fetch_assoc($bresult)) {
                                                $browArr = explode(".", $brow['image']);
                                                $ext = $browArr[count($browArr)-1];

                                                $imgArr = array("jpg", "jpeg", "png", "gif");
                                                $vidArr = array("mp3", "mp4", "wma");

                                                echo "<div class='prodbanners' id='banner_".$brow['categories']."' style='display:none;'>";
                                                if (in_array($ext, $imgArr)) {
                                                    echo "<img src='".$brow['image']."' width=350>";
                                                } else {
                                                    echo '<video width="300" height="400" controls>
                                                    <source src="'.$brow['image'].'" type="video/mp4">
                                                    Your browser does not support the video tag.
                                                    </video>';
                                                }
                                                echo "</div>";
                                            }
                                        }
                                    }
                                ?>

                                    <form id='addProdDescBanner' action='processProdDesc.php?banner=1' method='post' enctype="multipart/form-data">

                                        <div id="addProdDescBannerError" style="color:red">
                                            <?php 
                                                if (isset($_SESSION['addProdDescBannerError'])) {
                                                    echo $_SESSION['addProdDescBannerError'];
                                                }
                                            ?>
                                        </div>

                                        <div id="addProdDescBannerSuccess" style="color:green">
                                            <?php 
                                                if (isset($_SESSION['addProdDescBannerSuccess'])) {
                                                    echo $_SESSION['addProdDescBannerSuccess'];
                                                }
                                            ?>
                                        </div>
                                        <select id='categories' name='categories'>
                                            <option value='glasses'>Glasses</option>
                                            <option value='sunglasses'>Sunglasses</option>
                                        </select>
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
                                <h1 class="page-header">Manage Product Description Sections</h1>
                                
                                <div id="updateProdDescSuccess" style="color:green">
                                    <?php 
                                        if (isset($_SESSION['updateProdDescSuccess'])) {
                                            echo $_SESSION['updateProdDescSuccess'];
                                        }
                                    ?>
                                </div>
                                <div id="updateProdDescError" style="color:red">
                                    <?php 
                                        if (isset($_SESSION['updateProdDescError'])) {
                                            echo $_SESSION['updateProdDescError'];
                                        }
                                    ?>
                                </div>
                                
                                <p>
                                    <?php 
                                        $qry = "Select * from productdescription order by type";

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
                                            <th>Page</th> 
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
                                                echo "<td>".$row['type']."</td>";     
                                                echo "<td>".$row['fieldorder']."</td>";  
                                                echo "<td>".$row['status']."</td>";                        
                                                echo '<td><button onClick="window.location.href=`productdesc.php?id='.$row['id'].'#menu1`">E</button>';
                                                echo '<td><button onClick="deleteFunction('.$row['id'].')">D</button></td>';
                                                echo "</tr>";
                                            }
                                        ?>
                                    </table>
                                    <?php
                                        }
                                    }
                                    
                                    $rowsQry = "Select count(id) as count, type from productdescription group by type;";
                                    $rowResult = mysqli_query($link, $rowsQry);

                                    while($r1 = mysqli_fetch_assoc($rowResult)) {
                                        echo "<input type='hidden' id='".$r1['type']."Value' value='".$r1['count']."'>";
                                    }
                                    ?>
                                    
                                    <form id='addProdDescSection' action='processProdDesc.php' method='post' enctype="multipart/form-data">

                                        <div id="addProdDescError" style="color:red">
                                            <?php 
                                                if (isset($_SESSION['addProdDescError'])) {
                                                    echo $_SESSION['addProdDescError'];
                                                }

                                                if (isset($_SESSION['uploadProdDescError'])) {
                                                    echo $_SESSION['uploadProdDescError'];
                                                }
                                            ?>
                                        </div>
                                        <p id='nanError' style="display: none;">Please enter numbers only</p>
                                        <div id="addProdDescSuccess" style="color:green">
                                            <?php 
                                                if (isset($_SESSION['addProdDescSuccess'])) {
                                                    echo $_SESSION['addProdDescSuccess'];
                                                }
                                            ?>
                                        </div>

                                        <h1 id="add" class="page-header">Add/Edit Product Description Section</h1>

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
                                                    Page*:
                                                    <select id='type' name='type'>
                                                        <option value='glasses' <?php 
                                                        if (!empty($erow['type'])) {
                                                            if (strcmp($erow['type'], "glasses") === 0) {
                                                                echo " selected";
                                                            }
                                                        } ?>>GLASSES</option>
                                                        <option value='sunglasses' <?php 
                                                        if (!empty($erow['type'])) {
                                                            if (strcmp($erow['type'], "sunglasses") === 0) {
                                                                echo " selected";
                                                            }
                                                        } ?>>SUNGLASSES</option>
                                                    </select>
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
                                                                    Position: <br/>
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
    
    document.getElementById('type').onchange = function() {
        var index = this.selectedIndex;
        var inputText = this.children[index].value;
        var obj = document.getElementById(inputText+ "Value");
        var str = inputText + "Value";
        var count;
        
        if (obj !== null) {
            count = obj.value;
        } else {
            count = 0;
        }
        <?php
        if(!isset($_GET['id'])) {
        ?>
            document.getElementById('order').value = Number(count)+1;
        <?php 
        }
        ?>
    };

    window.onload = function() {
        var index = document.getElementById('type').selectedIndex;
        var inputText = document.getElementById('type').children[index].value;
        var str = inputText + "Value";
        var obj = document.getElementById(str);
        var count;
        
        if (obj !== null) {
            count = obj.value;
        } else {
            count = 0;
        }
        <?php
        if(!isset($_GET['id'])) {
        ?>
            document.getElementById('order').value = Number(count)+1;
        <?php 
        }
        ?>
    };
    
    function hideElements() {
        var arr = document.getElementsByClassName('prodbanners');
        for (var i = 0; i < arr.length; i++) {
            arr[i].style.display = "none";
        }
    }
    (function() {
        // your page initialization code here
        // the DOM will be available here
        <?php
            unset($_SESSION['addProdDescBannerError']);
            unset($_SESSION['addProdDescBannerSuccess']);
        ?>
        hideElements();
        var cat = document.getElementById('categories').value;
        var str = "banner_" + cat;
        var obj = document.getElementById(str);
        if (obj === null) {
            document.getElementById('bannerPreview').style.display = "block";
        } else {
            obj.style.display = "block";
            document.getElementById('bannerPreview').style.display = "none";
        }
    })();
    
    document.getElementById('categories').onchange = function() {
        hideElements();
        var cat = "banner_" + this.value;
        var obj = document.getElementById(cat);
        if (obj === null) {
            document.getElementById('bannerPreview').style.display = "block";
        } else {
            obj.style.display = "block";
            document.getElementById('bannerPreview').style.display = "none";
        }
        <?php
            unset($_SESSION['addProdDescBannerError']);
            unset($_SESSION['addProdDescBannerSuccess']);
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

    function deleteFunction(locId) {
        var r = confirm("Are you sure you wish to delete this section?");
        if (r === true) {
            window.location="processProdDesc.php?delete=1&id=" + locId;
        } else if (r === false) {
            <?php
                unset($_SESSION['addProdDescError']);
                unset($_SESSION['addProdDescSuccess']);
                unset($_SESSION['updateProdDescSuccess']);
                $_SESSION['updateProdDescError'] = "Nothing was deleted";
            ?>
            window.location='productdesc.php';
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
