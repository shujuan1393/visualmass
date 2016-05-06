<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
require_once '../config/db.php';
require_once('../nav/adminHeader.php');

if (empty($_GET['delete']) && isset($_GET['id'])) {
    $editFaq = "Select * from faq where id ='". $_GET['id']."'";
    $editresult = mysqli_query($link, $editFaq);
    
    if (!mysqli_query($link,$editFaq))
    {
        echo("Error description: " . mysqli_error($link));
    } else {
        $editrow = mysqli_fetch_assoc($editresult);
    }
    
    unset($_SESSION['updateFaqSuccess']);
    unset($_SESSION['updateFaqError']);
    unset($_SESSION['addFaqSuccess']);
    unset($_SESSION['addFaqError']);
    unset($_SESSION['addFaqBannerError']);
    unset($_SESSION['addFaqBannerSuccess']);
} else if (isset($_GET['delete'])) {
    $deletesql = "DELETE FROM faq where id ='". $_GET['id']."'";
    if (mysqli_query($link, $deletesql)) {
        unset($_SESSION['updateFaqError']);
        unset($_SESSION['addFaqSuccess']);
        unset($_SESSION['addFaqError']);
        unset($_SESSION['addFaqBannerError']);
        unset($_SESSION['addFaqBannerSuccess']);
        $_SESSION['updateFaqSuccess'] = "Record deleted successfully";
    } 
} else if (isset($_GET['update'])) {
    if (empty($_POST['title']) || empty($_POST['html'])) {
        unset($_SESSION['updateFaqSuccess']);
        unset($_SESSION['addFaqSuccess']);
        unset($_SESSION['updateFaqError']);
        unset($_SESSION['addFaqBannerError']);
        unset($_SESSION['addFaqBannerSuccess']);
        $_SESSION['addFaqError'] = "Empty field(s)";        
    } else {
        $title = $_POST['title'];
        $html = htmlentities($_POST['html']);

        $editid = $_POST['editid'];

        if (empty($editid)) {
            $faqSql = "INSERT INTO faq (title, html, type) VALUES "
                    . "('$title', '$html', 'section');";
            unset($_SESSION['updateFaqSuccess']);
            unset($_SESSION['addFaqError']);
            unset($_SESSION['updateFaqError']);
            unset($_SESSION['addFaqBannerError']);
            unset($_SESSION['addFaqBannerSuccess']);
            mysqli_query($link, $faqSql);
            $_SESSION['addFaqSuccess'] = "FAQ section successfully added";
        } else {
            $faqSql = "UPDATE faq SET title='$title', html='$html', "
                . "type='section' where id = '$editid';";
            if (mysqli_query($link, $faqSql)) {
                unset($_SESSION['addFaqSuccess']);
                unset($_SESSION['addFaqError']);
                unset($_SESSION['updateFaqError']);
                unset($_SESSION['addFaqBannerError']);
                unset($_SESSION['addFaqBannerSuccess']);
                $_SESSION['updateFaqSuccess'] = "Record updated successfully";
            } else {
                echo "Error updating record: " . mysqli_error($link);
            }
        }
    }
} else if (isset($_POST['submit'])) {
    if (!empty($_FILES['image']['name'])) {
        unset($_SESSION['updateFaqSuccess']);
        unset($_SESSION['addFaqSuccess']);
        unset($_SESSION['addFaqError']);
        unset($_SESSION['updateFaqError']);
        
        $target_dir = "../uploads/banner/";
        $random_digit=md5(uniqid(rand(), true));
        $new_file = 'faq_'.$random_digit.basename($_FILES["image"]["name"]);
        $target_file = $target_dir . $new_file;
        $uploadOk = 1;

        $imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);

        // Check if image file is a actual image or fake image
        if(isset($_POST["submit"])) {
            $check = getimagesize($_FILES["image"]["tmp_name"]);
            if($check !== false) {
                $uploadOk = 1;
            } else {
                unset($_SESSION['addFaqBannerSuccess']);
                $_SESSION['addFaqBannerError'] = "File is not an image.";
//                header('Location: faq.php');
            }
        }
        // Check if file already exists
        if (file_exists($target_file)) {
            unset($_SESSION['addFaqBannerSuccess']);
            $_SESSION['addFaqBannerError'] = "Sorry, file already exists.";
//            header('Location: faq.php');
        }
        // Check file size
        if ($_FILES["image"]["size"] > 500000) {
            unset($_SESSION['addFaqBannerSuccess']);
            $_SESSION['addFaqBannerError'] = "Sorry, your file is too large.";
//            header('Location: faq.php');
        }
        // Allow certain file formats
        if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
        && $imageFileType != "gif" ) {
            unset($_SESSION['addFaqBannerSuccess']);
            $_SESSION['addFaqBannerError'] = "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
//            header('Location: faq.php');
        }
        if ($uploadOk === 1) {
            if (!move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
                unset($_SESSION['addFaqBannerSuccess']);
                $_SESSION['addFaqBannerError'] = "Sorry, there was an error uploading your file.";
//                header('Location: faq.php');   
            } else {
                unset($_SESSION['addFaqBannerError']);
                
                $check = "Select * from faq where type='banner';";
                $cresult = mysqli_query($link, $check);
                
                if (!mysqli_query($link, $check)) {
                    echo "Error description: ". mysqli_error($link);
                    exit();
                } else {
                    $crow = mysqli_fetch_assoc($cresult);
                    
                    if ($cresult -> num_rows != 0) {
                        $faqBanner = "UPDATE faq SET html='$target_file' where type='banner'";
                    } else {
                        $faqBanner = "INSERT INTO faq (title, html, type) VALUES "
                                . "('banner', '$target_file', 'banner');";
                    }
                    if (!empty($faqBanner)) {
                        unset($_SESSION['updateFaqSuccess']);
                        unset($_SESSION['addFaqSuccess']);
                        unset($_SESSION['addFaqError']);
                        unset($_SESSION['updateFaqError']);
                        mysqli_query($link, $faqBanner);
                        unset($_SESSION['addFaqBannerError']);
                        $_SESSION['addFaqBannerSuccess'] = "Banner updated successfully";
//                        header("Location: faq.php");
                    }
                }
            }
        }
    } 
} 
?>

<html>  
    <div id="framecontent">
        <div class='innertube'>
        <?php
            require '../nav/adminSidebar.php';
        ?>
        </div>
    </div>
    <div id="maincontent">
        <div class="innertube">
        <h2>FAQs</h2>
        <br>
        <?php 
            $getBanner = "Select * from faq where type='banner';";
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
        <form id='addFaqBanner' action='faq.php' method='post' enctype="multipart/form-data">
            <fieldset >
            <legend>Update FAQ Banner</legend>
            <input type='hidden' name='submitted' id='submitted' value='1'/>
            <label for='image' >Image:</label>
            <input type="file" name="image" id='image' accept="image/*" />
            <br>
            <input type='submit' name='submit' value='Submit' />
            <div id="addFaqBannerError" style="color:red">
                <?php 
                    if (isset($_SESSION['addFaqBannerError'])) {
                        echo $_SESSION['addFaqBannerError'];
                    }
                ?>
            </div>
            
            <div id="addFaqBannerSuccess" style="color:green">
                <?php 
                    if (isset($_SESSION['addFaqBannerSuccess'])) {
                        echo $_SESSION['addFaqBannerSuccess'];
                    }
                ?>
            </div>
            </fieldset>
        </form>
        <h3>FAQ Sections</h3>
        <?php 
            $qry = "Select * from faq where type <> 'banner'";
            
            $result = mysqli_query($link, $qry);

            if (!mysqli_query($link,$qry))
            {
                echo("Error description: " . mysqli_error($link));
            } else {
                if ($result->num_rows === 0) {
                    echo "You have not created any FAQs yet.";
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
                    echo '<td><button onClick="window.location.href=`faq.php?id='.$row['id'].'`">E</button>';
                    echo '<td><button onClick="deleteFunction('.$row['id'].')">D</button></td>';
                    echo "</tr>";
                }
            ?>
            </table>
            <?php
                } 
            }
            ?>
            <div id="updateFaqSuccess" style="color:green">
                <?php 
                    if (isset($_SESSION['updateFaqSuccess'])) {
                        echo $_SESSION['updateFaqSuccess'];
                    }
                ?>
            </div>
            <div id="updateFaqError" style="color:red">
                <?php 
                    if (isset($_SESSION['updateFaqError'])) {
                        echo $_SESSION['updateFaqError'];
                    }
                ?>
            </div>
        <hr><br>
        <form id='addFaqSection' action='faq.php?update=1' method='post'>
            <fieldset >
            <legend>Add/Edit FAQ Section</legend>
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
            <div id="addFaqError" style="color:red">
                <?php 
                    if (isset($_SESSION['addFaqError'])) {
                        echo $_SESSION['addFaqError'];
                    }
                ?>
            </div>
            
            <div id="addFaqSuccess" style="color:green">
                <?php 
                    if (isset($_SESSION['addFaqSuccess'])) {
                        echo $_SESSION['addFaqSuccess'];
                    }
                ?>
            </div>
            </fieldset>
        </form>
        </div>
    </div>
    <script>
        function deleteFunction(locId) {
            var r = confirm("Are you sure you wish to delete this FAQ section?");
            if (r === true) {
                window.location="faq.php?delete=1&id=" + locId;
            } else if (r === false) {
                <?php
                    unset($_SESSION['addFaqError']);
                    unset($_SESSION['addFaqSuccess']);
                    unset($_SESSION['updateFaqSuccess']);
                    unset($_SESSION['addFaqBannerSuccess']);
                    unset($_SESSION['addFaqBannerError']);
                    $_SESSION['updateFaqError'] = "Nothing was deleted";
                ?>
                window.location='faq.php';
            }
        }
    </script>
</html>
