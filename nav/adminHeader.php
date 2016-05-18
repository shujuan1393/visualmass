<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
    
?>

<html>
    <head>
        <title>Visual Mass Admin Panel</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <script type="text/javascript" src="../ckeditor/ckeditor.js"></script>
        <link href="../calendar/calendar.css" rel="stylesheet" type="text/css" />
        <script language="javascript" src="../calendar/calendar.js"></script>
        <link href="../styles.css" rel="stylesheet" type="text/css" />        
    </head>
    <body>
        <div class="logo_sidebar"></div>
        <div class ="rightheader">
            <?php 
                echo "Welcome, " .$_SESSION['loggedUser']; 
                echo "&nbsp<a href='../logout.php'>Logout</a>";
            ?>
        </div>
    </body>
</html>