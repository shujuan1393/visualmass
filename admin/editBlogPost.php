<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
session_start();
require '../config/db.php';
require_once('../calendar/classes/tc_calendar.php');
require_once('../nav/adminHeader.php');
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
        <h2>Edit Blog Post</h2>
    <?php 
    $qry = "Select * from blog where id ='". $_GET['id']."'";

    $result = mysqli_query($link, $qry);
    
    if (!mysqli_query($link,$qry))
    {
        echo("Error description: " . mysqli_error($link));
    } else {
        while ($row = mysqli_fetch_assoc($result)) {
    ?>
        <form id='editBlog' method='post' action='processBlogPosts.php?edit=1' enctype="multipart/form-data">
            <input type='hidden' name='editid' value='<?php echo $row['id']?>'>
            Title: <input type="text" name='edittitle' value="<?php echo $row['title']?>"/> </br>
            <div id="showDiv">+ Add Excerpt</div>
            <div id="addExcerpt" style="display:none">
            <label for='excerpt' >Excerpt:</label>
            <textarea name="editexcerpt" id='editexcerpt'><?php echo $row['excerpt']; ?></textarea>
            <script type="text/javascript">
                CKEDITOR.replace('editexcerpt');
            </script>
            <br>
            </div>
            Image: <img src='<?php echo $row['image']; ?>' width='200'><br>
            <input type='hidden' name="oldImage" value='<?php echo $row['image'] ?>'>
            <input type="file" name="editimage" id='editimage' accept="image/*" /></br>
            
            Visibility: 
            <select name="editvisibility">
                <option value='active' <?php if (strcmp($row['visibility'], "active") === 0) {
                    echo "selected"; } ?>>Active</option>
                <option value ='inactive' <?php if (strcmp($row['visibility'], "inactive") === 0) {
                    echo "selected"; } ?>>Inactive</option>
            </select><br>
            Date Posted:
            <?php
            $myCalendar = new tc_calendar("date3", true, false);
            $myCalendar->setIcon("../calendar/images/iconCalendar.gif");
            $myCalendar->setDate(date('d', strtotime($row['dateposted'])), date('m', strtotime($row['dateposted'])), date('Y', strtotime($row['dateposted'])));
            $myCalendar->setPath("../calendar/");
            $myCalendar->setYearInterval(1970, 2020);
            //$myCalendar->dateAllow('2009-02-20', "", false);
            $myCalendar->setAlignment('left', 'bottom');
            //$myCalendar->setSpecificDate(array("2011-04-01", "2011-04-04", "2011-12-25"), 0, 'year');
            $myCalendar->writeScript();
            ?>
            <br>
            <label for='html' >Content:</label>
            <textarea name="edithtml" id='edithtml'><?php echo $row['html']; ?></textarea>
            <script type="text/javascript">
                CKEDITOR.replace('edithtml');
            </script>
            <br>
            <input type='submit' name='submit' value='Update'>
        </form>
    <?php
        }
    }
    ?>
        <div id="editAdvError" style="color:red">
            <?php 
                if (isset($_SESSION['editUploadAdvError'])) {
                    echo $_SESSION['editUploadAdvError'];
                }
            ?>
        </div>
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
    </script>
</html>
