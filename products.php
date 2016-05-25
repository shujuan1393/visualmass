<?php 
    require_once 'config/db.php';
?>
<html>
    <head>
        <meta charset="UTF-8">
    </head>
    <body>
        
        <div id="wrapper">
            <div id="header"><?php require_once 'nav/header.php';?></div>
            
            <div id="content">
                <?php
                    $banner = "Select * from products where type='banner';";
                    $bresult = mysqli_query($link, $banner);
                    
                    if (!mysqli_query($link, $banner)) {
                        echo "Error: ".mysqli_error($link);
                    } else {
                        if ($bresult -> num_rows == 0) {
                            echo "<h3 class='banner-title' id='banner'>There is no product banner at the moment.</h3>";
                        } else {
                            $brow = mysqli_fetch_assoc($bresult);
                            
                            $browArr = explode(".", $brow['html']);
                            $ext = $browArr[count($browArr)-1];

                            $imgArr = array("jpg", "jpeg", "png", "gif");
                            $vidArr = array("mp3", "mp4", "wma");
                            
                            $pos = strpos($brow['html'], '/');
                            $url = substr($brow['html'], $pos+1);
                            echo "<div class='webbanner'>";
                            
                            if (in_array($ext, $imgArr)) {
                                echo "<img id='banner' src='".$url."'>";
                            } else {
                                echo '<video id="banner" autoplay>
                                <source src="'.$url.'" type="video/mp4">
                                Your browser does not support the video tag.
                                </video>';
                            }
                            echo "</div>";
                        }
                    }
                ?>
                <div class='search_filter'>
                    <input type='checkbox' name='homeTry' value='yes'> Available for Home Try-on
                    <ul>
                        <li>COLOUR</li>
                        <li>WIDTH</li>
                        <li>SHAPE</li>
                        <li>MATERIAL</li>
                    </ul>
                    <div class='rightsearch'>
                        SEARCH FRAMES
                    </div>
                </div>
                <hr>
                <?php 
                    $sql = "Select * from products where type='".$_GET['type'].
                            "' and gender LIKE '%".$_GET['gender']."%'";
                    $result = mysqli_query($link, $sql);
                    
                    if (!mysqli_query($link, $sql)) {
                        echo "Error: ".mysqli_error($link);
                    } else {
                        if ($result -> num_rows == 0) {
                            echo "<h3 class='banner-title'>There are no products matching '".$_GET['type']."' and '".$_GET['gender']."' at the moment.</h3>";
                        } else {
                ?>  
                    <!--<div id='terms_content'>-->
                    <table class='product_table'>
                        <?php 
                            $count = 0;
                            echo "<tr>";
                            while ($row = mysqli_fetch_assoc($result)) {
                                $count++;
                                if (!empty($row['images'])) {
                                    $imgArr = explode(",", $row['images']);
                                    $imgpos = strpos($imgArr[0], '/');
                                    $imgurl = substr($imgArr[0], $imgpos+1);
                                    echo "<td><a href='product.php?id=".$row['pid']."'><img src='".$imgurl."'></a><br>";
                                    echo "<span class='product-title'><a href='product.php?id=".$row['pid']."'>"
                                        .$row['name']."</a></span>";
                                    echo "</td>";
                                }
                            }
                        ?>
                    </table>
                    <!--</div>-->
                <?php
                        }
                    }
                ?>
                
            </div>
            
            <div id="footer"><?php require_once 'nav/footer.php';?></div>
            
            <script>
                var clientHeight = document.getElementById('header').clientHeight;
                var height = window.innerHeight || document.documentElement.clientHeight || document.body.clientHeight;
//                alert(clientHeight + " " + height);
                document.getElementById('banner').style.maxHeight = height - clientHeight;
            </script>
        </div>
    </body>
</html>
