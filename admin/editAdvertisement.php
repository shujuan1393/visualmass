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
        <h2>Edit Advertisement</h2>
    <?php 
    $qry = "Select * from advertisements where id ='". $_GET['id']."'";

    $result = mysqli_query($link, $qry);
    
    if (!mysqli_query($link,$qry))
    {
        echo("Error description: " . mysqli_error($link));
    } else {
        while ($row = mysqli_fetch_assoc($result)) {
    ?>
        <form id='editAdv' method='post' action='processAdvertisements.php?edit=1' enctype="multipart/form-data">
            <input type='hidden' name='editid' value='<?php echo $row['id']?>'>
            Title: <input type="text" name='edittitle' value="<?php echo $row['title']?>"/> </br>
            Image: <img src='<?php echo $row['image']; ?>' width='200'><br>
            <input type='hidden' name="oldImage" value='<?php echo $row['image'] ?>'>
            <input type="file" name="editimage" id='editimage' accept="image/*" /></br>
            Link: <input type="text" name='editlink' value="<?php echo $row['link']?>"/> </br>
            
            Status: 
            <select name="editstatus">
                <option value='active' <?php if (strcmp($row['status'], "active") === 0) {
                    echo "selected"; } ?>>Active</option>
                <option value ='inactive' <?php if (strcmp($row['status'], "inactive") === 0) {
                    echo "selected"; } ?>>Inactive</option>
            </select><br>
            Valid from:
            <?php
            $thisweek = date('W');
            $thisyear = date('Y');
            
            function getDaysInWeek ($weekNumber, $year, $dayStart = 1) {
              // Count from '0104' because January 4th is always in week 1
              // (according to ISO 8601).
              $time = strtotime($year . '0104 +' . ($weekNumber - 1).' weeks');
              // Get the time of the first day of the week
              $dayTime = strtotime('-' . (date('w', $time) - $dayStart) . ' days', $time);
              // Get the times of days 0 -> 6
              $dayTimes = array ();
              for ($i = 0; $i < 7; ++$i) {
                    $dayTimes[] = strtotime('+' . $i . ' days', $dayTime);
              }
              // Return timestamps for mon-sun.
              return $dayTimes;
            }
            
            $dayTimes = getDaysInWeek($thisweek, $thisyear);
            //----------------------------------------

            $date1 = date('Y-m-d', $dayTimes[0]);
            $date2 = date('Y-m-d', $dayTimes[(sizeof($dayTimes)-1)]);
            
            $myCalendar = new tc_calendar("date3", true, false);
            $myCalendar->setIcon("../calendar/images/iconCalendar.gif");
            $myCalendar->setDate(date('d', strtotime($row['start'])), date('m', strtotime($row['start'])), date('Y', strtotime($row['start'])));
            $myCalendar->setPath("../calendar/");
            $myCalendar->setYearInterval(1970, 2020);
            //$myCalendar->dateAllow('2009-02-20', "", false);
            $myCalendar->setAlignment('left', 'bottom');
            $myCalendar->setDatePair('date3', 'date4', $date2);
            //$myCalendar->setSpecificDate(array("2011-04-01", "2011-04-04", "2011-12-25"), 0, 'year');
            $myCalendar->writeScript();
            ?>
            to
            <?php
            $myCalendar = new tc_calendar("date4", true, false);
            $myCalendar->setIcon("../calendar/images/iconCalendar.gif");
            $myCalendar->setDate(date('d', strtotime($row['end'])), date('m', strtotime($row['end'])), date('Y', strtotime($row['end'])));
            $myCalendar->setPath("../calendar/");
            $myCalendar->setYearInterval(1970, 2020);
            //$myCalendar->dateAllow("", '2009-11-03', false);
            $myCalendar->setAlignment('left', 'bottom');
            $myCalendar->setDatePair('date3', 'date4', $date1);
            //$myCalendar->setSpecificDate(array("2011-04-01", "2011-04-04", "2011-12-25"), 0, 'year');
            $myCalendar->writeScript();
            ?>
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
</html>
