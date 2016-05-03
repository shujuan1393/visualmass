<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
session_start();
require '../config/db.php';
require_once('../calendar/classes/tc_calendar.php');
require_once('../nav/adminHeader.html');
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
        <h2>Manage Advertisements</h2>
        
        <?php 
            $qry = "Select * from advertisements";
            
            $result = mysqli_query($link, $qry);

            if (!mysqli_query($link,$qry))
            {
                echo("Error description: " . mysqli_error($link));
            } else {
                if ($result->num_rows === 0) {
                    echo "You have not created any advertisements yet.";
                } else {
            ?>
            <table>
                <thead>
                    <th>Title</th>
                    <th>Start Date</th>
                    <th>End Date</th>
                    <th>Status</th>
                    <th>Edit</th>
                    <th>Delete</th>                        
                </thead>
            <?php
                // output data of each row
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<tr>";
                    echo "<td>".$row['title'] ."</td>";                          
                    echo "<td>".$row['start']."</td>";                           
                    echo "<td>".$row['end']."</td>";                           
                    echo "<td>".$row['status']."</td>";                        
                    echo '<td><button onClick="window.location.href=`editAdvertisement.php?id='.$row['id'].'`">E</button>';
                    echo '<td><button onClick="deleteFunction('.$row['id'].')">D</button></td>';
                    echo "</tr>";
                }
            ?>
            </table>
            <?php
                } 
            }
            ?>
            <div id="updateAdvSuccess" style="color:green">
                <?php 
                    if (isset($_SESSION['updateAdvSuccess'])) {
                        echo $_SESSION['updateAdvSuccess'];
                    }
                ?>
            </div>
            <div id="updateAdvError" style="color:red">
                <?php 
                    if (isset($_SESSION['updateAdvError'])) {
                        echo $_SESSION['updateAdvError'];
                    }
                ?>
            </div>
        <hr><br>
        
        <form id='addDiscount' action='processAdvertisements.php' method='post' enctype="multipart/form-data">
            <fieldset >
            <legend>Add Advertisement</legend>
            <input type='hidden' name='submitted' id='submitted' value='1'/>
            <label for='title' >Title*:</label>
            <input type='text' name='title' id='title' maxlength="50" />
            <br>
            <label for='image' >Image*:</label>
            <input type="file" name="image" id='image' accept="image/*" />
            <br>
            <label for='link' >Link (optional):</label>
            <input type='text' name='link' id='link'  maxlength="50" />
            <br>
            <label for='status' >Status*:</label>
            <select name='status'>
                <option value='active'>Active</option>
                <option value='inactive'>Inactive</option>
            </select>
            <br>
            Valid from:
            <?php
            $thisweek = date('W');
            $thisyear = date('Y');

            $dayTimes = getDaysInWeek($thisweek, $thisyear);
            //----------------------------------------

            $date1 = date('Y-m-d', $dayTimes[0]);
            $date2 = date('Y-m-d', $dayTimes[(sizeof($dayTimes)-1)]);

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


            $myCalendar = new tc_calendar("date3", true, false);
            $myCalendar->setIcon("../calendar/images/iconCalendar.gif");
            $myCalendar->setDate(date('d', strtotime($date1)), date('m', strtotime($date1)), date('Y', strtotime($date1)));
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
            $myCalendar->setDate(date('d', strtotime($date2)), date('m', strtotime($date2)), date('Y', strtotime($date2)));
            $myCalendar->setPath("../calendar/");
            $myCalendar->setYearInterval(1970, 2020);
            //$myCalendar->dateAllow("", '2009-11-03', false);
            $myCalendar->setAlignment('left', 'bottom');
            $myCalendar->setDatePair('date3', 'date4', $date1);
            //$myCalendar->setSpecificDate(array("2011-04-01", "2011-04-04", "2011-12-25"), 0, 'year');
            $myCalendar->writeScript();
            ?>
            <br>
            <input type='submit' name='submit' value='Submit' />
            <div id="addAdvError" style="color:red">
                <?php 
                    if (isset($_SESSION['addAdvError'])) {
                        echo $_SESSION['addAdvError'];
                    }
                    
                    if (isset($_SESSION['uploadAdvError'])) {
                        echo $_SESSION['uploadAdvError'];
                    }
                ?>
            </div>
            
            <div id="addAdvSuccess" style="color:green">
                <?php 
                    if (isset($_SESSION['addAdvSuccess'])) {
                        echo $_SESSION['addAdvSuccess'];
                    }
                ?>
            </div>
            </fieldset>
        </form>
        </div>
    </div>
    <script>
        function randomString() {
            var text = "";
            var possible = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";

            for( var i=0; i < 5; i++ )
                text += possible.charAt(Math.floor(Math.random() * possible.length));

            document.getElementById('code').value = text;
            return false;
        }
        
        function deleteFunction(locId) {
            var r = confirm("Are you sure you wish to delete this advertisement?");
            if (r === true) {
                window.location="processAdvertisements.php?delete=1&id=" + locId;
            } else if (r === false) {
                <?php
                    unset($_SESSION['addAdvError']);
                    unset($_SESSION['addAdvSuccess']);
                    unset($_SESSION['updateAdvSuccess']);
                    $_SESSION['updateAdvError'] = "Nothing was deleted";
                ?>
                window.location='advertisements.php';
            }
        }
    </script>
    
</html>

