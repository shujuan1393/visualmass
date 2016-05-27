<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
require_once 'config/db.php';
require_once 'mailer/PHPMailerAutoload.php';

$formsql = "Select * from forms where type='field' and form='Career' and status='active' order by fieldorder asc";
$formres = mysqli_query($link, $formsql);

$emailBody="Hi <br><br> A new application has been received. The details are as follows: <br><br>";

while ($row = mysqli_fetch_assoc($formres)) {
    $field = $row['name'];
    $emailBody .= $field . ": " .$_POST[$field]."<br>";
}

$emailBody .= "<br> Regards <br> Visual Mass Tech Team";

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
$mail->addAddress('jacintatan-@hotmail.com');     
// Add a recipient, Name is optional

//                $mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
//                $mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name
$mail->isHTML(true);                                  // Set email format to HTML

$mail->Subject = 'New Job Application';
$mail->Body    = $emailBody;
//                $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

if ($mail->send()) {
    header("Location: career.php");
}
