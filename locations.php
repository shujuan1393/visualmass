<?php 
    require_once 'config/db.php';
 
    // function to geocode address, it will return false if unable to geocode address
    function geocode($add){

        // url encode the address
        $address = urlencode($add);
        
        // google map geocode api url
        $url = "http://maps.google.com/maps/api/geocode/json?address={$address}";

        // get the json response
        $resp_json = file_get_contents($url);

        // decode the json
        $resp = json_decode($resp_json, true);

        // response status will be 'OK', if able to geocode given address 
        if($resp['status']=='OK'){
            // get the important data
            $lati = $resp['results'][0]['geometry']['location']['lat'];
            $longi = $resp['results'][0]['geometry']['location']['lng'];
            $formatted_address = $resp['results'][0]['formatted_address'];

            // verify if data is complete
            if($lati && $longi && $formatted_address){

                // put the data in the array
                $data_arr = array();     
                array_push(
                    $data_arr, 
                        $lati, 
                        $longi, 
                        $formatted_address
                    );

                return $data_arr;

            }else{
                return false;
            }
        }else{
            return false;
        }
    }
?>
<html>
    <head>
        <meta charset="UTF-8">
    </head>
    <body>
        
        <div id="wrapper">
            <div id="header"><?php require_once 'nav/header.php';?></div>
            
            <div id="locContent">
                <?php
                    $banner = "Select * from locations where name='banner';";
                    
                    $bresult = mysqli_query($link, $banner);
                    
                    if (!mysqli_query($link, $banner)) {
                        echo "Error: ".mysqli_error($link);
                    } else {
                        if ($bresult -> num_rows == 0) {
                            echo "<h3 id='banner' class='banner-title'>Sorry, this page is under construction.</h3>";
                        } else {
                            $brow = mysqli_fetch_assoc($bresult);
                            $browArr = explode(".", $brow['featured']);

                            $ext = $browArr[count($browArr)-1];

                            $imgArr = array("jpg", "jpeg", "png", "gif");
                            $vidArr = array("mp3", "mp4", "wma");
 
                            $pos = strpos($brow['featured'], '/');
                            $url = substr($brow['featured'], $pos+1);
                            echo "<div id='banner_space' class='webbanner'>";
                            
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
                
                <div id='service_filter'>
                    <div class='heading row'>
                        <div class='col-md-6'>
                            <h3>IMAGE | RETAIL STORES</h3>
                        </div>
                        <div class='col-md-6'>
                            <h3>IMAGE | POP-UP STORES</h3>
                        </div>
                    </div>
                    <div id='loc_header' class='row'>
                        <div class='services text-center col-md-10 col-md-offset-1'>
                            <?php 
                                $services = "Select * from services;";
                                $sres = mysqli_query($link, $services);

                                if (!mysqli_query($link, $services)) {
                                    echo "Error: " . mysqli_error($link);
                                } else {
                                    if ($sres -> num_rows === 0) {
                                        echo "<h5>There are no services available for filter.</h5>";
                                    } else {
                                        $servcount = 0;
                                        while($row = mysqli_fetch_assoc($sres)) {
                                            echo "<div class='service text-center col-md-3'>";
                                            echo "<input type='hidden' id='serviceval".$servcount."' value='".$row['servicecode']."'>";
                                            echo "<a id='serv".$servcount."'>".$row['servicename']."</a>";
                                            echo "</div>";
                                            $servcount++;
                                        }
                                    }
                                }
                            ?>
                        </div>
                        <div id='loc-search' class='col-md-1 col-md-offset-11'>
                            SEARCH
                        </div>
                        <div class='col-md-12'><hr></div>
                    </div>
                </div>
                
                <div class='row'>
                    <div id='loc_sidemenu' class='col-md-2'>
                        <div id='scrollable_loc' style='float:left;'>
                            <ul>
                                <?php 
                                    $ret = "Select * from locations where type='retail';";
                                    $retres = mysqli_query($link, $ret);

                                    if (!mysqli_query($link, $ret)) {
                                        echo "Error: ".mysqli_error($link);                                
                                    } else {
                                        echo "<li class='store_header'>RETAIL STORE</li>";
                                        $locCount = 0;
                                        while($row = mysqli_fetch_assoc($retres)) {
                                            echo "<li><a href='#".$row['name']."' id='link".$locCount."' onclick='makeActive(".$locCount.")'>".$row['name']."</a></li>";
                                            $locCount++;
                                        }
                                    }

                                    $pop = "Select * from locations where type='popup';";
                                    $popres = mysqli_query($link, $pop);

                                    if (!mysqli_query($link, $pop)) {
                                        echo "Error: ".mysqli_error($link);                                
                                    } else {
                                        echo "<li class='store_header'>POP-UP STORE</li>";
                                        while($row = mysqli_fetch_assoc($popres)) {
                                            echo "<li><a href='#".$row['name']."' id='link".$locCount."' onclick='makeActive(".$locCount.")'>".$row['name']."</a></li>";
                                            $locCount++;
                                        }
                                    }
                                ?>
                            </ul>
                        </div>
                    </div>
                    
                <div id='retail_locs' class='col-md-9'>
                    <h3 id='retail'>RETAIL</h3>
                    <div id='retail_filter' style='display: none;'>
                        <h4>There are no retail stores for this filter.</h4>
                    </div>
                    
                    <div id='retails' class='col-md-10 col-md-offset-2'>
                    <?php 
                        $retail = "Select * from locations where type='retail';";
                        $retailres = mysqli_query($link, $retail);
                        
                        if (!mysqli_query($link, $retail)) {
                            echo "Error: ".mysqli_error($link);
                        } else {
                            $count = 0;
                            while ($row = mysqli_fetch_assoc($retailres)) {
                                $pos = strpos($row['featured'], '/');
                                $url = substr($row['featured'], $pos+1);
                                
                                echo "<div id='loc".$count."' class='row'>";
                                    echo "<div id='retailadd".$count."' class='col-md-10'>";
                                    echo "<h4 id='".$row['name']."'><a href='location.php?id=".$row['id']."'>".$row['name']."</a></h4>";
                                    echo "<p>".$row['address']." ".$row['apt']. "</p>";
                                    echo "<p>".$row['country']." ".$row['zip']."</p>";
                                    echo "</div>";
                                    echo "<div id='storeimg' class='col-md-5'><img src='".$url."' style='width:100%'></div>";
                                    echo "<div class='col-md-5'>";

                                    /******* GEOCODE ******/

                                    // get latitude, longitude and formatted address
                                    $data_arr = geocode($row['country']. " ". $row['zip']);

                                    // if able to geocode the address
                                    if($data_arr){

                                        $latitude = $data_arr[0];
                                        $longitude = $data_arr[1];
                                        $formatted_address = $data_arr[2];

                                    ?>

                                    <!-- google map will be shown here -->
                                    <!--<div id="gmap_canvas" style='width: 100%;'>Loading map...</div>-->
                                    <div id="gmap_canvas<?php echo $count;?>" style='width: 100%; height:70%; margin: 5px;'>Loading map...</div>

                                    <!-- JavaScript to show google map -->
                                    <script type="text/javascript">
                                        function init_map() {
                                            var myOptions = {
                                                zoom: 14,
                                                center: new google.maps.LatLng(<?php echo $latitude; ?>, <?php echo $longitude; ?>),
                                                mapTypeId: google.maps.MapTypeId.ROADMAP
                                            };

                                            var mapname = "gmap_canvas" + <?php echo $count;?>;
                                            map = new google.maps.Map(document.getElementById(mapname), myOptions);
    //                                            map = new google.maps.Map(document.getElementById("gmap_canvas"), myOptions);

                                            var image = {
                                                url: 'images/Monogram.png', 
                                                scaledSize: new google.maps.Size(50, 50)   
                                            }; 
                                            marker = new google.maps.Marker({
                                                map: map,
                                                position: new google.maps.LatLng(<?php echo $latitude; ?>, <?php echo $longitude; ?>),
                                                icon: image
                                            });
    //                                        infowindow = new google.maps.InfoWindow({
    //                                            content: "<?php echo $formatted_address; ?>"
    //                                        });
                                            google.maps.event.addListener(marker, "click", function () {
                                                infowindow.open(map, marker);
                                            });
    //                                        infowindow.open(map, marker);
                                        }

                                        google.maps.event.addDomListener(window, 'load', init_map);
                                    </script>

                                    <?php
                                    // if unable to geocode the address                                
                                    }else{
                                        echo "No map found.";
                                    }

                                    $servArr = explode(",", $row['services']);

                                    echo "</div>";
                                    echo "<div id='loc_service".$count."' class='loc_serv col-md-10'>";
                                    echo "<ul>";

                                    for ($i = 0; $i < count($servArr); $i++) {
                                        echo "<li servId='".$servArr[$i]."'>".$servArr[$i]."</li>";
                                    }                                
                                    echo "</ul>";
                                    echo "</div>";
                                echo "</div>";
                                $count++;
                            }
                        }
                    ?>
                    </div>
                </div>
                
                </div>
                <div id='popup_locs' class='row'>
                    <h3 id='popup'>POP-UP</h3>
                    <div id='popup_filter' style='display: none;'>
                        <h4>There are no popup stores for this filter.</h4>
                    </div>
                    <div id='pops' class='col-md-10 col-md-offset-2'>
                    <?php 
                        $popup = "Select * from locations where type='popup';";
                        $popupres = mysqli_query($link, $popup);
                        
                        if (!mysqli_query($link, $popup)) {
                            echo "Error: ".mysqli_error($link);
                        } else {
                            while ($row = mysqli_fetch_assoc($popupres)) {
                                $pos = strpos($row['featured'], '/');
                                $url = substr($row['featured'], $pos+1);
                                
                                echo "<div id='loc".$count."' class='row'>";
                                echo "<div id='popupadd".$count."' class='col-md-10'>";
                                echo "<h4 id='".$row['name']."'><a href='location.php?id=".$row['id']."'>".$row['name']."</a></h4>";
                                echo "<p>".$row['address']." ".$row['apt']. "</p>";
                                echo "<p>".$row['country']." ".$row['zip']."</p>";
                                echo "</div>";
                                echo "<div id='storeimg' class='col-md-5'><img src='".$url."' style='width:100%'></div>";
                                echo "<div class='col-md-5'>";
                                
                                /******* GEOCODE ******/
                                
                                // get latitude, longitude and formatted address
                                $data_arr = geocode($row['country']. " ". $row['zip']);

                                // if able to geocode the address
                                if($data_arr){

                                    $latitude = $data_arr[0];
                                    $longitude = $data_arr[1];
                                    $formatted_address = $data_arr[2];

                                ?>

                                <!-- google map will be shown here -->
                                <!--<div id="gmap_canvas" style='width: 100%;'>Loading map...</div>-->
                                <div id="gmap_canvas<?php echo $count;?>" style='width: 100%; height:70%; margin: 5px;'>Loading map...</div>
                                
                                <!-- JavaScript to show google map -->
                                <script type="text/javascript">
                                    function init_map() {
                                        var myOptions = {
                                            zoom: 14,
                                            center: new google.maps.LatLng(<?php echo $latitude; ?>, <?php echo $longitude; ?>),
                                            mapTypeId: google.maps.MapTypeId.ROADMAP
                                        };
                                        
                                        var mapname = "gmap_canvas" + <?php echo $count;?>;
                                        map = new google.maps.Map(document.getElementById(mapname), myOptions);
//                                            map = new google.maps.Map(document.getElementById("gmap_canvas"), myOptions);
                                        
                                        var image = {
                                            url: 'images/Monogram.png',
                                            scaledSize: new google.maps.Size(20, 20)    
                                        }; 
                                        
                                        marker = new google.maps.Marker({
                                            map: map,
                                            position: new google.maps.LatLng(<?php echo $latitude; ?>, <?php echo $longitude; ?>),
                                            icon: image
                                        });
//                                        infowindow = new google.maps.InfoWindow({
//                                            content: "<?php echo $formatted_address; ?>"
//                                        });
                                        google.maps.event.addListener(marker, "click", function () {
                                            infowindow.open(map, marker);
                                        });
//                                        infowindow.open(map, marker);
                                    }
                                    
                                    google.maps.event.addDomListener(window, 'load', init_map);
                                </script>

                                <?php
                                // if unable to geocode the address                                
                                }else{
                                    echo "No map found.";
                                }
                                
                                $pservArr = explode(",", $row['services']);
                                
                                echo "</div>";
                                echo "<div id='loc_service".$count."' class='loc_serv col-md-10'>";
                                echo "<ul>";
                                
                                for ($i = 0; $i < count($pservArr); $i++) {
                                    echo "<li servId='".$pservArr[$i]."'>".$pservArr[$i]."</li>";
                                }                                
                                echo "</ul>";
                                echo "</div>";
                                echo "</div>";
                                $count++;
                            }
                        }
                    ?>
                    </div>
                </div>
                
            </div>
            
            <div id="footer"><?php require_once 'nav/footer.php';?></div>
            
            <script>
                function makeActive(num) {
                    for(var i = 0; i < <?php echo $locCount; ?>; i++) {
                        var link = "link" + i;
                        document.getElementById(link).style.fontWeight = "normal";
                    }
                    
                    var selected = "link" + num;
                    document.getElementById(selected).style.fontWeight = "bold";
                }
                
                var floating = $('#scrollable_loc');
                var fixtop = floating.offset().top;
                
                var lowpoint = $('#whole_footer').offset().top;
                
                $(window).scroll(function() {
                    var current = $(window).scrollTop();
                    floating.addClass('above');
                    if (current >= fixtop && current <= lowpoint) {
                        floating.addClass('stuck').css('top', 60);
                    } else if (current > lowpoint) {
                        floating.removeClass('stuck').css('top', lowpoint);
                    } else if (current < fixtop) {
                        floating.removeClass('stuck').css('top', 60);
                    }
                });
                
                var clientHeight = document.getElementById('header').clientHeight;
                var height = window.innerHeight || document.documentElement.clientHeight || document.body.clientHeight;
//                alert(clientHeight + " " + height);
                var obj = document.getElementById('banner');
                
                if (obj !== null) {
                    obj.style.maxHeight = height - clientHeight;
                }
                
                for (var i = 0; i < <?php echo $count; ?>; i++) {
                    var str = "loc" + i;
                    document.getElementById(str).style.height = "100%";
                }
//                
                function resetLocs() {
                    for (var i = 0; i < <?php echo $count; ?>; i++) {
                        var locToHide = "loc"+i;
                        document.getElementById(locToHide).style.display = "block"; 
                    }
                }
                
                function findLocs(value) {
                    for (var l = 0; l < <?php echo $count; ?>; l++) {
                        var loc = "loc_service" + l;
                        var location = document.getElementById(loc);
                        var display = false;
                        
                        var allservices = location.getElementsByTagName('li');
                        for (var i = 0; i < allservices.length; i++) {
                            if (allservices[i].getAttribute('servId').indexOf(value) > -1) {
                                display = true;
                            } 
                        }
                        if (!display) {
                            var locToHide = "loc"+l;
                            document.getElementById(locToHide).style.display = "none";
                            var retail = document.getElementById('retails');
                            var allretails = retail.getElementsByTagName('div');
                            var retdisplay = false;
                            var oneret = true;
                            
                            for (var i = 0; i < allretails.length; i++) {
                                if (allretails[i].style.display === "block") {
                                    oneret = false;
                                } 
                            }
                            
                            var popup = document.getElementById('pops');
                            var allpops = popup.getElementsByTagName('div');
                            var popdisplay = false;
                            var onepop = true;
                            
                            for (var i = 0; i < allpops.length; i++) {
                                if (allpops[i].style.display === "block") {
                                    onepop = false;
                                } 
                            }
                            
                            if (oneret) {
                                retdisplay = true;
                            }
                            
                            if (onepop) {
                                popdisplay = true;
                            }
                        }
                    }
                    
                    if (retdisplay && !popdisplay) {
                        document.getElementById('retail_filter').style.display = "block";
                        document.getElementById('popup_filter').style.display = "none";
                    } else if (!retdisplay && popdisplay) {
                        document.getElementById('retail_filter').style.display = "none";
                        document.getElementById('popup_filter').style.display = "block";
                    } else if (retdisplay && popdisplay) {
                        document.getElementById('retail_filter').style.display = "block";                                
                        document.getElementById('popup_filter').style.display = "block";
                    } else if (!retdisplay && ! popdisplay) {
                        document.getElementById('retail_filter').style.display = "none";
                        document.getElementById('popup_filter').style.display = "none";                                
                    }
                }
                
                function filterServices(s) {
                    var service = "serv"+s;
                    document.getElementById(service).onclick = function() {
                        resetLocs();
                        var val = "serviceval" + s;
                        var value = document.getElementById(val).value;
                        findLocs(value);
                    };
                }
                
                for (var s = 0; s < <?php echo $servcount; ?>; s++) {
                    filterServices(s);
                }
                
            </script>
        </div>
    </body>
</html>
