<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

require_once 'config/db.php';

if (isset($_GET['delete']) && isset($_GET['id'])) {
    $get = "Select * from favourites where email='".$_SESSION['loggedUserEmail']."';";
    $gres = mysqli_query($link, $get);
    
    $row = mysqli_fetch_assoc($gres);
    $newFavs = "";
    
    $favArr = explode(",", $row['pid']);
    
    for ($i = 0; $i < count($favArr); $i++) {
        $fav = $favArr[$i];
        if (strcmp($fav, $_GET['id']) === 0) {
            unset($fav);
        } else {
            $newFavs .= $fav;
        }
        
        if ($i +1 != count($favArr)) {
            $newFavs.=",";
        }
    }
    $delete = "UPDATE favourites set pid='$newFavs' where email ='".$_SESSION['loggedUserEmail']."';";
    
    mysqli_query($link, $delete);
    echo "<script>window.location.replace(document.referrer);</script>";
//    header("Location: cart.php");
} else if (isset($_GET['id'])) {
    $pid = $_GET['id'];
    $type = $_GET['type'];
    $cartid = GetCartId();

    $getproduct = "Select * from products where pid='$pid'";
    $prodres = mysqli_query($link, $getproduct);

    if (!mysqli_query($link, $getproduct)) {
        echo "Error: ".mysqli_error($link);
    } else {
        $prod = mysqli_fetch_assoc($prodres);
        $user = $_SESSION['loggedUserEmail'];
        
        $query = "Select * from favourites where email='".$user."';";
        $result = mysqli_query($link, $query);

        if (!mysqli_query($link, $query)) {
            echo "Error: ". mysqli_error($link);
        } else {
            $sql;
            if ($result -> num_rows === 0) {
                $sql = "INSERT into favourites (pid, email) "
                        . "VALUES ('$pid', '$user')";
            } else {
                $row = mysqli_fetch_assoc($result);
                if (strcmp($row['pid'], "")=== 0) {
                    $pids = $pid;
                } else {
                    $pids = $row['pid'].",".$pid;
                }
                $sql = "UPDATE favourites set pid='$pids' where email='$user';";
            }
            
            mysqli_query($link, $sql);
            echo "<script>window.location.replace(document.referrer);</script>";
//            header("Location: product.php?id=".$pid);
        }
    }
} 