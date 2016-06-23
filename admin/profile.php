<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
require_once '../config/db.php';
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
                                Profile
                            </li>
                        </ol>
                        
                        <h1 class="page-header">Manage Profile</h1>
        
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

                        <div id="profileError" class="error">
                            <?php
                                if (isset($_SESSION['profileError'])) {
                                    echo $_SESSION['profileError'];
                                }
                            ?>
                        </div>
                        <div id="profileSuccess" class="success">
                            <?php
                                if (isset($_SESSION['profileSuccess'])) {
                                    echo $_SESSION['profileSuccess'];
                                }
                            ?>
                        </div>
                        <p id='nanError' style="display: none;">Please enter numbers only</p>
                        <form id='profile' method='post' action='saveProfile.php'>
                            <table class="content">
                                <tr>
                                    <td>
                                        First Name:
                                        <input type='text' name='firstname' value='<?php echo $row['firstname'];?>' id='firstname'  maxlength="50" />
                                    </td>
                                    <td>
                                        Last Name:
                                        <input type='text' name='lastname' value='<?php echo $row['lastname'];?>' id='lastname'  maxlength="50" />
                                    </td>
                                </tr>

                                <tr id="showPw">
                                    <td colspan="2"><span id="showPassword">Change Password</span></td>
                                </tr>

                                <tr id="changePassword" style="display:none">
                                    <td>
                                        Change Password*:
                                        <input type='password' name='password' id='password'  maxlength="50" />
                                    </td>
                                    <td>
                                        Retype Password*:
                                        <input type='password' name='repassword' id='repassword'  maxlength="50" />
                                    </td>
                                </tr>

                                <tr>
                                    <td>
                                        Email:
                                        <input type='text' name='email' value='<?php echo $row['email'];?>' id='email'  maxlength="50" />
                                    </td>
                                    <td>
                                        Phone Number:
                                        <input type='text' name='phone' value='<?php echo $row['phone'];?>' id='phone'  maxlength="50" onkeypress="return isNumber(event)" />
                                    </td>
                                </tr>

                                <tr>
                                    <td colspan="2">
                                        Website:
                                        <input type='text' name='web' value='<?php echo $row['website'];?>' id='website'  maxlength="50" />
                                    </td>
                                </tr>

                                <tr>
                                    <td colspan="2">
                                        Biography:
                                        <textarea name="biography"><?php echo $row['biography'];?></textarea>
                                        <script type="text/javascript">
                                            CKEDITOR.replace('biography');
                                        </script>
                                    </td>
                                </tr>

                                <tr>
                                    <td colspan="2">
                                        <input type='submit' name='submit' value='Save Changes' />
                                    </td>
                                </tr>
                            </table>
                        </form>            
                        <?php 
                                }
                            }
                        ?>
                        </div>
                    </div>
    
                    </div>
                </div>
                <!-- /.row -->

            </div>
            <!-- /.container-fluid -->

        </div>
        <!-- /#page-wrapper -->
    </body>
</html>

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

    document.getElementById('showPassword').onclick = function(){  
        var e = document.getElementById('changePassword'); 
        var text = document.getElementById('showPw');
        if(e.style.display == 'table-row'){
             e.style.display = 'none';
             text.style.display = 'table-row';
        } else {
             e.style.display = 'table-row';
             text.style.display = 'none';
         }

    };
</script>