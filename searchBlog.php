<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
require_once 'config/db.php';
?>
<head>
    <link href="styles.css" rel="stylesheet" type="text/css" />
</head>
<html>
    <body>
        <div id="searchWrapper">
            <div class="rightheader close_modal">
                <button type="button" id='closeModal' class="close" data-dismiss="modal" aria-hidden="true">X</button>
            </div>
            <div class="logo"></div>
            <div id='login'>
                <div id="searchError" class="error">
                    <?php 
                        if (isset($_SESSION['searchError'])) {
                            echo $_SESSION['searchError'];
                        }
                    ?>
                </div>
                <form id='searchBlogForm' autofocus>
                    <input type='hidden' name='submitted' id='submitted' value='1'/>

                    <input type='text' name='search' id='searchBlog'  maxlength="50" 
                           placeholder="SEARCH BLOG" value="<?php if (isset($_SESSION['searchVal'])) { echo $_SESSION['searchVal']; }?>"/>
                </form>
                
                <div class="searchResults">
                    <?php
                        if (isset($_SESSION['searchResult'])) {
                            $idArr = explode(",", $_SESSION['searchResult']);
                            
                            for ($i = 0; $i < count($idArr); $i++) {
                                $bid = $idArr[$i];
                                if (strcmp($bid, "") !== 0) {
                                    $sql = "Select * from blog where id='".$bid."';";
                                    $res = mysqli_query($link, $sql);

                                    if (!mysqli_query($link, $sql)) {
                                        die(mysqli_error($link));
                                    } else {
                                        $row = mysqli_fetch_assoc($res);

                                        echo "<div class='col-md-12'>";
                                        echo "<h5>Article Name: ".$row['title']."</h5>";
                                        echo "<h5>Written by: ".$row['author']."</h5>";
                                        if (!empty($row['excerpt'])) {
                                            echo html_entity_decode($row['excerpt']);
                                        } else {
                                            echo html_entity_decode($row['html']);
                                        }
                                        echo "<a href='blog.php?id=".$bid."'>READ > </a>";
                                        echo "</div>";
                                    }
                                }
                            }
                        }
                        unset($_SESSION['searchVal']);
                        unset($_SESSION['searchResult']);
                    ?>
                </div>
            </div>
            <!--<div id="footer"><?php // require_once 'nav/footer.php';?></div>-->
        </div>
    </body>
    <script>        
        $('#searchBlog').keypress(function (e) {
            if (e.which === 13) {
              $('form#searchBlogForm').submit();
              return false;    //<---- Add this line
            }
        });
        
        $('#searchBlogForm').validate({
            rules: {
                search: {
                    required: true
                }
            },
            highlight: function(element) {
            $(element).closest('.control-group').removeClass('success').addClass('error');
            },
            submitHandler: function(form) {
                $.ajax({
                    type:"POST",
                    url: "processSearch.php?blog=1",
                    data: $(form).serialize(),
                    }).done(function(data) {
                    $('#searchWrapper').replaceWith(data);
                    });
            }
        });
    </script>
</html>
