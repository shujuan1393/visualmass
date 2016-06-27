<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

require_once '../config/db.php';

if (!isset($_GET['type']) && !isset($_GET['id']) && isset($_GET['file'])) {
    $filename = $_GET['file'];
    if (file_exists($filename)) {
      unlink($filename);
      unset($_SESSION['updateMediaError']);
      $_SESSION['updateMediaSuccess'] = 'File deleted successfully';
      header("Location: media.php");
    } else {
      unset($_SESSION['updateMediaSuccess']);
      $_SESSION['updateMediaError'] = 'File could not be deleted';
      header("Location: media.php");
    }
} else if (isset($_GET['type']) && isset($_GET['id']) && isset($_GET['file'])) {
    $filename = $_GET['file'];
    if (file_exists($filename)) {
      unlink($filename);
    }
    //update url in database
    if (strcmp($_GET['type'], "products") === 0) {
        $sql = "Select * from ".$_GET['type']." where pid='".$_GET['id']."';";
    } else {
        $sql = "Select * from ".$_GET['type']." where id='".$_GET['id']."';";
    }
    
    $res = mysqli_query($link, $sql);
    
    if (!mysqli_query($link, $sql)) {
        die(mysqli_error($link));
    } else {
        $row = mysqli_fetch_assoc($res);
        
        if (strcmp($_GET['feat'], "yes") === 0) {
            $imgs = $row['featured'];
        } else {
            $imgs = $row['images'];
        }
        
        
        $imgArr = explode(",", $imgs);
        $images = "";
        for ($i = 0; $i < count($imgArr); $i++) {
            $img = $imgArr[$i];
            if (strcmp($img, $filename) !== 0) {
                $images .= $img;
                if ($i !== count($imgArr) - 1) {
                    $images .= ",";
                }
            }
        }
//        echo $imgs."<br>";
//        echo $images;
//        exit();
        
        if (strcmp($_GET['feat'], "yes") === 0) {
            if (strcmp($_GET['type'], "products") === 0) {
                $update = "UPDATE ".$_GET['type']." set featured='$images' where pid='".$_GET['id']."';";
            } else {
                $update = "UPDATE ".$_GET['type']." set featured='$images' where id='".$_GET['id']."';";
            }
        } else {
            if (strcmp($_GET['type'], "products") === 0) {
                $update = "UPDATE ".$_GET['type']." set images='$images' where pid='".$_GET['id']."';";
            } else {
                $update = "UPDATE ".$_GET['type']." set images='$images' where id='".$_GET['id']."';";
            }
        }
        
        mysqli_query($link, $update);
    }
    $str = $_GET['type'].".php?id=".$_GET['id'];
    header("Location: ".$str);
}
//    else {
//      unset($_SESSION['updateMediaSuccess']);
//      $_SESSION['updateMediaError'] = 'File could not be deleted';
//      header("Location: media.php");
//    }

