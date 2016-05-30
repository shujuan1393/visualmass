<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
require_once '../config/db.php';

if (isset($_POST['submit'])) {
    unset($_SESSION['addProdBannerSuccess']);
    unset($_SESSION['addProdBannerError']);
    
    if (!empty($_FILES['image']['name'])) {
        
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
        if (!isset($_SESSION['addProdBannerError'])) {
            if (!move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
                unset($_SESSION['addProdBannerSuccess']);
                $_SESSION['addProdBannerError'] = "Sorry, there was an error uploading your file.";
            } else {
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
        <h2>Banner for Products Page</h2>
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
                            echo "<img src='".$brow['image']."' width=450>";
                        } else {
                            echo '<video width="500" height="400" controls>
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
            <fieldset >
            <div id="addProdBannerError" style="color:red">
                <?php 
                    if (isset($_SESSION['addProdBannerError'])) {
                        echo $_SESSION['addProdBannerError'];
                    }
                ?>
            </div>
            
            <div id="addProdBannerSuccess" style="color:green">
                <?php 
                    if (isset($_SESSION['addProdBannerSuccess'])) {
                        echo $_SESSION['addProdBannerSuccess'];
                    }
                ?>
            </div>
            <legend>Update Product Banner</legend>
            <input type='hidden' name='submitted' id='submitted' value='1'/>
            <select id='gender' name='gender'>
                <option value='men'>Men</option>
                <option value='women'>Women</option>
            </select>
            <select id='categories' name='categories'>
                <option value='frames'>Glasses</option>
                <option value='sunglasses'>Sunglasses</option>
            </select>
            <label for='image' >Image:</label>
            <input type="file" name="image" id='image' />
            <br>
            <input type='submit' name='submit' value='Submit' />
            </fieldset>
        </form>
        </div>
    </div>
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
</html>
