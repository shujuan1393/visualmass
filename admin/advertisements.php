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
    unset($_SESSION['updateAdvSuccess']);    
    unset($_SESSION['updateAdvError']);
    unset($_SESSION['addAdvSuccess']);
    unset($_SESSION['addAdvError']);
    unset($_SESSION['uploadAdvError']);
    $selectSql = "Select * from advertisements where id ='" .$_GET['id']."';";
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
                    <th>Expiry</th>   
                    <th>Status</th>
                    <th>Visibility</th>
                    <th>Edit</th>
                    <th>Delete</th>                        
                </thead>
            <?php
                // output data of each row
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<tr>";
                    $oDate = new DateTime($row['start']);
                    $sDate = $oDate->format("d-m-Y");
                    
                    
                    $mDate = new DateTime($row['end']);
                    $eDate = $mDate->format("d-m-Y");
                    
                    echo "<td>".$row['title'] ."</td>";     
                    echo "<td>".$row['expiry'];
                    if (strcmp($row['expiry'], "yes")===0) {
                          echo " (".$sDate." to ".$eDate.")";
                    }
                    echo "</td>";  
                    
                    echo "<td>".$row['status']."</td>";                           
                    echo "<td>".$row['visibility']."</td>";                       
                    echo '<td><button onClick="window.location.href=`advertisements.php?id='.$row['id'].'`">E</button>';
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
        
        <form id='addAdvertisement' action='processAdvertisements.php' method='post' enctype="multipart/form-data">
            <fieldset >
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
            <legend>Add/Edit Advertisement</legend>
            <input type='hidden' name='submitted' id='submitted' value='1'/>
            <input type='hidden' name='editid' id='editid' 
                   value='<?php if (isset($_GET['id'])) { echo $erow['id']; } ?>'/>
            <label for='title' >Title*:</label>
            <input type='text' name='title' id='title' maxlength="50" 
                   value='<?php if (!empty($erow['title'])) { echo $erow['title']; } ?>'/>
            <br>
            <?php 
                if (!empty($erow['image'])) {
                    echo "<img src='".$erow['image']."' width=200><br>";
                    echo "<input type='hidden' name='oldImage' id='oldImage' value='".$erow['image']."'>";
                }
            ?>
            <label for='image' >Image*:</label>
            <input type="file" name="image" id='image' accept="image/*" />
            <br>
            <label for='link' >Link (optional):</label>
            <input type='text' name='link' id='link'  maxlength="50" 
                   value='<?php if (!empty($erow['link'])) { echo $erow['link']; } ?>'/>
            <br>
            <label for='status' >Status*:</label>
            <select name='status'>
                <option value='active' <?php 
                    if (!empty($erow['status'])) {
                        if (strcmp($erow['status'], "active") === 0) {
                            echo " selected";
                        }
                    }
                ?>>Active</option>
                <option value='inactive' <?php 
                    if (!empty($erow['status'])) {
                        if (strcmp($erow['status'], "inactive") === 0) {
                            echo " selected";
                        }
                    }
                ?>>Inactive</option>
            </select>
            <br>
            
            <label for='expiry' >Expiry*:</label>
            <input type='radio' name='expiry' id='checkYes' value='yes' <?php 
                    if (!empty($erow['expiry'])) {
                        if (strcmp($erow['expiry'], "yes") === 0) {
                            echo " checked";
                        }
                    }
                ?>> Yes
            <input type='radio' name='expiry' id='checkNo' value='no' <?php 
                    if (!empty($erow['expiry'])) {
                        if (strcmp($erow['expiry'], "no") === 0) {
                            echo " checked";
                        } 
                    } else {
                        echo " checked";
                    }
                ?>> No
            <br>
            <div id='expiryDate' style='display:none'>
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
                if (!empty($erow['start'])) {
                    $myCalendar->setDate(date('d', strtotime($erow['start'])), date('m', strtotime($erow['start'])), date('Y', strtotime($erow['start'])));
                } else {
                    $myCalendar->setDate(date('d', strtotime($date1)), date('m', strtotime($date1)), date('Y', strtotime($date1)));
                }
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

                if (!empty($erow['end'])) {
                    $myCalendar->setDate(date('d', strtotime($erow['end'])), date('m', strtotime($erow['end'])), date('Y', strtotime($erow['end'])));
                } else {
                    $myCalendar->setDate(date('d', strtotime($date2)), date('m', strtotime($date2)), date('Y', strtotime($date2)));
                }
                $myCalendar->setPath("../calendar/");
                $myCalendar->setYearInterval(1970, 2020);
                //$myCalendar->dateAllow("", '2009-11-03', false);
                $myCalendar->setAlignment('left', 'bottom');
                $myCalendar->setDatePair('date3', 'date4', $date1);
                //$myCalendar->setSpecificDate(array("2011-04-01", "2011-04-04", "2011-12-25"), 0, 'year');
                $myCalendar->writeScript();
                ?>
            <br>
            </div>
            Content (optional): 
            <textarea name="html"><?php 
            if(!empty($erow['html'])) { echo $erow['html']; }?></textarea>
            <script type="text/javascript">
                CKEDITOR.replace('html');
            </script>
            <br>
            Visibility*: 
            <?php 
                if(!empty($erow['visibility'])) {
                    $visib = explode(",", $erow['visibility']);
                }
            ?>
            <div class='checkboxAlign'>
            <input name='visibility[]' type='checkbox' value='homepage' <?php 
                if (!empty($erow['visibility'])) {
                    if (in_array("homepage", $visib)) {
                        echo " checked";
                    }
                }
                ?>><label>Homepage</label>
            <input name='visibility[]' type='checkbox' value='catalogue' <?php 
                if (!empty($erow['visibility'])) {
                    if (in_array("catalogue", $visib)) {
                        echo " checked";
                    }
                }
                ?>><label>Product Catalogue</label>
            <input name='visibility[]' type='checkbox' value='prodDetails' <?php 
                if (!empty($erow['visibility'])) {
                    if (in_array("prodDetails", $visib)) {
                        echo " checked";
                    }
                }
                ?>><label>Product Details</label>
            
            <input name='visibility[]' type='checkbox' value='locations' <?php 
                if (!empty($erow['visibility'])) {
                    if (in_array("locations", $visib)) {
                        echo " checked";
                    }
                }
                ?>><label>Locations</label>
            <br>
            <input name='visibility[]' type='checkbox' value='story' <?php 
                if (!empty($erow['visibility'])) {
                    if (in_array("story", $visib)) {
                        echo " checked";
                    }
                }
                ?>><label>Our Story</label>
            
            <input name='visibility[]' type='checkbox' value='culture' <?php 
                if (!empty($erow['visibility'])) {
                    if (in_array("culture", $visib)) {
                        echo " checked";
                    }
                }
                ?>><label>Culture</label>
            
            <input name='visibility[]' type='checkbox' value='design' <?php 
                if (!empty($erow['visibility'])) {
                    if (in_array("design", $visib)) {
                        echo " checked";
                    }
                }
                ?>><label>Design</label>
            
            <input name='visibility[]' type='checkbox' value='one' <?php 
                if (!empty($erow['visibility'])) {
                    if (in_array("one", $visib)) {
                        echo " checked";
                    }
                }
                ?>><label>One for You, One for Them</label>
            <br>
            <input name='visibility[]' type='checkbox' value='blog' <?php 
                if (!empty($erow['visibility'])) {
                    if (in_array("blog", $visib)) {
                        echo " checked";
                    }
                }
                ?>><label>Blog</label>
            
            <input name='visibility[]' type='checkbox' value='hometry' <?php 
                if (!empty($erow['visibility'])) {
                    if (in_array("hometry", $visib)) {
                        echo " checked";
                    }
                }
                ?>><label>Home Try-on</label>
            </div>
            <br>
            <label for='minheight' >Min height*: <span class='setting-tooltips'>(for the advertisement to appear on each page)</span></label>
                        
            <p id='nanError' style="display: none;">Please enter numbers only</p>
            <input type='text' name='minheight' id='minheight' maxlength="50" 
                   onkeypress="return isNumber(event)" 
                   value='<?php if (!empty($erow['minheight'])) { echo $erow['minheight']; } ?>'/>
            <br>
            <input type='submit' name='submit' value='Submit' />
            </fieldset>
        </form>
        </div>
    </div>
    <script> 
        if (document.getElementById('checkYes').checked) {
           document.getElementById('expiryDate').style.display = "block";            
        }
        document.getElementById('checkYes').onclick = function(){  
           document.getElementById('expiryDate').style.display = "block";
        };
        
        document.getElementById('checkNo').onclick = function(){  
           document.getElementById('expiryDate').style.display = "none";
        };
        
        function isNumber(evt) {
            evt = (evt) ? evt : window.event;
            var charCode = (evt.which) ? evt.which : evt.keyCode;
            if (charCode > 31 && (charCode < 48 || charCode > 57)) {
                document.getElementById('nanError').style.display='block';
                document.getElementById('nanError').style.color='red';
                return false;
            }
            document.getElementById('nanError').style.display='none';
            return true;
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

