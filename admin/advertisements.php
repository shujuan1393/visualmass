<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
require_once '../config/db.php';

if (isset($_GET['id'])) {
    $selectSql = "Select * from advertisements where id ='" .$_GET['id']."';";
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
                                Advertisement
                            </li>
                        </ol>
                        <h1 class="page-header">Manage Advertisements</h1>

                        <div id="updateAdvSuccess" class="success">
                            <?php 
                                if (isset($_SESSION['updateAdvSuccess'])) {
                                    echo $_SESSION['updateAdvSuccess'];
                                }
                            ?>
                        </div>
                        <div id="updateAdvError" class="error">
                            <?php 
                                if (isset($_SESSION['updateAdvError'])) {
                                    echo $_SESSION['updateAdvError'];
                                }
                            ?>
                        </div>
                        
                        <?php 
                            $qry = "Select * from advertisements";

                            $result = mysqli_query($link, $qry);

                            if (!mysqli_query($link,$qry))
                            {
                                echo("Error description: " . mysqli_error($link));
                            } else {
                                if ($result->num_rows === 0) {
                                    echo "You have not created any advertisements yet.";
                                } else {
                            ?>
                        
                            <p class="text-right">
                                <a href="#add"><i class="fa fa-fw fa-plus"></i> Add Advertisement</a>
                            </p>
                                        
                            <div class="pull-left filter-align">Filter: </div>
                            <div style="overflow:hidden">
                                <input type="text" id="filter" class="pull-right" placeholder="Type here to search">
                            </div>

                            <table id ="example">
                                <thead>
                                    <th>Title</th>
                                    <th>Expiry</th>   
                                    <th>Status</th>
                                    <th>Visibility</th>
                                    <th>Edit</th>
                                    <th>Delete</th>                        
                                </thead>
                                <tbody class="searchable">
                                <?php
                                    // output data of each row
                                    while ($row = mysqli_fetch_assoc($result)) {
                                        echo "<tr>";
                                        $oDate = new DateTime($row['start']);
                                        $sDate = $oDate->format("d-m-Y");


                                        $mDate = new DateTime($row['end']);
                                        $eDate = $mDate->format("d-m-Y");

                                        echo "<td>".$row['title'] ."</td>";     
                                        echo "<td>".$row['expiry'];
                                        if (strcmp($row['expiry'], "yes")===0) {
                                              echo " (".$sDate." to ".$eDate.")";
                                        }
                                        echo "</td>";  

                                        echo "<td>".$row['status']."</td>";                           
                                        echo "<td>".$row['visibility']."</td>";                       
                                        echo '<td><button onClick="window.location.href=`advertisements.php?id='.$row['id'].'`">E</button>';
                                        echo '<td><button onClick="deleteFunction('.$row['id'].')">D</button></td>';
                                        echo "</tr>";
                                    }
                                ?>
                                </tbody>
                            </table>
                            <?php
                                } 
                            }
                            ?>
        
                            <form id='addAdvertisement' action='processAdvertisements.php' method='post' enctype="multipart/form-data">

                                <div id="addAdvError" class="error">
                                    <?php 
                                        if (isset($_SESSION['addAdvError'])) {
                                            echo $_SESSION['addAdvError'];
                                        }

                                        if (isset($_SESSION['uploadAdvError'])) {
                                            echo $_SESSION['uploadAdvError'];
                                        }
                                    ?>
                                </div>
                                <div id="addAdvSuccess" class="success">
                                    <?php 
                                        if (isset($_SESSION['addAdvSuccess'])) {
                                            echo $_SESSION['addAdvSuccess'];
                                        }
                                    ?>
                                </div>
            
                                <h1 id='add' class="page-header">Add/Edit Advertisement</h1>
            
                                <input type='hidden' name='submitted' id='submitted' value='1'/>
                                <input type='hidden' name='editid' id='editid' 
                                       value='<?php if (isset($_GET['id'])) { echo $erow['id']; } ?>'/>
            
                                <table class="content">
                                    <tr>
                                        <td colspan="2">
                                            Title*:
                                            <input type='text' name='title' id='title' maxlength="50" 
                                                   value='<?php if (!empty($erow['title'])) { echo $erow['title']; } ?>'/>
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
                                        <td>
                                            Expiry*: <br/>
                                            <input type='radio' name='expiry' id='checkYes' value='yes' <?php 
                                                    if (!empty($erow['expiry'])) {
                                                        if (strcmp($erow['expiry'], "yes") === 0) {
                                                            echo " checked";
                                                        }
                                                    }
                                                ?>> Yes
                                            <input type='radio' name='expiry' id='checkNo' value='no' <?php 
                                                    if (!empty($erow['expiry'])) {
                                                        if (strcmp($erow['expiry'], "no") === 0) {
                                                            echo " checked";
                                                        } 
                                                    } else {
                                                        echo " checked";
                                                    }
                                                ?>> No
                                            
                                            <div id='expiryDate' style='display:none'>
                                                Start date:
                                                <input type="text" id="date3" name="date3">
                                                End date:
                                                <input type="text" id="date4" name="date4">
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="2">
                                            Content (optional): 
                                            <textarea name="html"><?php if (isset($editrow['html'])) { echo $editrow['html']; } ?></textarea>
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
                                        <td>
                                            Visibility*: <br/> 
                                            <?php 
                                                if(!empty($erow['visibility'])) {
                                                    $visib = explode(",", $erow['visibility']);
                                                }
                                            ?>
                                            <div class='checkboxAlign'>
                                                <input name='visibility[]' type='checkbox' value='homepage' <?php 
                                                    if (!empty($erow['visibility'])) {
                                                        if (in_array("homepage", $visib)) {
                                                            echo " checked";
                                                        }
                                                    }
                                                    ?>>Homepage
                                                
                                                <input name='visibility[]' type='checkbox' value='catalogue' <?php 
                                                    if (!empty($erow['visibility'])) {
                                                        if (in_array("catalogue", $visib)) {
                                                            echo " checked";
                                                        }
                                                    }
                                                    ?>>Product Catalogue
                                                <input name='visibility[]' type='checkbox' value='locations' <?php 
                                                    if (!empty($erow['visibility'])) {
                                                        if (in_array("locations", $visib)) {
                                                            echo " checked";
                                                        }
                                                    }
                                                    ?>>Locations

                                                <input name='visibility[]' type='checkbox' value='story' <?php 
                                                    if (!empty($erow['visibility'])) {
                                                        if (in_array("story", $visib)) {
                                                            echo " checked";
                                                        }
                                                    }
                                                    ?>>Our Story
                                                
                                                <input name='visibility[]' type='checkbox' value='culture' <?php 
                                                    if (!empty($erow['visibility'])) {
                                                        if (in_array("culture", $visib)) {
                                                            echo " checked";
                                                        }
                                                    }
                                                    ?>>Culture

                                                <input name='visibility[]' type='checkbox' value='design' <?php 
                                                    if (!empty($erow['visibility'])) {
                                                        if (in_array("design", $visib)) {
                                                            echo " checked";
                                                        }
                                                    }
                                                    ?>>Design
                                                <br/>
                                                <input name='visibility[]' type='checkbox' value='one' <?php 
                                                    if (!empty($erow['visibility'])) {
                                                        if (in_array("one", $visib)) {
                                                            echo " checked";
                                                        }
                                                    }
                                                    ?>>One for You, One for Them
                                                
                                                <input name='visibility[]' type='checkbox' value='blog' <?php 
                                                    if (!empty($erow['visibility'])) {
                                                        if (in_array("blog", $visib)) {
                                                            echo " checked";
                                                        }
                                                    }
                                                    ?>>Blog

                                                <input name='visibility[]' type='checkbox' value='hometry' <?php 
                                                    if (!empty($erow['visibility'])) {
                                                        if (in_array("hometry", $visib)) {
                                                            echo " checked";
                                                        }
                                                    }
                                                    ?>>Home Try-on
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="2">
                                            Min height*: <span class='setting-tooltips'>(for the advertisement to appear on each page)</span>

                                            <p id='nanError' style="display: none;">Please enter numbers only</p>
                                            <input type='text' name='minheight' id='minheight' maxlength="50" 
                                                   onkeypress="return isNumber(event)" 
                                                   value='<?php if (!empty($erow['minheight'])) { echo $erow['minheight']; } ?>'/>
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
                                            <p onclick="addButton()" class="addMore text-right">
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
                                                                            $pagesql = "Select * from pages where type='banner';";
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
                                                                    <select name='pageItem1'>
                                                                        <?php 
                                                                            $pagesql = "Select * from pages where type='banner';";
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
                                        <td>
                                            <input type='submit' name='submit' value='Save' />
                                        </td>
                                    </tr>
                                </table>
                            </form>
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
    
    var myCalendar = new dhtmlXCalendarObject(["date3"]);
            myCalendar.hideTime();
    var myCalendar2 = new dhtmlXCalendarObject(["date4"]);
            myCalendar2.hideTime();

    if (document.getElementById('checkYes').checked) {
       document.getElementById('expiryDate').style.display = "block";            
    }
    document.getElementById('checkYes').onclick = function(){  
       document.getElementById('expiryDate').style.display = "block";
    };

    document.getElementById('checkNo').onclick = function(){  
       document.getElementById('expiryDate').style.display = "none";
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
        var r = confirm("Are you sure you wish to delete this advertisement?");
        if (r === true) {
            window.location="processAdvertisements.php?delete=1&id=" + locId;
        } else if (r === false) {
            <?php
                unset($_SESSION['addAdvError']);
                unset($_SESSION['addAdvSuccess']);
                unset($_SESSION['updateAdvSuccess']);
                $_SESSION['updateAdvError'] = "Nothing was deleted";
            ?>
            window.location='advertisements.php';
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
                        $sql = "Select * from pages where type='banner';";
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
    
    $("#filter").keyup(function () {
        var search = $(this).val();
        $(".searchable").children().show();
        $('.noresults').remove();
        if (search) {
            $(".searchable").children().not(":containsNoCase(" + search + ")").hide();
            $(".searchable").each(function () {
                if ($(this).children(':visible').length === 0) 
                    $(this).append('<tr class="noresults"><td colspan="100%">No matching results found</td></tr>');
            });

        }
    });
    
    $.expr[":"].containsNoCase = function (el, i, m) {
        var search = m[3];
        if (!search) return false;
           return new RegExp(search,"i").test($(el).text());
    };
    
    $(document).ready(function() {
        $('#example').DataTable({
            dom: "<'row'tr>" +
                "<'row'<'col-sm-5'i><'col-sm-7'p>>"
        });
    });
</script>