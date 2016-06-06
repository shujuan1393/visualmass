<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
require_once '../config/db.php';

if (isset($_POST['submit'])) {
    if (!empty($_FILES['image']['name'])) {
        unset($_SESSION['addLocBannerSuccess']);
        unset($_SESSION['addLocBannerError']);
        
        $target_dir = "../uploads/banner/";
        $random_digit=md5(uniqid(rand(), true));
        $new_file = 'location_'.$random_digit.basename($_FILES["image"]["name"]);
        $target_file = $target_dir . $new_file;
        $uploadOk = 1;

        $imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);

        // Check if file already exists
        if (file_exists($target_file)) {
            unset($_SESSION['addLocBannerSuccess']);
            $_SESSION['addLocBannerError'] = "Sorry, file already exists.";
        }
        
        // Allow certain file formats
        if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
        && $imageFileType != "gif" && $imageFileType != "mp3" && $imageFileType != "mp4" 
                && $imageFileType != "wma" ) {
            unset($_SESSION['addLocBannerSuccess']);
            $_SESSION['addLocBannerError'] = "Sorry, only JPG, JPEG, PNG, GIF, MP3, MP4 & WMA files are allowed.";
        }
        
         // Check file size
        if ($_FILES["image"]["size"] > 5000000) {
            unset($_SESSION['addLocBannerSuccess']);
            $_SESSION['addLocBannerError'] = "Sorry, uploads cannot be greater than 5MB.";
        }
        
        if (!isset($_SESSION['addLocBannerError'])) {
            if (!move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
                unset($_SESSION['addLocBannerSuccess']);
                $_SESSION['addLocBannerError'] = "Sorry, there was an error uploading your file.";
            } else {
                unset($_SESSION['addLocBannerError']);
                
                $check = "Select * from locations where name='banner';";
                $cresult = mysqli_query($link, $check);
                
                if (!mysqli_query($link, $check)) {
                    echo "Error description: ". mysqli_error($link);
                    exit();
                } else {
                    $crow = mysqli_fetch_assoc($cresult);
                    
                    if ($cresult -> num_rows != 0) {
                        $locBanner = "UPDATE locations SET featured='$target_file' where name='banner'";
                    } else {
                        $locBanner = "INSERT INTO locations (name, featured) VALUES "
                                . "('banner', '$target_file');";
                    }
                    if (!empty($locBanner)) {
                        mysqli_query($link, $locBanner);
                        unset($_SESSION['addLocBannerError']);
                        $_SESSION['addLocBannerSuccess'] = "Banner updated successfully";
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
        <h2>Banner for Locations Page</h2>
        <?php 
            $getBanner = "Select * from locations where name='banner';";
            $bresult = mysqli_query($link, $getBanner);
            
            if (!mysqli_query($link, $getBanner)) {
                echo "Error description: ". mysqli_error($link);
            } else {
                if ($bresult -> num_rows == 0 ) {
                    echo "You have not uploaded a banner image yet.<br><br>";
                } else {
                    $brow = mysqli_fetch_assoc($bresult);
                    $browArr = explode(".", $brow['image']);
                    $ext = $browArr[count($browArr)-1];
                    
                    $imgArr = array("jpg", "jpeg", "png", "gif");
                    $vidArr = array("mp3", "mp4", "wma");
                    
                    if (in_array($ext, $imgArr)) {
                        echo "<img src='".$brow['image']."' width=450>";
                    } else {
                        echo '<video width="500" height="400" controls>
                        <source src="'.$brow['image'].'" type="video/mp4">
                        Your browser does not support the video tag.
                        </video>';
                    }
                }
            }
        ?>
        <form id='addLocBanner' action='locBanner.php' method='post' enctype="multipart/form-data">
            <fieldset >
            <div id="addLocBannerError" style="color:red">
                <?php 
                    if (isset($_SESSION['addLocBannerError'])) {
                        echo $_SESSION['addLocBannerError'];
                    }
                ?>
            </div>
            
            <div id="addLocBannerSuccess" style="color:green">
                <?php 
                    if (isset($_SESSION['addLocBannerSuccess'])) {
                        echo $_SESSION['addLocBannerSuccess'];
                    }
                ?>
            </div>
            <legend>Update Location Banner</legend>
            <input type='hidden' name='submitted' id='submitted' value='1'/>
            <label for='image' >Image:</label>
            <input type="file" name="image" id='image' />
            <br>
            <input type='submit' name='submit' value='Submit' />
            </fieldset>
        </form>
        </div>
    </div>
</html>
