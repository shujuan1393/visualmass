<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
require_once '../config/db.php';

if (empty($_GET['delete']) && isset($_GET['id']) && !isset($_SESSION['title']) && !isset($_SESSION['html']) && !isset($_SESSION['order'])) {
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
        
        header("Location: faq.php#menu1");
        
    } 
} else if (isset($_GET['update'])) {
    $_SESSION['title'] = $_POST['title'];
    $_SESSION['html'] = $_POST['html'];
    $_SESSION['order'] = $_POST['order'];
    
    if (empty($_POST['title']) || empty($_POST['html'])) {
        unset($_SESSION['updateFaqSuccess']);
        unset($_SESSION['addFaqSuccess']);
        unset($_SESSION['updateFaqError']);
        unset($_SESSION['addFaqBannerError']);
        unset($_SESSION['addFaqBannerSuccess']);
        $_SESSION['addFaqError'] = "Empty field(s)";   
        if (isset($_POST['editid'])) {
            header("Location: faq.php?id=".$_POST['editid']."#menu1");
        } else {
            header("Location: faq.php#menu1");
        }
    } else {
        unset($_SESSION['title']);
        unset($_SESSION['html']);
        unset($_SESSION['order']);
        
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
            $_SESSION['addFaqSuccess'] = "FAQ section successfully added";
        } else {
            $faqSql = "UPDATE faq SET title='$title', html='$html', "
                . "type='section', fieldorder='$order' where id = '$editid';";
            
            unset($_SESSION['addFaqSuccess']);
            unset($_SESSION['addFaqError']);
            unset($_SESSION['updateFaqError']);
            unset($_SESSION['addFaqBannerError']);
            unset($_SESSION['addFaqBannerSuccess']);
            $_SESSION['updateFaqSuccess'] = "Record updated successfully";
        }
        mysqli_query($link, $faqSql);

        header("Location: faq.php#menu1");
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
        
        // Check file size
        if ($_FILES["image"]["size"] > 5000000) {
            unset($_SESSION['addFaqBannerSuccess']);
            $_SESSION['addFaqBannerError'] = "Sorry, uploads cannot be greater than 5MB.";
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
                                FAQ
                            </li>
                        </ol>
        
                        <ul class="nav nav-tabs">
                            <li class="active"><a data-toggle="tab" href="#faqb">FAQ Banner</a></li>
                            <li><a data-toggle="tab" href="#menu1">FAQ Sections</a></li>
                        </ul>

                        <div class="tab-content">
                            <div id="faqb" class="tab-pane fade in active">
                                <h1 class="page-header">Update FAQ Banner</h1>
                                <p>
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
                                                    echo '<video width="500" height="400" autoplay>
                                                    <source src="'.$brow['html'].'" type="video/mp4">
                                                    Your browser does not support the video tag.
                                                    </video>';
                                                }
                                            }
                                        }
                                    ?>
                                    
                                    <form id='addFaqBanner' action='faq.php' method='post' enctype="multipart/form-data">
                            
                                        <div id="addFaqBannerError" class="error">
                                            <?php 
                                                if (isset($_SESSION['addFaqBannerError'])) {
                                                    echo $_SESSION['addFaqBannerError'];
                                                }
                                            ?>
                                        </div>

                                        <div id="addFaqBannerSuccess" class="success">
                                            <?php 
                                                if (isset($_SESSION['addFaqBannerSuccess'])) {
                                                    echo $_SESSION['addFaqBannerSuccess'];
                                                }
                                            ?>
                                        </div>

                                        <input type='hidden' name='submitted' id='submitted' value='1'/>

                                        Image:
                                        <input type="file" name="image" id='image' />
                                        <br/>
                                        <input type='submit' name='submit' value='Submit' />
                                    </form>
                                </p>
                            </div>
                            <div id="menu1" class="tab-pane fade">
                                <h1 class="page-header">Manage FAQ Sections</h1>
                                
                                <div id="updateFaqSuccess" class="success">
                                    <?php 
                                        if (isset($_SESSION['updateFaqSuccess'])) {
                                            echo $_SESSION['updateFaqSuccess'];
                                        }
                                    ?>
                                </div>
                                <div id="updateFaqError" class="error">
                                    <?php 
                                        if (isset($_SESSION['updateFaqError'])) {
                                            echo $_SESSION['updateFaqError'];
                                        }
                                    ?>
                                </div>
                                
                                <p>
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
                                    
                                    <p class="text-right">
                                        <a href="#add"><i class="fa fa-fw fa-plus"></i> Add Section</a>
                                    </p>
                                    
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
                                                echo '<td><button onClick="window.location.href=`faq.php?id='.$row['id'].'#menu1`">E</button>';
                                                echo '<td><button onClick="deleteFunction('.$row['id'].')">D</button></td>';
                                                echo "</tr>";
                                            }
                                        ?>
                                    </table>
                                    <?php
                                        } 
                                    }
                                    ?>

                                    <form id='addFaqSection' action='faq.php?update=1' method='post'>
                                        <div id="addFaqError" class="error">
                                            <?php 
                                                if (isset($_SESSION['addFaqError'])) {
                                                    echo $_SESSION['addFaqError'];
                                                }
                                            ?>
                                        </div>
                                        <p id='nanError' style="display: none;">Please enter numbers only</p>

                                        <div id="addFaqSuccess" class="success">
                                            <?php 
                                                if (isset($_SESSION['addFaqSuccess'])) {
                                                    echo $_SESSION['addFaqSuccess'];
                                                }
                                            ?>
                                        </div>

                                        <h1 id="add" class="page-header">Add/Edit FAQ Section</h1>

                                        <input type="hidden" name="editid" id="editid" 
                                               value="<?php if(isset($_GET['id'])) { echo $_GET['id']; } ?>"
                                        <input type='hidden' name='submitted' id='submitted' value='1'/>

                                        <table class="content">
                                            <tr>
                                                <td>
                                                    Title*:
                                                    <input type='text' name='title' id='title' 
                                                           value="<?php if(isset($_SESSION['title'])) {
                                                               echo $_SESSION['title'];
                                                           } else if (isset($editrow['title']) && !isset($_SESSION['title'])) { 
                                                               echo $editrow['title']; 
                                                           }  
                                                               ?>"/>
                                                </td>
                                                <td>
                                                    Order*:
                                                    <input type='text' name='order' id='order'  
                                                       onkeypress="return isNumber(event)" 
                                                           value="<?php 
                                                                if(isset($_SESSION['order'])) {
                                                                    echo $_SESSION['order'];
                                                                } else if(!empty($editrow['fieldorder'])){
                                                                    if (isset($editrow['fieldorder']) && !isset($_SESSION['order'])) { 
                                                                        echo $editrow['fieldorder']; 
                                                                    } else { 
                                                                        echo $rowCount+1; 
                                                                    } 
                                                                } ?>"/>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="2">
                                                    Content*: 
                                                    <textarea name="html"><?php if(isset($_SESSION['html'])) {
                                                        echo $_SESSION['html'];
                                                    } else if (isset($editrow['html']) && !isset($_SESSION['html'])) { 
                                                        echo $editrow['html']; 
                                                    }?></textarea>
                                                    <script type="text/javascript">
                                                        CKEDITOR.replace('html');
                                                    </script>
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
//                unset($_SESSION['addFaqError']);
//                unset($_SESSION['addFaqSuccess']);
//                unset($_SESSION['updateFaqSuccess']);
//                unset($_SESSION['addFaqBannerSuccess']);
//                unset($_SESSION['addFaqBannerError']);
//                $_SESSION['updateFaqError'] = "Nothing was deleted";
            ?>
            window.location='faq.php#menu1';
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
