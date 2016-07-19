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

                                        <div id="addProdDescBannerError" class="error">
                                            <?php 
                                                if (isset($_SESSION['addProdDescBannerError'])) {
                                                    echo $_SESSION['addProdDescBannerError'];
                                                }
                                            ?>
                                        </div>

                                        <div id="addProdDescBannerSuccess" class="success">
                                            <?php 
                                                if (isset($_SESSION['addProdDescBannerSuccess'])) {
                                                    echo $_SESSION['addProdDescBannerSuccess'];
                                                }
                                            ?>
                                        </div>
                                        
                                        <table class="content">
                                            <tr>
                                                <td>
                                                    <select id='categories' name='categories'>
                                                        <option value='glasses'>Glasses</option>
                                                        <option value='sunglasses'>Sunglasses</option>
                                                    </select>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <input type='hidden' name='submitted' id='submitted' value='1'/>
                                                    <input type='hidden' name='oldImage' id='oldImage' value='<?php if(!empty($brow['html'])) echo $brow['html']; ?>'/>

                                                    Image:
                                                    <input type="file" name="image" id='image'/>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <input type='submit' name='submit' value='Save' />
                                                </td>
                                            </tr>
                                        </table>
                                    </form>
                                </p>
                            </div>
                            <div id="menu1" class="tab-pane fade">
                                <h1 class="page-header">Manage Product Description Sections</h1>
                                
                                <div id="updateProdDescSuccess" class="success">
                                    <?php 
                                        if (isset($_SESSION['updateProdDescSuccess'])) {
                                            echo $_SESSION['updateProdDescSuccess'];
                                        }
                                    ?>
                                </div>
                                <div id="updateProdDescError" class="error">
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

                                    if (!empty($rowResult)){
                                        while($r1 = mysqli_fetch_assoc($rowResult)) {
                                            echo "<input type='hidden' id='".$r1['type']."Value' value='".$r1['count']."'>";
                                        }
                                    }
                                    ?>
                                    
                                    <form id='addProdDescSection' action='processProdDesc.php' method='post' enctype="multipart/form-data">

                                        <div id="addProdDescError" class="error">
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
                                        <div id="addProdDescSuccess" class="success">
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
                                                           value='<?php if (isset($_SESSION['title'])) { 
                                                               echo $_SESSION['title'];
                                                           } else if (!empty($erow['title'])) { echo $erow['title']; } ?>'/>
                                                </td>
                                                <td>
                                                    Page*:
                                                    <select id='type' name='type'>
                                                        <option value='glasses' <?php 
                                                        if (isset($_SESSION['type'])) { 
                                                            if (strcmp($_SESSION['type'], "glasses") === 0) {
                                                                echo " selected";
                                                            }
                                                        } else if (!empty($erow['type'])) {
                                                            if (strcmp($erow['type'], "glasses") === 0) {
                                                                echo " selected";
                                                            }
                                                        } ?>>GLASSES</option>
                                                        <option value='sunglasses' <?php 
                                                        if (isset($_SESSION['type'])) { 
                                                            if (strcmp($_SESSION['type'], "sunglasses") === 0) {
                                                                echo " selected";
                                                            }
                                                        } else if (!empty($erow['type'])) {
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
                                                        if (isset($_SESSION['imagepos'])) { 
                                                            if (strcmp($_SESSION['imagepos'], "left") === 0) {
                                                                echo " checked";
                                                            }
                                                        } else if (!empty($erow['imagepos'])) {
                                                            if (strcmp($erow['imagepos'], "left") === 0) {
                                                                echo " checked";
                                                            }
                                                        }
                                                    ?>>Left 
                                                    <input type='radio' name='imagepos' value='background' <?php 
                                                        if (isset($_SESSION['imagepos'])) { 
                                                            if (strcmp($_SESSION['imagepos'], "background") === 0) {
                                                                echo " checked";
                                                            }
                                                        } else if (!empty($erow['imagepos'])) {
                                                            if (strcmp($erow['imagepos'], "background") === 0) {
                                                                echo " checked";
                                                            }
                                                        }
                                                    ?>>Background 
                                                    <input type='radio' name='imagepos' value='right' <?php 
                                                        if (isset($_SESSION['imagepos'])) { 
                                                            if (strcmp($_SESSION['imagepos'], "right") === 0) {
                                                                echo " checked";
                                                            }
                                                        } else if (!empty($erow['imagepos'])) {
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
                                                           value="<?php if (isset($_SESSION['order'])) { 
                                                            echo $_SESSION['order'];
                                                        } else if (isset($erow['fieldorder'])) { 
                                                            echo $erow['fieldorder']; 
                                                        } else { echo $rowCount+1; } ?>"/>
                                                </td>
                                                <td>
                                                    Status*:
                                                    <select name='status'>
                                                        <option value='active' <?php 
                                                            if (isset($_SESSION['status'])) { 
                                                                if (strcmp($_SESSION['status'], "active") === 0) {
                                                                    echo " selected";
                                                                }
                                                            } else if (!empty($erow['status'])) {
                                                                if (strcmp($erow['status'], "active") === 0) {
                                                                    echo " selected";
                                                                }
                                                            }
                                                        ?>>Active</option>
                                                        <option value='inactive' <?php 
                                                            if (isset($_SESSION['status'])) { 
                                                                if (strcmp($_SESSION['status'], "inactive") === 0) {
                                                                    echo " selected";
                                                                }
                                                            } else if (!empty($erow['status'])) {
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
                                                    if (isset($_SESSION['html'])) { 
                                                        echo $_SESSION['html'];
                                                    } else if(!empty($erow['html'])) { echo $erow['html']; }?></textarea>
                                                    <script type="text/javascript">
                                                        CKEDITOR.replace('html');
                                                    </script>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    Content Position: <br/>
                                                    <input type='radio' name='htmlpos' value='left' <?php 
                                                        if (isset($_SESSION['htmlpos'])) { 
                                                            if (strcmp($_SESSION['htmlpos'], "left") === 0) {
                                                                echo " checked";
                                                            }
                                                        } else if (!empty($erow['htmlpos'])) {
                                                            if (strcmp($erow['htmlpos'], "left") === 0) {
                                                                echo " checked";
                                                            }
                                                        }
                                                    ?>>Left 
                                                    <input type='radio' name='htmlpos' value='center' <?php 
                                                        if (isset($_SESSION['htmlpos'])) { 
                                                            if (strcmp($_SESSION['htmlpos'], "center") === 0) {
                                                                echo " checked";
                                                            }
                                                        } else if (!empty($erow['htmlpos'])) {
                                                            if (strcmp($erow['htmlpos'], "center") === 0) {
                                                                echo " checked";
                                                            }
                                                        }
                                                    ?>>Center 
                                                    <input type='radio' name='htmlpos' value='right' <?php 
                                                        if (isset($_SESSION['htmlpos'])) { 
                                                            if (strcmp($_SESSION['htmlpos'], "right") === 0) {
                                                                echo " checked";
                                                            }
                                                        } else if (!empty($erow['htmlpos'])) {
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
                                                        if (isset($_SESSION['buttontexts'])) { 
                                                            $buttontexts = $_SESSION['buttontexts'];
                                                        } else if (!empty($erow['buttontext'])) {
                                                            $buttontexts = explode(",", $erow['buttontext']);
                                                        }
                                                        
                                                        if (isset($_SESSION['links'])) { 
                                                            $links = $_SESSION['links'];
                                                        } else if (!empty($erow['link'])) {
                                                            $links = explode(",", $erow['link']);
                                                        }

                                                        if (isset($_SESSION['linkpos'])) { 
                                                            $linkposArr = $_SESSION['linkpos'];
                                                        } else if (!empty($erow['linkpos'])) {
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
            <!--                                                            <input type='text' name='link<?php // echo $i+1; ?>' 
                                                                               id='link<?php // echo $i+1; ?>'  maxlength="50" 
                                                                               value='<?php // if (!empty($links[$i])) { echo $links[$i]; } ?>'/>-->
                                                                        <select name='type<?php  echo $i+1; ?>' id='type<?php  echo $i+1; ?>'>
                                                                            <option value='nil' <?php if (empty($links[$i])) { 
                                                                                if (strcmp($links[$i], "") === 0) {
                                                                                    echo "selected"; 
                                                                                }
                                                                            } ?>>None</option>

                                                                            <option value='index' <?php if (!empty($links[$i])) { 
                                                                                if (strcmp($links[$i], "index.php") === 0) {
                                                                                    echo "selected"; 
                                                                                }
                                                                            } ?>>Homepage</option>
                                                                            <option value='products' <?php if (!empty($links[$i])) { 
                                                                                if (is_numeric(strpos($links[$i], "?"))) {
                                                                                    $str = explode("?", $links[$i]);
                                                                                    if (strcmp($str[0], "products.php") === 0) {
                                                                                        echo "selected"; 
                                                                                    }
                                                                                }
                                                                            } ?>>Products</option>
                                                                            <option value='product' <?php if (!empty($links[$i])) { 
                                                                                if (is_numeric(strpos($links[$i], "?"))) {
                                                                                    $str = explode("?", $links[$i]);
                                                                                    if (strcmp($str[0], "product.php") === 0) {
                                                                                        echo "selected"; 
                                                                                    }
                                                                                }
                                                                            } ?>>Specific Product</option>
                                                                            <option value='hometry' <?php if (!empty($links[$i])) { 
                                                                                if (strcmp($links[$i], "hometry.php") === 0) {
                                                                                    echo "selected"; 
                                                                                }
                                                                            } ?>>Home Try-on</option>
                                                                            <option value='locations' <?php if (!empty($links[$i])) { 
                                                                                if (strcmp($links[$i], "locations.php") === 0) {
                                                                                    echo "selected"; 
                                                                                }
                                                                            } ?>>Locations</option>
                                                                            <option value='ourstory' <?php if (!empty($links[$i])) { 
                                                                                if (is_numeric(strpos($links[$i], "?"))) {
                                                                                    $str = explode("?", $links[$i]);
                                                                                    if (strcmp($str[0], "ourstory.php") === 0) {
                                                                                        echo "selected"; 
                                                                                    }
                                                                                }
                                                                            } ?>>Our Story</option> 
                                                                            <option value='faq' <?php if (!empty($links[$i])) { 
                                                                                if (strcmp($links[$i], "faq.php") === 0) {
                                                                                    echo "selected"; 
                                                                                }
                                                                            } ?>>FAQs</option>
                                                                            <option value='giftcards' <?php if (!empty($links[$i])) { 
                                                                                if (strcmp($links[$i], "giftcard.php") === 0) {
                                                                                    echo "selected"; 
                                                                                }
                                                                            } ?>>Gift cards</option>
                                                                            <option value='career' <?php if (!empty($links[$i])) { 
                                                                                if (strcmp($links[$i], "career.php") === 0) {
                                                                                    echo "selected"; 
                                                                                }
                                                                            } ?>>Careers</option>
                                                                            <option value='blog' <?php if (!empty($links[$i])) { 
                                                                                if (strcmp($links[$i], "blog.php") === 0) {
                                                                                    echo "selected"; 
                                                                                }
                                                                            } ?>>Blog</option>
                                                                            <option value='terms' <?php if (!empty($links[$i])) { 
                                                                                if (strcmp($links[$i], "terms.php") === 0) {
                                                                                    echo "selected"; 
                                                                                }
                                                                            } ?>>Terms & Conditions</option>
                                                                            <option value='page' <?php if (!empty($links[$i])) { 
                                                                                if (is_numeric(strpos($links[$i], "?"))) {
                                                                                    $str = explode("?", $links[$i]);
                                                                                    if (strcmp($str[0], "page.php") === 0) {
                                                                                        echo "selected"; 
                                                                                    }
                                                                                }
                                                                            } ?>>Pages</option>
                                                                        </select>
                                                                        <!--sections-->
                                                                        <div id='productsPage<?php  echo $i+1; ?>' style='display:none;'>
                                                                            <select name='productstype<?php  echo $i+1; ?>'>
                                                                                <option value='type=frames&gender=men' <?php if (!empty($links[$i])) { 
                                                                                if (is_numeric(strpos($links[$i], "?"))) {
                                                                                    $str = explode("?", $links[$i]);
                                                                                    if (strcmp($str[1], "type=frames&gender=men") === 0) {
                                                                                        echo "selected"; 
                                                                                    }
                                                                                }
                                                                            } ?>>Men's Glasses</option>
                                                                                <option value='type=frames&gender=women' <?php if (!empty($links[$i])) { 
                                                                                if (is_numeric(strpos($links[$i], "?"))) {
                                                                                    $str = explode("?", $links[$i]);
                                                                                    if (strcmp($str[1], "type=frames&gender=women") === 0) {
                                                                                        echo "selected"; 
                                                                                    }
                                                                                }
                                                                            } ?>>Women's Glasses</option>
                                                                                <option value='type=sunglasses&gender=men' <?php if (!empty($links[$i])) { 
                                                                                if (is_numeric(strpos($links[$i], "?"))) {
                                                                                    $str = explode("?", $links[$i]);
                                                                                    if (strcmp($str[1], "type=sunglasses&gender=men") === 0) {
                                                                                        echo "selected"; 
                                                                                    }
                                                                                }
                                                                            } ?>>Men's Sunglasses</option>
                                                                                <option value='type=sunglasses&gender=women' <?php if (!empty($links[$i])) { 
                                                                                if (is_numeric(strpos($links[$i], "?"))) {
                                                                                    $str = explode("?", $links[$i]);
                                                                                    if (strcmp($str[1], "type=sunglasses&gender=women") === 0) {
                                                                                        echo "selected"; 
                                                                                    }
                                                                                }
                                                                            } ?>>Women's Sunglasses</option>
                                                                            </select>
                                                                        </div>
                                                                        <!--sections-->
                                                                        <div id='productPage<?php  echo $i+1; ?>' style='display:none;'>
                                                                            <select name='productItem<?php  echo $i+1; ?>'>
                                                                                <?php 
                                                                                    $sql = "Select * from products;";
                                                                                    $pres = mysqli_query($link, $sql);

                                                                                    if (!mysqli_query($link, $sql)) {
                                                                                        die(mysqli_error($link));
                                                                                    } else {
                                                                                        while ($row = mysqli_fetch_assoc($pres)) {
                                                                                            echo "<option value='".$row['pid']."'";
                                                                                            if (is_numeric(strpos($links[$i], "?"))) {
                                                                                                $str = explode("?", $links[$i]);
                                                                                                if (is_numeric(strpos($str[1], "id="))) {
                                                                                                    $id = explode("id=", $str[1]);
                                                                                                    if (strcmp($id[1], $row['pid']) === 0) {
                                                                                                        echo "selected"; 
                                                                                                    }
                                                                                                }
                                                                                            }
                                                                                            echo ">".
                                                                                                    $row['name']."</option>";
                                                                                        }
                                                                                    }
                                                                                ?>
                                                                            </select>
                                                                        </div>
                                                                        <!--sections-->
                                                                        <div id='ourstoryPage<?php  echo $i+1; ?>' style='display: none;'>
                                                                            <select name='ourstorytype<?php  echo $i+1; ?>'>
                                                                                <option value='main' <?php
                                                                                    if (is_numeric(strpos($links[$i], "?"))) {
                                                                                        $str = explode("?", $links[$i]);
                                                                                        $id = explode("type=", $str[1]);
                                                                                        if (strcmp($id[1], "main") === 0) {
                                                                                            echo "selected"; 
                                                                                        }
                                                                                    }
                                                                                ?>>History</option>
                                                                                <option value='one' <?php
                                                                                    if (is_numeric(strpos($links[$i], "?"))) {
                                                                                        $str = explode("?", $links[$i]);
                                                                                        $id = explode("type=", $str[1]);
                                                                                        if (strcmp($id[1], "one") === 0) {
                                                                                            echo "selected"; 
                                                                                        }
                                                                                    }
                                                                                ?>>One For You, One For Them</option>
                                                                                <option value='culture' <?php
                                                                                    if (is_numeric(strpos($links[$i], "?"))) {
                                                                                        $str = explode("?", $links[$i]);
                                                                                        $id = explode("type=", $str[1]);
                                                                                        if (strcmp($id[1], "culture") === 0) {
                                                                                            echo "selected"; 
                                                                                        }
                                                                                    }
                                                                                ?>>Culture</option>
                                                                                <option value='design' <?php
                                                                                    if (is_numeric(strpos($links[$i], "?"))) {
                                                                                        $str = explode("?", $links[$i]);
                                                                                        $id = explode("type=", $str[1]);
                                                                                        if (strcmp($id[1], "design") === 0) {
                                                                                            echo "selected"; 
                                                                                        }
                                                                                    }
                                                                                ?>>Design</option>
                                                                            </select>
                                                                        </div>
                                                                        <!--sections-->
                                                                        <div id='page<?php  echo $i+1; ?>' style='display:none;'>
                                                                            <select name='pageItem<?php  echo $i+1; ?>'>
                                                                                <?php 
                                                                                    $pagesql = "Select * from pages where type='banner' and status='active';";
                                                                                    $pageres = mysqli_query($link, $pagesql);

                                                                                    if (!mysqli_query($link, $pagesql)) {
                                                                                        die(mysqli_error($link));
                                                                                    } else {
                                                                                        while ($row = mysqli_fetch_assoc($pageres)) {
                                                                                            echo "<option value='".$row['id']."'";
                                                                                            if (is_numeric(strpos($links[$i], "?"))) {
                                                                                                $str = explode("?", $links[$i]);
                                                                                                if (is_numeric(strpos($str[1], "id="))) {
                                                                                                    $id = explode("id=", $str[1]);
                                                                                                    if (strcmp($id[1], $row['id']) === 0) {
                                                                                                        echo "selected"; 
                                                                                                    }
                                                                                                }
                                                                                            }
                                                                                            echo ">".
                                                                                                    $row['title']."</option>";
                                                                                        }
                                                                                    }
                                                                                ?>
                                                                            </select>
                                                                        </div>
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
                                                                        <!--<input type="text" name="link1" id="link1" maxlength="50">-->
                                                                        <select name='type1' id='type1'>
                                                                            <option value='nil'>None</option>
                                                                            <option value='index'>Homepage</option>
                                                                            <option value='products'>Products</option>
                                                                            <option value='product'>Specific Product</option>
                                                                            <option value='hometry'>Home Try-on</option>
                                                                            <option value='locations'>Locations</option>
                                                                            <option value='ourstory'>Our Story</option> 
                                                                            <option value='faq'>FAQs</option>
                                                                            <option value='giftcards'>Gift cards</option>
                                                                            <option value='career'>Careers</option>
                                                                            <option value='blog'>Blog</option>
                                                                            <option value='terms'>Terms & Conditions</option>
                                                                            <option value='page'>Pages</option>
                                                                        </select>
                                                                        <!--sections-->
                                                                        <div id='productsPage1' style='display:none;'>
                                                                            <select name='productstype1'>
                                                                                <option value='type=frames&gender=men'>Men's Glasses</option>
                                                                                <option value='type=frames&gender=women'>Women's Glasses</option>
                                                                                <option value='type=sunglasses&gender=men'>Men's Sunglasses</option>
                                                                                <option value='type=sunglasses&gender=women'>Women's Sunglasses</option>
                                                                            </select>
                                                                        </div>
                                                                        <!--sections-->
                                                                            <div id='productPage1' style='display:none;'>
                                                                                <select name='productItem1'>
                                                                                    <?php 
                                                                                        $sql = "Select * from products;";
                                                                                        $pres = mysqli_query($link, $sql);

                                                                                        if (!mysqli_query($link, $sql)) {
                                                                                            die(mysqli_error($link));
                                                                                        } else {
                                                                                            while ($row = mysqli_fetch_assoc($pres)) {
                                                                                                echo "<option value='".$row['pid']."'>".
                                                                                                        $row['name']."</option>";
                                                                                            }
                                                                                        }
                                                                                    ?>
                                                                                </select>
                                                                            </div>
                                                                        <!--sections-->
                                                                        <div id='ourstoryPage1' style='display: none;'>
                                                                            <select name='ourstorytype1'>
                                                                                <option value='main'>History</option>
                                                                                <option value='one'>One For You, One For Them</option>
                                                                                <option value='culture'>Culture</option>
                                                                                <option value='design'>Design</option>
                                                                            </select>
                                                                        </div>
                                                                        <!--sections-->
                                                                        <div id='page1' style='display:none;'>
                                                                            <select name='pageItem<?php  echo $i+1; ?>'>
                                                                                <?php 
                                                                                    $pagesql = "Select * from pages where type='banner' and status='active';";
                                                                                    $pageres = mysqli_query($link, $pagesql);

                                                                                    if (!mysqli_query($link, $pagesql)) {
                                                                                        die(mysqli_error($link));
                                                                                    } else {
                                                                                        while ($row = mysqli_fetch_assoc($pageres)) {
                                                                                            echo "<option value='".$row['id']."'>".
                                                                                                    $row['title']."</option>";
                                                                                        }
                                                                                    }
                                                                                ?>
                                                                            </select>
                                                                        </div>
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
                                                    <input type='submit' name='submit' value='Save' />
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
    var count = document.getElementById('buttonno').value;
    attachClick(count);
    
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
//                unset($_SESSION['addProdDescError']);
//                unset($_SESSION['addProdDescSuccess']);
//                unset($_SESSION['updateProdDescSuccess']);
//                $_SESSION['updateProdDescError'] = "Nothing was deleted";
            ?>
            window.location='productdesc.php#menu1';
        }
    }
    
    var count=1;
    
    function addButton() {
        count++;
        document.getElementById('buttonno').value = count;
        var node = document.createElement('fieldset');  
        node.innerHTML = "<h5 class='page-header'>Button " + count + " (optional)</h5>" +
                "<table class='content-sub'><tr>"+
                "<td>Text:" + "<input type='text' name='buttontext" + count +
                    "' id='buttontext" + count + "' maxlength='50' /></td>" +
                "<td>Position: <br/>" +
                    "<input type='radio' name='linkpos"+count+"' value='left'>Left "+
                    "<input type='radio' name='linkpos"+count+"' value='center'>Center "+ 
                    "<input type='radio' name='linkpos"+count+"' value='right'>Right </td></tr>" +
                "<tr><td colspan='2'>Link:"  
                + "<select name='type"+count+
                "' id='type"+count+"'><option value='nil'>None</option>"
                + "<option value='index'>Homepage</option>" 
                + "<option value='products'>Products</option>"
                + "<option value='product'>Specific Product</option>"
                + "<option value='hometry'>Home Try-on</option>"
                + "<option value='locations'>Locations</option>"
                + "<option value='ourstory'>Our Story</option>"
                + "<option value='faq'>FAQs</option>"
                + "<option value='giftcards'>Gift cards</option>"
                + "<option value='career'>Careers</option>"
                + "<option value='blog'>Blog</option>"
                + "<option value='terms'>Terms & Conditions</option>"
                + "<option value='page'>Pages</option></select>"
                + "<div id='productsPage"+count+"' style='display:none;'>"
                + "<select name='productstype"+count+"'>"
                + "<option value='type=frames&gender=men'>Men's Glasses</option>"
                + "<option value='type=frames&gender=women'>Women's Glasses</option>"
                + "<option value='type=sunglasses&gender=men'>Men's Sunglasses</option>"
                + "<option value='type=sunglasses&gender=women'>Women's Sunglasses</option>"
                + "</select></div>"
                + "<div id='productPage"+count+"' style='display:none;'>"
                + "<select name='productItem"+count+"'>"
                + "    <?php 
                        $sql = "Select * from products;";
                        $pres = mysqli_query($link, $sql);

                        if (!mysqli_query($link, $sql)) {
                            die(mysqli_error($link));
                        } else {
                            while ($row = mysqli_fetch_assoc($pres)) {
                                echo "<option value='".$row['pid']."'>".
                                        $row['name']."</option>";
                            }
                        }
                    ?>"
                + "</select></div>"
                + "<div id='ourstoryPage"+count+"' style='display: none;'>"
                + "<select name='ourstorytype"+count+"'>"
                + "<option value='main'>History</option>"
                + "<option value='one'>One For You, One For Them</option>"
                + "<option value='culture'>Culture</option>"
                + "<option value='design'>Design</option>"
                + "</select></div>"
                + "<div id='page"+count+"' style='display:none;'>"
                + "<select name='pageItem"+count+"'>"
                + "    <?php 
                        $sql = "Select * from pages where type='banner' and status='active';";
                        $pres = mysqli_query($link, $sql);

                        if (!mysqli_query($link, $sql)) {
                            die(mysqli_error($link));
                        } else {
                            while ($row = mysqli_fetch_assoc($pres)) {
                                echo "<option value='".$row['id']."'>".
                                        $row['title']."</option>";
                            }
                        }
                    ?>"
                + "</select></div>"
                + "</td></tr></table>";
//            node.innerHTML = 'Button Text ' + count + ' : <input type="text" name="buttontext'+count+'">';
        
        document.getElementById('buttonlinks').appendChild(node); 
        attachClick(count);
    }
    
    function checkSelect(num) {
        var products = "productsPage" + num;
        var product = "productPage" + num;
        var ourstory = "ourstoryPage" + num;
        var pages = "page" + num;
        var type = "type" + num;
        
        var obj = document.getElementById(type);
        if (obj !== null) {
            var val = obj.value;
            if (val === "products") {
                document.getElementById(products).style.display = "block";
                document.getElementById(product).style.display = "none";
                document.getElementById(ourstory).style.display = "none";
                document.getElementById(pages).style.display = "none";
            } else if (val === "product") {
                document.getElementById(products).style.display = "none";
                document.getElementById(product).style.display = "block";
                document.getElementById(ourstory).style.display = "none";
                document.getElementById(pages).style.display = "none";
            } else if (val === "ourstory") {
                document.getElementById(products).style.display = "none";
                document.getElementById(product).style.display = "none";
                document.getElementById(ourstory).style.display = "block";
                document.getElementById(pages).style.display = "none";
            } else if (val === "page") {
                document.getElementById(products).style.display = "none";
                document.getElementById(product).style.display = "none";
                document.getElementById(ourstory).style.display = "none";
                document.getElementById(pages).style.display = "block";                
            } else {
                document.getElementById(products).style.display = "none";
                document.getElementById(product).style.display = "none";
                document.getElementById(ourstory).style.display = "none";
                document.getElementById(pages).style.display = "none";                      
            }
        }
    }
    
    function handleLinkSelect(num) {
        var products = "productsPage" + num;
        var product = "productPage" + num;
        var ourstory = "ourstoryPage" + num;
        var pages = "page" + num;
        var type = "type" + num;
        
        var obj = document.getElementById(type);
        if (obj !== null) {
            obj.onclick = function() {
                var val = this.value;
                if (val === "products") {
                    document.getElementById(products).style.display = "block";
                    document.getElementById(product).style.display = "none";
                    document.getElementById(ourstory).style.display = "none";
                document.getElementById(pages).style.display = "none";
                } else if (val === "product") {
                    document.getElementById(products).style.display = "none";
                    document.getElementById(product).style.display = "block";
                    document.getElementById(ourstory).style.display = "none";
                document.getElementById(pages).style.display = "none";
                } else if (val === "ourstory") {
                    document.getElementById(products).style.display = "none";
                    document.getElementById(product).style.display = "none";
                    document.getElementById(ourstory).style.display = "block";
                document.getElementById(pages).style.display = "none";
                } else if (val === "page") {
                    document.getElementById(products).style.display = "none";
                    document.getElementById(product).style.display = "none";
                    document.getElementById(ourstory).style.display = "none";
                    document.getElementById(pages).style.display = "block";                
                } else {
                    document.getElementById(products).style.display = "none";
                    document.getElementById(product).style.display = "none";
                    document.getElementById(ourstory).style.display = "none";
                    document.getElementById(pages).style.display = "none";                      
                }
            };
        }
    }
    
    function attachClick(num) {
        for (var i = 1; i <= num; i++) {
            handleLinkSelect(i);
            checkSelect(i);
        }
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
