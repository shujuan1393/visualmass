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
            $_SESSION['searchError'] = "No results matching '".$searchVal."';";
        } else {
            unset($_SESSION['searchError']);
            while($row= mysqli_fetch_assoc($result)) {
                $pid.= $row['pid'].",";
            }
            $_SESSION['searchResult'] = $pid;
            $_SESSION['searchVal'] = $searchVal;
        }
        header("Location: searchFrames.php");
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
    header("Location: search.php");
//    if (mysqli_multi_query($link,$sql)) {
//        do {
//            /* store first result set */
//            if ($result = mysqli_store_result($link)) {
//                while ($row = mysqli_fetch_row($result)) {
//                    printf("%s\n", $row[1]);
//                }
//                mysqli_free_result($result);
//            }
//            /* print divider */
//            if (mysqli_more_results($link)) {
//                printf("-----------------\n");
//            }
//        }
//        while (mysqli_next_result($link));
//    }
//
////    $sql = "Select * from locations, blog, faq, terms, ourstory, hometry, homepage, careers"
////            . " where locations.name like '%".$searchVal."%' or blog.title like ='%".$searchVal."%' "
////            . "or faq.title like ='%".$searchVal."%' or terms.title like ='%".$searchVal."%' or ourstory.title like ='%".$searchVal."%'"
////            . " or hometry.title like ='%".$searchVal."%' or homepage.title like ='%".$searchVal."%' or careers.title like ='%".$searchVal."%';";
//    echo $sql;
//    exit();
}