<?php 
    require_once 'config/db.php';
?>
<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->

<html>
    <head>
        <meta charset="UTF-8">
        <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
        <link href="styles.css" rel="stylesheet" type="text/css" />     
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.2/jquery.min.js"></script>
        <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script> 
        <script src="http://ajax.aspnetcdn.com/ajax/jquery.validate/1.11.1/jquery.validate.min.js"></script> 
    </head>
    <body>
        
        <div id="wrapper">
            <div id="header">
                <div class='left_nav'>
                    <ul>
                        <li><a href='index.php'>SHOP AT VISUAL MASS</a></li>
                    </ul>
                    <div class='blog_logo'></div>
                </div>                
            </div>
            
            <div id="content">
                <div class='row'>
                    <div class='col-md-2'></div>
                    <div class='col-md-8'><h3 class='blog_title_page'>THE BLOG</h3></div>

                    <div class='col-md-2'>
                        <a href='searchBlog.php' data-toggle="modal" data-target="#searchModal">SEARCH BLOG</a>
                    </div>
                </div>
                <?php 
                    $sql = "Select * from categories where type='blog'";
                    $result = mysqli_query($link, $sql);
                    
                    if (!mysqli_query($link, $sql)) {
                        echo "Error: ".mysqli_error($link);
                    } else {
                        if ($result -> num_rows == 0) {
                            echo "<h3>Sorry, this page is under construction.</h3>";
                        } else {
                ?>  
                    <div id='terms_nav'>
                        <ul>
                            <?php
                                while($row = mysqli_fetch_assoc($result)) {
                                    $id = $row['name'];
                                    echo "<li><a href='blog.php?cat=".$id."'>".
                                            $row['name']."</a></li>";
                                } 
                            ?>
                        </ul>
                    </div>
                    <div id='blog_content'>
                        <?php 
                            if (isset($_GET['cat'])) {
                                $get = "Select * from blog where categories LIKE '%".$_GET['cat']."%';";
                            } else if (isset($_GET['id'])) { 
                                $get = "Select * from blog where id = '".$_GET['id']."';";
                            } else {
                                $get = "Select * from blog order by dateposted desc";
                            }
                            $result = mysqli_query($link, $get);
                            $count = 0;
                            
                            while ($row = mysqli_fetch_assoc($result)) {
                                echo "<div class='blog_post'>";
                                $pos = strpos($row['image'], '/');
                                $url = substr($row['image'], $pos+1);
                                echo "<img src='$url'><br>";
                                echo "<div class='blog_author'>";
                                echo $row['author']."&nbsp;&nbsp;".date("M d, Y", strtotime($row['dateposted']));
                                echo "</div>";
                                echo "<span class='blog_title'>".$row['title']."</span><br>";
                                if (!empty($row['excerpt'])) {
                                    $count++;
                                    echo "<div id='excerpt'>".html_entity_decode($row['excerpt']);
                                    echo "</div><br>";
                                    echo "<div id='readMore".$count."' class='read_link'>+ READ MORE</div>";    
                                    echo "<div id='content".$count."' style='display:none;'>";
                                }
                                    echo html_entity_decode($row['html']);
                                if (!empty($row['excerpt'])) {
                                    echo "</div>";
                                }
                                echo "</div>";
                            }
                        ?>
                    </div>
                <?php
                        }
                    }
                ?>
                
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
            <div id="footer"><?php // require_once 'nav/footer.php';?></div>
            <script>
                if (<?php echo $count;?> > 0) {
                    document.getElementById('readMore<?php echo $count; ?>').onclick = function(){  
                        var e = document.getElementById('content<?php echo $count; ?>');
                        if (e.style.display == 'block') {
                             e.style.display = 'none';
                             document.getElementById('readMore<?php echo $count; ?>').innerHTML = '+ READ MORE';
                         } else {
                             e.style.display = 'block';
                             document.getElementById('readMore<?php echo $count; ?>').innerHTML = '- READ LESS';
                         }
                     };
                 }
            </script>
        </div>
    </body>
</html>