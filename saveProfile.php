<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

require_once 'config/db.php';
    
if (isset($_POST['submit'])) {
    $marketing = isset($_POST['marketing']) ? $_POST['marketing'] : '';
    
    if (strcmp($marketing, "yes") === 0 && empty($_POST['preference'])) {
        unset($_SESSION['updateProfile']);
        $_SESSION['updateProfileError'] = "Preference not selected";
    } else {
        unset($_SESSION['updateProfileError']);
        unset($_SESSION['updateProfile']);
        $first = isset($_POST['firstname']) ? $_POST['firstname'] : '';
        $last = isset($_POST['lastname']) ? $_POST['lastname'] : '';
        $email = isset($_POST['email']) ? $_POST['email'] : '';
        $password = isset($_POST['password']) ? $_POST['password'] : '';
        $phone = isset($_POST['phone']) ? $_POST['phone'] : '';

        if (strcmp($password, "")===0) {
            $getold = "select * from user where email = '".$_SESSION['loggedUserEmail']."';";
            $gres = mysqli_query($link, $getold);
            
            if (!mysqli_query($link, $getold)) {
                die(mysqli_error($link));
            } else {
                $row = mysqli_fetch_assoc($gres);
                $pwd = $row['password'];
            }
        } else {
            $pwd = md5($password);
        }
        
        $add = isset($_POST['address']) ? $_POST['address'] : '';
        $zip = isset($_POST['zip']) ? $_POST['zip'] : '';
        $apt = isset($_POST['apt']) ? $_POST['apt'] : '';
        $city = isset($_POST['city']) ? $_POST['city'] : '';
        $country = isset($_POST['country']) ? $_POST['country'] : '';

        if (empty($marketing)) {
            $marketing = "no";
        }
        $updateFav = "UPDATE favourites set email='$email' where email='".$_SESSION['loggedUserEmail']."';";

        mysqli_query($link, $updateFav);
        
        $update = "UPDATE user set firstname='$first', lastname='$last', email='$email', "
                . "password='$pwd', address='$add',phone='$phone', marketing='$marketing', "
                . "zip='$zip', apt='$apt', city='$city', country='$country' where email='".$_SESSION['loggedUserEmail']."';";

        mysqli_query($link, $update);

            $getMailing = "Select * from mailinglist where email='".$_SESSION['loggedUserEmail']."';";
            $mailingRes = mysqli_query($link, $getMailing);        

            if (!mysqli_query($link, $getMailing)) {
                die(mysqli_error($link));
            } else {
                if (strcmp($marketing, "yes") === 0) {

                    $genderArr = $_POST['preference'];
                    $gender = "";

                    for($i = 0; $i < count($genderArr); $i++) {
                        $gender .= $genderArr[$i];

                        if ($i+1 !== count($genderArr)) {
                            $gender.=",";
                        }
                    }
                    if ($mailingRes -> num_rows === 0) {
                        $mailing = "INSERT INTO mailinglist (email, preference) VALUES ('$email', '$gender');";
                    } else {
                        $mailing = "UPDATE mailinglist set email='$email', preference='$gender' where email='".$_SESSION['loggedUserEmail']."';";
                    }
                } else {
                    $mailing = "DELETE FROM mailinglist where email='".$_SESSION['loggedUserEmail']."'";
                }
            }

            mysqli_query($link, $mailing);
        $_SESSION['loggedUserEmail'] = $email;
        $_SESSION['updateProfile'] = "Profile updated";
    }
    header("Location: profile.php");
}