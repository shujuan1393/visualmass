<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
require_once '../config/db.php';
require_once('../nav/adminHeader.php');

if (empty($_GET['delete']) && isset($_GET['id'])) {
    $editFaq = "Select * from contact where id ='". $_GET['id']."'";
    $editresult = mysqli_query($link, $editFaq);
    
    if (!mysqli_query($link,$editFaq))
    {
        echo("Error description: " . mysqli_error($link));
    } else {
        $editrow = mysqli_fetch_assoc($editresult);
    }
    
    unset($_SESSION['updateContactSuccess']);
    unset($_SESSION['updateContactError']);
    unset($_SESSION['addContactSuccess']);
    unset($_SESSION['addContactError']);
    unset($_SESSION['setContactDetailsError']);
    unset($_SESSION['setContactDetailsSuccess']);
} else if (isset($_GET['delete'])) {
    $deletesql = "DELETE FROM contact where id ='". $_GET['id']."'";
    if (mysqli_query($link, $deletesql)) {
        unset($_SESSION['updateContactError']);
        unset($_SESSION['addContactSuccess']);
        unset($_SESSION['addContactError']);
        unset($_SESSION['setContactDetailsError']);
        unset($_SESSION['setContactDetailsSuccess']);
        $_SESSION['updateContactSuccess'] = "Record deleted successfully";
    } 
} else if (isset($_GET['update'])) {
    $typeArr = array('dropdown', 'checkbox');
    if (empty($_POST['name']) || empty($_POST['type'])) {
        unset($_SESSION['updateContactError']);
        unset($_SESSION['addContactSuccess']);
        unset($_SESSION['updateContactSuccess']);
        unset($_SESSION['setContactDetailsError']);
        unset($_SESSION['setContactDetailsSuccess']);
        $_SESSION['addContactError'] = "Empty field(s)";
    } else if (in_array($_POST['type'], $typeArr) && empty($_POST['options'])) { 
        unset($_SESSION['updateContactError']);
        unset($_SESSION['addContactSuccess']);
        unset($_SESSION['updateContactSuccess']);
        unset($_SESSION['setContactDetailsError']);
        unset($_SESSION['setContactDetailsSuccess']);
        $_SESSION['addContactError'] = "Options required";        
    } else {
        $title = $_POST['name'];
        $type = $_POST['type'];
        $order = $_POST['order'];
        $html = $_POST['options'];

        $editid = $_POST['editid'];
        echo "here";
        exit();
        if (empty($editid)) {
            $contactSql = "INSERT INTO contact (title, html, fieldorder, type) VALUES "
                    . "('$title', '$html','$order', '$type');";
           
            unset($_SESSION['updateContactError']);
            unset($_SESSION['updateContactSuccess']);
            unset($_SESSION['addContactError']);
            unset($_SESSION['setContactDetailsError']);
            unset($_SESSION['setContactDetailsSuccess']);
            mysqli_query($link, $contactSql);
            $_SESSION['addContactSuccess'] = "Contact form field successfully added";
        } else {
            $contactSql = "UPDATE contact SET title='$title', html='$html', "
                . "type='$type', fieldorder='$order' where id = '$editid';";
            
            if (mysqli_query($link, $contactSql)) {
                unset($_SESSION['updateContactError']);
                unset($_SESSION['addContactSuccess']);
                unset($_SESSION['addContactError']);
                unset($_SESSION['setContactDetailsError']);
                unset($_SESSION['setContactDetailsSuccess']);
                $_SESSION['updateContactSuccess'] = "Record updated successfully";
            } else {
                echo "Error updating record: " . mysqli_error($link);
            }
        }
    }
} else if (isset($_POST['submit'])) {
    $target_file;
    if (empty($_POST['title']) || empty($_POST['html'])) {
        $_SESSION['setContactDetailsError'] = "Empty field(s)";
    } else if (!empty($_FILES['image']['name'])) {
        unset($_SESSION['setContactDetailsSuccess']);
        unset($_SESSION['addContactSuccess']);
        unset($_SESSION['addContactError']);
        unset($_SESSION['updateContactError']);
        
        $target_dir = "../uploads/banner/";
        $random_digit=md5(uniqid(rand(), true));
        $new_file = 'contact_'.$random_digit.basename($_FILES["image"]["name"]);
        $target_file = $target_dir . $new_file;
        $uploadOk = 1;

        $imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);

        // Check if image file is a actual image or fake image
        if(isset($_POST["submit"])) {
            $check = getimagesize($_FILES["image"]["tmp_name"]);
            if($check !== false) {
                $uploadOk = 1;
            } else {
                unset($_SESSION['setContactDetailsSuccess']);
                $_SESSION['setContactDetailsError'] = "File is not an image.";
//                header('Location: faq.php');
            }
        }
        // Check if file already exists
        if (file_exists($target_file)) {
            unset($_SESSION['setContactDetailsSuccess']);
            $_SESSION['setContactDetailsError'] = "Sorry, file already exists.";
//            header('Location: faq.php');
        }
        // Check file size
        if ($_FILES["image"]["size"] > 5000000) {
            unset($_SESSION['setContactDetailsSuccess']);
            $_SESSION['setContactDetailsSuccess'] = "Sorry, uploads cannot be greater than 5MB.";
        }
        // Allow certain file formats
        if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
        && $imageFileType != "gif" ) {
            unset($_SESSION['setContactDetailsSuccess']);
            $_SESSION['setContactDetailsError'] = "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
//            header('Location: faq.php');
        }
        if ($uploadOk === 1) {
            if (!move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
                unset($_SESSION['setContactDetailsSuccess']);
                $_SESSION['setContactDetailsError'] = "Sorry, there was an error uploading your file.";
//                header('Location: faq.php');   
            } 
        }
    } else {
        $target_file = $_POST['oldImage'];
    }
    
    if (!isset($_SESSION['setContactDetailsError'])) {
        $title = $_POST['title'];
        $html = htmlentities($_POST['html']);

        $check = "Select * from contact where type='general';";
        $cresult = mysqli_query($link, $check);

        if (!mysqli_query($link, $check)) {
            echo "Error description: ". mysqli_error($link);
            exit();
        } else {
            $crow = mysqli_fetch_assoc($cresult);

            if ($cresult -> num_rows != 0) {
                $contactDetails = "UPDATE contact SET title='$title', "
                        . "html='$html', image='$target_file' where type='general'";
            } else {
                $contactDetails = "INSERT INTO contact (title, html, image, type) VALUES "
                        . "('$title', '$html', '$target_file', 'general');";
            }
            
            if (!empty($contactDetails)) {
                unset($_SESSION['updateContactSuccess']);
                unset($_SESSION['addContactSuccess']);
                unset($_SESSION['addContactError']);
                unset($_SESSION['updateContactError']);
                unset($_SESSION['setContactDetailsError']);
                mysqli_query($link, $contactDetails);
                $_SESSION['setContactDetailsSuccess'] = "Details updated successfully";
//                        header("Location: faq.php");
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
        <h2>Contact Page</h2>
        <br>
        <?php 
            $getDetails = "Select * from contact where type='general';";
            $dresult = mysqli_query($link, $getDetails);
            $drow;
            if (!mysqli_query($link, $getDetails)) {
                echo "Error description: ". mysqli_error($link);
            } else {
                if ($dresult -> num_rows == 0 ) {
                    echo "You have not set your details yet.<br><br>";
                } else {
//                    unset($_SESSION['setContactDetailsSuccess']);
                    $drow = mysqli_fetch_assoc($dresult);
                    echo "<img src='".$drow['image']."' width=200>";
       
                }
            }
        ?>
        <form id='setContactDetails' action='contact.php' method='post' enctype="multipart/form-data">
            <fieldset >
            <div id="setContactDetailsError" class="error">
                <?php 
                    if (isset($_SESSION['setContactDetailsError'])) {
                        echo $_SESSION['setContactDetailsError'];
                    }
                ?>
            </div>
            
            <div id="setContactDetailsSuccess" class="success">
                <?php 
                    if (isset($_SESSION['setContactDetailsSuccess'])) {
                        echo $_SESSION['setContactDetailsSuccess'];
                    }
                ?>
            </div>
            <legend>Update Contact Page Details</legend>
            <input type='hidden' name='submitted' id='submitted' value='1'/>
            <label for='title' >Title*:</label>
            <input type='text' name='title' id='title' 
                   value="<?php if (isset($drow['title'])) { echo $drow['title']; } ?>"/>
            <br>
            <label for='image' >Image:</label>
            <input type="file" name="image" id='image' accept="image/*" />
            <input type="hidden" name="oldImage" value="<?php echo $drow['image']; ?>">
            <br>
            Content*: 
            <textarea name="html" rows='10'><?php if (isset($drow['html'])) { echo $drow['html']; } ?></textarea>
            <script type="text/javascript">
                CKEDITOR.replace('html');
            </script>
            <br>
            <input type='submit' name='submit' value='Submit' />
            </fieldset>
        </form>
        
        <h3>Contact Form Fields</h3>
        <?php 
            $qry = "Select * from contact where type <> 'general' ORDER BY fieldorder asc";
            
            $result = mysqli_query($link, $qry);

            if (!mysqli_query($link,$qry))
            {
                echo("Error description: " . mysqli_error($link));
            } else {
                $rowCount=0;
                if ($result->num_rows === 0) {
                    echo "You have not created any contact form fields yet.";
                } else {
            ?>
            <table>
                <thead>
                    <th>Order</th>
                    <th>Title</th>
                    <th>Type</th>
                    <th>Edit</th>
                    <th>Delete</th>                        
                </thead>
            <?php
                // output data of each row
                while ($row = mysqli_fetch_assoc($result)) {
                    $rowCount = $result -> num_rows;
                    echo "<tr>";
                    echo "<td>".$row['fieldorder'] ."</td>";  
                    echo "<td>".$row['title'] ."</td>";  
                    echo "<td>".$row['type'] ."</td>";                        
                    echo '<td><button onClick="window.location.href=`contact.php?id='.$row['id'].'`">E</button>';
                    echo '<td><button onClick="deleteFunction('.$row['id'].')">D</button></td>';
                    echo "</tr>";
                }
            ?>
            </table>
            <?php
                } 
            }
            ?>
            <div id="updateContactSuccess" class="success">
                <?php 
                    if (isset($_SESSION['updateContactSuccess'])) {
                        echo $_SESSION['updateContactSuccess'];
                    }
                ?>
            </div>
            <div id="updateContactError" class="error">
                <?php 
                    if (isset($_SESSION['updateContactError'])) {
                        echo $_SESSION['updateContactError'];
                    }
                ?>
            </div>
        <hr><br>
        <form id='addContactFormField' action='contact.php?update=1' method='post'>
            <fieldset >
            <div id="addContactError" class="error">
                <?php 
                    if (isset($_SESSION['addContactError'])) {
                        echo $_SESSION['addContactError'];
                    }
                ?>
            </div>
            
            <div id="addContactSuccess" class="success">
                <?php 
                    if (isset($_SESSION['addContactSuccess'])) {
                        echo $_SESSION['addContactSuccess'];
                    }
                ?>
            </div>
            <legend>Add/Edit Contact Form Field</legend>
            <input type="hidden" name="editid" id="editid" 
                   value="<?php if(isset($_GET['id'])) { echo $_GET['id']; } ?>"
            <input type='hidden' name='submitted' id='submitted' value='1'/>
            <label for='name' >Name*:</label>
            <input type='text' name='name' id='name' 
                   value="<?php if (isset($editrow['title'])) { echo $editrow['title']; } ?>"/>
            <label for='order' >Order*:</label>
            <input type='text' name='order' id='order' 
                   value="<?php if (isset($editrow['fieldorder'])) { echo $editrow['fieldorder']; } else { echo $rowCount+1; } ?>"/>
            <br>
            <label for='type' >Type*:</label>
            <select name='type'>
                <option value='textbox'
                        <?php
                            if (isset($editrow['type'])) {
                                if (strcmp($editrow['type'], "textbox") === 0) {
                                    echo " selected";
                                }
                            }
                        ?>
                        >Single-line Textbox</option>
                <option value='dropdown'
                        <?php
                            if (isset($editrow['type'])) {
                                if (strcmp($editrow['type'], "dropdown") === 0) {
                                    echo " selected";
                                }
                            }
                        ?>>Dropdown List</option>
                <option value='checkbox'
                        <?php
                            if (isset($editrow['type'])) {
                                if (strcmp($editrow['type'], "checkbox") === 0) {
                                    echo " selected";
                                }
                            }
                        ?>>Checkbox</option>
                <option value='textarea'
                        <?php
                            if (isset($editrow['type'])) {
                                if (strcmp($editrow['type'], "textarea") === 0) {
                                    echo " selected";
                                }
                            }
                        ?>>Multi-line Textbox</option>
            </select>
            <br>
            Options (only for dropdown & checkbox types): 
            <p class='setting-tooltips'>Please place a comma (,) after each option</p>
            <textarea name="options" cols='50' rows='10'><?php if (isset($editrow['html'])) { echo $editrow['html']; } ?></textarea>
            <br>
            <input type='submit' name='submit' value='Submit' />
            </fieldset>
        </form>
        </div>
    </div>
    <script>
        function deleteFunction(locId) {
            var r = confirm("Are you sure you wish to delete this form field?");
            if (r === true) {
                window.location="contact.php?delete=1&id=" + locId;
            } else if (r === false) {
                <?php
                    unset($_SESSION['addContactError']);
                    unset($_SESSION['addContactSuccess']);
                    unset($_SESSION['updateContactSuccess']);
                    unset($_SESSION['setContactDetailsSuccess']);
                    unset($_SESSION['setContactDetailsError']);
                    $_SESSION['updateContactError'] = "Nothing was deleted";
                ?>
                window.location='contact.php';
            }
        }
    </script>
</html>
