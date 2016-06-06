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
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">X</button>
            </div>
            <div class="logo"></div>
            <div id='login'>
                <div id="searchError" class="error">
                    <?php 
                        if (isset($_SESSION['searchError'])) {
                            echo $_SESSION['searchError'];
                        }
                        if (isset($_SESSION['searchResult'])) {
                            $sql = $_SESSION['searchResult'];
                            if (mysqli_multi_query($link,$sql)) {
                                if (!mysqli_next_result($link)) {
                                    echo "No results matching your search";
                                } 
                            }
                        }
                    ?>
                </div>
                <form id='searchForm' autofocus>
                    <input type='hidden' name='submitted' id='submitted' value='1'/>

                    <input type='text' name='search' id='search'  maxlength="50" 
                           placeholder="SEARCH" value="<?php if (isset($_SESSION['searchVal'])) { echo $_SESSION['searchVal']; }?>"/>
                </form>
                <div class="searchResults">
                    <?php 
                        if (isset($_SESSION['searchResult'])) {
                            $sql = $_SESSION['searchResult'];
                            echo "<div class='col-md-12'>";
                            if (mysqli_multi_query($link,$sql)) {
                                if (mysqli_next_result($link)) {
                                    do {
                                        /* store first result set */
                                        if ($result = mysqli_store_result($link)) {
                                            while ($row = mysqli_fetch_row($result)) {
                                                echo "<h5>".$row[1]."</h5>";
                                                echo html_entity_decode($row[2]);
        //                                        printf("%s\n", $row[1]);
                                            }
                                            mysqli_free_result($result);
                                        }
                                        /* print divider */
                                        if (mysqli_more_results($link)) {
        //                                    printf("-----------------\n");
                                            echo "</div> <div class='col-md-12'>";
                                        }
                                    }
                                    while (mysqli_next_result($link));
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
        $('#search').keypress(function (e) {
            if (e.which === 13) {
              $('form#searchForm').submit();
              return false;    //<---- Add this line
            }
        });
        $('#searchForm').validate({
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
                    url: "processSearch.php?general=1",
                    data: $(form).serialize(),
                    }).done(function(data) {
                    $('#searchWrapper').replaceWith(data);
                    });
            }
        });
    </script>
</html>
