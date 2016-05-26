<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<?php 
    require_once 'config/db.php';
    
    if (isset($_GET['id'])) {
        $getSql = "Select * from terms where id='".$_GET['id']."';";
        $eresult = mysqli_query($link, $getSql);
        
        $erow=  mysqli_fetch_assoc($eresult);
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
                    $sql = "Select * from terms order by fieldorder asc";
                    $result = mysqli_query($link, $sql);
                    
                    if (!mysqli_query($link, $sql)) {
                        echo "Error: ".mysqli_error($link);
                    } else {
                        if ($result -> num_rows == 0) {
                            echo "<h3>Sorry, this page is under construction.</h3>";
                        } else {
                ?>  
                    <div id='terms_nav'>
                        <ul>
                            <?php
                                while($row = mysqli_fetch_assoc($result)) {
                                    $id = $row['id'];
                                    echo "<li><a href='terms.php?id=".$id."'>".
                                            $row['title']."</a></li>";
                                } 
                            ?>
                        </ul>
                    </div>
                    <div id='terms_content'>
                        <?php 
                            if (isset($_GET['id'])) {
                                echo "<span class='title'>".$erow['title']."</span>";
                                echo html_entity_decode($erow['html']);
                            } else {
                                $get = "Select * from terms where fieldorder='1';";
                                $result = mysqli_query($link, $get);
                                $row = mysqli_fetch_assoc($result);
                                
                                echo "<span class='title'>".$row['title']."</span>";
                                echo html_entity_decode($row['html']);
                            }
                        ?>
                    </div>
                <?php
                        }
                    }
                ?>
                
            </div>
            
            <div id="footer"><?php require_once 'nav/footer.php';?></div>
        </div>
    </body>
</html>
