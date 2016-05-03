<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
session_start();
require '../config/db.php';
?>

<html>    
    <head><script type="text/javascript" src="../ckeditor/ckeditor.js"></script></head>
    
    <div id="framecontent">
        <div class='innertube'>
        <?php
            require '../nav/adminSidebar.php';
        ?>
        </div>
    </div>
    <div id="maincontent">
        <div class="innertube">
        <h2>Manage Profile</h2>
        
        <br>
        
        <?php 
            $empSql = "Select * from staff where email='".$_SESSION['loggedUserEmail']."';";
            $empresult = mysqli_query($link, $empSql);
            
            if (!mysqli_query($link,$empSql)) {
                echo("Error description: " . mysqli_error($link));
            } else {
                if ($empresult->num_rows === 0) {
                    echo "Error retrieving account details.";
                } else {
                    $row = mysqli_fetch_assoc($empresult);
        ?>
        <form id='profile' method='post' action='saveProfile.php'>
            <label for='firstname' >First Name:</label>
            <input type='text' name='firstname' value='<?php echo $row['firstname'];?>' id='firstname'  maxlength="50" />
            <label for='lastname' >Last Name:</label>
            <input type='text' name='lastname' value='<?php echo $row['lastname'];?>' id='lastname'  maxlength="50" />
            <br>
            <label for='email' >Email*:</label>
            <input type='text' name='email' value='<?php echo $row['email'];?>' id='email'  maxlength="50" />
            <br>
            <label for='password' >Password*:</label>
            <input type='password' name='password' id='password'  maxlength="50" />
            <br>
            <label for='phone' >Phone Number:</label>
            <input type='text' name='phone' value='<?php echo $row['phone'];?>' id='phone'  maxlength="50" onkeypress="return isNumber(event)" />
            <br>
            <label for='web' >Website:</label>
            <input type='text' name='web' value='<?php echo $row['website'];?>' id='web'  maxlength="50" />
            <br>
            <label for='biography' >Biography:</label>
            <textarea name="biography"><?php echo $row['biography'];?></textarea>
            <script type="text/javascript">
                CKEDITOR.replace('biography');
            </script>
            <br>
            <input type='submit' name='submit' value='Save Changes' />
        </form>
        <div id="profileError" style='color:red'>
            <?php
                if (isset($_SESSION['profileError'])) {
                    echo $_SESSION['profileError'];
                }
            ?>
        </div>
        <div id="profileSuccess" style='color:green'>
            <?php
                if (isset($_SESSION['profileSuccess'])) {
                    echo $_SESSION['profileSuccess'];
                }
            ?>
        </div>
        <p id='nanError' style="display: none;">Please enter numbers only</p>
            
        <?php 
                }
            }
        ?>
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
    </script>
</html>
