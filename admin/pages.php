<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
require_once '../config/db.php';

if (!isset($_GET['delete']) && isset($_GET['pid'])) { 
    $sql = "Select * from pages where id ='".$_GET['pid']."';";
    $result = mysqli_query($link, $sql);
    
    $frow = mysqli_fetch_assoc($result);
    
} else if (!isset($_GET['delete']) && isset($_GET['id'])) { 
    $sql = "Select * from pages where id ='".$_GET['id']."';";
    $result = mysqli_query($link, $sql);
    
    $erow = mysqli_fetch_assoc($result);
} else if (isset($_GET['add'])) {
    if (empty($_POST['name'])) {
        unset($_SESSION['updatePageError']);
        unset($_SESSION['addPageSuccess']);
        unset($_SESSION['addPageError']);

        unset($_SESSION['updatePageSectionError']);
        unset($_SESSION['updatePageSectionSuccess']);
        unset($_SESSION['addPageSectionSuccess']);
        unset($_SESSION['addPageSectionError']);
        $_SESSION['addPageError'] = "Empty field(s)";
    } else {
        unset($_SESSION['addPageError']);
        if (empty($_POST['name'])) {
            $_SESSION['addPageError'] = "Empty field(s)";
        } else {
            $image;
            if (!empty($_FILES['image']['name'])) {
                unset($_SESSION['updatePageSectionSuccess']);
                unset($_SESSION['addPageSectionSuccess']);
                unset($_SESSION['addPageSectionError']);
                unset($_SESSION['updatePageSectionError']);

                $target_dir = "../uploads/banner/";
                $random_digit=md5(uniqid(rand(), true));
                $new_file = 'page_'.$random_digit.basename($_FILES["image"]["name"]);
                $target_file = $target_dir . $new_file;
                $uploadOk = 1;

                $imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);

                // Check if file already exists
                if (file_exists($target_file)) {
                    unset($_SESSION['addPageSuccess']);
                    $_SESSION['addPageError'] = "Sorry, file already exists.";
        //            header('Location: faq.php');
                }

                // Allow certain file formats
                if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
                && $imageFileType != "gif" && $imageFileType != "mp3" && $imageFileType != "mp4" 
                        && $imageFileType != "wma" ) {
                    unset($_SESSION['addPageSuccess']);
                    $_SESSION['addPageError'] = "Sorry, only JPG, JPEG, PNG, GIF, MP3, MP4 & WMA files are allowed.";
        //            header('Location: faq.php');
                }

                // Check file size
                if ($_FILES["image"]["size"] > 5000000) {
                    unset($_SESSION['addPageSuccess']);
                    $_SESSION['addPageError'] = "Sorry, uploads cannot be greater than 5MB.";
                }

                if (!isset($_SESSION['addPageError'])) {
                    if (!move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
                        unset($_SESSION['addPageSuccess']);
                        $_SESSION['addPageError'] = "Sorry, there was an error uploading your file.";
        //                header('Location: faq.php');   
                    } else {
                        unset($_SESSION['addPageError']);
                        $image = $target_file;
                    }
                }
            } else {
                $image = $_POST['oldBanner'];
            }
            
            $name = $_POST['name'];
            $status = $_POST['status'];

            if (!empty($_POST['editid'])) {
                //update all fields with edited form name;
                $formName = "Select * from pages where id='".$_POST['editid']."';";
                $res = mysqli_query($link, $formName);
                $row = mysqli_fetch_assoc($res);

                $sql = "UPDATE pages set title='$name', status='$status', image='$image', type='banner' where id='".$_POST['editid']."';";
                mysqli_query($link, $sql);

                unset($_SESSION['updatePageError']);
                unset($_SESSION['addPageSuccess']);
                unset($_SESSION['addPageError']);

                unset($_SESSION['updatePageSectionError']);
                unset($_SESSION['updatePageSectionSuccess']);
                unset($_SESSION['addPageSectionSuccess']);
                unset($_SESSION['addPageSectionError']);
                $_SESSION['updatePageSuccess'] = "Page updated successfully";
            } else {
                $sql = "INSERT INTO pages (title, status, image, type) VALUES ('$name', '$status', '$image', 'banner');";
                mysqli_query($link, $sql);

                unset($_SESSION['updatePageError']);
                unset($_SESSION['updatePageSuccess']);
                unset($_SESSION['addPageError']);

                unset($_SESSION['updatePageSectionError']);
                unset($_SESSION['updatePageSectionSuccess']);
                unset($_SESSION['addPageSectionSuccess']);
                unset($_SESSION['addPageSectionError']);
                $_SESSION['addPageSuccess'] = "Page added successfully";
            }
        }
    }
} else if (isset($_GET['delete']) && isset($_GET['pid'])) {
    $deletesql = "DELETE FROM pages where id ='". $_GET['pid']."'";
    if (mysqli_query($link, $deletesql)) {
        unset($_SESSION['updatePageError']);
        unset($_SESSION['addPageSuccess']);
        unset($_SESSION['addPageError']);
        unset($_SESSION['updatePageSectionError']);
        unset($_SESSION['updatePageSectionSuccess']);
        unset($_SESSION['addPageSectionSuccess']);
        unset($_SESSION['addPageSectionError']);
        $_SESSION['updatePageSuccess'] = "Page deleted successfully";
    } 
} else if (isset($_GET['delete']) && isset($_GET['id'])) {
    $deletesql = "DELETE FROM pages where id ='". $_GET['id']."'";
    if (mysqli_query($link, $deletesql)) {
        unset($_SESSION['updatePageError']);
        unset($_SESSION['addPageSuccess']);
        unset($_SESSION['addPageError']);
        unset($_SESSION['updatePageSectionError']);
        unset($_SESSION['updatePageSuccess']);
        unset($_SESSION['addPageSectionSuccess']);
        unset($_SESSION['addPageSectionError']);
        $_SESSION['updatePageSectionSuccess'] = "Page section deleted successfully";
        header("Location: pages.php#menu1");
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
                                Pages
                            </li>
                        </ol>
                        
                        <ul class="nav nav-tabs">
                            <li class="active"><a data-toggle="tab" href="#forms">Pages</a></li>
                            <li><a data-toggle="tab" href="#menu1">Page Sections</a></li>
                        </ul>

                        <div class="tab-content">
                            <div id="forms" class="tab-pane fade in active">
                                <h1 class="page-header">Manage Pages</h1>
                                
                                <div id="updatePageError" class="error">
                                    <?php 
                                        if (isset($_SESSION['updatePageError'])) {
                                            echo $_SESSION['updatePageError'];
                                        }
                                    ?>
                                </div>

                                <div id="updatePageSuccess" class="success">
                                    <?php 
                                        if (isset($_SESSION['updatePageSuccess'])) {
                                            echo $_SESSION['updatePageSuccess'];
                                        }
                                    ?>
                                </div>
                                
                                <p>
                                    <?php 
                                        $getDetails = "Select * from pages where type='banner';";
                                        $dresult = mysqli_query($link, $getDetails);
                                        $drow;
                                        if (!mysqli_query($link, $getDetails)) {
                                            echo "Error description: ". mysqli_error($link);
                                        } else {
                                            if ($dresult -> num_rows == 0 ) {
                                                echo "You have not created any pages yet.<br><br>";
                                            } else {
                                    ?>

                                    <p class="text-right">
                                        <a href="#addf"><i class="fa fa-fw fa-plus"></i> Add Page</a>
                                    </p>

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
                                                echo "<td>".$row['title'] ."</td>";    
                                                echo "<td>".$row['status'] ."</td>";                       
                                                echo '<td><button onClick="window.location.href=`pages.php?pid='.$row['id'].'`">E</button>';
                                                echo '<td><button onClick="deletePageFunction('.$row['id'].')">D</button></td>';
                                                echo "</tr>";
                                            }
                                        ?>
                                    </table>
                                    <?php 
                                            }
                                        }
                                    ?>

                                    <form id='addPages' action='pages.php?add=1' method='post' enctype="multipart/form-data">

                                        <div id="addPageError" class="error">
                                            <?php 
                                                if (isset($_SESSION['addPageError'])) {
                                                    echo $_SESSION['addPageError'];
                                                }
                                            ?>
                                        </div>

                                        <div id="addPageSuccess" class="success">
                                            <?php 
                                                if (isset($_SESSION['addPageSuccess'])) {
                                                    echo $_SESSION['addPageSuccess'];
                                                }
                                            ?>
                                        </div>

                                        <h1 id="addf" class="page-header">Add/Edit Pages</h1>

                                        <input type='hidden' name='submitted' id='submitted' value='1'/>
                                        <input type='hidden' name='editid' id='editid' value='<?php 
                                            if (!isset($_GET['delete']) && isset($_GET['pid'])) {
                                                echo $_GET['pid'];
                                            }
                                        ?>'/>

                                        <table class="content">
                                            <tr>
                                                <td>
                                                    Name*:
                                                    <input type='text' name='name' id='name' 
                                                           value="<?php if (isset($frow['title'])) { echo $frow['title']; } ?>"/>
                                                </td>
                                                <td>
                                                    Status:
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
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="2">
                                                    <?php 
                                                    if (isset($frow['image'])) {
                                                        $browArr = explode(".", $frow['image']);
                                                        $ext = $browArr[count($browArr)-1];

                                                        $imgArr = array("jpg", "jpeg", "png", "gif");
                                                        $vidArr = array("mp3", "mp4", "wma");

                                                        echo "<input type='hidden' name='oldBanner' value='".$frow['image']."'>";
                                                        if (in_array($ext, $imgArr)) {
                                                            echo "<img src='".$frow['image']."' width=450>";
                                                        } else {
                                                            echo '<video width="500" height="400" autoplay>
                                                            <source src="'.$frow['image'].'" type="video/mp4">
                                                            Your browser does not support the video tag.
                                                            </video>';
                                                        }
                                                        echo "<br>";
                                                    }      
                                                    ?>
                                                    Image:
                                                    <input type="file" name="image" id='image' />
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
                            <div id="menu1" class="tab-pane fade">
                                <h1 class="page-header">Manage Page Sections</h3>
                                <p>
                                    <?php 
                                        //get num of sections for each page
                                        $page = "Select * from pages where type='banner';";
                                        $pageres = mysqli_query($link, $page);
                                        
                                        if (!mysqli_query($link, $page)) {
                                            die(mysqli_error($link));
                                        } else {
                                            while ($row = mysqli_fetch_assoc($pageres)) {
                                                $getSections = "Select count(distinct id) as count from pages where pageid='".$row['id']."';";
                                                $secres = mysqli_query($link, $getSections);
                                                
                                                if (!mysqli_query($link, $getSections)) {
                                                    die(mysqli_error($link));
                                                } else {
                                                    if ($secres -> num_rows === 0) {
                                                        echo "<input type='hidden' id='".$row['id']."Value' value='0'>";
                                                    } else {
                                                        $r1 = mysqli_fetch_assoc($secres); 
                                                        echo "<input type='hidden' id='".$row['id']."Value' value='".$r1['count']."'>";
                                                    }
                                                }
                                            }
                                        }
                                        
                                        $qry = "Select * from pages where type='section' ORDER BY pageid asc";

                                        $result = mysqli_query($link, $qry);

                                        if (!mysqli_query($link,$qry))
                                        {
                                            echo("Error description: " . mysqli_error($link));
                                        } else {
                                            if ($result->num_rows === 0) {
                                                echo "You have not created any page sections yet.";
                                            } else {
                                    ?>

                                    <p class="text-right">
                                        <a href="#add"><i class="fa fa-fw fa-plus"></i> Add Page Section</a>
                                    </p>

                                    <table>
                                        <thead>
                                            <th>Order</th> 
                                            <th>Title</th> 
                                            <th>Page</th>
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
                                                echo "<td>";
                                                
                                                $getpage = "Select * from pages where id='".$row['pageid']."';";
                                                $pager = mysqli_query($link, $getpage);
                                                
                                                if (!mysqli_query($link, $getpage)) {
                                                    die(mysqli_error($link));
                                                } else {
                                                    $pagerow = mysqli_fetch_assoc($pager);
                                                    echo $pagerow['title'];
                                                }
                                                
                                                echo "</td>";                        
                                                echo '<td><button onClick="window.location.href=`pages.php?id='.$row['id'].'#menu1`">E</button>';
                                                echo '<td><button onClick="deleteFunction('.$row['id'].')">D</button></td>';
                                                echo "</tr>";
                                            }
                                        ?>
                                    </table>
                                    <?php
                                        } 
                                    }
                                    ?>

                                    <form id='addPageSection' action='processPages.php?update=1' method='post' enctype="multipart/form-data">

                                        <div id="addPageSectionError" class="error">
                                            <?php 
                                                if (isset($_SESSION['addPageSectionError'])) {
                                                    echo $_SESSION['addPageSectionError'];
                                                }

                                                if (isset($_SESSION['uploadPageSectionError'])) {
                                                    echo $_SESSION['uploadPageSectionError'];
                                                }
                                            ?>
                                        </div>

                                        <p id='nanError' style="display: none;">Please enter numbers only</p>
                                        <div id="addPageSectionSuccess" class="success">
                                            <?php 
                                                if (isset($_SESSION['addPageSectionSuccess'])) {
                                                    echo $_SESSION['addPageSectionSuccess'];
                                                }
                                            ?>
                                        </div>

                                        <h1 id="add" class="page-header">Add/Edit Page Section</h1>

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
                                                <td>
                                                    Page*:
                                                    <select name='pageid' id='pageid'>
                                                        <?php 
                                                            $pages = "Select * from pages where type='banner';";
                                                            $res = mysqli_query($link, $pages);
                                                            
                                                            if (!mysqli_query($link, $pages)) {
                                                                die(mysqli_error($link));
                                                            } else {
                                                                while ($row = mysqli_fetch_assoc($res)) {
                                                                    echo "<option value='".$row['id']."' ";
                                                                    if (isset($erow['pageid'])) {
                                                                        if (strcmp($erow['pageid'], $row['id']) === 0) {
                                                                            echo " selected";
                                                                        }
                                                                    }
                                                                    echo ">".$row['title'];
                                                                    echo "</option>";
                                                                }
                                                            }
                                                        ?>
                                                    </select>
                                                </td>
                                                <td>
                                                    Status*:
                                                    <select name='status'>
                                                        <option value='active' <?php 
                                                            if (isset($erow['status'])) {
                                                                if(strcmp($erow['status'], "active") === 0) {
                                                                    echo " selected";
                                                                }
                                                            }
                                                        ?>>Active</option>
                                                        <option value='inactive' <?php 
                                                            if (isset($erow['status'])) {
                                                                if(strcmp($erow['status'], "inactive") === 0) {
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
    var count = document.getElementById('buttonno').value;
    attachClick(count);
    
    function deletePageFunction(locId) {
        var r = confirm("Are you sure you wish to delete this page?");
        if (r === true) {
            window.location="pages.php?delete=1&pid=" + locId;
        } else if (r === false) {
            <?php
                unset($_SESSION['addPageError']);
                unset($_SESSION['addPageSuccess']);
                unset($_SESSION['updatePageSuccess']);
                unset($_SESSION['addPageSectionError']);
                unset($_SESSION['addPageSectionSuccess']);
                unset($_SESSION['updatePageSectionSuccess']);
                unset($_SESSION['updatePageSectionError']);
                $_SESSION['updatePageError'] = "Nothing was deleted";
            ?>
            window.location='pages.php';
        }
    }
    function deleteFunction(locId) {
        var r = confirm("Are you sure you wish to delete this section?");
        if (r === true) {
            window.location="pages.php?delete=1&id=" + locId;
        } else if (r === false) {
            <?php
                unset($_SESSION['addPageError']);
                unset($_SESSION['updatePageError']);
                unset($_SESSION['addPageSuccess']);
                unset($_SESSION['updatePageSuccess']);
                unset($_SESSION['addPageSectionError']);
                unset($_SESSION['addPageSectionSuccess']);
                unset($_SESSION['updatePageSectionSuccess']);
                $_SESSION['updatePageSectionError'] = "Nothing was deleted";
            ?>
            window.location='pages.php#menu1';
        }
    }

    document.getElementById('pageid').onchange = function() {
        var index = this.selectedIndex;
        var inputText = document.getElementById('pageid').children[index].value;
        var str = inputText + "Value";
        var count = document.getElementById(str).value;
        <?php
        if(!isset($_GET['id'])) {
        ?>
            document.getElementById('order').value = Number(count)+1;
        <?php 
        }
        ?>
    };

    window.onload = function() {
        var index = document.getElementById('pageid').selectedIndex;
        var inputText = document.getElementById('pageid').children[index].value;
        var str = inputText + "Value";
        var count = document.getElementById(str).value;
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
</script>
