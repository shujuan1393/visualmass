<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
require_once '../config/db.php';
?>

<head>
    
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!--styling for multi select dropdown-->    
    <link rel="stylesheet" href="../selectize/css/normalize.css">
    <link rel="stylesheet" href="../selectize/css/stylesheet.css">
    <!--[if IE 8]><script src="js/es5.js"></script><![endif]-->
    
    <link rel="stylesheet" type="text/css" href="../calendar/codebase/dhtmlxcalendar.css"/>
    <script type="text/javascript" src="../calendar/codebase/dhtmlxcalendar.js"/>  
    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.2/jquery.min.js"></script>
    <!-- jQuery -->
    <script src="../bootstrap/js/jquery.js"></script>
    <!-- Bootstrap Core JavaScript -->
    <script src="../bootstrap/js/bootstrap.min.js"></script>
    <!-- Bootstrap Core CSS -->
    <link href="../bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="../bootstrap/css/sb-admin.css" rel="stylesheet">
    <!-- Custom Fonts -->
    <link href="../bootstrap/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
    
    <!-- Datatable -->
    <link href="https://cdn.datatables.net/plug-ins/1.10.7/integration/bootstrap/3/dataTables.bootstrap.css" rel="stylesheet" />
    <script src="https://cdn.datatables.net/1.10.7/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/plug-ins/1.10.7/integration/bootstrap/3/dataTables.bootstrap.js"></script>
    
    <script src="http://cdnjs.cloudflare.com/ajax/libs/modernizr/2.8.2/modernizr.js"></script>

    <!-- CK Editor -->
    <script type="text/javascript" src="../ckeditor/ckeditor.js"></script>
    
    <link href="../styles.css" rel="stylesheet" type="text/css" />
    
    <meta name="description" content="Singapore Eyewear">
    <meta name="author" content="Visual Mass">

    <title>Visual Mass Admin Panel</title>

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
    
</head>
<script>
    //paste this code under the head tag or in a separate js file.
    // Wait for window load
    $(window).load(function() {
        // Animate loader off screen
        $(".se-pre-con").fadeOut("slow");;
    });
</script>

<!--script for multi select dropdown-->
<!--<script src="../selectize/js/jquery.js"></script>-->
<script src="../dist/js/standalone/selectize.js"></script>
<!--devil causing the js code to appear-->
<script src="../selectize/js/index.js"></script>