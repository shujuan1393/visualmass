<?php 
    require_once 'config/db.php';
?>
<?php
 
// function to geocode address, it will return false if unable to geocode address
function geocode($address){
 
    // url encode the address
    $address = urlencode($address);
     
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
            
            <div id="content">
                <?php
                    $banner = "Select * from locations where id='".$_GET['id']."';";
                    $bresult = mysqli_query($link, $banner);
                    
                    if (!mysqli_query($link, $banner)) {
                        echo "Error: ".mysqli_error($link);
                    } else {
                        if ($bresult -> num_rows == 0) {
                            echo "<h3 class='banner-title'>Sorry, this location is no longer available.</h3>";
                        } else {
                            $brow = mysqli_fetch_assoc($bresult);
                            $browArr = explode(".", $brow['featured']);

                            $ext = $browArr[count($browArr)-1];

                            $imgArr = array("jpg", "jpeg", "png", "gif");
                            $vidArr = array("mp3", "mp4", "wma");
 
                            $pos = strpos($brow['featured'], '/');
                            $url = substr($brow['featured'], $pos+1);
                            echo "<div class='webbanner'>";
                            
                            if (in_array($ext, $imgArr)) {
                                echo "<img id='banner' src='".$url."'>";
                            } else {
                                echo '<video id="banner" controls>
                                <source src="'.$url.'" type="video/mp4">
                                Your browser does not support the video tag.
                                </video>';
                            }
                            echo "</div>";
                ?>
                <div class='row col-md-12'>
                    <h3><?php echo $brow['name']; ?></h3>
                </div>
                
                <div class='col-md-9 col-md-offset-3'>
                    <div class='col-md-4'>
                        <?php 
                            echo "<p>".$brow['address']."</p>";
                            echo "<p> Apartment/Suite ".$brow['apt']."</p>";
                            echo "<p>".$brow['city'].", ".$brow['country']. " ".$brow['zip']."</p>";
                            
                            echo html_entity_decode($brow['opening']);
                        ?>
                    </div>
                    <div class='col-md-4'>
                        <?php
                        // get latitude, longitude and formatted address
                        $data_arr = geocode($brow['country']. " ".$brow['zip']);

                        // if able to geocode the address
                        if($data_arr){
                            $latitude = $data_arr[0];
                            $longitude = $data_arr[1];
                            $formatted_address = $data_arr[2];
                        ?>

                        <!-- google map will be shown here -->
                        <div id="canvas" style='width: 100%; height:70%; margin: 5px;'>Loading map...</div>

                        <!-- JavaScript to show google map -->   
                        <script type="text/javascript">
                            function init_map() {
                                var myOptions = {
                                    zoom: 14,
                                    center: new google.maps.LatLng(<?php echo $latitude; ?>, <?php echo $longitude; ?>),
                                    mapTypeId: google.maps.MapTypeId.ROADMAP
                                };
                                map = new google.maps.Map(document.getElementById("canvas"), myOptions);
                                marker = new google.maps.Marker({
                                    map: map,
                                    position: new google.maps.LatLng(<?php echo $latitude; ?>, <?php echo $longitude; ?>)
                                });
//                                infowindow = new google.maps.InfoWindow({
//                                    content: "<?php echo $formatted_address; ?>"
//                                });
//                                google.maps.event.addListener(marker, "click", function () {
//                                    infowindow.open(map, marker);
//                                });
//                                infowindow.open(map, marker);
                            }
                            google.maps.event.addDomListener(window, 'load', init_map);
                        </script>
                        <?php
                        // if unable to geocode the address
                        }else{
                            echo "No map found.";
                        }
                        ?>
                    </div>
                </div>
                
                <div class='col-md-12 full_section'>
                    <hr>
                    <div class='col-md-2'></div>
                    <div id='locservices' class='col-md-9'>
                        <?php 
                            $serArr = explode(",", $brow['services']);
                            
                            for ($i = 0; $i < count($serArr); $i++) {
                                $service = $serArr[$i];
                                
                                $serSql = "Select * from services where servicecode ='".$service."';";
                                $res = mysqli_query($link, $serSql);
                                
                                if (!mysqli_query($link, $serSql)) {
                                    die(mysqli_error($link));
                                } else {
                                    $row = mysqli_fetch_assoc($res);
                                    echo "<div class='col-md-5'>";
                                    echo "<h5>".$row['servicename']."</h5>";
                                    echo html_entity_decode($row['description']);
                                    echo "</div>";
                                }
                            }
                        ?>
                    </div>
                </div>
                
                <div id='locImages'>
                    <?php 
                        $imagesArr = explode(",", $brow['images']);
                        
                        for ($i = 0; $i < count($imagesArr); $i++) {
                            $img = $imagesArr[$i];
                            $pos = strpos($img, '/');
                            $url = substr($img, $pos+1);
                            echo "<img src='$url' ><br>";
                        }
                    ?>
                </div>
                
                <div class='full_section'>
                    <h3>About <?php echo $brow['name']; ?></h3>
                    <?php echo html_entity_decode($brow['description']); ?>
                </div>
                
                <div class='full_section'>
                    <div class=' col-md-10 col-md-offset-1'>
                    <h3>Shop our frames</h3>
                    <div class='col-md-5 col-md-offset-1'>
                        IMAGE
                        <p class='text-center'><a href='description.php?type=glasses'>GLASSES</a></p>
                    </div>
                    <div class='col-md-5 col-md-offset-1'>
                        IMAGE
                        <p class='text-center'><a href='description.php?type=sunglasses'>SUNGLASSES</a></p>
                    </div>
                </div>
            </div>
        </div>
                        
            <?php        }
                }
            ?>
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
