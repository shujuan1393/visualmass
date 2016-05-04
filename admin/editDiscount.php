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
        <h2>Edit Discount</h2>
    <?php 
    $qry = "Select * from discounts where id ='". $_GET['id']."'";

    $result = mysqli_query($link, $qry);
    
    if (!mysqli_query($link,$qry))
    {
        echo("Error description: " . mysqli_error($link));
    } else {
        while ($row = mysqli_fetch_assoc($result)) {
            $editusage = explode(',', $row['discusage']);
    ?>
        <form id='editDisc' method='post' action='processDiscounts.php?edit=1'>
            <input type='hidden' name='editid' value='<?php echo $row['id']?>'>
            Discount Code: <input type="text" name='editcode' value="<?php echo $row['code']?>"/> </br>
            Name: <input type="text" name='editname' value="<?php echo $row['name']?>"/> </br>
            Limit: <input type="text" name='editlimit' value="<?php echo $row['disclimit']?>"/> </br>
            Recurrence: 
            <select name="editrecurrence">
                <option value='adhoc' <?php if (strcmp($row['recurrence'], "adhoc") === 0) {
                    echo "selected"; } ?>>Ad-hoc</option>
                <option value ='weekly' <?php if (strcmp($row['recurrence'], "weekly") === 0) {
                    echo "selected"; } ?>>Weekly</option>
                <option value='monthly' <?php if (strcmp($row['recurrence'], "monthly") === 0) {
                    echo "selected"; } ?>>Monthly</option>
                <option value ='yearly' <?php if (strcmp($row['recurrence'], "yearly") === 0) {
                    echo "selected"; } ?>>Yearly</option>
            </select><br>
            Usage:
                <table width='500px' style='margin-left: 70px; margin-top:-20px'>
                    <tr><td>
                <?php
                    $useArr = explode(",", $row['discusage']);
                    
                    if (count($useArr) < 1) {
                        $useArr = array($row['disusage']);
                    }
                    echo '<input type="checkbox" name="editusage[]" 
                        value="cust"';
                            if (in_array("cust", $useArr)) {
                                echo " checked";
                            }
                            echo '><label>Customer</label>';
                    echo '<input type="checkbox" name="editusage[]" 
                        value="emp"';
                            if (in_array("emp", $useArr)) {
                                echo " checked";
                            }
                            echo '><label>Employee</label>';
                    
                ?>
                        </td></tr>
                </table>
                <br>
            
            Status: 
            <select name="editstatus">
                <option value='active' <?php if (strcmp($row['status'], "active") === 0) {
                    echo "selected"; } ?>>Retail</option>
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
        </div>
    </div>
</html>
