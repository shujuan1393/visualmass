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
                    $banner = "Select * from homepage where type='banner';";
                    $bresult = mysqli_query($link, $banner);
                    
                    if (!mysqli_query($link, $banner)) {
                        echo "Error: ".mysqli_error($link);
                    } else {
                        if ($bresult -> num_rows == 0) {
                            echo "<h3 class='banner-title'>Sorry, this page is under construction.</h3>";
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
                <?php 
                    $sql = "Select * from homepage where type='section' and status='active' order by fieldorder asc";
                    $result = mysqli_query($link, $sql);
                    
                    if (!mysqli_query($link, $sql)) {
                        echo "Error: ".mysqli_error($link);
                    } else {
                        if ($result -> num_rows == 0) {
                            echo "<h3>Sorry, this page is under construction.</h3>";
                        } else {
                ?>  
                    <!--<div id='terms_content'>-->
                        <?php 
                            $count = 0;
                            while ($row = mysqli_fetch_assoc($result)) {
                                $count++;
                                $img = $row['image'];
                                $imagepos = strpos($img, '/');
                                $imageurl = substr($img, $imagepos+1);
                                
                                echo "<div class='home-section' id='section".$count."' ";
                                if (strcmp($row['imagepos'], "background") === 0) {
                                    $image = '"'.$imageurl.'"';
                                    echo "style='background-image: url($image); background-repeat: no-repeat; background-size: 500px auto;'>";
//                                    echo "<script>document.getElementById('section".$count."').style.backgroundImage = 'url('$imageurl')';"
//                                    . "document.getElementById('section".$count."').style.backgroundRepeat='no-repeat';</script>";
                                } else {
                                    echo ">";
                                    echo "<div class='section-image' style='text-align:".$row['imagepos']."; float:".$row['imagepos']."'>";
                                    echo "<img src='".$imageurl."'>";
                                    echo "</div>";
                                }
                                
                                if (!empty($row['html'])) {
                                    echo "<div class='section-text' style='float:".$row['htmlpos']."'>"; 
                                    echo html_entity_decode($row['html'])."</div>";
                                }
                                
                                if (!empty($row['buttontext'])) {
                                    $textArr = explode(",", $row['buttontext']);
                                    $linkArr = explode(",", $row['link']);
                                    $linkposArr = explode(",", $row['linkpos']);
                                    $prevpos = $linkposArr[0];
                                    
//                                    echo "<div class='section-link'>";
                                    echo "<div class='section-link' style='text-align:".$linkposArr[0]."'>";
                                    for ($i = 0; $i < count($textArr); $i++) {
                                        if (strcmp($linkposArr[$i], $prevpos)!==0 ) {
                                            echo "</div>";
                                        }
                                        if (strcmp($linkposArr[$i], $prevpos)!==0 ) {
                                            echo "<div class='section-link' style='text-align:".$linkposArr[$i]."'>";
                                        }
                                        echo "<a class='button' href='".$linkArr[$i]."'>".$textArr[$i]."</a>";
                                        $prevpos = $linkposArr[$i];
                                    }
//                                    echo "</div>";
                                }
                                
                                echo "</div>";
                                echo "</div>";
                            }
                        ?>
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
