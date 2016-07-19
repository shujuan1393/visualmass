<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
require_once '../config/db.php';

if (isset($_POST['submit'])) {
    $_SESSION['gender'] = $_POST['gender'];
    $_SESSION['categories'] = $_POST['categories'];
    
    unset($_SESSION['addProdBannerSuccess']);
    unset($_SESSION['addProdBannerError']);
    
    if (empty($_FILES['image']['name'])) { 
        $_SESSION['addProdBannerError'] = "No image selected";
    } else {
        $gender = $_POST['gender'];
        $categories = $_POST['categories'];
        
        $target_dir = "../uploads/banner/";
        $random_digit=md5(uniqid(rand(), true));
        $new_file = 'product_'.$random_digit.basename($_FILES["image"]["name"]);
        $target_file = $target_dir . $new_file;

        $imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);

        // Check if file already exists
        if (file_exists($target_file)) {
            unset($_SESSION['addProdBannerSuccess']);
            $_SESSION['addProdBannerError'] = "Sorry, file already exists.";
        }
        // Allow certain file formats
        
        if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
        && $imageFileType != "gif" && $imageFileType != "mp3" && $imageFileType != "mp4" 
                && $imageFileType != "wma" ) {
            unset($_SESSION['addProdBannerSuccess']);
            $_SESSION['addProdBannerError'] = "Sorry, only JPG, JPEG, PNG, GIF, MP3, MP4 & WMA files are allowed.";
        }
        
        // Check file size
        if ($_FILES["image"]["size"] > 5000000) {
            unset($_SESSION['addProdBannerSuccess']);
            $_SESSION['addProdBannerError'] = "Sorry, uploads cannot be greater than 5MB.";
        }
        
        if (!isset($_SESSION['addProdBannerError'])) {
            if (!move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
                unset($_SESSION['addProdBannerSuccess']);
                $_SESSION['addProdBannerError'] = "Sorry, there was an error uploading your file.";
            } else {
                unset($_SESSION['gender']);
                unset($_SESSION['categories']);
                unset($_SESSION['addProdBannerError']);
                
                $check = "Select * from productbanner where gender='$gender' "
                        . "and categories='$categories';";
                $cresult = mysqli_query($link, $check);
                
                if (!mysqli_query($link, $check)) {
                    echo "Error description: ". mysqli_error($link);
                    exit();
                } else {
                    $crow = mysqli_fetch_assoc($cresult);
                    
                    if ($cresult -> num_rows != 0) {
                        $prodBanner = "UPDATE productbanner SET image='$target_file' where gender='$gender' "
                        . "and categories='$categories';";
                    } else {
                        $prodBanner = "INSERT INTO productbanner (gender, categories, image) VALUES "
                                . "('$gender', '$categories', '$target_file');";
                    }
                    if (!empty($prodBanner)) {
                        mysqli_query($link, $prodBanner);
                        unset($_SESSION['addProdBannerError']);
                        $_SESSION['addProdBannerSuccess'] = "Banner updated successfully";
                    }
                }
            }
        }
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
                                Product Banners
                            </li>
                        </ol>
                        
                        <h1 class="page-header">Manage Product Banners</h1>
                        <div id='bannerPreview' style='display: none;'>
                            You have not uploaded a banner image yet.
                        </div>
                        <?php 
                            $getBanner = "Select * from productbanner";
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

                                        echo "<div class='prodbanners' id='".$brow['gender']."&".$brow['categories']."' style='display:none;'>";
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
        
                        <form id='addProdBanner' action='prodBanner.php' method='post' enctype="multipart/form-data">
                            <div id="addProdBannerError" class="error">
                                <?php 
                                    if (isset($_SESSION['addProdBannerError'])) {
                                        echo $_SESSION['addProdBannerError'];
                                    }
                                ?>
                            </div>

                            <div id="addProdBannerSuccess" class="success">
                                <?php 
                                    if (isset($_SESSION['addProdBannerSuccess'])) {
                                        echo $_SESSION['addProdBannerSuccess'];
                                    }
                                ?>
                            </div>
                            
                            <h1 id="add" class="page-header">Add/Edit Product Banner</h1>
                            <table class="content">
                                <tr>
                                    <td>
                                        <select id='gender' name='gender'>
                                            <option value='men' 
                                                    <?php if (isset($_SESSION['gender'])) { 
                                                            if (strcmp($_SESSION['gender'], "men") === 0) {
                                                                echo " selected";
                                                            }
                                                        }
                                                    ?>>Men</option>
                                            <option value='women'
                                                    <?php if (isset($_SESSION['gender'])) { 
                                                            if (strcmp($_SESSION['gender'], "women") === 0) {
                                                                echo " selected";
                                                            }
                                                        }
                                                    ?>>Women</option>
                                        </select>
                                    </td>
                                    <td>
                                        <select id='categories' name='categories'>
                                            <option value='frames' <?php if (isset($_SESSION['categories'])) { 
                                                            if (strcmp($_SESSION['categories'], "frames") === 0) {
                                                                echo " selected";
                                                            }
                                                        }
                                                    ?>>Glasses</option>
                                            <option value='sunglasses'<?php if (isset($_SESSION['categories'])) { 
                                                            if (strcmp($_SESSION['categories'], "sunglasses") === 0) {
                                                                echo " selected";
                                                            }
                                                        }
                                                    ?>>Sunglasses</option>
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan='2'>
                                        <span class='pull-left'>Image: </span>
                                            &nbsp;<input type="file" name="image" id='image' />
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan='2'>
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
    function hideElements() {
        var arr = document.getElementsByClassName('prodbanners');
        for (var i = 0; i < arr.length; i++) {
            arr[i].style.display = "none";
        }
    }
    (function() {
        // your page initialization code here
        // the DOM will be available here
        hideElements();
        var cat = document.getElementById('categories').value;
        var gender = document.getElementById('gender').value;
        var g = gender+"&"+cat;
        var obj = document.getElementById(g);
        if (obj === null) {
            document.getElementById('bannerPreview').style.display = "block";
        } else {
            obj.style.display = "block";
            document.getElementById('bannerPreview').style.display = "none";
        }
    })();

    document.getElementById('gender').onchange = function() {
        hideElements();
        var cat = document.getElementById('categories').value;
        var gender = this.value;
        var g = gender+"&"+cat;
        var obj = document.getElementById(g);
        if (obj === null) {
            document.getElementById('bannerPreview').style.display = "block";
        } else {
            obj.style.display = "block";
            document.getElementById('bannerPreview').style.display = "none";
        }
    };

    document.getElementById('categories').onchange = function() {
        hideElements();
        var gender = document.getElementById('gender').value;
        var cat = this.value;
        var g = gender+"&"+cat;
        var obj = document.getElementById(g);
        if (obj === null) {
            document.getElementById('bannerPreview').style.display = "block";
        } else {
            obj.style.display = "block";
            document.getElementById('bannerPreview').style.display = "none";
        }
    };
</script>
