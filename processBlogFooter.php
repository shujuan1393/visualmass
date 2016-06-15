<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

require_once 'config/db.php';

if(empty($_POST['email']) || empty($_POST['firstName']) 
        || empty($_POST['lastName']) || empty($_POST['phone']) ) {
    unset($_SESSION['addAuthorSuccess']);
    unset($_SESSION['blogError']);

    $_SESSION['blogError'] = "Empty field(s)";
    echo "<script>window.history.back()</script>";
} else if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
    unset($_SESSION['addAuthorSuccess']);
    unset($_SESSION['blogError']);

    $_SESSION['addEmpError'] = "Invalid email";
    echo "<script>window.history.back()</script>";
} else {        
    $authorfirst = $_POST['firstName'];
    $authorlast = $_POST['lastName'];
    $authoremail = $_POST['email'];
    $authorphone = $_POST['phone'];

    $qry = "Select * from staff where email ='". $authoremail ."'";

    $result = mysqli_query($link, $qry);
    if (!mysqli_query($link,$qry))
    {
        echo("Error description: " . mysqli_error($link));
    } else {
        if ($result->num_rows != 0) {
            unset($_SESSION['addAuthorSuccess']);
            unset($_SESSION['blogError']);

            $_SESSION['blogError'] = "Email address have already been registered";
            echo "<script>window.history.back()</script>";
        } else {
            unset($_SESSION['addAuthorSuccess']);
            unset($_SESSION['blogError']);
            $authortype = "author";
            $pwd = md5('P@ssw0rd!23');

            $sql = "INSERT INTO staff (firstname, lastname, email, password, phone, type, datejoined) "
                        . "VALUES ('$authorfirst', '$authorlast', '$authoremail', 
                        '$pwd', '$authorphone', '$authortype', CURRENT_TIMESTAMP);";

                    mysqli_query($link, $sql);
                    $_SESSION['addAuthorSuccess'] = "Thank you for joining us!";
                    echo "<script>window.history.back()</script>";
        }
    }
}

?>