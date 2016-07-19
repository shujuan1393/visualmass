<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
require_once '../config/db.php';

if (isset($_GET['delete'])) {
    $deletesql = "DELETE FROM partners where id ='". $_GET['id']."'";
    if (mysqli_query($link, $deletesql)) {
        unset($_SESSION['updatePartnerError']);
        unset($_SESSION['addPartnerSuccess']);
        unset($_SESSION['addPartnerError']);
        $_SESSION['updatePartnerSuccess'] = "Record deleted successfully";
        header("Location: partners.php");
    } 
} else if (isset($_POST['submit'])) {
    $_SESSION['company'] = $_POST['company'];
    $_SESSION['contactname'] = $_POST['contactname'];
    $_SESSION['phone'] = $_POST['phone'];
    $_SESSION['address'] = $_POST['address'];
    $_SESSION['country'] = $_POST['country'];
    $_SESSION['zip'] = $_POST['zip'];
    $_SESSION['city'] = $_POST['city'];
    $_SESSION['apt'] = $_POST['apt'];
    $_SESSION['type'] = $_POST['type'];
    $_SESSION['email'] = $_POST['email'];
    
    if(empty($_POST['company']) || empty($_POST['contactname']) || 
            empty($_POST['phone']) || empty($_POST['address']) || empty($_POST['email']) 
            || empty($_POST['address']) || empty($_POST['country']) || empty($_POST['zip'])) {
        unset($_SESSION['addPartnerSuccess']);
        unset($_SESSION['updatePartnerError']);
        unset($_SESSION['updatePartnerSuccess']);
        $_SESSION['addPartnerError'] = "Empty field(s)";
        if (!empty($_POST['editid'])) {
            header('Location: partners.php?id='.$_POST['editid']);
        } else {
            header('Location: partners.php');
        }
    } else if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) { 
        unset($_SESSION['addPartnerSuccess']);
        unset($_SESSION['updatePartnerError']);
        unset($_SESSION['updatePartnerSuccess']);
        $_SESSION['addPartnerError'] = "Invalid email address";
        if (!empty($_POST['editid'])) {
            header('Location: partners.php?id='.$_POST['editid']);
        } else {
            header('Location: partners.php');
        }
    } else {
        unset($_SESSION['company']);
        unset($_SESSION['contactname']);
        unset($_SESSION['phone']);
        unset($_SESSION['address']);
        unset($_SESSION['country']);
        unset($_SESSION['zip']);
        unset($_SESSION['city']);
        unset($_SESSION['apt']);
        unset($_SESSION['type']);
        unset($_SESSION['email']);

        unset($_SESSION['addPartnerError']);
        unset($_SESSION['updatePartnerError']);
        unset($_SESSION['updatePartnerSuccess']);
        
        $company = $_POST['company'];
        $contactname = $_POST['contactname'];
        $phone = $_POST['phone'];
        $address = $_POST['address'];
        $apt = $_POST['apt'];
        $country = $_POST['country'];
        $city = $_POST['city'];
        $zip = $_POST['zip'];
        $email = $_POST['email'];
        $type = $_POST['type'];
        
        if (!empty($_POST['editid'])) {
            $editid = $_POST['editid'];

            $updateDiscSql = "UPDATE partners SET company='$company', contactname='$contactname', "
                . "phone='$phone', address='$address', apt='$apt', country='$country', "
                . "city='$city', zip='$zip', email='$email', type='$type' where id = '$editid';";

            if (mysqli_query($link, $updateDiscSql)) {
                unset($_SESSION['addPartnerSuccess']);
                unset($_SESSION['addPartnerError']);
                unset($_SESSION['updatePartnerError']);
                $_SESSION['updatePartnerSuccess'] = "Record updated successfully";
                header("Location: partners.php");
            } else {
                echo "Error updating record: " . mysqli_error($link);
            }
        } else {
            $partnersSql = "INSERT INTO partners (company, contactname, phone, address, apt, "
                    . "country, city, zip, email, type) VALUES ('$company','$contactname', '$phone', '$address', "
                    . "'$apt', '$country', '$city', '$zip', '$email', '$type');";

            mysqli_query($link, $partnersSql);
            $_SESSION['addPartnerSuccess'] = "Partner successfully added";
            header('Location: partners.php');
        }
    }
}

