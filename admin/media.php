<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

?>

<!DOCTYPE html>
<html lang="en">
    <?php require '../nav/adminHeader.php'; ?>
    <body>
        <div id="wrapper">
            <?php require '../nav/adminMenubar.php'; ?>
            
            <!-- Content -->
            <div id="page-wrapper">

            <div class="container-fluid">

                <!-- Page Heading -->
                <div class="row">
                    <div class="col-lg-12">
                        <ol class="breadcrumb">
                            <li>
                                <a href="index.php"><i class="fa fa-home"></i></a>
                            </li>
                            <li class="active">
                                Media Gallery
                            </li>
                        </ol>
        
                        <div id="updateMediaError" style="color:red">
                            <?php 
                                if (isset($_SESSION['updateMediaError'])) {
                                    echo $_SESSION['updateMediaError'];
                                }
                            ?>
                        </div>

                        <div id="updateMediaSuccess" style="color:green">
                            <?php 
                                if (isset($_SESSION['updateMediaSuccess'])) {
                                    echo $_SESSION['updateMediaSuccess'];
                                }
                            ?>
                        </div>
                        <?php 
                            //path to directory to scan. i have included a wildcard for a subdirectory
                            $directory = "../uploads/*/";

                            //get all image files with a .jpg extension.
                            $images = glob("" . $directory . "*.*");

                            $imgs = '';
                            // create array
                            foreach($images as $image){ $imgs[] = "$image"; }

                            //shuffle array
                            shuffle($imgs);

                            //select first 20 images in randomized array
                //            $imgs = array_slice($imgs, 0, 20);
                            $count = 0;
                            //display images
                            echo "<div class='row'>";
                            foreach ($imgs as $img) {
                                ?>
                        
                                <div id='img-col' class='col-lg-3 col-md-4 col-sm-5'>
                                    <a href='#' class='pop' onclick='setImg(this); return false;'>
                                        <img src='<?php echo $img; ?>' class='img-grid'/>
                                    </a>
                                    
                                    <div class="modal fade" id="imagemodal" tabindex="-1" 
                                        role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                                                                                         
                                        <div class="modal-dialog">
                                            <div class="modal-content">
<!--                                                <div class="modal-header">
                                                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                                    <h4 class="modal-title">Modal title</h4>
                                                </div>-->
                                                <div class="modal-body">
                                                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                                    <img src='' id='imagepreview' class='img-modal'/>
                                                </div>
<!--                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                    <button type="button" class="btn btn-primary">Save changes</button>
                                                </div>-->
                                            </div><!-- /.modal-content -->
                                        </div><!-- /.modal-dialog -->
                                    </div><!-- /.modal -->
                            <?php
                                echo '<button class="btn-overlay" onClick="deleteImg(\''.$img.'\')"><i class="fa fa-trash-o" aria-hidden="true"></i></button></div>';
                                $count++;
                                
                                if ($count % 4 === 0) {
                                    echo "</div><div class='row'>";
                                }
                            }
                            echo "</div>";
//                            echo $count;
                        ?>
                    </div>
                </div>
                <!-- /.row -->

            </div>
            <!-- /.container-fluid -->

        </div>
        <!-- /#page-wrapper -->
    </div>    
</html>

<script>
    function deleteImg(img) {
        window.location="processMedia.php?file=" + img;
    }

    function setImg(a)
    {
        document.getElementById('imagepreview').src = a.getElementsByTagName('img')[0].src;
    }
    
    $(function() {
        $('.pop').on('click', function() {
//            $('.imagepreview').attr('src', $(this).find('img').attr('src'));
            $('#imagemodal').modal('show');   
        });		
    });
    
</script>
