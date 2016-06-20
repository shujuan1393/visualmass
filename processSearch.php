<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

require_once 'config/db.php';

if (isset($_GET['frames'])) {
    $searchVal = $_POST['search'];
    $sql = "Select * from products where name like '%".$searchVal."%';";
    $result = mysqli_query($link, $sql);
    $pid = "";
    
    if (!mysqli_query($link, $sql)) {
        die(mysqli_error($link));
    } else {
        if ($result -> num_rows === 0) {
            unset($_SESSION['searchResult']);
            unset($_SESSION['searchVal']);
            $_SESSION['searchError'] = "No results matching '".$searchVal."';";
        } else {
            unset($_SESSION['searchError']);
            while($row= mysqli_fetch_assoc($result)) {
                $pid.= $row['pid'].",";
            }
            $_SESSION['searchResult'] = $pid;
            $_SESSION['searchVal'] = $searchVal;
        }
        
        $searchsql = "INSERT INTO searchstatistics (type, keyword) VALUES ('product', '$searchVal')";
        mysqli_query($link, $searchsql);
        
        header("Location: searchFrames.php");
    }
} else if (isset($_GET['blog'])) {
    $searchVal = $_POST['search'];
    $sql = "Select * from blog where title like '%".$searchVal."%' "
            . "or html like '%".$searchVal."%' or excerpt like '%".$searchVal."%'"
            . " or author like '%".$searchVal."%';";
    $result = mysqli_query($link, $sql);
    $bid = "";
    if (!mysqli_query($link, $sql)) {
        die(mysqli_error($link));
    } else {
        if ($result -> num_rows === 0) {
            unset($_SESSION['searchResult']);
            unset($_SESSION['searchVal']);
            $_SESSION['searchError'] = "No blog entries matching '".$searchVal."';";
        } else {
            unset($_SESSION['searchError']);
            while($row= mysqli_fetch_assoc($result)) {
                $bid.= $row['id'].",";
            }
//            echo $bid;
//            exit();
            $_SESSION['searchResult'] = $bid;
            $_SESSION['searchVal'] = $searchVal;
        }
        $searchsql = "INSERT INTO searchstatistics (type, keyword) VALUES ('blog', '$searchVal')";
        mysqli_query($link, $searchsql);
        
        header("Location: searchBlog.php");
    }
} else if (isset($_GET['locations'])) {
    $searchVal = $_POST['search'];
    $sql = "Select * from locations where name like '%".$searchVal."%' "
            . "or services like '%".$searchVal."%' or description like '%".$searchVal."%'"
            . " or opening like '%".$searchVal."%' or type like '%".$searchVal."%'"
            . " or address like '%".$searchVal."%';";
    $result = mysqli_query($link, $sql);
    $bid = "";
    if (!mysqli_query($link, $sql)) {
        die(mysqli_error($link));
    } else {
        if ($result -> num_rows === 0) {
            unset($_SESSION['searchResult']);
            unset($_SESSION['searchVal']);
            $_SESSION['searchError'] = "No locations matching '".$searchVal."';";
        } else {
            unset($_SESSION['searchError']);
            while($row= mysqli_fetch_assoc($result)) {
                $bid.= $row['id'].",";
            }
            $_SESSION['searchResult'] = $bid;
            $_SESSION['searchVal'] = $searchVal;
        }
        $searchsql = "INSERT INTO searchstatistics (type, keyword) VALUES ('location', '$searchVal')";
        mysqli_query($link, $searchsql);
        
        header("Location: searchLocations.php");
    }
} else if (isset($_GET['general'])) {
    $searchVal = $_POST['search'];
    
    $sql = "Select * from locations where name like '%$searchVal%' and name <> 'banner';";
    
    $sql .= "Select * from blog where title like '%$searchVal%';";
    
    $sql .= "Select * from faq where title like '%$searchVal%' and title <> 'banner';";
    
    $sql .= "Select * from terms where title like '%$searchVal%';";
    
    $sql .= "Select * from ourstory where title like '%$searchVal%' and title <> 'banner';";
    
    $sql .= "Select * from hometry where title like '%$searchVal%' and type <> 'banner';";
    
    $sql .= "Select * from homepage where title like '%$searchVal%' and type <> 'banner';";
    
    $sql .= "Select * from careers where title like '%$searchVal%' and type <> 'banner';";
    
    $_SESSION['searchVal'] = $searchVal;
    $_SESSION['searchResult'] = $sql;
    
    $searchsql = "INSERT INTO searchstatistics (type, keyword) VALUES ('general', '$searchVal')";
    mysqli_query($link, $searchsql);
        
    header("Location: search.php");
}