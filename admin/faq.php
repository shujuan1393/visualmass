<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
require_once '../config/db.php';

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

        $order = $_POST['order'];
        
        if (empty($editid)) {
            $faqSql = "INSERT INTO faq (title, html, type, fieldorder) VALUES "
                    . "('$title', '$html', 'section', '$order');";
            unset($_SESSION['updateFaqSuccess']);
            unset($_SESSION['addFaqError']);
            unset($_SESSION['updateFaqError']);
            unset($_SESSION['addFaqBannerError']);
            unset($_SESSION['addFaqBannerSuccess']);
            mysqli_query($link, $faqSql);
            $_SESSION['addFaqSuccess'] = "FAQ section successfully added";
        } else {
            $faqSql = "UPDATE faq SET title='$title', html='$html', "
                . "type='section', fieldorder='$order' where id = '$editid';";
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

        // Check if file already exists
        if (file_exists($target_file)) {
            unset($_SESSION['addFaqBannerSuccess']);
            $_SESSION['addFaqBannerError'] = "Sorry, file already exists.";
//            header('Location: faq.php');
        }
        
        // Allow certain file formats
        if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
        && $imageFileType != "gif" && $imageFileType != "mp3" && $imageFileType != "mp4" 
                && $imageFileType != "wma" ) {
            unset($_SESSION['addFaqBannerSuccess']);
            $_SESSION['addFaqBannerError'] = "Sorry, only JPG, JPEG, PNG, GIF, MP3, MP4 & WMA files are allowed.";
//            header('Location: faq.php');
        }
        if (!isset($_SESSION['addFaqBannerError'])) {
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
                    $browArr = explode(".", $brow['html']);
                    $ext = $browArr[count($browArr)-1];
                    
                    $imgArr = array("jpg", "jpeg", "png", "gif");
                    $vidArr = array("mp3", "mp4", "wma");
                    
                    if (in_array($ext, $imgArr)) {
                        echo "<img src='".$brow['html']."' width=450>";
                    } else {
                        echo '<video width="500" height="400" controls>
                        <source src="'.$brow['html'].'" type="video/mp4">
                        Your browser does not support the video tag.
                        </video>';
                    }
                }
            }
        ?>
        <form id='addFaqBanner' action='faq.php' method='post' enctype="multipart/form-data">
            <fieldset >
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
            <legend>Update FAQ Banner</legend>
            <input type='hidden' name='submitted' id='submitted' value='1'/>
            <label for='image' >Image:</label>
            <input type="file" name="image" id='image' />
            <br>
            <input type='submit' name='submit' value='Submit' />
            </fieldset>
        </form>
        <h3>FAQ Sections</h3>
        <?php 
            $qry = "Select * from faq where type <> 'banner' order by fieldorder asc";
            
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
                    <th>Order</th>
                    <th>Title</th>
                    <th>Edit</th>
                    <th>Delete</th>                        
                </thead>
            <?php
                // output data of each row
            $rowCount = 0;
                while ($row = mysqli_fetch_assoc($result)) {
                    $rowCount++;
                    echo "<tr>";
                    echo "<td>".$row['fieldorder'] ."</td>";   
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
                
            <div id="addFaqError" style="color:red">
                <?php 
                    if (isset($_SESSION['addFaqError'])) {
                        echo $_SESSION['addFaqError'];
                    }
                ?>
            </div>
            <p id='nanError' style="display: none;">Please enter numbers only</p>
            
            <div id="addFaqSuccess" style="color:green">
                <?php 
                    if (isset($_SESSION['addFaqSuccess'])) {
                        echo $_SESSION['addFaqSuccess'];
                    }
                ?>
            </div>
            <legend>Add/Edit FAQ Section</legend>
            <input type="hidden" name="editid" id="editid" 
                   value="<?php if(isset($_GET['id'])) { echo $_GET['id']; } ?>"
            <input type='hidden' name='submitted' id='submitted' value='1'/>
            <label for='title' >Title*:</label>
            <input type='text' name='title' id='title' 
                   value="<?php if (isset($editrow['title'])) { echo $editrow['title']; } ?>"/>
            <br>
            <label for='order' >Order*:</label>
            <input type='text' name='order' id='order'  
               onkeypress="return isNumber(event)" 
                   value="<?php if (isset($editrow['fieldorder'])) { echo $editrow['fieldorder']; } else { echo $rowCount+1; } ?>"/>
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
