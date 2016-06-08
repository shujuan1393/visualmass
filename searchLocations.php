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
                <form id='searchLocForm' autofocus>
                    <input type='hidden' name='submitted' id='submitted' value='1'/>

                    <input type='text' name='search' id='searchLoc'  maxlength="50" 
                           placeholder="SEARCH LOCATIONS" value="<?php if (isset($_SESSION['searchVal'])) { echo $_SESSION['searchVal']; }?>"/>
                </form>
                
                <div class="searchResults">
                    <?php
                        if (isset($_SESSION['searchResult'])) {
                            $idArr = explode(",", $_SESSION['searchResult']);
                            
                            for ($i = 0; $i < count($idArr); $i++) {
                                $bid = $idArr[$i];
                                if (strcmp($bid, "") !== 0) {
                                    $sql = "Select * from locations where id='".$bid."';";
                                    $res = mysqli_query($link, $sql);

                                    if (!mysqli_query($link, $sql)) {
                                        die(mysqli_error($link));
                                    } else {
                                        $row = mysqli_fetch_assoc($res);

                                        echo "<div class='col-md-12'>";
                                        echo "<h5>Store Name: ".$row['name']."</h5>";
                                        echo "<h5>Address: ".$row['address']."</h5>";
                                        if (!empty($row['opening'])) {
                                            echo html_entity_decode($row['opening']);
                                        } 
                                        echo "<br>";
                                        echo html_entity_decode($row['description']);
                                        
                                        echo "<a href='location.php?id=".$bid."'>READ MORE > </a>";
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
        $('#searchLoc').keypress(function (e) {
            if (e.which === 13) {
              $('form#searchLocForm').submit();
              return false;    //<---- Add this line
            }
        });
        
        $('#searchLocForm').validate({
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
                    url: "processSearch.php?locations=1",
                    data: $(form).serialize(),
                    }).done(function(data) {
                    $('#searchWrapper').replaceWith(data);
                    });
            }
        });
    </script>
</html>
