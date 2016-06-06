<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

require_once 'config/db.php';
require_once 'mailer/PHPMailerAutoload.php';

if(isset($_GET['forget'])) {
    $email = $_POST['email'];
    
    $emailSql = "Select * from user where email ='".$email."';";
    $emailres = mysqli_query($link, $emailSql);
    
    if (!mysqli_query($link, $emailSql)) {
        die(mysqli_error($link));
    } else {
        if ($emailres -> num_rows === 0) {
            $_SESSION['forgetFormError'] = "Invalid email ";
            header('Location: forgetPassword.php');
        } else {
            //send email with new password
            $row = mysqli_fetch_assoc($emailres);
            
            $name = $row['firstname']." ".$row['lastname'];
            $password = uniqid(rand(), true);
            
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
            $mail->addAddress($email);     
            // Add a recipient, Name is optional

//                $mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
//                $mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name
            $mail->isHTML(true);                                  // Set email format to HTML

            $mail->Subject = 'Visual Mass Password Reset';
            $mail->Body    = 'Hi '.$name.',<br><br>'
                    . 'Your new login details are: <br><br>'
                    . 'Email: '.$email.'<br>'
                    . 'Password: '.$password.'<br><br>'
                    . 'It is recommended you change your password after logging in.<br>'
                    . '<br>'
                    . 'Cheers,<br>'
                    . 'Visual Mass Team';
//                $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

            if($mail->send()) {
                $updateSql = "UPDATE user set password='".md5($password)."' where email='$email';";
                mysqli_query($link, $updateSql);
                
                unset($_SESSION['forgetFormError']);
                unset($_SESSION['loggedUserEmail']);
                header('Location: login.php?reset=1');
            }
        }
    }
} else {
    $favId = $_POST['addToCart'];
    
    unset($_SESSION['loginFormError']);
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
        if ($result->num_rows === 0) {
            $_SESSION['loginFormError'] = "Invalid email/password ";
            header('Location: login.php');
        } else {
            // output data of each row
            while ($row = mysqli_fetch_assoc($result)) {
                $type = $row['accountType'];
                if (strcmp($type, "customer") === 0) {
                    $_SESSION['user_time'] = time();
//                    setcookie("user", $row['email'], time() + (86400 * 30), "/"); // 86400 = 1 day
                    $_SESSION['loggedUserEmail'] = $row['email'];
                    $_SESSION['loggedUser'] = $row['firstname'];
                    if (isset($favId)) {
                        $query = "Select * from favourites where email='".$_SESSION['loggedUserEmail']."';";
                        $result = mysqli_query($link, $query);

                        if (!mysqli_query($link, $query)) {
                            echo "Error: ". mysqli_error($link);
                        } else {
                            $sql;
                            if ($result -> num_rows === 0) {
                                $sql = "INSERT into favourites (pid, email) "
                                        . "VALUES ('$favId', '".$_SESSION['loggedUserEmail']."')";
                            } else {
                                $row = mysqli_fetch_assoc($result);
                                if (strcmp($row['pid'], "")=== 0) {
                                    $pids = $favId;
                                } else {
                                    $pids = $row['pid'].",".$favId;
                                }
                                $sql = "UPDATE favourites set pid='$pids' where email='".$_SESSION['loggedUserEmail']."';";
                            }

                            mysqli_query($link, $sql);
                        }
                    }
                    echo "<script>window.history.back();</script>";
//                    header('Location: index.php');
                } 
            }
        } 
    }
}
  
