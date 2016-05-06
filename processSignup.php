<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

require_once 'config/db.php';
if(empty($_POST['email']) || empty($_POST['password']) ||
        empty($_POST['firstName']) || empty($_POST['lastName']) ) {
    $_SESSION['signUpError'] = "Empty field(s)";
    header('Location: signUp.php');
} else if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
    $_SESSION['signUpError'] = "Invalid email";
    header('Location: signUp.php');
} else {
    unset($_SESSION['signUpError']);
    $firstName = trim($_POST['firstName']);
    $lastName = trim($_POST['lastName']);
    $username = trim($_POST['email']);
    $password = trim($_POST['password']);
    $pwdmd5 = md5($password);
        
    $qry = "Select * from user ".
        " where email='$username' and password='$pwdmd5' ";
    
    $result = mysqli_query($link, $qry);
    if (!mysqli_query($link,$qry))
    {
        echo("Error description: " . mysqli_error($link));
    } else {
        if ($result->num_rows != 0) {
            echo "Account already exists. <br>";
            echo "Login <a href='login.php'>here</a>";
        } else {
            // output data of each row
            $sql = "INSERT INTO user (firstname, lastname, email, password,
            datejoined, accountType) VALUES ('$firstName',
            '$lastName', '$username', '$pwdmd5',
            CURRENT_TIMESTAMP, 'customer');";
            
            mysqli_query($link, $sql);

            echo "<h2>Thank you for signing up with us!</h2>";
        } 
    }
}