<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
require_once '../config/db.php';
?>

<html>
    <head>
        <title>Visual Mass Admin Panel</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" type="text/css" href="../calendar/codebase/dhtmlxcalendar.css"/>
        <script type="text/javascript" src="../calendar/codebase/dhtmlxcalendar.js"/>   
        <script type="text/javascript" src="../ckeditor/ckeditor.js"></script>
        <link href="../styles.css" rel="stylesheet" type="text/css" />     
    </head>
    <body>
        <div class="logo_sidebar"></div>
        <div class ="rightheader">
            <ul>
                <li><a href='profile.php'><?php echo $_SESSION['loggedUser'] ?></a></li>
                <li><a href='../logout.php'>Logout</a></li>
            </ul>
        </div>
    </body>
</html>