<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
session_start();
if (isset($_GET['file'])) {
    $filename = $_GET['file'];
    if (file_exists($filename)) {
      unlink($filename);
      unset($_SESSION['updateMediaError']);
      $_SESSION['updateMediaSuccess'] = 'File deleted successfully';
      header("Location: media.php");
    } else {
      unset($_SESSION['updateMediaSuccess']);
      $_SESSION['updateMediaError'] = 'File could not be deleted';
      header("Location: media.php");
    }
}
