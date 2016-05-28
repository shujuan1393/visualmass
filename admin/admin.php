<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
require_once '../config/db.php';

unset($_SESSION['updateError']);
unset($_SESSION['updateSuccess']);
unset($_SESSION['addEmpSuccess']);
unset($_SESSION['addEmpError']);

unset($_SESSION['updateEmpTypeError']);
unset($_SESSION['updateEmpTypeSuccess']);
unset($_SESSION['addEmpTypeSuccess']);
unset($_SESSION['addEmpTypeError']);

unset($_SESSION['randomString']);
unset($_SESSION['addLocError']);
unset($_SESSION['addLocSuccess']);
unset($_SESSION['uploadLocError']);
unset($_SESSION['updateLocSuccess']);
unset($_SESSION['updateLocError']);
unset($_SESSION['editUpdateLocError']);

unset($_SESSION['editUpdateProdError']);
unset($_SESSION['updateProdError']);
unset($_SESSION['updateProdSuccess']);
unset($_SESSION['addProdError']);
unset($_SESSION['addProdSuccess']);

unset($_SESSION['editUpdateDiscError']);
unset($_SESSION['updateDiscError']);
unset($_SESSION['updateDiscSuccess']);
unset($_SESSION['addDiscError']);
unset($_SESSION['addDiscSuccess']);

unset($_SESSION['updateMediaError']);
unset($_SESSION['updateMediaSuccess']);

unset($_SESSION['updateServSuccess']);
unset($_SESSION['addServSuccess']);
unset($_SESSION['updateServError']);
unset($_SESSION['addServError']);

unset($_SESSION['gensetError']);
unset($_SESSION['updateGenSetSuccess']);
unset($_SESSION['updateGcsetError']);
unset($_SESSION['updateGcSetSuccess']);
unset($_SESSION['updateHTSetSuccess']);
unset($_SESSION['updateAccSetSuccess']);
unset($_SESSION['updateNotiSetSuccess']);
unset($_SESSION['updateCheckSetSuccess']);
unset($_SESSION['updateCheckSetError']);

unset($_SESSION['addAdvError']);
unset($_SESSION['addAdvSuccess']);
unset($_SESSION['uploadAdvError']);
unset($_SESSION['updateAdvSuccess']);
unset($_SESSION['updateAdvError']);
unset($_SESSION['editUpdateAdvError']);

unset($_SESSION['addBlogError']);
unset($_SESSION['addBlogSuccess']);
unset($_SESSION['uploadBlogError']);
unset($_SESSION['updateBlogSuccess']);
unset($_SESSION['updateBlogError']);
unset($_SESSION['editUpdateBlogError']);

unset($_SESSION['addFaqError']);
unset($_SESSION['addFaqSuccess']);
unset($_SESSION['addFaqBannerError']);
unset($_SESSION['addFaqBannerSuccess']);
unset($_SESSION['updateFaqSuccess']);
unset($_SESSION['updateFaqError']);

unset($_SESSION['addTermError']);
unset($_SESSION['addTermSuccess']);
unset($_SESSION['updateTermSuccess']);
unset($_SESSION['updateTermError']);

unset($_SESSION['updateInvError']);
unset($_SESSION['updateInvSuccess']);
unset($_SESSION['profileError']);
unset($_SESSION['profileSuccess']);
unset($_SESSION['updateContactSuccess']);
unset($_SESSION['updateContactError']);
unset($_SESSION['addContactSuccess']);
unset($_SESSION['addContactError']);
unset($_SESSION['setContactDetailsError']);
unset($_SESSION['setContactDetailsSuccess']);

unset($_SESSION['addProdBannerSuccess']);
unset($_SESSION['addProdBannerError']);
?>
<script>
    var date = new Date();
    var hrs = date.getHours();
    var welcome = "";
    
    if(hrs < 12) {
        welcome = "Good morning, ";
    }else if(hrs < 17) {
        welcome = "Good afternoon, ";
    }else if(hrs < 21) {
        welcome = "Good evening, ";
    }else {
        welcome = "Good night, ";
    }
</script>
<html>
    <div id="frameheader">
        <?php
            require '../nav/adminHeader.php';
        ?>
    </div>
    <div id="framecontent">
        <?php
            require '../nav/adminSidebar.php';
        ?>
    </div>
    <div id="maincontent">
        <div class="innertube">
            <h2><?php 
                    echo $welcome = "<script>document.write(welcome)</script>" .$_SESSION['loggedUser'];
                ?></h2>
        </div>
    </div>
    
</html>

