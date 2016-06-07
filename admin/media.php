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
                            echo "<table><tr>";
                            foreach ($imgs as $img) {
                                $count++;
                                echo "<td><img src='$img' width=200/> <br>";
                                echo '<button onClick="deleteImg(\''.$img.'\')">Delete</button><br></td>';
                                if ($count % 4 === 0) {
                                    echo "</tr><tr>";
                                }
                            }
                            echo "</table>";
                            echo $count;
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
    </script>
