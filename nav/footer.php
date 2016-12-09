<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
?>

<div id="whole_footer">
    <div class="left_nav">
        <ul class="pull-left">
            <li><a href='giftcard.php'>GIFT CARDS</a></li>
            <li><a href='career.php'>CAREER</a></li>
            <li><a href='blog.php'>BLOG</a></li>
        </ul>
        <ul class ="pull-right">
            <li><a href='mailing.php' data-toggle="modal" data-target="#mailingModal">MAILING LIST</a></li>
            <li><a href='search.php' data-toggle="modal" data-target="#searchModal">SEARCH</a></li>
        </ul>
    </div>

    <div class="clearfix"></div>
    <hr id="footer-hr">

    <div class="left_nav">
        <div class="clearfix"></div>
        <div class="contact pull-left">
            CONTACT US <br>
            We are here Monday-Sunday, <br>
            12 noon - 9 p.m. SGT Singapore Time <br>
        </div>
        <div class="copyright pull-right">&#169; 2016 VISUAL MASS</div>
        <div class="clearfix"></div>
        <ul class="pull-left">
            <li><a href="tel:+65 6702 3480">PHONE</a></li>
            <li><a href="mailto:contact@visualmass.co">MAIL</a></li>
            <li><a href="#">CHAT</a></li>
        </ul>
    </div>
    <div class="clearfix"></div>
    <div class="left_nav">
        <ul class="pull-left">
            <li><a href="faq.php">FAQ</a></li>
            <li>|</li>
            <li><a href="terms.php">Terms</a></li>
            <li>|</li>
            <li>Returns & Exchanges</li>
        </ul>

        <ul class="pull-right">
            <li><a href="www.facebook.com/visualmass">FACEBOOK</a></li>
            <li><a href="www.instagram.com/visualmass">INSTAGRAM</a></li>
            <li><a href="www.twitter.com/visualmass">TWITTER</a></li>
            <li><a href="www.youtube.com/user/visualmass">YOUTUBE</a></li>
        </ul>
    </div>
</div>

<div class="modal fade modal-fullscreen force-fullscreen" id="searchModal" tabindex="-1" 
     role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title"></h4>
            </div>
            <div class="modal-body">
            </div>
            <div class="modal-footer">
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<div class="modal fade" id="mailingModal" tabindex="-1" 
     role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title"></h4>
            </div>
            <div class="modal-body">
            </div>
            <div class="modal-footer">
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<script>
    $('#searchModal').appendTo("body");
    $('#mailingModal').appendTo("body");


    $('#searchModal').on('shown.bs.modal', function () {
        $('#search').focus();
    })
</script>