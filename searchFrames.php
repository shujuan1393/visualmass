<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
require_once 'config/db.php';
$fav = "Select * from favourites where email='".$_SESSION['loggedUserEmail']."';";
$fres = mysqli_query($link, $fav);

if (!mysqli_query($link, $fav)) {
    echo "Error: ".mysqli_error($link);
} else {
    $frow = mysqli_fetch_assoc($fres);
    $favArr = explode(",", $frow['pid']);
}
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
                <form id='searchFramesForm' autofocus>
                    <input type='hidden' name='submitted' id='submitted' value='1'/>

                    <input type='text' name='search' id='searchFrames'  maxlength="50" 
                           placeholder="SEARCH FRAMES" value="<?php if (isset($_SESSION['searchVal'])) { echo $_SESSION['searchVal']; }?>"/>
                </form>
                
                <div class="searchResults">
                    <?php
                        if (isset($_SESSION['searchResult'])) {
                            $pidArr = explode(",", $_SESSION['searchResult']);
                            
                            for ($i = 0; $i < count($pidArr); $i++) {
                                $pid = $pidArr[$i];
                                if (strcmp($pid, "") !== 0) {
                                    $sql = "Select * from products where pid='".$pidArr[$i]."';";
                                    $res = mysqli_query($link, $sql);

                                    if (!mysqli_query($link, $sql)) {
                                        die(mysqli_error($link));
                                    } else {
                                        $row = mysqli_fetch_assoc($res);

                                        $imgArr = explode(",", $row['images']);

                                        $imgpos = strpos($imgArr[0], '/');
                                        $imgurl = substr($imgArr[0], $imgpos+1);
                                        echo "<div class='products col-md-4'>";
                                        echo "<a href='product.php?id=".$row['pid']."'><img src='".$imgurl."'></a><br>";
                                        echo "<div class='product_name col-md-2'><a href='product.php?id=".$row['pid']."'>".$row['name']."</a></div>";
                                        echo '<div class="cart_icons col-md-3">'
                                        . '<ul>'
                                                . '<li><a class="addcart" href="addCart.php?type=purchase&id='.$row['pid'].
                                                '"><i class="fa fa-shopping-cart fa-2x" aria-hidden="true"></i></a></li>';
                                        if (in_array($row['pid'], $favArr)) {
                                            echo '<li><a id="heart" href="addFavourite.php?delete=1&id='.$row['pid'].'"><i class="fa fa-heart fa-2x" aria-hidden="true"></i></a></li>';
                                        } else {
                                            echo '<li><a id="heart" href="addFavourite.php?id='.$row['pid'].'"><i class="fa fa-heart-o fa-2x" aria-hidden="true"></i></a></li>';
                                        }
                                        echo '</ul></div>';
                                        echo "</div>";
                                    }
                                }
                            }
                        }
                    ?>
                </div>
            </div>
            <!--<div id="footer"><?php // require_once 'nav/footer.php';?></div>-->
        </div>
    </body>
    <script>        
        $('#searchFrames').keypress(function (e) {
            if (e.which === 13) {
              $('form#searchFramesForm').submit();
              return false;    //<---- Add this line
            }
        });
        
        $('#searchFramesForm').validate({
            rules: {
                search: {
                    required: true
                }
            },
            highlight: function(element) {
            $(element).closest('.control-group').removeClass('success').addClass('error');
            },
            success: function(element) {
                element
                .text('OK!').addClass('valid')
                .closest('.control-group').removeClass('error').addClass('success');
            },
            submitHandler: function(form) {
                $.ajax({
                    type:"POST",
                    url: "processSearch.php?frames=1",
                    data: $(form).serialize(),
                    }).done(function(data) {
                    $('#searchWrapper').replaceWith(data);
                    });
            }
        });
    </script>
</html>
