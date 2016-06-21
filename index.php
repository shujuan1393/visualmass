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
                    
                    $count = 0;
                    
                    if (!mysqli_query($link, $sql)) {
                        echo "Error: ".mysqli_error($link);
                    } else {
                        if ($result -> num_rows == 0) {
                            echo "<h3>Sorry, this page is under construction.</h3>";
                        } else {
                ?>  
                    <!--<div id='terms_content'>-->
                        <?php 
                            while ($row = mysqli_fetch_assoc($result)) {
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
//                                    echo "<div class='section-link' style='text-align:".$linkposArr[0]."'>";
//                                    for ($i = 0; $i < count($textArr); $i++) {
//                                        if (strcmp($linkposArr[$i], $prevpos)!==0 ) {
//                                            echo "</div>";
//                                        }
//                                        if (strcmp($linkposArr[$i], $prevpos)!==0 ) {
//                                            echo "<div class='section-link' style='text-align:".$linkposArr[$i]."'>";
//                                        }
//                                        echo "<a class='button' href='".$linkArr[$i]."'>".$textArr[$i]."</a>";
//                                        $prevpos = $linkposArr[$i];
//                                    }
                                    
                                if (strcmp($linkposArr[0], "center") === 0) {
                                    echo "<div class='section-link col-md-3' style='left: 25%; right: 25%;'>";
                                } else {
                                    echo "<div class='section-link col-md-3' style='text-align:".$linkposArr[0]."; ".$linkposArr[0].": 0;'>";
                                }
                                for ($i = 0; $i < count($textArr); $i++) {
                                    if (strcmp($linkposArr[$i], $prevpos)!==0 ) {
                                        echo "</div>";
                                    }
                                    if (strcmp($linkposArr[$i], $prevpos)!==0 ) {
                                        if (strcmp($linkposArr[$i], "center") === 0) {
                                            echo "<div class='section-link col-md-3' style='left: 25%; right: 25%;'>";
                                        } else {
                                            echo "<div class='section-link col-md-3' style='text-align:".$linkposArr[$i]."; ".$linkposArr[$i].": 0;'>";
                                        }
                                    }
                                    echo "<a class='button' href='".$linkArr[$i]."'>".$textArr[$i]."</a>";
                                    $prevpos = $linkposArr[$i];
                                }
//                                    echo "</div>";
                                }
                                
                                echo "</div>";
                                echo "</div>";
                                $count++;
                            }
                        ?>
                    <!--</div>-->
                <?php
                        }
                    }
                    
                    $advSql = "Select * from advertisements where status='active' and visibility like '%homepage%';";
                    $advres = mysqli_query($link, $advSql);
                ?>
                
            </div>
            
            <div id="footer"><?php require_once 'nav/footer.php';?></div>
            
            <script>
                var clientHeight = document.getElementById('header').clientHeight;
                var height = window.innerHeight || document.documentElement.clientHeight || document.body.clientHeight;
                document.getElementById('banner').style.maxHeight = height - clientHeight;
                
                for (var i = 0; i < <?php echo $count; ?>; i++) {
                   var str = "section" + i;
                   var sectionObj = document.getElementById(str);
                   var secheight = sectionObj.offsetTop;
                   
                <?php 
                    if (!mysqli_query($link, $advSql)) {
                        die(mysqli_error($link));
                    } else {
                        while ($advrow = mysqli_fetch_assoc($advres)) {
                            $minheight = $advrow['minheight'];
                ?>
                        if (secheight > <?php echo $minheight; ?>) {
                <?php
                    $advimg = $advrow['image'];
                    $advimagepos = strpos($advimg, '/');
                    $advimageurl = substr($advimg, $advimagepos+1);
                    $toPrint = "";
                    
                    $toPrint .= "<div class='home-section' ";
                    if (strcmp($advrow['imagepos'], "background") === 0) {
                        $image = '"'.$advimageurl.'"';
                        $toPrint .= "style='background-image: url($advimg); background-repeat: no-repeat; background-size: 500px auto;'>";
//                                    echo "<script>document.getElementById('section".$count."').style.backgroundImage = 'url('$imageurl')';"
//                                    . "document.getElementById('section".$count."').style.backgroundRepeat='no-repeat';</script>";
                    } else {
                        $toPrint .= ">";
                        $toPrint .= "<div class='section-image' style='text-align:".$row['imagepos']."; float:".$row['imagepos']."'>";
                        $toPrint .= "<img src='".$advimageurl."'>";
                        $toPrint .= "</div>";
                    }

                    if (!empty($advrow['html'])) {
                        $toPrint .= "<div class='section-text' style='float:".$advrow['htmlpos']."'>"; 
                        $toPrint .= html_entity_decode($advrow['html'])."</div>";
                    }

                    if (!empty($advrow['buttontext'])) {
                        $textArr = explode(",", $advrow['buttontext']);
                        $linkArr = explode(",", $advrow['link']);
                        $linkposArr = explode(",", $advrow['linkpos']);
                        $prevpos = $linkposArr[0];

//                                    echo "<div class='section-link'>";
                        if (strcmp($linkposArr[0], "center") === 0) {
                            $toPrint .= "<div class='section-link col-md-3' style='left: 25%; right: 25%;'>";
                        } else {
                            $toPrint .= "<div class='section-link col-md-3' style='text-align:".$linkposArr[0]."; ".$linkposArr[0].": 0;'>";
                        }
                        for ($i = 0; $i < count($textArr); $i++) {
                            if (strcmp($linkposArr[$i], $prevpos)!==0 ) {
                                $toPrint .= "</div>";
                            }
                            if (strcmp($linkposArr[$i], $prevpos)!==0 ) {
                                if (strcmp($linkposArr[$i], "center") === 0) {
                                    $toPrint .= "<div class='section-link col-md-3' style='left: 25%; right: 25%;'>";
                                } else {
                                    $toPrint .= "<div class='section-link col-md-3' style='text-align:".$linkposArr[$i]."; ".$linkposArr[$i].": 0;'>";
                                }
                            }
                            $toPrint .= "<a class='button' href='".$linkArr[$i]."'>".$textArr[$i]."</a>";
                            $prevpos = $linkposArr[$i];
                        }
                        
                    }
                    $toPrint .= "</div>";
                    $toPrint .= "</div>";
                ?>    
                    var newElm = document.createElement('div');
                    newElm.className = "home-section";
                    newElm.innerHTML = "<?php echo $toPrint; ?>";  
                    sectionObj.parentNode.insertBefore(newElm);//firstChild.nextSibling

//                            sectionObj.innerHTML = sectionObj.innerHTML + "<?php echo $toPrint; ?>";
                        }
                <?php
                        }
                    }
                ?>
                }
            </script>
        </div>
    </body>
</html>
