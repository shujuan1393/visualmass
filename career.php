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
                    $banner = "Select * from careers where type='banner';";
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
                    $sql = "Select * from careers where type='section' and status='active' order by fieldorder asc";
                    $result = mysqli_query($link, $sql);
                    
                    if (!mysqli_query($link, $sql)) {
                        echo "Error: ".mysqli_error($link);
                    } else {
                        if ($result -> num_rows !== 0) {
                ?>  
                    <div id='ourstory'>
                        <?php 
                            $count = 0;
                            $arrSize = $result -> num_rows;
                            
                            while ($row = mysqli_fetch_assoc($result)) {
                                $count++;
                                echo "<div class='row'>";
                                echo "<div class='col-md-2'></div>";
                                echo "<div class='col-md-8";
                                
                                if ($arrSize % 2 === 0 || ($arrSize % 2 === 1 && $count < $arrSize)) {
                                    echo " career_section_half";
                                } 
                                
                                if ($arrSize % 2 === 1 && $count === $arrSize) {
                                    echo " career_section_full";
                                }
                                
                                echo "'>";
                                echo "<h3>".$row['title']."</h3>";
                                echo html_entity_decode($row['html']);
                                echo "</div>";
                                echo "<div class='col-md-2'></div>";
                                echo "</div>";
                            }
                        ?>
                    </div>
                <?php
                        }
                    }
                ?>
                
                <div id='featured_jobs' class='row'>
                    <div class='col-md-8 col-md-offset-2'>
                        <h3>FEATURED JOBS</h3><br>
                        <?php 
                            $featured = "Select * from jobs where featured='yes';";
                            $fresult = mysqli_query($link, $featured);

                            if(!mysqli_query($link, $featured)) {
                                echo "Error: ".mysqli_error($link);
                            } else {
                                $featcount = 0;
                                while ($frow = mysqli_fetch_assoc($fresult)) {
                                    $featcount++;
                                    echo "<input type='hidden' id='featdesc".$featcount."' value='".$frow['html']."'>";
                                    echo "<a id='featjob".$featcount."'>".$frow['title']."</a>";
                                }
                            }
                        ?>
                        <hr>
                    </div>
                </div>

                <div id='job_openings' class='row'>
                    <div class='col-md-8 col-md-offset-2'>
                        <h3>AVAILABLE OPENINGS</h3><br>
                        <a id='showHq'>HEADQUARTERS</a>
                        <a id='showRetail'>RETAIL</a>
                        <hr>
                    </div>
                </div>
                <div id='jobs' class='row'>
                    <div class='col-md-2'></div>
                    <div id='retailJobs' class='col-md-8' style='display:none;'>
                    </div>
                    <div id='hqJobs' class='col-md-8' style='display:none;'>
                    </div>
                    <div class='col-md-2'></div>
                </div>
                <?php 
                    $jobSql = "Select * from jobs where status='active' ORDER BY type asc";
                    $res = mysqli_query($link, $jobSql);
                    
                    if(!mysqli_query($link, $jobSql)) {
                        echo "Error: ".mysqli_error($link);
                    } else {
                        $jobcount = 0;
                        while ($row = mysqli_fetch_assoc($res)) {
                            echo "<input type='hidden' id='desc".$row['id']."' value='".$row['html']."'>";
                            
                            if (strcmp($row['type'], "retail")===0) {
                                echo "<script>";
                                echo "document.getElementById('retailJobs').innerHTML +=";
                                echo "'<a id=job".$row['id'].">".$row['title']."</a>'";
                                echo "</script>";
                                
                            } else if (strcmp($row['type'], "hq")===0) {
                                echo "<script>";
                                echo "document.getElementById('hqJobs').innerHTML +=";
                                echo "'<a id=job".$row['id'].">".$row['title']."</a>'";
                                echo "</script>";
                                
                            }
                            $jobcount++;
                        }
                    }
                ?>
                <div id='job_details_row' class='row' style="display:none;">
                    <div class='col-md-2'></div>
                    <div id='job_details' class='col-md-8'>
                    <div class='col-md-2'></div>
                    </div>
                </div>
                
                <div id='job_form' class='row'>
                    <div class='col-md-8 col-md-offset-2'>
                        <h5>TO APPLY, WRITE TO US AT CONTACT@VISUALMASS.CO OR USE THE FORM BELOW</h5>
                        <form id='career_form' method='post' action='sendContact.php'>
                        <?php 
                            $formsql = "Select * from forms where type='field' and form='Career' and status='active' order by fieldorder asc";
                            $formres = mysqli_query($link, $formsql);
                            
                            while ($row = mysqli_fetch_assoc($formres)) {
                                $field = $row['field'];
//                                echo "<label>".$row['name']."</label> ";
                                if (strcmp($field, "textbox") === 0) {
                                    echo "<input type='textbox' id='".$row['name']."' name='".$row['name']."' placeholder=' ".$row['name']."'>";
                                } else if (strcmp($field, "textarea") === 0) {
                                    echo "<textarea id='".$row['name']."' name='".$row['name']."'></textarea>";
                                } else if (strcmp($field, "dropdown") === 0) {
                                    $options = $row['options'];
                                    $opArr = explode(",", $options);
                                    
                                    echo "<select id='".$row['name']."' name='".$row['name']."'>";
                                    
                                    for ($i = 0; $i < count($opArr); $i++) {
                                        echo "<option value='".$opArr[$i]."'>";
                                        echo $opArr[$i]."</option>";
                                    }
                                    
                                    echo "</select>";
                                } else if (strcmp($field, "checkbox") === 0) {
                                    $options = $row['options'];
                                    $opArr = explode(",", $options);
                                    
                                    for ($i = 0; $i < count($opArr); $i++) {
                                        echo "<input type='checkbox' id='".$row['name']."' name='".$row['name']."[]' value='".$opArr[$i]."' >";
                                        echo $opArr[$i]."<br>";
                                    }
                                }
                            }
                        ?>
                            <input type='file' name='files[]'>
                            <input type='submit' value='Submit' name='submit'>
                        </form>
                    </div>
                </div>
            </div>
            
            <div id="footer"><?php require_once 'nav/footer.php';?></div>
            
            <script>
                var clientHeight = document.getElementById('header').clientHeight;
                var height = window.innerHeight || document.documentElement.clientHeight || document.body.clientHeight;
//                alert(clientHeight + " " + height);
                document.getElementById('banner').style.maxHeight = height - clientHeight;
                
                document.getElementById('showRetail').onclick = function(){  
                    var e = document.getElementById('retailJobs');
                    if (e.style.display === 'block') {
                        e.style.display = 'none';
                    } else {
                        e.style.display = 'block';
                    }
                    if (document.getElementById('hqJobs').style.display === 'block') {
                        document.getElementById('hqJobs').style.display = 'none';
                    }
                };
                
                document.getElementById('showHq').onclick = function(){  
                    var e = document.getElementById('hqJobs');
                    if (e.style.display === 'block') {
                        e.style.display = 'none';
                    } else {
                        e.style.display = 'block';
                    }
                    if (document.getElementById('retailJobs').style.display === 'block') {
                        document.getElementById('retailJobs').style.display = 'none';
                    }
                };
                
                function handleElement(i) {
                    var j = "desc" + i;
                    var job = document.getElementById(j).value;
                    document.getElementById("job"+i).onclick=function() {
                        document.getElementById('job_details').innerHTML = job;
                        document.getElementById('job_details_row').style.display = "block";
                    };
                }

                for(var i=1; i<=<?php echo $jobcount; ?>; i++) {
                    handleElement(i);
                }
                
                function handleFeatElement(i) {
                    var j = "featdesc" + i;
                    var job = document.getElementById(j).value;
                    document.getElementById("featjob"+i).onclick=function() {
                        document.getElementById('job_details').innerHTML = job;
                        document.getElementById('job_details_row').style.display = "block";
                    };
                }

                for(var i=1; i<=<?php echo $featcount; ?>; i++) {
                    handleFeatElement(i);
                }
            </script>
        </div>
    </body>
</html>
