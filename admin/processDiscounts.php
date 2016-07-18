<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
require_once '../config/db.php';

if (isset($_GET['delete'])) {
    $deletesql = "DELETE FROM discounts where id ='". $_GET['id']."'";
    if (mysqli_query($link, $deletesql)) {
        unset($_SESSION['updateDiscError']);
        unset($_SESSION['addDiscSuccess']);
        unset($_SESSION['addDiscError']);
        $_SESSION['updateDiscSuccess'] = "Record deleted successfully";
        header("Location: discounts.php");
    } 
} else if (isset($_POST['submit'])) {
    $_SESSION['randomString'] = $_POST['code'];
    $_SESSION['name'] = $_POST['name'];
    $_SESSION['disclimit'] = $_POST['limit'];
    $_SESSION['recurrence'] = $_POST['recurrence'];
    $_SESSION['userlimit'] = $_POST['userlimit'];
    $_SESSION['start'] = $_POST['date3'];
    $_SESSION['end'] = $_POST['date4'];
    $_SESSION['status'] = $_POST['status'];
    $_SESSION['condition'] = $_POST['condition'];
    $_SESSION['discterms'] = $_POST['conditionfor'];
    $_SESSION['serial'] = $_POST['serial'];
    
    $usagesArr = $_POST['usage'];
    $usages = "";

    for($i = 0; $i < count($usagesArr); $i++) {
        $usages .= $usagesArr[$i];

        if ($i+1 !== count($usagesArr)) {
            $usages.=",";
        }
    }
    $_SESSION['usage'] = $usages;
    
    $_SESSION['bundledisc'] = array("qty" => $_POST['bundlediscqty'], "price" => $_POST['bundlediscprice']);
    $_SESSION['bundleamt'] = array("qty" => $_POST['bundleamtqty'], "price" => $_POST['bundleamtprice']);
    $_SESSION['nextfree'] = array("qty" => $_POST['nextfreeqty'], "amt" => $_POST['nextfreeamt']);
    $_SESSION['nextdisc'] = array("qty" => $_POST['nextdiscqty'], "amt" => $_POST['nextdiscamt']);
    $_SESSION['fixedperc'] = $_POST['fixedperc'];
    $_SESSION['fixedamt'] = $_POST['fixedamt'];
    $_SESSION['aboveamt'] = $_POST['aboveamt'];
    $_SESSION['tags'] = $_POST['tags'];
    $_SESSION['specificprod'] = $_POST['specificprod'];
    
    $disctype = $_POST['condition'];
    $discterm = $_POST['conditionfor'];
    $serial = $_POST['serial'];
    
    if (empty($serial)) {
        $serial = "no";
    }
    if (strcmp($serial, "yes") === 0 && (empty($_POST['limit']) || strcmp($_POST['limit'], "unlimited") === 0)) { 
        unset($_SESSION['addDiscSuccess']);
        unset($_SESSION['updateDiscError']);
        unset($_SESSION['updateDiscSuccess']);
        $_SESSION['addDiscError'] = "Limit is not numeric when serial tracking is enabled";
        if (!empty($_POST['editid'])) {
            header ("Location: discounts.php?id=".$_POST['editid']);
        } else {
            header('Location: discounts.php');
        }
    } else if (strcmp($disctype, "null") === 0 || strcmp($discterm, "null") === 0) {
        unset($_SESSION['addDiscSuccess']);
        unset($_SESSION['updateDiscError']);
        unset($_SESSION['updateDiscSuccess']);
        $_SESSION['addDiscError'] = "Empty field(s)";
        if (!empty($_POST['editid'])) {
            header ("Location: discounts.php?id=".$_POST['editid']);
        } else {
            header('Location: discounts.php');
        }
    } else if ( (strcmp($disctype, "bundlediscount") === 0 && (empty($_POST['bundlediscqty']) || empty($_POST['bundlediscprice']))) || 
            (strcmp($disctype, "bundleamount") === 0 && (empty($_POST['bundleamtqty']) || empty($_POST['bundleamtprice']))) ||
            (strcmp($disctype, "nextfree") === 0 && (empty($_POST['nextfreeqty']) || empty($_POST['nextfreeamt']))) || 
            (strcmp($disctype, "nextdiscount") === 0 && (empty($_POST['nextdiscqty']) || empty($_POST['nextdiscamt']))) ||
            (strcmp($disctype, "fixedpercent") === 0 && empty($_POST['fixedperc'])) ||
            (strcmp($disctype, "fixedamount") === 0 && empty($_POST['fixedamt'])) 
            ) { 
        unset($_SESSION['addDiscSuccess']);
        unset($_SESSION['updateDiscError']);
        unset($_SESSION['updateDiscSuccess']);
        $_SESSION['addDiscError'] = "Empty field(s)";
        $_SESSION['condition'] = $disctype;
        if (!empty($_POST['editid'])) {
            header ("Location: discounts.php?id=".$_POST['editid']);
        } else {
            header('Location: discounts.php');
        }
    } else if ((strcmp($discterm, "ordersabove") === 0 && empty($_POST['aboveamt'])) ||
            (strcmp($discterm, "productcat") === 0 && empty($_POST['tags'])) || 
            (strcmp($discterm, "specificprod") === 0 && empty($_POST['specificprod']))
            ) {
        unset($_SESSION['addDiscSuccess']);
        unset($_SESSION['updateDiscError']);
        unset($_SESSION['updateDiscSuccess']);
        $_SESSION['addDiscError'] = "Empty field(s)";
        $_SESSION['discterms'] = $discterm;
        if (!empty($_POST['editid'])) {
            header ("Location: discounts.php?id=".$_POST['editid']);
        } else {
            header('Location: discounts.php');
        }
    } else if(strcmp($_POST['limit'], "") === 0 
            || empty($_POST['recurrence']) || empty($_POST['status']) 
            || empty($_POST['usage']) || empty($_POST['code']) ) {
        unset($_SESSION['addDiscSuccess']);
        unset($_SESSION['updateDiscError']);
        unset($_SESSION['updateDiscSuccess']);
        $_SESSION['addDiscError'] = "Empty field(s)";
        if (!empty($_POST['editid'])) {
            header ("Location: discounts.php?id=".$_POST['editid']);
        } else {
            header('Location: discounts.php');
        }
    } else {
        unset($_SESSION['randomString']);
        unset($_SESSION['name']);
        unset($_SESSION['disclimit']);
        unset($_SESSION['recurrence']);
        unset($_SESSION['usage']);
        unset($_SESSION['userlimit']);
        unset($_SESSION['start']);
        unset($_SESSION['end']);
        unset($_SESSION['status']);
        unset($_SESSION['condition']);
        unset($_SESSION['discterms']);
        unset($_SESSION['serial']);
        unset($_SESSION['bundledisc']);
        unset($_SESSION['bundleamt']);
        unset($_SESSION['nextfree']);
        unset($_SESSION['nextdisc']);
        unset($_SESSION['fixedperc']);
        unset($_SESSION['fixedamt']);
        unset($_SESSION['aboveamt']);
        unset($_SESSION['tags']);
        unset($_SESSION['specificprod']);

        $discount;
        
        if (strcmp($disctype, "bundlediscount") === 0) { 
            $discount = "Buy ".$_POST['bundlediscqty']." Get ".$_POST['bundlediscprice']."% Discount";
        } else if (strcmp($disctype, "bundleamount") === 0) {
            $discount = "Buy ".$_POST['bundleamtqty']." For $".$_POST['bundleamtprice'];
        } else if (strcmp($disctype, "nextfree") === 0) {
            $discount = "Buy " .$_POST['nextfreeqty']." Get ".$_POST['nextfreeamt']." Free";
        } else if (strcmp($disctype, "nextdiscount") === 0) {
            $discount = "Buy ".$_POST['nextdiscqty']." Next Pair ".$_POST['nextdiscamt']."% Discount";
        } else if (strcmp($disctype, "fixedpercent") === 0) {
            $discount = "Get ".$_POST['fixedperc']."% Off";
        } else if (strcmp($disctype, "fixedamount") === 0) {
            $discount = "Get $".$_POST['fixedamt']." Off";
        } else if (strcmp($disctype, "upgrade") === 0) {
            $discount = "Get One Free Upgrade";
        }
        $condition;
        if (strcmp($discterm, "allorders") === 0) {
            $condition = "All orders";
        } else if (strcmp($discterm, "ordersabove") === 0) {
            $condition = "Orders above $".$_POST['aboveamt'];
        } else if (strcmp($discterm, "productcat") === 0) {
            $condition = "On product categories: ".$_POST['tags'];
        } else if (strcmp($discterm, "specificprod") === 0) {
            $condition = "For product: ".$_POST['specificprod'];
        }
        
        //process all tags
        $tagArr = explode(",", $_POST['tags']);

        for ($i = 0; $i < count($tagArr); $i++) {
            $t = $tagArr[$i];

            $check = "Select * from tags where keyword = '$t' and type='product';";
            $cres = mysqli_query($link, $check);

            if (!mysqli_query($link, $check)) {
                die(mysqli_error($link));
            } else {
                if ($cres -> num_rows === 0) {
                    $sql = "INSERT INTO tags (type, keyword) VALUES ('product', '$t');";
                    mysqli_query($link, $sql);
                }
            }
        }
        
        unset($_SESSION['addDiscError']);
        unset($_SESSION['updateDiscError']);
        unset($_SESSION['updateDiscSuccess']);
        
        $code = $_POST['code'];
        $name = $_POST['name'];
        
        if (strcmp($serial, "") === 0) {
            $serial = "no";
        }
        
        if (strcmp($_POST['limit'], "0") === 0) {
            $limit = "unlimited";
        } else {
            $limit = $_POST['limit'];
        }
        
        if (strcmp($_POST['userlimit'], "0") === 0) {
            $userlimit = "unlimited";
        } else {
            $userlimit = $_POST['userlimit'];
        }
        $recurrence = $_POST['recurrence'];
        $status = $_POST['status'];
        $start = $_POST['date3'];
        $end = $_POST['date4'];
//        $amount = $_POST['amount'];
        $usageArr = $_POST['usage'];
        $usage = "";

        for($i = 0; $i < count($usageArr); $i++) {
            $usage .= $usageArr[$i];

            if ($i+1 !== count($usageArr)) {
                $usage.=",";
            }
        }
        
        if (!empty($_POST['editid'])) {
            $editid = $_POST['editid'];
            
            $updateDiscSql = "UPDATE discounts SET code='$code', name='$name', "
                    . "disclimit='$limit', userlimit='$userlimit', recurrence='$recurrence', "
                    . "discusage='$usage', status='$status', start='$start', "
                    . "disctype='$discount', disccondition ='$condition', "
                    . "end='$end', serial='$serial' where id = '$editid';";

            if (mysqli_query($link, $updateDiscSql)) {
                unset($_SESSION['addDiscSuccess']);
                unset($_SESSION['addDiscError']);
                unset($_SESSION['updateDiscError']);
                $_SESSION['updateDiscSuccess'] = "Record updated successfully";
                header("Location: discounts.php");
            } else {
                echo "Error updating record: " . mysqli_error($link);
            }

        } else {
            $discSql = "INSERT INTO discounts (code, name, disclimit, recurrence, discusage, "
                    . "status, start, end, disctype, disccondition, userlimit, serial) VALUES ('$code','$name', '$limit', '$recurrence', "
                    . "'$usage', '$status', '$start', '$end', '$discount', '$condition', '$userlimit', '$serial');";

            mysqli_query($link, $discSql);
            $_SESSION['addDiscSuccess'] = "Discount successfully added";
            header('Location: discounts.php');
        }
    }
}

