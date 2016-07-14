<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
require_once '../config/db.php';
$target_dir = "../uploads/blog/";

if (isset($_GET['delete'])) {
    $deletesql = "DELETE FROM blog where id ='". $_GET['id']."'";
    if (mysqli_query($link, $deletesql)) {
        unset($_SESSION['updateBlogError']);
        unset($_SESSION['addBlogSuccess']);
        unset($_SESSION['addBlogError']);
        $_SESSION['updateBlogSuccess'] = "Record deleted successfully";
        header("Location: blog.php");
    } 
} else if (isset($_POST['submit'])) {
    $visibility = $_POST['visibility'];
    
    if (strcmp($visibility, "inactive") === 0 && (empty($_POST['date4']) || empty($_POST['scheduledtime']))) {
        unset($_SESSION['addBlogSuccess']);
        unset($_SESSION['updateBlogError']);
        unset($_SESSION['updateBlogSuccess']);
        $_SESSION['addBlogError'] = "Date/time not selected for scheduled post";
        if (!empty($_POST['editid'])) {
            header('Location: blog.php?id='.$_POST['editid']);
        } else {
            header('Location: blog.php');
        }
    } else if(empty($_POST['title']) || empty($_POST['html']) || 
            (strcmp($_POST['addNewAuthor'], "yes") === 0 && (empty($_POST['firstname']) 
                    || empty($_POST['lastname']) || empty($_POST['email']) || empty($_POST['phone'])) )) {
        unset($_SESSION['addBlogSuccess']);
        unset($_SESSION['updateBlogError']);
        unset($_SESSION['updateBlogSuccess']);
        $_SESSION['addBlogError'] = "Empty field(s)";
        if (!empty($_POST['editid'])) {
            header('Location: blog.php?id='.$_POST['editid']);
        } else {
            header('Location: blog.php');
        }
    } else if (strcmp($_POST['addNewAuthor'], "yes") === 0 
            && !filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) { 
        unset($_SESSION['addBlogSuccess']);
        unset($_SESSION['updateBlogError']);
        unset($_SESSION['updateBlogSuccess']);
        $_SESSION['addBlogError'] = "Invalid email address";
        if (!empty($_POST['editid'])) {
            header('Location: blog.php?id='.$_POST['editid']);
        } else {
            header('Location: blog.php');
        }
    } else {
        unset($_SESSION['addBlogError']);
        unset($_SESSION['updateBlogError']);
        unset($_SESSION['updateBlogSuccess']);
        
        $image = "";
        $title = $_POST['title'];
        $excerpt = htmlentities($_POST['excerpt']);
        $html = htmlentities($_POST['html']);
        $date = $_POST['date3'];
        
        $scheduledate = $_POST['date4'];
        $scheduletime = $_POST['scheduledtime'];
        $schedule = date('Y-m-d G:i:s', strtotime($scheduledate." ".$scheduletime));
        
        $author;
        
        if (strcmp($_POST['addNewAuthor'], "yes") === 0) {
            $firstname = $_POST['firstname'];
            $lastname = $_POST['lastname'];
            $email = $_POST['email'];
            $phone = $_POST['phone'];
            $date = date('Y-m-d');
            
            $sql = "INSERT INTO authors (firstname, lastname, email, phone, datejoined) VALUES "
                    . "('$firstname', '$lastname', '$email', '$phone', '$date');";
            mysqli_query($link, $sql);
            $author = $firstname. " ". $lastname;
        } else {
            $author = $_POST['author'];
        }
        
        $tags = $_POST['tags'];
        
        $catArr = $_POST['categories'];
        $cats = "";

        for($i = 0; $i < count($catArr); $i++) {
            $cats .= $catArr[$i];

            if ($i+1 !== count($catArr)) {
                $cats.=",";
            }
        }
        
        //process all tags
        $tagArr = explode(",", $tags);
        
        for ($i = 0; $i < count($tagArr); $i++) {
            $t = $tagArr[$i];
            
            $check = "Select * from tags where keyword = '$t' and type='blog';";
            $cres = mysqli_query($link, $check);
            
            if (!mysqli_query($link, $check)) {
                die(mysqli_error($link));
            } else {
                if ($cres -> num_rows === 0) {
                    $sql = "INSERT INTO tags (type, keyword) VALUES ('blog', '$t');";
                    mysqli_query($link, $sql);
                }
            }
        }
        
        if (!empty($_FILES['image']['name'])) {
            $random_digit=md5(uniqid(rand(), true));
            $new_file = $random_digit.basename($_FILES["image"]["name"]);
            $target_file = $target_dir . $new_file;
            $uploadOk = 1;
            
            $imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);

            // Check if image file is a actual image or fake image
            if(isset($_POST["submit"])) {
                $check = getimagesize($_FILES["image"]["tmp_name"]);
                if($check !== false) {
                    $uploadOk = 1;
                } else {
                    unset($_SESSION['addBlogSuccess']);
                    $_SESSION['uploadBlogError'] = "File is not an image.";
                    if (!empty($_POST['editid'])) {
                        header('Location: blog.php?id='.$_POST['editid']);
                    } else {
                        header('Location: blog.php');
                    }
                }
            }
            // Check if file already exists
            if (file_exists($target_file)) {
                unset($_SESSION['addBlogSuccess']);
                $_SESSION['uploadBlogError'] = "Sorry, file already exists.";
                if (!empty($_POST['editid'])) {
                    header('Location: blog.php?id='.$_POST['editid']);
                } else {
                    header('Location: blog.php');
                }
            }
            // Check file size
            if ($_FILES["image"]["size"] > 5000000) {
                unset($_SESSION['addBlogSuccess']);
                $_SESSION['uploadBlogError'] = "Sorry, uploads cannot be greater than 5MB.";
                if (!empty($_POST['editid'])) {
                    header('Location: blog.php?id='.$_POST['editid']);
                } else {
                    header('Location: blog.php');
                }
            }
            // Allow certain file formats
            if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
            && $imageFileType != "gif" ) {
                unset($_SESSION['addBlogSuccess']);
                $_SESSION['uploadBlogError'] = "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
                if (!empty($_POST['editid'])) {
                    header('Location: blog.php?id='.$_POST['editid']);
                } else {
                    header('Location: blog.php');
                }
            }
            if (!isset($_SESSION['uploadBlogError'])) {
                if (!move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
                    unset($_SESSION['addBlogSuccess']);
                    $_SESSION['uploadBlogError'] = "Sorry, there was an error uploading your file.";
                    if (!empty($_POST['editid'])) {
                        header('Location: blog.php?id='.$_POST['editid']);
                    } else {
                        header('Location: blog.php');
                    }   
                } else {
                    unset($_SESSION['uploadBlogError']);
                    $image .= $target_file;
                }
            }
        } else {
            if (!empty($_POST['oldImage'])) {
                $image = $_POST['oldImage'];
            }
        }
        
        if (!isset($_SESSION['uploadBlogError'])) {
            if (!empty($_POST['editid'])) {
                $editid = $_POST['editid'];
                
                $updateDiscSql = "UPDATE blog SET title='$title', excerpt='$excerpt', "
                    . "html='$html', visibility='$visibility', categories='$cats', dateposted='$date', "
                    . "tags='$tags', image='$image', scheduled='$schedule' where id = '$editid';";

                if (mysqli_query($link, $updateDiscSql)) {
                    unset($_SESSION['addBlogSuccess']);
                    unset($_SESSION['addBlogError']);
                    unset($_SESSION['updateBlogError']);
                    $_SESSION['updateBlogSuccess'] = "Record updated successfully";
                    header("Location: blog.php");
                } else {
                    echo "Error updating record: " . mysqli_error($link);
                }
            } else {
                $blogSql = "INSERT INTO blog (title, excerpt, image, html, visibility, "
                        . "author, dateposted, tags, categories, scheduled) VALUES ('$title','$excerpt', '$image', '$html', "
                        . "'$visibility', '$author', '$date', '$tags', '$cats', '$schedule');";

                mysqli_query($link, $blogSql);
                $_SESSION['addBlogSuccess'] = "Blog entry successfully added";
                header('Location: blog.php');
            }
        }
    }
}

