<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
require_once '../config/db.php';
require_once('../calendar/classes/tc_calendar.php');
require_once('../nav/adminHeader.php');

if (isset($_GET['id'])) {
    unset($_SESSION['addBlogSuccess']);
    unset($_SESSION['addBlogError']);
    unset($_SESSION['updateBlogError']);
    unset($_SESSION['updateBlogSuccess']);
    unset($_SESSION['uploadBlogError']);
    $selectSql = "Select * from blog where id ='" .$_GET['id']."';";
    $eresult = mysqli_query($link, $selectSql);

    if (!mysqli_query($link,$selectSql))
    {
        echo("Error description: " . mysqli_error($link));
    } else {
        $erow = mysqli_fetch_assoc($eresult);
    }
}
?>

<html>  
    <div id="framecontent">
        <div class='innertube'>
        <?php
            require '../nav/adminSidebar.php';
        ?>
        </div>
    </div>
    <div id="maincontent">
        <div class="innertube">
        <h2>Blog</h2>
        <br>
        <?php 
            $qry = "Select * from blog";
            
            $result = mysqli_query($link, $qry);

            if (!mysqli_query($link,$qry))
            {
                echo("Error description: " . mysqli_error($link));
            } else {
                if ($result->num_rows === 0) {
                    echo "You have not created any blog entries yet.";
                } else {
            ?>
            <table>
                <thead>
                    <th>Title</th>
                    <th>Author</th>
                    <th>Date Posted</th>
                    <th>Visibility</th>
                    <th>Edit</th>
                    <th>Delete</th>                        
                </thead>
            <?php
                // output data of each row
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<tr>";
                    echo "<td>".$row['title'] ."</td>";
                    echo "<td>".$row['author']."</td>";                            
                    echo "<td>".$row['dateposted']."</td>";                           
                    echo "<td>".$row['visibility']."</td>";                         
                    echo '<td><button onClick="window.location.href=`blog.php?id='.$row['id'].'`">E</button>';
                    echo '<td><button onClick="deleteFunction('.$row['id'].')">D</button></td>';
                    echo "</tr>";
                }
            ?>
            </table>
            <?php
                } 
            }
            ?>
            <div id="updateBlogSuccess" style="color:green">
                <?php 
                    if (isset($_SESSION['updateBlogSuccess'])) {
                        echo $_SESSION['updateBlogSuccess'];
                    }
                ?>
            </div>
            <div id="updateBlogError" style="color:red">
                <?php 
                    if (isset($_SESSION['updateBlogError'])) {
                        echo $_SESSION['updateBlogError'];
                    }
                ?>
            </div>
        <hr><br>
        
        <form id='addBlogPost' action='processBlogPosts.php' method='post' enctype="multipart/form-data">
            <fieldset >
            <legend>Add Blog Post</legend>
            <input type='hidden' name='submitted' id='submitted' value='1'/>
            <input type='hidden' name='editid' id='editid' 
                   value='<?php if (isset($_GET['id'])) { echo $erow['id']; }?>'/>
            <label for='title' >Title*:</label>
            <input type='text' name='title' id='title'
                   value='<?php if (!empty($erow['title'])) { echo $erow['title']; }?>'/>
            <br>
            <div id="showDiv">+ Add Excerpt</div>
            <div id="addExcerpt" style="display:none">
            <label for='excerpt' >Excerpt:</label>
            <textarea name="excerpt" id='excerpt'><?php 
            if(!empty($erow['excerpt'])) { echo $erow['excerpt']; }?></textarea>
            <script type="text/javascript">
                CKEDITOR.replace('excerpt');
            </script>
            <br>
            </div>
            <label for='visibility' >Visibility*:</label>
            <select name='visibility'>
                <option value='active' <?php 
                    if (!empty($erow['visibility'])) {
                        if (strcmp($erow['visibility'], "active") === 0) {
                            echo " selected";
                        }
                    }
                ?>>Active</option>
                <option value='inactive' <?php 
                    if (!empty($erow['visibility'])) {
                        if (strcmp($erow['visibility'], "inactive") === 0) {
                            echo " selected";
                        }
                    }
                ?>>Inactive</option>
            </select>
            <br>
            <label for='tags' >Tags:</label>
            <input type='text' name='tags' id='tags' 
                   value='<?php if (!empty($erow['tags'])) { echo $erow['tags']; } ?>'/>
            <br>
            Date Posted:
            <?php
            $myCalendar = new tc_calendar("date3", true, false);
            $myCalendar->setIcon("../calendar/images/iconCalendar.gif");
            if (!empty($erow['dateposted'])) {
                $myCalendar->setDate(date('d', strtotime($erow['dateposted'])), date('m', strtotime($erow['dateposted'])), date('Y', strtotime($erow['dateposted'])));
            } else {
                $myCalendar->setDate(date('d', strtotime(date('Y-m-d'))), date('m', strtotime(date('Y-m-d'))), date('Y', strtotime(date('Y-m-d'))));
            }
            $myCalendar->setPath("../calendar/");
            $myCalendar->setYearInterval(1970, 2020);
            //$myCalendar->dateAllow('2009-02-20', "", false);
            $myCalendar->setAlignment('left', 'bottom');
            //$myCalendar->setSpecificDate(array("2011-04-01", "2011-04-04", "2011-12-25"), 0, 'year');
            $myCalendar->writeScript();
            ?>
            <br>
            <?php 
                if (!empty($erow['image'])) {
                    echo "<img src='".$erow['image']."' width=200><br>";
                    echo "<input type='hidden' name='oldImage' id='oldImage' value='".$erow['image']."'>";
                }
            ?>
            <label for='image' >Image:</label>
            <input type="file" name="image" id='image' accept="image/*" />
            <br>
            Content: 
            <textarea name="html"><?php 
            if(!empty($erow['html'])) { echo $erow['html']; }?></textarea>
            <script type="text/javascript">
                CKEDITOR.replace('html');
            </script>
            <br>
            <input type='submit' name='submit' value='Submit' />
            <div id="addBlogError" style="color:red">
                <?php 
                    if (isset($_SESSION['addBlogError'])) {
                        echo $_SESSION['addBlogError'];
                    }
                    
                    if (isset($_SESSION['uploadBlogError'])) {
                        echo $_SESSION['uploadBlogError'];
                    }
                ?>
            </div>
            
            <div id="addBlogSuccess" style="color:green">
                <?php 
                    if (isset($_SESSION['addBlogSuccess'])) {
                        echo $_SESSION['addBlogSuccess'];
                    }
                ?>
            </div>
            </fieldset>
        </form>
        </div>
    </div>
    <script>
        document.getElementById('showDiv').onclick = function(){  
           var e = document.getElementById('addExcerpt');
           if(e.style.display == 'block')
                e.style.display = 'none';
             else
                e.style.display = 'block';
        };
        
        function deleteFunction(locId) {
            var r = confirm("Are you sure you wish to delete this blog entry?");
            if (r === true) {
                window.location="processBlogPosts.php?delete=1&id=" + locId;
            } else if (r === false) {
                <?php
                    unset($_SESSION['addBlogError']);
                    unset($_SESSION['addBlogSuccess']);
                    unset($_SESSION['updateBlogSuccess']);
                    $_SESSION['updateBlogError'] = "Nothing was deleted";
                ?>
                window.location='blog.php';
            }
        }
    </script>
</html>