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
    </head>
    <body>
        
        <div id="wrapper">
            <div id="header"><?php require_once 'nav/header.php';?></div>
            
            <div id="content">
                <div class='row'>
                    <div >
                        <h3>FAVOURITES</h3>
                        
                        <div id='product_table' class='products row'>
                        <div id='noFavourites' style='display: none;text-align: center; margin: 20px;'><h4>No favourites yet :( </h4></div>
                        <?php 
                            $fav = "Select * from favourites where email='".$_SESSION['loggedUserEmail']."';";
                            $fres = mysqli_query($link, $fav);

                            if (!mysqli_query($link, $fav)) {
                                echo "Error: ".mysqli_error($link);
                            } else {
                                if ($fres -> num_rows === 0) {
                                        echo "<script>document.getElementById('noFavourites').style.display='block';</script>";                                    
                                } else {
                                    $frow = mysqli_fetch_assoc($fres);
                                    if (strcmp($frow['pid'], "")===0) {
                                        echo "<script>document.getElementById('noFavourites').style.display='block';</script>";
                                    } else {
                                        echo "<script>document.getElementById('noFavourites').style.display='none';</script>";
                                        $favArr = explode(",", $frow['pid']);
                                        for ($i = 0; $i < count($favArr); $i++) {
                                            $sql = "select * from products where pid='".$favArr[$i]."';";
                                            $result = mysqli_query($link, $sql);

                                            if (!mysqli_query($link, $sql)) {
                                                echo 'Error: '.mysqli_query($link, $sql);
                                            } else {
                                                $row = mysqli_fetch_assoc($result);
                                                
                                                if (strcmp($row['pid'], "") !== 0) {
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

                                                        echo '<li><a id="heart" href="addFavourite.php?delete=1&id='.$row['pid'].'"><i class="fa fa-heart fa-2x" aria-hidden="true"></i></a></li>';
                                                    echo '</ul></div>';
                                                    echo "</div>";
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        ?>
                        </div>
                    </div>
                </div>
            </div>
            
            <div id="footer"><?php require_once 'nav/footer.php';?></div>
        </div>
    </body>
</html>
