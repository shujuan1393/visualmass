<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

require_once '../config/db.php';
require_once('../calendar/classes/tc_calendar.php');
require_once('../nav/adminHeader.php');

$selectSql = "SELECT value from settings WHERE type='blog'";
$savedresult = mysqli_query($link, $selectSql);

if (isset($_GET['id'])) {
    unset($_SESSION['addBlogCatError']);
    unset($_SESSION['addBlogCatSuccess']);
    unset($_SESSION['updateBlogCatError']);
    unset($_SESSION['updateBlogCatSuccess']);
    
    unset($_SESSION['addAuthorError']);
    unset($_SESSION['addAuthorSuccess']);
    unset($_SESSION['updateAuthorError']);
    unset($_SESSION['updateAuthorSuccess']);
    $getBlogCat = "Select * from categories where id='".$_GET['id']."';";
    $eresult = mysqli_query($link, $getBlogCat);
    $crow = mysqli_fetch_assoc($eresult);
} else if (isset($_GET['aid'])) {
    unset($_SESSION['addBlogCatError']);
    unset($_SESSION['addBlogCatSuccess']);
    unset($_SESSION['updateBlogCatError']);
    unset($_SESSION['updateBlogCatSuccess']);
    
    unset($_SESSION['addAuthorError']);
    unset($_SESSION['addAuthorSuccess']);
    unset($_SESSION['updateAuthorError']);
    unset($_SESSION['updateAuthorSuccess']);
    $getAuthor = "Select * from authors where id='".$_GET['aid']."';";
    $result = mysqli_query($link, $getAuthor);
    $erow = mysqli_fetch_assoc($result);
}

if (!mysqli_query($link,$selectSql)) {
    echo("Error description: " . mysqli_error($link));
} else {
    $savedrow = mysqli_fetch_assoc($savedresult);
    $valArr = explode("&", $savedrow['value']);
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
        <h2>Settings - Blog</h2>
        <h3>Blog Categories</h3> 
        <?php 
            $blogCatSql = "select * from categories";
            $empresult = mysqli_query($link, $blogCatSql);
            if (!mysqli_query($link,$blogCatSql)) {
                echo("Error description: " . mysqli_error($link));
            } else {
                if ($empresult->num_rows === 0) {
                    echo "There are no blog categories yet.";
                } else {
            ?>
            <table>
                <thead>
                    <th>Name</th>
                    <th>Edit</th>
                    <th>Delete</th>
                </thead>
            <?php 
                    while($row=  mysqli_fetch_assoc($empresult)) {
                        echo "<tr>";
                        echo "<td>".$row['name']."</td>";
                        echo '<td><button onClick="window.location.href=`blogSettings.php?id='.$row['id'].'`">E</button>';
                        echo '<td><button onClick="deleteFunction('.$row['id'].')">D</button></td>';
                        echo "</tr>";
                    }
                    echo "</table>";
        ?>
        <?php
                }
            }
        ?>
                
        <div id="updateBlogCatError" style="color:red">
            <?php 
                if (isset($_SESSION['updateBlogCatError'])) {
                    echo $_SESSION['updateBlogCatError'];
                }
            ?>
        </div>

        <div id="updateBlogCatSuccess" style="color:green">
            <?php 
                if (isset($_SESSION['updateBlogCatSuccess'])) {
                    echo $_SESSION['updateBlogCatSuccess'];
                }
            ?>
        </div>
        <form id='addBlogCat' method='post' action='saveBlogSettings.php?add=1'>
            <fieldset >
            <div id="addBlogCatError" style="color:red">
                <?php 
                    if (isset($_SESSION['addBlogCatError'])) {
                        echo $_SESSION['addBlogCatError'];
                    }
                ?>
            </div>
            
            <div id="addBlogCatSuccess" style="color:green">
                <?php 
                    if (isset($_SESSION['addBlogCatSuccess'])) {
                        echo $_SESSION['addBlogCatSuccess'];
                    }
                ?>
            </div>
            <legend>Add/Edit Blog Category</legend>
            <input type='hidden' name='editid' id='editid' value='<?php 
                    if (isset($crow['id'])) {
                        echo $crow['id'];
                    }
                   ?>'/>
            <input type='hidden' name='submitted' id='submitted' value='1'/>
            <label for='name' >Name*:</label>
            <input type='text' name='name' id='name'  maxlength="50" value='<?php 
                    if (isset($crow['name'])) {
                        echo $crow['name'];
                    }
                   ?>'/>
            <br>
            <input type='submit' name='submit' value='Submit' />
            </fieldset>
        </form> 
        
        <h3>Manage Authors</h3> 
            <?php
                $authorSql = "Select * from authors;";
                
                $result = mysqli_query($link, $authorSql);
                
                if (!mysqli_query($link,$authorSql)) {
                    echo("Error description: " . mysqli_error($link));
                } else {
                    if ($result->num_rows === 0) {
                        echo "You have not added any authors yet.<br>";
                    } else {
                    ?>
                    <table>
                        <thead>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Edit</th>
                        <th>Delete</th>
                        </thead>
                    <?php
                        while ($row = mysqli_fetch_assoc($result)) {
                            echo "<tr>";
                            echo "<td>".$row['firstname']." ".$row['lastname']."</td>";
                            echo "<td>".$row['email']."</td>";
                            echo "<td>".$row['phone']."</td>";
                            echo '<td><button onClick="window.location.href=`blogSettings.php?aid='.$row['id'].'`">E</button>';
                            echo '<td><button onClick="deleteAuthFunction('.$row['id'].')">D</button></td>';
                        
                            echo "</tr>";
                       } 
                    ?>
                        </table><br>
            <?php
                    }
                }
            ?>
            <div id="updateAuthorError" style="color:red">
                <?php 
                    if (isset($_SESSION['updateAuthorError'])) {
                        echo $_SESSION['updateAuthorError'];
                    }
                ?>
            </div>
            <p id='nanError' style="display: none;">Please enter numbers only</p>
            <div id="updateAuthorSuccess" style="color:green">
                <?php 
                    if (isset($_SESSION['updateAuthorSuccess'])) {
                        echo $_SESSION['updateAuthorSuccess'];
                    }
                ?>
            </div>
        <form id='addAuthor' method='post' action='saveBlogSettings.php?update=1'>
            <fieldset >
            <div id="addAuthorError" style="color:red">
                <?php 
                    if (isset($_SESSION['addAuthorError'])) {
                        echo $_SESSION['addAuthorError'];
                    }
                ?>
            </div>
            <p id='nanError' style="display: none;">Please enter numbers only</p>
            <div id="addAuthorSuccess" style="color:green">
                <?php 
                    if (isset($_SESSION['addAuthorSuccess'])) {
                        echo $_SESSION['addAuthorSuccess'];
                    }
                ?>
            </div>
            <legend>Add/Edit Author</legend>
            <input type='hidden' name='editid' id='editid' value='<?php 
                    if (isset($erow['id'])) {
                        echo $erow['id'];
                    }
                   ?>'/>
            <input type='hidden' name='submitted' id='submitted' value='1'/>
            <label for='firstname' >First Name*:</label>
            <input type='text' name='firstname' id='firstname'  maxlength="50" value='<?php 
                    if (isset($erow['firstname'])) {
                        echo $erow['firstname'];
                    }
                   ?>'/>
            <br>
            <label for='lastname' >Last Name*:</label>
            <input type='text' name='lastname' id='lastname'  maxlength="50" value='<?php 
                    if (isset($erow['lastname'])) {
                        echo $erow['lastname'];
                    }
                   ?>'/>
            <br>
            <label for='email' >Email*:</label>
            <input type='text' name='email' id='email'  maxlength="50" value='<?php 
                    if (isset($erow['email'])) {
                        echo $erow['email'];
                    }
                   ?>'/>
            <br>
            <label for='phone' >Phone*:</label>
            <input type='text' name='phone' id='phone'  maxlength="50" 
                   onkeypress="return isNumber(event)" value='<?php 
                    if (isset($erow['phone'])) {
                        echo $erow['phone'];
                    }
                   ?>'/>
            <br>
            Date Joined:
            <?php
            $myCalendar = new tc_calendar("date3", true, false);
            $myCalendar->setIcon("../calendar/images/iconCalendar.gif");
            if (!empty($erow['datejoined'])) {
                $myCalendar->setDate(date('d', strtotime($erow['datejoined'])), date('m', strtotime($erow['datejoined'])), date('Y', strtotime($erow['datejoined'])));
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
            <input type='submit' name='submit' value='Submit' />
            </fieldset>
        </form>         
        </div>
    </div>
    <script>
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
    
    function deleteAuthFunction(empId) {
        var r = confirm("Are you sure you wish to delete this author?");
        if (r === true) {
            window.location="saveBlogSettings.php?delete=1&aid=" + empId;
        } else if (r === false) {
            <?php
                unset($_SESSION['addAuthorSuccess']);
                unset($_SESSION['addAuthorError']);
                unset($_SESSION['updateAuthorSuccess']);
                $_SESSION['updateAuthorError'] = "Nothing was deleted";
            ?>
            window.location='blogSettings.php';
        }
    }
    
    function deleteFunction(empId) {
        var r = confirm("Are you sure you wish to delete this blog category?");
        if (r === true) {
            window.location="saveBlogSettings.php?delete=1&id=" + empId;
        } else if (r === false) {
            <?php
                unset($_SESSION['addBlogCatSuccess']);
                unset($_SESSION['addBlogCatError']);
                unset($_SESSION['updateBlogCatSuccess']);
                $_SESSION['updateBlogCatError'] = "Nothing was deleted";
            ?>
            window.location='blogSettings.php';
        }
    }
    </script>
</html>
<?php } ?>