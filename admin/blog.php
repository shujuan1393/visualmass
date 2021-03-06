<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
require_once '../config/db.php';

if (isset($_GET['id'])) {
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
                                Web
                            </li>
                            <li class="active">
                                Blog
                            </li>
                        </ol>
                        
                        <h1 class="page-header">Manage Blog</h1>
                        
                        <div id="updateBlogSuccess" class="success">
                            <?php 
                                if (isset($_SESSION['updateBlogSuccess'])) {
                                    echo $_SESSION['updateBlogSuccess'];
                                }
                            ?>
                        </div>
                        <div id="updateBlogError" class="error">
                            <?php 
                                if (isset($_SESSION['updateBlogError'])) {
                                    echo $_SESSION['updateBlogError'];
                                }
                            ?>
                        </div>
                        
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
                        
                        <p class="text-right">
                            <a href="#add"><i class="fa fa-fw fa-plus"></i> Add Blog Post</a>
                        </p>
                                        
                        <div class="pull-left filter-align">Filter: </div>
                        <div style="overflow:hidden">
                            <input type="text" id="filter" class="pull-right" placeholder="Type here to search">
                        </div>

                        <table id ="example">
                            <thead>
                                <th>Title</th>
                                <th>Author</th>
                                <th>Date Published</th>
                                <th>Visibility</th>
                                <th>Edit</th>
                                <th>Delete</th>                        
                            </thead>
                            <tbody class="searchable">
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
                            </tbody>
                        </table>
                        <?php
                            } 
                        }
                        ?>
        
                        <form id='addBlogPost' action='processBlogPosts.php' method='post' enctype="multipart/form-data">

                            <div id="addBlogError" class="error">
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
                            <div id="addBlogSuccess" class="success">
                                <?php 
                                    if (isset($_SESSION['addBlogSuccess'])) {
                                        echo $_SESSION['addBlogSuccess'];
                                    }
                                ?>
                            </div>
                            
                            <h1 id="add" class="page-header">Add/Edit Blog Post</h1>
            
                            <input type='hidden' name='submitted' id='submitted' value='1'/>
                            <input type='hidden' name='editid' id='editid' 
                                   value='<?php if (isset($_GET['id'])) { echo $erow['id']; }?>'/>
            
                            <table class="content">
                                <tr>
                                    <td colspan='2'>
                                        <div class="pull-left">Title*:</div><div id="showDiv" class="text-right">+ Add Excerpt</div>
                                        <input type='text' name='title' id='title'
                                               value='<?php if (isset($_SESSION['title'])) {
                                                   echo $_SESSION['title'];
                                               } else if (!empty($erow['title'])) { 
                                                   echo $erow['title']; 
                                               } ?>'/>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan='2'>
                                        <div id="addExcerpt" style="display:none">
                                            Excerpt:
                                            <textarea name="excerpt" id='excerpt'>
                                                <?php 
                                                    if (isset($_SESSION['excerpt'])) {
                                                        echo $_SESSION['excerpt'];
                                                    } else if(!empty($erow['excerpt'])) { 
                                                        echo $erow['excerpt']; 
                                                    }  
                                                ?>
                                            </textarea>
                                            <script type="text/javascript">
                                                CKEDITOR.replace('excerpt');
                                            </script>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan='2'>
                                        <div class="pull-left">Author*:</div><div id="showAuthor" class="text-right">+ Add New Author</div>
                                        
                                        <input type="hidden" id="addNewAuthor" name='addNewAuthor' value='no'>
                                        
                                        <select name='author' id="existingAuthor">
                                            <?php 
                                                $userSql = "Select firstname, lastname from staff ";
//                                                        . "UNION ALL"
//                                                        . " Select firstname, lastname from authors";
                                                $uresult = mysqli_query($link, $userSql);

                                                while ($urow = mysqli_fetch_assoc($uresult)) {
                                                    $name = $urow['firstname'] . " " . $urow['lastname'];
                                                    echo "<option value='". $name."' ";
                                                    if (isset($_SESSION['author'])) {
                                                        if (strcmp($_SESSION['author'], $name) === 0) {
                                                            echo " selected";
                                                        }
                                                    } else if (!empty($erow['author'])) {
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
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan='2'>
                                    <div id="addAuthor" style="display:none">
                                        <table class="content-sub">
                                            <tr>
                                                <td>
                                                    First Name*:
                                                    <input type='text' name='firstname' id='firstname'  maxlength="50"
                                                           value='<?php
                                                           if (isset($_SESSION['firstname'])) {
                                                               echo $_SESSION['firstname'];
                                                           }
                                                           ?>'/>
                                                </td>
                                                <td>
                                                    Last Name*:
                                                    <input type='text' name='lastname' id='lastname'  maxlength="50"
                                                           value='<?php
                                                           if (isset($_SESSION['lastname'])) {
                                                               echo $_SESSION['lastname'];
                                                           }
                                                           ?>'/>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    Email*:
                                                    <input type='text' name='email' id='email'  maxlength="50"
                                                           value='<?php
                                                           if (isset($_SESSION['email'])) {
                                                               echo $_SESSION['email'];
                                                           }
                                                           ?>'/>
                                                </td>
                                                <td>
                                                    Phone*:
                                                    <input type='text' name='phone' id='phone'  maxlength="50" 
                                                           onkeypress="return isNumber(event)"
                                                           value='<?php
                                                           if (isset($_SESSION['phone'])) {
                                                               echo $_SESSION['phone'];
                                                           }
                                                           ?>'/>    
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        Categories*: <br>
                                        <?php
                                            if (isset($_SESSION['categories'])) {
                                                $cats = explode(",", $_SESSION['categories']);
                                            } else if(!empty($erow['categories'])) {
                                                $cats = explode(",", $erow['categories']);
                                            }

                                            $blogCat = "Select * from categories where type='blog'";
                                            $bresult = mysqli_query($link, $blogCat);

                                            while ($brow = mysqli_fetch_assoc($bresult)) {
                                                echo "<input name='categories[]' type='checkbox' value='".$brow['name']."'";
                                                if (!empty($erow['categories']) || isset($_SESSION['categories'])) {
                                                    if (in_array($brow['name'], $cats)) {
                                                        echo " checked";
                                                    }
                                                }
                                                echo ">".$brow['name'];
                                            }
                                        ?>
                                    </td>
                                    <td width="40%">
                                        Tags:
                                        <div id='no-tags' stye='display:none;'>
                                            No existing tags found
                                        </div>
                                        <input type='hidden' id='tags' name='tags'>
                                        <div class="control-group">
                                                <select id="select-to" class="contacts" placeholder="Add some tags..."></select>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        Publish Date:
                                        <input type="text" id="date3" name="date3" value='<?php if (isset($_SESSION['publish'])) {
                                                echo $_SESSION['publish'];
                                            } else if(!empty($erow['dateposted'])) {
                                            echo $erow['dateposted'];
                                            } 
?>'>
                                    </td>
                                    <td>
                                        Visibility*:
                                        <select name='visibility' id='visibility'>
                                            <option value='active' <?php 
                                                if (isset($_SESSION['visibility'])) {
                                                    if (strcmp($_SESSION['visibility'], "active") === 0) {
                                                        echo " selected";
                                                    }
                                                } else if (!empty($erow['visibility'])) {
                                                    if (strcmp($erow['visibility'], "active") === 0) {
                                                        echo " selected";
                                                    }
                                                } 
                                            ?>>Active</option>
                                            <option value='inactive' <?php 
                                                if (isset($_SESSION['visibility'])) {
                                                    if (strcmp($_SESSION['visibility'], "inactive") === 0) {
                                                        echo " selected";
                                                    }
                                                } else if (!empty($erow['visibility'])) {
                                                    if (strcmp($erow['visibility'], "inactive") === 0) {
                                                        echo " selected";
                                                    }
                                                } 
                                            ?>>Inactive</option>
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <?php 
                                            if (!empty($erow['image'])) {
                                                echo "<img src='".$erow['image']."' width=200><br>";
                                                echo "<input type='hidden' name='oldImage' id='oldImage' value='".$erow['image']."'>";
                                            }
                                        ?>

                                        Image:
                                        <input type="file" name="image" id='image' accept="image/*" />
                                    </td>
                                    <td id='scheduledposts' style='display:none;'>
                                        Scheduled Date/Time:<br>
                                        <input style='width:45%!important;' type="text" placeholder="DATE" id="date4" name="date4" value='<?php if (isset($_SESSION['scheduledate'])) {
                                                echo $_SESSION['scheduledate'];
                                            } else if(!empty($erow['scheduled'])) {
                                            echo date('Y-m-d', strtotime($erow['scheduled']));
                                            } ?>'>
                                        <input style='width:45%!important;' id="setTimeExample" name='scheduledtime' placeholder="TIME" 
                                               type="text" class="time" value='<?php if (isset($_SESSION['time'])) {
                                                echo $_SESSION['time'];
                                            } else if(!empty($erow['scheduled'])) {
                                            echo date('H.i.s', strtotime($erow['scheduled']));
                                            }?>'/><br>
                                        <button id="setTimeButton">Set current time</button>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan='2'>
                                        Content*: 
                                        <textarea name="html">
                                            <?php 
                                                if (isset($_SESSION['html'])) {
                                                    echo $_SESSION['html'];
                                                } else if(!empty($erow['html'])) { 
                                                    echo $erow['html']; 
                                                } 
                                            ?>
                                        </textarea>
                                        <script type="text/javascript">
                                            CKEDITOR.replace('html');
                                        </script>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan='2'>
                                        <input type='submit' name='submit' value='Save' />
                                    </td>
                                </tr>
                            </table>
                        </form>
                    </div>
                </div>
                <!-- /.row -->

            </div>
            <!-- /.container-fluid -->

        </div>
        <!-- /#page-wrapper -->
    </div>
</html>
     
<?php 
    $tagStr = "";

    $tags = "Select * from tags where type='blog';";
    $tres = mysqli_query($link, $tags);

    if (!mysqli_query($link, $tags)) {
        die(mysqli_errno($link));
    } else {
        while ($row = mysqli_fetch_assoc($tres)) {
            $tagStr.= "{tag: '".$row['keyword']."'},";
        }
    }
?>

<script>
    <?php if (strcmp($tagStr, "") === 0) { ?>
        document.getElementById('no-tags').style.display = "block";
    <?php } ?>
        
    var myCalendar = new dhtmlXCalendarObject(["date3"]);
    myCalendar.hideTime();
    
    var myCalendar2 = new dhtmlXCalendarObject(["date4"]);
    myCalendar2.hideTime();

    $(function() {
        $('#setTimeExample').timepicker();
        $('#setTimeButton').on('click', function (event){
            event.preventDefault();
            $('#setTimeExample').timepicker('setTime', new Date());
        });
        
        
    });
    
    var status = document.getElementById('visibility').value;
        
    if (status === "inactive") {
        document.getElementById('scheduledposts').style.display = "block";
    } else {
        document.getElementById('scheduledposts').style.display = "none";
    }
    
    document.getElementById('visibility').onclick = function() {
        var val = document.getElementById('visibility').value;
        
        if (val === "inactive") {
            document.getElementById('scheduledposts').style.display = "block";
        } else {
            document.getElementById('scheduledposts').style.display = "none";
        }
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

    document.getElementById('showDiv').onclick = function(){  
       var e = document.getElementById('addExcerpt');
       if(e.style.display === 'block')
            e.style.display = 'none';
         else
            e.style.display = 'block';
    };
    document.getElementById('showAuthor').onclick = function(){  
       var e = document.getElementById('addAuthor');
       if (e.style.display === 'block') {
            e.style.display = 'none';
            document.getElementById('existingAuthor').style.display = "block";
            document.getElementById('addNewAuthor').value = "no";
        } else {
            e.style.display = 'block';
            document.getElementById('existingAuthor').style.display = "none";
            document.getElementById('addNewAuthor').value = "yes";
        }
    };
    <?php if (!empty($erow['excerpt']) || isset($_SESSION['excerpt'])) {
    ?>
        document.getElementById('addExcerpt').style.display = "block";
    <?php
        }
    ?>
    
    <?php if (isset($_SESSION['firstname']) && isset($_SESSION['lastname']) && isset($_SESSION['email']) && isset($_SESSION['phone'])) {
    ?>
        document.getElementById('existingAuthor').style.display = "block";
    <?php
        }
    ?>
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
    
    $(function() {
        $("#select-to").selectize({
            create: true
        });

        var selectize_tags = $("#select-to")[0].selectize;
        <?php 
        if (isset($_SESSION['tags'])) {
            $tagsAr = explode(",", $_SESSION['tags']);
                
            for ($i = 0; $i < count($tagsAr); $i++) {
        ?>
            selectize_tags.addOption({
                
        <?php
                    echo "tag: '".$tagsAr[$i]."'";
        ?>
            });
            selectize_tags.addItem('<?php echo $tagsAr[$i]; ?>');
        <?php
            }
        } else if (isset($_GET['id'])) {
            $tagsql = "Select * from blog where id='".$_GET['id']."';";
            $tresult = mysqli_query($link, $tagsql);

            if (!mysqli_query($link, $tagsql)) {
                die(mysqli_errno($link));
            } else {
//                while ($row = mysqli_fetch_assoc($tresult)) {
                $row = mysqli_fetch_assoc($tresult);
                $tagsAr = explode(",", $row['tags']);
                
                for ($i = 0; $i < count($tagsAr); $i++) {
        ?>
            selectize_tags.addOption({
                
        <?php
                    echo "tag: '".$tagsAr[$i]."'";
        ?>
            });
            selectize_tags.addItem('<?php echo $tagsAr[$i]; ?>');
        <?php
                }
//                }
            }
        } 
        ?>
    });
    
    $('#select-to').selectize({
            persist: false,
            maxItems: null,
            valueField: 'tag',
            labelField: 'tag',
            searchField: ['tag'],
            sortField: [
                    {field: 'tag', direction: 'asc'}
            ],
            options: [<?php echo $tagStr; ?>],
//                    {tag: 'Nikola'},
//                    {tag: 'someone@gmail.com'}
//            ],
            render: {
                    item: function(item, escape) {
                            return '<div>' +
                                    (item.tag ? '<span>' + escape(item.tag) + '</span>' : '') +
                            '</div>';
                    },
                    option: function(item, escape) {
                            var name = item.tag;
                            var label = name || item.tag;
                            var caption = name ? item.tag : null;
                            return '<div>' +
                                    (caption ? '<span class="caption">' + escape(caption) + '</span>' : '') +
                            '</div>';
                    }
            },

            create: function(input) {
                return {tag: input};
            }
    });
    
    function checkValue() {
        var value = document.getElementById('tags').value;
        var isEmpty = false;
        <?php if (strcmp($tagStr, "") === 0) { ?>
                isEmpty = true;
        <?php } ?>
        if (value === "" && isEmpty) {
            document.getElementById('no-tags').style.display = "block";
        } else {
            document.getElementById('no-tags').style.display = "none";
        }
    };   
    
    $('#select-to').change(function() {
        var selectize = $('#select-to').selectize()[0].selectize;
        var val = selectize.getValue();
        document.getElementById('tags').value = val;
        checkValue();
    });
    
    $("#filter").keyup(function () {
        var search = $(this).val();
        $(".searchable").children().show();
        $('.noresults').remove();
        if (search) {
            $(".searchable").children().not(":containsNoCase(" + search + ")").hide();
            $(".searchable").each(function () {
                if ($(this).children(':visible').length === 0) 
                    $(this).append('<tr class="noresults"><td colspan="100%">No matching results found</td></tr>');
            });

        }
    });
    
    $.expr[":"].containsNoCase = function (el, i, m) {
        var search = m[3];
        if (!search) return false;
           return new RegExp(search,"i").test($(el).text());
    };
    
    $(document).ready(function() {
        $('#example').DataTable({
            dom: "<'row'tr>" +
                "<'row'<'col-sm-5'i><'col-sm-7'p>>"
        });
    });
</script>
