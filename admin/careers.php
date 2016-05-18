<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
require_once '../config/db.php';
require_once('../nav/adminHeader.php');

if (empty($_GET['delete']) && isset($_GET['id'])) {
    $editCareer = "Select * from careers where id ='". $_GET['id']."'";
    $editresult = mysqli_query($link, $editCareer);
    
    if (!mysqli_query($link,$editCareer))
    {
        echo("Error description: " . mysqli_error($link));
    } else {
        $editrow = mysqli_fetch_assoc($editresult);
    }
    
    unset($_SESSION['updateCareerSuccess']);
    unset($_SESSION['updateCareerError']);
    unset($_SESSION['addCareerSuccess']);
    unset($_SESSION['addCareerError']);
    unset($_SESSION['addCareerBannerError']);
    unset($_SESSION['addCareerBannerSuccess']);
} else if (isset($_GET['delete'])) {
    $deletesql = "DELETE FROM careers where id ='". $_GET['id']."'";
    if (mysqli_query($link, $deletesql)) {
        unset($_SESSION['updateCareerError']);
        unset($_SESSION['addCareerSuccess']);
        unset($_SESSION['addCareerError']);
        unset($_SESSION['addCareerBannerError']);
        unset($_SESSION['addCareerBannerSuccess']);
        $_SESSION['updateCareerSuccess'] = "Record deleted successfully";
    } 
} else if (isset($_GET['update'])) {
    if (empty($_POST['title']) || empty($_POST['html'])) {
        unset($_SESSION['updateCareerSuccess']);
        unset($_SESSION['updateCareerError']);
        unset($_SESSION['addCareerSuccess']);
        unset($_SESSION['addCareerBannerError']);
        unset($_SESSION['addCareerBannerSuccess']);
        $_SESSION['addCareerError'] = "Empty field(s)";        
    } else {
        $title = $_POST['title'];
        $html = htmlentities($_POST['html']);

        $editid = $_POST['editid'];

        if (empty($editid)) {
            $faqSql = "INSERT INTO careers (title, html, type) VALUES "
                    . "('$title', '$html', 'section');";
            unset($_SESSION['updateCareerSuccess']);
            unset($_SESSION['updateCareerError']);
            unset($_SESSION['addCareerError']);
            unset($_SESSION['addCareerBannerError']);
            unset($_SESSION['addCareerBannerSuccess']);
            mysqli_query($link, $faqSql);
            $_SESSION['addCareerError'] = "Career section successfully added";
        } else {
            $faqSql = "UPDATE careers SET title='$title', html='$html', "
                . "type='section' where id = '$editid';";
            if (mysqli_query($link, $faqSql)) {
                unset($_SESSION['updateCareerError']);
                unset($_SESSION['addCareerSuccess']);
                unset($_SESSION['addCareerError']);
                unset($_SESSION['addCareerBannerError']);
                unset($_SESSION['addCareerBannerSuccess']);
                $_SESSION['updateCareerSuccess'] = "Record updated successfully";
            } else {
                echo "Error updating record: " . mysqli_error($link);
            }
        }
    }
} else if (isset($_POST['submit'])) {
    if (!empty($_FILES['image']['name'])) {
        unset($_SESSION['updateCareerSuccess']);
        unset($_SESSION['updateCareerError']);
        unset($_SESSION['addCareerSuccess']);
        unset($_SESSION['addCareerError']);
        unset($_SESSION['addCareerBannerError']);
        unset($_SESSION['addCareerBannerSuccess']);
        
        $target_dir = "../uploads/banner/";
        $random_digit=md5(uniqid(rand(), true));
        $new_file = 'career_'.$random_digit.basename($_FILES["image"]["name"]);
        $target_file = $target_dir . $new_file;
        $uploadOk = 1;

        $imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);

        // Check if image file is a actual image or fake image
        if(isset($_POST["submit"])) {
            $check = getimagesize($_FILES["image"]["tmp_name"]);
            if($check !== false) {
                $uploadOk = 1;
            } else {
                unset($_SESSION['addCareerBannerSuccess']);
                $_SESSION['addCareerBannerError'] = "File is not an image.";
//                header('Location: faq.php');
            }
        }
        // Check if file already exists
        if (file_exists($target_file)) {
            unset($_SESSION['addCareerBannerSuccess']);
            $_SESSION['addCareerBannerError'] = "Sorry, file already exists.";
//            header('Location: faq.php');
        }
        // Check file size
        if ($_FILES["image"]["size"] > 500000) {
            unset($_SESSION['addCareerBannerSuccess']);
            $_SESSION['addCareerBannerError'] = "Sorry, your file is too large.";
//            header('Location: faq.php');
        }
        // Allow certain file formats
        if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
        && $imageFileType != "gif" ) {
            unset($_SESSION['addCareerBannerSuccess']);
            $_SESSION['addCareerBannerError'] = "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
//            header('Location: faq.php');
        }
        if ($uploadOk === 1) {
            if (!move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
                unset($_SESSION['addCareerBannerSuccess']);
                $_SESSION['addCareerBannerError'] = "Sorry, there was an error uploading your file.";
//                header('Location: faq.php');   
            } else {
                unset($_SESSION['addCareerBannerError']);
                
                $check = "Select * from careers where type='banner';";
                $cresult = mysqli_query($link, $check);
                
                if (!mysqli_query($link, $check)) {
                    echo "Error description: ". mysqli_error($link);
                    exit();
                } else {
                    $crow = mysqli_fetch_assoc($cresult);
                    
                    if ($cresult -> num_rows != 0) {
                        $faqBanner = "UPDATE careers SET html='$target_file' where type='banner'";
                    } else {
                        $faqBanner = "INSERT INTO careers (title, html, type) VALUES "
                                . "('banner', '$target_file', 'banner');";
                    }
                    if (!empty($faqBanner)) {
                        unset($_SESSION['updateCareerSuccess']);
                        unset($_SESSION['updateCareerError']);
                        unset($_SESSION['addCareerSuccess']);
                        unset($_SESSION['addCareerError']);
                        unset($_SESSION['addCareerBannerError']);
                        unset($_SESSION['addCareerBannerSuccess']);
                        mysqli_query($link, $faqBanner);
                        $_SESSION['addCareerBannerSuccess'] = "Banner updated successfully";
//                        header("Location: faq.php");
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
        <div class='innertube'>
        <?php
            require '../nav/adminSidebar.php';
        ?>
        </div>
    </div>
    <div id="maincontent">
        <div class="innertube">
        <h2>Careers</h2>
        <br>
        <?php 
            $getBanner = "Select * from careers where type='banner';";
            $bresult = mysqli_query($link, $getBanner);
            
            if (!mysqli_query($link, $getBanner)) {
                echo "Error description: ". mysqli_error($link);
            } else {
                if ($bresult -> num_rows == 0 ) {
                    echo "You have not uploaded a banner image yet.<br><br>";
                } else {
                    $brow = mysqli_fetch_assoc($bresult);
                    echo "<img src='".$brow['html']."' width=200>";
                }
            }
        ?>
        <form id='addCareerBanner' action='careers.php' method='post' enctype="multipart/form-data">
            <fieldset >
            <legend>Update Career Banner</legend>
            <div id="addCareerBannerError" style="color:red">
                <?php 
                    if (isset($_SESSION['addCareerBannerError'])) {
                        echo $_SESSION['addCareerBannerError'];
                    }
                ?>
            </div>
            
            <div id="addCareerBannerSuccess" style="color:green">
                <?php 
                    if (isset($_SESSION['addCareerBannerSuccess'])) {
                        echo $_SESSION['addCareerBannerSuccess'];
                    }
                ?>
            </div>
            <input type='hidden' name='submitted' id='submitted' value='1'/>
            <label for='image' >Image:</label>
            <input type="file" name="image" id='image' accept="image/*" />
            <br>
            <input type='submit' name='submit' value='Submit' />
            </fieldset>
        </form>
        <h3>Careers Sections</h3>
        <?php 
            $qry = "Select * from careers where type <> 'banner'";
            
            $result = mysqli_query($link, $qry);

            if (!mysqli_query($link,$qry))
            {
                echo("Error description: " . mysqli_error($link));
            } else {
                if ($result->num_rows === 0) {
                    echo "You have not created any career sections yet.";
                } else {
            ?>
            <table>
                <thead>
                    <th>Title</th>
                    <th>Edit</th>
                    <th>Delete</th>                        
                </thead>
            <?php
                // output data of each row
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<tr>";
                    echo "<td>".$row['title'] ."</td>";                        
                    echo '<td><button onClick="window.location.href=`careers.php?id='.$row['id'].'`">E</button>';
                    echo '<td><button onClick="deleteFunction('.$row['id'].')">D</button></td>';
                    echo "</tr>";
                }
            ?>
            </table>
            <?php
                } 
            }
            ?>
            <div id="updateCareerSuccess" style="color:green">
                <?php 
                    if (isset($_SESSION['updateCareerSuccess'])) {
                        echo $_SESSION['updateCareerSuccess'];
                    }
                ?>
            </div>
            <div id="updateCareerError" style="color:red">
                <?php 
                    if (isset($_SESSION['updateCareerError'])) {
                        echo $_SESSION['updateCareerError'];
                    }
                ?>
            </div>
        <hr><br>
        <form id='addCareerSection' action='careers.php?update=1' method='post'>
            <fieldset >
                
            <div id="addCareerError" style="color:red">
                <?php 
                    if (isset($_SESSION['addCareerError'])) {
                        echo $_SESSION['addCareerError'];
                    }
                ?>
            </div>
            
            <div id="addCareerSuccess" style="color:green">
                <?php 
                    if (isset($_SESSION['addCareerSuccess'])) {
                        echo $_SESSION['addCareerSuccess'];
                    }
                ?>
            </div>
            <legend>Add/Edit Career Section</legend>
            <input type="hidden" name="editid" id="editid" 
                   value="<?php if(isset($_GET['id'])) { echo $_GET['id']; } ?>"
            <input type='hidden' name='submitted' id='submitted' value='1'/>
            <label for='title' >Title*:</label>
            <input type='text' name='title' id='title' 
                   value="<?php if (isset($editrow['title'])) { echo $editrow['title']; } ?>"/>
            <br>
            Content*: 
            <textarea name="html"><?php if (isset($editrow['html'])) { echo $editrow['html']; } ?></textarea>
            <script type="text/javascript">
                CKEDITOR.replace('html');
            </script>
            <br>
            <input type='submit' name='submit' value='Submit' />
            </fieldset>
        </form>
        </div>
    </div>
    <script>
        function deleteFunction(locId) {
            var r = confirm("Are you sure you wish to delete this career section?");
            if (r === true) {
                window.location="careers.php?delete=1&id=" + locId;
            } else if (r === false) {
                <?php
                    unset($_SESSION['addCareerError']);
                    unset($_SESSION['addCareerSuccess']);
                    unset($_SESSION['updateCareerSuccess']);
                    unset($_SESSION['addCareerBannerSuccess']);
                    unset($_SESSION['addCareerBannerError']);
                    $_SESSION['updateCareerError'] = "Nothing was deleted";
                ?>
                window.location='careers.php';
            }
        }
    </script>
</html>
