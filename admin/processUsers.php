<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
require_once '../config/db.php';
require_once '../mailer/PHPMailerAutoload.php';

if (isset($_GET['delete'])) {
    $deletesql = "DELETE FROM staff where id ='". $_GET['id']."'";
    if (mysqli_query($link, $deletesql)) {
        unset($_SESSION['addEmpSuccess']);
        unset($_SESSION['addEmpError']);
        unset($_SESSION['updateError']);
        $_SESSION['updateSuccess'] = "Record deleted successfully";
        
        header("Location: users.php#menu1");
        
//        echo "<script>window.history.back()</script>";
    } 
} else {
    if(empty($_POST['email']) || empty($_POST['firstName']) 
            || empty($_POST['lastName']) ) {
        unset($_SESSION['addEmpSuccess']);
        unset($_SESSION['updateError']);
        unset($_SESSION['updateSuccess']);
        
        $_SESSION['addEmpError'] = "Empty field(s)";
        if (isset($_POST['editid'])) {
            header("Location: users.php?id=".$_POST['editid']."#menu1");
        } else {
            header("Location: users.php#menu1");
        }
    } else if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
        unset($_SESSION['addEmpSuccess']);
        unset($_SESSION['updateError']);
        unset($_SESSION['updateSuccess']);
        $_SESSION['addEmpError'] = "Invalid email";
        if (isset($_POST['editid'])) {
            header("Location: users.php?id=".$_POST['editid']."#menu1");
        } else {
            header("Location: users.php#menu1");
        }
    } else {        
        $empfirst = $_POST['firstName'];
        $emplast = $_POST['lastName'];
        $empemail = $_POST['email'];
        $emptype = $_POST['type'];
        
        if (!empty($_POST['editid'])) {
            $editid = $_POST['editid'];

            $updateSql = "UPDATE staff SET firstname='". $empfirst. "', "
                    . "lastname ='" .$emplast. "', email ='".$empemail."', "
                    . "type='".$emptype."' where id='". $editid. "'";

            if (mysqli_query($link, $updateSql)) {
                unset($_SESSION['addEmpSuccess']);
                unset($_SESSION['updateError']);
                $_SESSION['updateSuccess'] = "Record updated successfully";
            
                header("Location: users.php#menu1");
            } else {
                echo "Error updating record: " . mysqli_error($link);
            }

        } else {

            $qry = "Select * from staff where email ='". $empemail."'";

            $result = mysqli_query($link, $qry);
            if (!mysqli_query($link,$qry))
            {
                echo("Error description: " . mysqli_error($link));
            } else {
                if ($result->num_rows != 0) {
                    unset($_SESSION['addEmpSuccess']);
                    unset($_SESSION['updateSuccess']);
                    unset($_SESSION['updateError']);
                    $_SESSION['addEmpError'] = "Account already exists";
                    if (isset($_POST['editid'])) {
                        header("Location: users.php?id=".$_POST['editid']."#menu1");
                    } else {
                        header("Location: users.php#menu1");
                    }
                } else {               
                    //code to send email to new user

                    $mail = new PHPMailer;

    //                $mail->SMTPDebug = 3;                               // Enable verbose debug output

                    $mail->isSMTP();                                      // Set mailer to use SMTP
                    $mail->Host = 'smtp.gmail.com';  // Specify main and backup SMTP servers
                    $mail->SMTPAuth = true;                               // Enable SMTP authentication
                    $mail->Username = 'shujuan1393@gmail.com';                 // SMTP username
                    $mail->Password = 'Milkyway2309SJ';                           // SMTP password
                    $mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
                    $mail->Port = 587;                                    // TCP port to connect to

                    $mail->setFrom('admin@visualmass.com', 'Admin');
                    $mail->addAddress($empemail);     
                    // Add a recipient, Name is optional

    //                $mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
    //                $mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name
                    $mail->isHTML(true);                                  // Set email format to HTML

                    $mail->Subject = 'Welcome to Visual Mass';
                    $mail->Body    = 'Hi '.$empfirst.' '.$emplast.',<br><br>'
                            . 'An account has been created for you: <br><br>'
                            . 'Email: '.$empemail.'<br>'
                            . 'Password: P@ssw0rd!23<br><br>'
                            . 'It is recommended you change your password after your first login.<br>'
                            . '<br> Welcome to Visual Mass! :) <br>'
                            . 'Cheers,<br>'
                            . 'Visual Mass Team';
    //                $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

                    if(!$mail->send()) {
                        unset($_SESSION['addEmpSuccess']);
                        unset($_SESSION['updateSuccess']);
                        unset($_SESSION['updateError']);
                        $_SESSION['addEmpError'] = 'Invalid email address';
                        if (isset($_POST['editid'])) {
                            header("Location: users.php?id=".$_POST['editid']."#menu1");
                        } else {
                            header("Location: users.php#menu1");
                        }
                    } else {
                        unset($_SESSION['addEmpError']);
                        unset($_SESSION['updateSuccess']);
                        unset($_SESSION['updateError']);
                        $pwd = md5('P@ssw0rd!23');
                         $sql = "INSERT INTO staff (firstname, lastname, email, type, password) "
                            . "VALUES ('$empfirst',
                        '$emplast', '$empemail', '$emptype', '$pwd');";

                        mysqli_query($link, $sql);
                        $_SESSION['addEmpSuccess'] = "Account successfully added";
                        
                        header("Location: users.php#menu1");
                    }

                } 
            }
        }
    }
}
