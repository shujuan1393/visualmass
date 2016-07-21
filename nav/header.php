<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
    
require_once 'config/db.php';

if (isset($_SESSION['searchResult'])) {
    unset($_SESSION['searchResult']);
    unset($_SESSION['searchVal']);
}
if (isset($_SESSION['searchError'])) {
    unset($_SESSION['searchError']);
}

if (isset($_SESSION['mailError'])) {
    unset($_SESSION['mailError']);
    unset($_SESSION['mailAdd']);
}

if (isset($_SESSION['mailSuccess'])) {
    unset($_SESSION['mailSuccess']);
}

//$pageCanonical = "";
//$pageRobots = "";
    
//check user profile
if (isset($_SESSION['loggedUserEmail'])) {
    $user = "Select * from user where email ='".$_SESSION['loggedUserEmail']."';";
    $ures = mysqli_query($link, $user);
    
    if (!mysqli_query($link, $user)) {
        die(mysqli_error($link));
    } else {
        $urow = mysqli_fetch_assoc($ures);
        
        if (empty($urow['firstname']) || empty($urow['lastname']) || empty($urow['address'])
                || empty($urow['zip']) || empty($urow['city']) || empty($urow['apt'])
                || empty($urow['country'])) {
            $_SESSION['profile'] = "incomplete";
        } else {
            unset($_SESSION['profile']);
        }
    }
}

    //get from settings
    $web = "Select * from settings where type='web';";
    $wres = mysqli_query($link, $web);
    
    if (!mysqli_query($link, $web)) {
        die(mysqli_error($link));
    } else {
        $hrow = mysqli_fetch_assoc($wres);
        $valArr = explode("#", $hrow['value']);
        if(!empty($valArr[0])){
        $title = explode("web=", $valArr[0]);
        }
        if(!empty($valArr[1])){
            $meta = explode("meta=", $valArr[1]);
        }
    }
    
    // Define variables for SEO
    if(!empty($title[1])){
        $pageTitle = $title[1]; //'Visual Mass - Singapore\'s Online Eyeglass & Sunglasses';
    }
    if(!empty($meta[1])){
        $pageDescription = $meta[1]; // 'Provides quality prescription eyewear from $95. Free delivery and exchanges.';
    }
    $pageCanonical = 'http://www.visualmass.co/';
    // We don't want the search engines to see our website just yet
    $pageRobots = 'noindex,nofollow';

?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <title>
            <?php if(!empty($pageTitle)) { 
            echo $pageTitle; 
            } ?>
        </title>
        <meta name="description" content="<?php if(!empty($pageDescription)) { echo $pageDescription; } ?>">
        <meta name="author" content="Visual Mass">
        
        <?php
            // If canonical URL is specified, include canonical link element
            if($pageCanonical) {
                 if(!empty($pageCanonical)) { echo '<link rel="canonical" href="' . $pageCanonical . '">'; }
            }
            // If meta robots content is specified, include robots meta tag
            if($pageRobots) {
                 if(!empty($pageRobots)) { echo '<meta name="robots" content="' . $pageRobots . '">'; }
            }
        ?>
        
        <meta charset="UTF-8">  
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        
        <script type="text/javascript" src="http://maps.google.com/maps/api/js?key=AIzaSyCR78jxaf-XgjrUTFxK-jfaj9J_anb-kRA"></script> 

        <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
        <link href="styles.css" rel="stylesheet" type="text/css" />     
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.2/jquery.min.js"></script>
        <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script> 
        <link rel="stylesheet" type="text/css" href="calendar/codebase/dhtmlxcalendar.css"/>
        <script type="text/javascript" src="calendar/codebase/dhtmlxcalendar.js"></script>  
        <script src="http://ajax.aspnetcdn.com/ajax/jquery.validate/1.11.1/jquery.validate.min.js"></script>
        <script src="https://js.braintreegateway.com/js/braintree-2.24.1.min.js"></script>
        <script src="http://cdnjs.cloudflare.com/ajax/libs/modernizr/2.8.2/modernizr.js"></script>
        <link rel="stylesheet" href="styles/font-awesome.min.css">
    </head>
    <?php require '/menubar.php'; ?>
    <body>
        <?php 
            //get amount from settings
        $set = "Select * from settings where type='storecredit';";
        $sres = mysqli_query($link, $set);
        if (!mysqli_query($link, $set)) {
            die(mysqli_error($link));
        } else {
            $srow = mysqli_fetch_assoc($sres);
            $valArr = explode("&", $srow['value']);
            if(!empty($valArr[1])){
                $profile = explode("profile=", $valArr[1]);
            }
        }
        
        if (isset($_SESSION['profile'])) { ?>
            <div id='completeProfile' class='row text-center'>
                <h5>Complete your profile now and get store credit worth $<?php if(!empty($profile[1])) { echo $profile[1]; } ?></h5>
            </div>
        <?php } ?>
        
        <div class="modal fade modal-fullscreen force-fullscreen" id="myModal" tabindex="-1" 
             role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog">
              <div class="modal-content">
                <div class="modal-header">
                  <button type="button" class="close" id='closeModal' data-dismiss="modal" aria-hidden="true">&times;</button>
                  <h4 class="modal-title">Modal title</h4>
                </div>
                <div class="modal-body">
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                  <button type="button" class="btn btn-primary">Save changes</button>
                </div>
              </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
          </div><!-- /.modal -->
    </body>
</html>