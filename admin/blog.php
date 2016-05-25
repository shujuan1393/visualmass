<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
require_once '../config/db.php';

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
    <div id="frameheader">
        <?php
            require '../nav/adminHeader.php';
        ?>
    </div>
    <div id="framecontent">
        <?php
            require '../nav/adminSidebar.php';
        ?>
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
            
            <p id='nanError' style="display: none;">Please enter numbers only</p>
            <div id="addBlogSuccess" style="color:green">
                <?php 
                    if (isset($_SESSION['addBlogSuccess'])) {
                        echo $_SESSION['addBlogSuccess'];
                    }
                ?>
            </div>
            <legend>Add/Edit Blog Post</legend>
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
            <label for='author' >Author*:</label> 
            <input type="hidden" id="addNewAuthor" name='addNewAuthor' value='no'>
            <div id="showAuthor">+ Add New Author</div>
            <div id="addAuthor" style="display:none">
                <label for='firstname' >First Name*:</label>
                <input type='text' name='firstname' id='firstname'  maxlength="50"/>
                <br>
                <label for='lastname' >Last Name*:</label>
                <input type='text' name='lastname' id='lastname'  maxlength="50"/>
                <br>
                <label for='email' >Email*:</label>
                <input type='text' name='email' id='email'  maxlength="50"/>
                <br>
                <label for='phone' >Phone*:</label>
                <input type='text' name='phone' id='phone'  maxlength="50" 
                       onkeypress="return isNumber(event)"/>
                <br>
            </div>
            
            <select name='author' id="existingAuthor">
            <?php 
                $userSql = "Select firstname, lastname from staff UNION ALL"
                        . " Select firstname, lastname from authors";
                $uresult = mysqli_query($link, $userSql);
                
                while ($urow = mysqli_fetch_assoc($uresult)) {
                    $name = $urow['firstname'] . " " . $urow['lastname'];
                    echo "<option value='". $name."' ";
                    if (!empty($erow['author'])) {
                        if (strcmp($erow['author'], $name) === 0) {
                            echo " selected";
                        }
                    } else {
                        $selected = "Select * from staff where email ='".$_SESSION['loggedUserEmail']."'";
                        $sresult = mysqli_query($link, $selected);
                        
                        $srow = mysqli_fetch_assoc($sresult);
                        $sname = $srow['firstname']. " ". $srow['lastname'];
                        
                        if (strcmp($sname, $name) === 0) {
                            echo " selected";
                        }
                    }
                    echo ">".$name."</option>";
                }
            ?>
            </select>
            <br>
            Categories*:
            <?php
                if(!empty($erow['categories'])) {
                    $cats = explode(",", $erow['categories']);
                }
                
                $blogCat = "Select * from categories where type='blog'";
                $bresult = mysqli_query($link, $blogCat);
                
                while ($brow = mysqli_fetch_assoc($bresult)) {
                    echo "<input name='categories[]' type='checkbox' value='".$brow['name']."'";
                    if (!empty($erow['categories'])) {
                        if (in_array($brow['name'], $cats)) {
                            echo " checked";
                        }
                    }
                    echo ">".$brow['name'];
                }
            ?>
            <br>
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
            <input type="text" id="date3" name="date3">
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
            Content*: 
            <textarea name="html"><?php 
            if(!empty($erow['html'])) { echo $erow['html']; }?></textarea>
            <script type="text/javascript">
                CKEDITOR.replace('html');
            </script>
            <br>
            <input type='submit' name='submit' value='Submit' />
            
            </fieldset>
        </form>
        </div>
    </div>
    <script>
        var myCalendar = new dhtmlXCalendarObject(["date3"]);
                myCalendar.hideTime();
                
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
        
        document.getElementById('showDiv').onclick = function(){  
           var e = document.getElementById('addExcerpt');
           if(e.style.display == 'block')
                e.style.display = 'none';
             else
                e.style.display = 'block';
        };
        document.getElementById('showAuthor').onclick = function(){  
           var e = document.getElementById('addAuthor');
           if (e.style.display == 'block') {
                e.style.display = 'none';
                document.getElementById('existingAuthor').style.display = "block";
                document.getElementById('addNewAuthor').value = "no";
            } else {
                e.style.display = 'block';
                document.getElementById('existingAuthor').style.display = "none";
                document.getElementById('addNewAuthor').value = "yes";
            }
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
