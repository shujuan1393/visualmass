<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

require_once 'config/db.php';
require_once 'mailer/PHPMailerAutoload.php';

function gen_uuid($name, $len=8) {

    $hex = $name . uniqid("", true);   
    
    echo $hex;
    exit();
    $pack = pack('H*', $hex);
    $tmp =  base64_encode($pack);

    $uid = preg_replace("#(*UTF8)[^A-Za-z0-9]#", "", $tmp);

    $length = max(4, min(128, $len));

    while (strlen($uid) < $length) {
        $uid .= gen_uuid(22);
    }
    return substr($uid, 0, $length);
}

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
    $name = $firstName . " " . $lastName;
    
    $uniquecode = gen_uuid($name);
//    echo $uniquecode;
//    exit();
    
    $qry = "Select * from user ".
        " where email='$username' and password='$pwdmd5' ";
    
    $result = mysqli_query($link, $qry);
    if (!mysqli_query($link,$qry))
    {
        echo("Error description: " . mysqli_error($link));
    } else {
        if ($result->num_rows != 0) {
            $_SESSION['signUpError'] = "Account already exists";
            header("Location: signUp.php");
        } else {
            $mail = new PHPMailer;

//            $mail->SMTPDebug = 3;                               // Enable verbose debug output

            $mail->isSMTP();                                      // Set mailer to use SMTP
            $mail->Host = 'smtp.gmail.com';  // Specify main and backup SMTP servers
            $mail->SMTPAuth = true;                               // Enable SMTP authentication
            $mail->Username = 'shujuan1393@gmail.com';                 // SMTP username
            $mail->Password = 'Milkyway2309SJ';                           // SMTP password
            $mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
            $mail->Port = 587;                                    // TCP port to connect to

            $mail->setFrom('admin@visualmass.com', 'Admin');
            $mail->addAddress($username);     
            // Add a recipient, Name is optional

//                $mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
//                $mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name
            $mail->isHTML(true);                                  // Set email format to HTML

            $mail->Subject = 'Welcome to Visual Mass';
            $mail->Body    = 'Hi '.$name.',<br><br>'
                    . 'Your new login details are: <br><br>'
                    . 'Email: '.$username.'<br>'
                    . 'Password: '.$password.'<br><br>'
                    . 'It is recommended you change your password after logging in.<br>'
                    . '<br>'
                    . 'Cheers,<br>'
                    . 'Visual Mass Team';
//                $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

            if($mail->send()) {
                // output data of each row
                $sql = "INSERT INTO user (firstname, lastname, email, password,
                datejoined, accountType, code) VALUES ('$firstName',
                '$lastName', '$username', '$pwdmd5',
                CURRENT_TIMESTAMP, 'customer', '$uniquecode');";
                
                $_SESSION['loggedUserEmail'] = $username;
                $_SESSION['loggedUser'] = $firstName;

                mysqli_query($link, $sql);
                header("Location: index.php");
            }
//            echo "<h2>Thank you for signing up with us!</h2>";
        } 
    }
}