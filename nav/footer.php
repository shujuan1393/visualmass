<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
    
?>

<html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
    </head>
    <body>
        <div id='whole_footer'>
        <div class="left_nav">
            <ul>
                <li><a href='giftcard.php'>GIFT CARDS</a></li>
                <li><a href='career.php'>CAREER</a></li>
                <li><a href='blog.php'>BLOG</a></li>
            </ul>
        </div>
        <div class ="rightfooter">
            <ul>
                <li><a href='mailing.php' data-toggle="modal" data-target="#mailingModal">MAILING LIST</a></li>
                <li><a href='search.php' data-toggle="modal" data-target="#searchModal">SEARCH</a></li>
            </ul>
        </div>
        <hr>
        
        <div class="left_nav">
            <p class='contact'>CONTACT US <br>
            We are here Monday-Sunday, <br>
            12 noon - 9p.m., SGT-Singapore Time <br>
            </p>
        </div>
        <div class='left_nav'>
            <ul>
                <li>ICON</li>
                <li>ICON</li>
                <li>ICON</li>
            </ul>
        </div>
        <div class="rightcontact">
            <p class='copyright'>&#169; 2016 VISUAL MASS</p>
        </div>
        
        <div class="left_nav">
            <ul>
                <li><a href='faq.php'>FAQs</a></li>
                <li>|</li>
                <li><a href='terms.php'>Terms</a></li>
                <li>|</li>
                <li>Returns & Exchanges</li>
            </ul>
        </div>
        <div class ="righticons">
            <ul>
                <li>ICON</li>
                <li>ICON</li>
                <li>ICON</li>
            </ul>
            <?php 
//                echo "Welcome, " .$_SESSION['loggedUser']; 
//                echo "&nbsp<a href='../logout.php'>Logout</a>";
            ?>
        </div>
        </div>
        <div class="modal fade modal-fullscreen force-fullscreen" id="searchModal" tabindex="-1" 
             role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog">
              <div class="modal-content">
                <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
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
          
          <div class="modal fade" id="mailingModal" tabindex="-1" 
             role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog">
              <div class="modal-content">
                <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
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
    
    <script>
        $('#searchModal').appendTo("body");
        $('#mailingModal').appendTo("body");
    </script>
</html>