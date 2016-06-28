<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
require_once '../config/db.php';

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

        $order = $_POST['order'];
        $status = $_POST['status'];
        if (empty($editid)) {
            $faqSql = "INSERT INTO careers (title, html, type, fieldorder, status) VALUES "
                    . "('$title', '$html', 'section', '$order', '$status');";
            unset($_SESSION['updateCareerSuccess']);
            unset($_SESSION['updateCareerError']);
            unset($_SESSION['addCareerError']);
            unset($_SESSION['addCareerBannerError']);
            unset($_SESSION['addCareerBannerSuccess']);
            mysqli_query($link, $faqSql);
            $_SESSION['addCareerSuccess'] = "Career section successfully added";
        } else {
            $faqSql = "UPDATE careers SET title='$title', html='$html', "
                . "type='section', fieldorder ='$order', status='$status' where id = '$editid';";
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

        // Check if file already exists
        if (file_exists($target_file)) {
            unset($_SESSION['addCareerBannerSuccess']);
            $_SESSION['addCareerBannerError'] = "Sorry, file already exists.";
//            header('Location: faq.php');
        }
        
        // Allow certain file formats
        if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
        && $imageFileType != "gif" && $imageFileType != "mp3" && $imageFileType != "mp4" && $imageFileType != "wma" ) {
            unset($_SESSION['addCareerBannerSuccess']);
            $_SESSION['addCareerBannerError'] = "Sorry, only JPG, JPEG, PNG, GIF, MP3, MP4 & WMA files are allowed.";
            
        }
         // Check file size
        if ($_FILES["image"]["size"] > 5000000) {
            unset($_SESSION['addCareerBannerSuccess']);
            $_SESSION['addCareerBannerError'] = "Sorry, uploads cannot be greater than 5MB.";
        }
        
        if (!isset($_SESSION['addCareerBannerError'])) {
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
                                Career
                            </li>
                        </ol>

                        <ul class="nav nav-tabs">
                            <li class="active"><a data-toggle="tab" href="#careerb">Career Banner</a></li>
                            <li><a data-toggle="tab" href="#menu1">Career Sections</a></li>
                        </ul>

                        <div class="tab-content">
                            <div id="careerb" class="tab-pane fade in active">
                                <h1 class="page-header">Update Career Banner</h1>
                                <p>
                                    <form id='addCareerBanner' action='careers.php' method='post' enctype="multipart/form-data">
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
                                        Image:
                                        <input type="file" name="image" id='image'/>
                                        <br>
                                        <input type='submit' name='submit' value='Save' />
                                    </form>
                                </p>
                            </div>
                            <div id="menu1" class="tab-pane fade">
                                <h1 class="page-header">Manage Career Sections</h1>
                                
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
                                
                                <p>
                                    <?php 
                                        $qry = "Select * from careers where type <> 'banner' order by fieldorder asc";

                                        $result = mysqli_query($link, $qry);

                                        if (!mysqli_query($link,$qry))
                                        {
                                            echo("Error description: " . mysqli_error($link));
                                        } else {
                                            if ($result->num_rows === 0) {
                                                echo "You have not created any career sections yet.";
                                            } else {
                                    ?>
                                    
                                    <p class="text-right">
                                        <a href="#add"><i class="fa fa-fw fa-plus"></i> Add Section</a>
                                    </p>
                                    
                                    <table>
                                        <thead>
                                            <th>Order</th>
                                            <th>Title</th>
                                            <th>Status</th>
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
                                                echo "<td>".$row['status'] ."</td>";                      
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
                                
                                    <form id='addCareerSection' action='careers.php?update=1' method='post'>

                                        <div id="addCareerError" style="color:red">
                                            <?php 
                                                if (isset($_SESSION['addCareerError'])) {
                                                    echo $_SESSION['addCareerError'];
                                                }
                                            ?>
                                        </div>
                                        <p id='nanError' style="display: none;">Please enter numbers only</p>

                                        <div id="addCareerSuccess" style="color:green">
                                            <?php 
                                                if (isset($_SESSION['addCareerSuccess'])) {
                                                    echo $_SESSION['addCareerSuccess'];
                                                }
                                            ?>
                                        </div>
                                        
                                        <h1 id="add" class="page-header">Add/Edit Career Section</h1>
                                        
                                        <input type="hidden" name="editid" id="editid" 
                                               value="<?php if(isset($_GET['id'])) { echo $_GET['id']; } ?>"
                                        <input type='hidden' name='submitted' id='submitted' value='1'/>
                                        
                                        <table class="content">
                                            <tr>
                                                <td>
                                                    Title*:
                                                    <input type='text' name='title' id='title' 
                                                           value="<?php if (isset($editrow['title'])) { echo $editrow['title']; } ?>"/>
                                                </td>
                                                <td>
                                                    Order*:
                                                    <input type='text' name='order' id='order'  
                                                       onkeypress="return isNumber(event)" 
                                                           value="<?php 
                                                            if(!empty($editrow['fieldorder'])){
                                                                if (isset($editrow['fieldorder'])) { 
                                                                    echo $editrow['fieldorder']; 
                                                                } else { 
                                                                    echo $rowCount+1;
                                                                } 
                                                            } ?>"/>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="2">
                                                    Status*:
                                                    <select name='status'>
                                                        <option value='active' <?php 
                                                            if(isset($editrow['status'])) {
                                                                if (strcmp($editrow['status'], "active") === 0) {
                                                                    echo " selected";
                                                                }
                                                            }
                                                        ?>>Active</option>
                                                        <option value='inactive' <?php 
                                                            if(isset($editrow['status'])) {
                                                                if (strcmp($editrow['status'], "inactive") === 0) {
                                                                    echo " selected";
                                                                }
                                                            }
                                                        ?>>Inactive</option>
                                                    </select>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="2">
                                                    Content*: 
                                                    <textarea name="html"><?php if (isset($editrow['html'])) { echo $editrow['html']; } ?></textarea>
                                                    <script type="text/javascript">
                                                        CKEDITOR.replace('html');
                                                    </script>
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