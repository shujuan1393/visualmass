<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

require_once '../config/db.php';

$selectSql = "SELECT value from settings WHERE type='blog'";
$savedresult = mysqli_query($link, $selectSql);

if (isset($_GET['id'])) {    
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
    $getAuthor = "Select * from staff where id='".$_GET['aid']."' and type='author';";
    $result = mysqli_query($link, $getAuthor);
    $erow = mysqli_fetch_assoc($result);
}

if (!mysqli_query($link,$selectSql)) {
    echo("Error description: " . mysqli_error($link));
} else {
    $savedrow = mysqli_fetch_assoc($savedresult);
    $valArr = explode("&", $savedrow['value']);
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
                            <li>
                                Settings
                            </li>
                            <li class="active">
                                Blog
                            </li>
                        </ol>
                        
                        <ul class="nav nav-tabs">
                            <li class="active"><a data-toggle="tab" href="#blogc">Blog Categories</a></li>
                            <li><a data-toggle="tab" href="#menu1">Blog Authors</a></li>
                        </ul>
                        
                        <div class="tab-content">
                            <div id="blogc" class="tab-pane fade in active">
                                <h1 class="page-header">Manage Blog Categories</h1>
                                
                                        
                                <div id="updateBlogCatError" class="error">
                                    <?php 
                                        if (isset($_SESSION['updateBlogCatError'])) {
                                            echo $_SESSION['updateBlogCatError'];
                                        }
                                    ?>
                                </div>

                                <div id="updateBlogCatSuccess" class="success">
                                    <?php 
                                        if (isset($_SESSION['updateBlogCatSuccess'])) {
                                            echo $_SESSION['updateBlogCatSuccess'];
                                        }
                                    ?>
                                </div>
                                
                                <p>
                                    <?php 
                                        $blogCatSql = "select * from categories where type='blog'";
                                        $empresult = mysqli_query($link, $blogCatSql);
                                        if (!mysqli_query($link,$blogCatSql)) {
                                            echo("Error description: " . mysqli_error($link));
                                        } else {
                                            if ($empresult->num_rows === 0) {
                                                echo "There are no blog categories yet.";
                                            } else {
                                    ?>

                                    <p class="text-right">
                                        <a href="#addc"><i class="fa fa-fw fa-plus"></i> Add Category</a>
                                    </p>
                                    
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
                                        
                                    <form id='addBlogCat' method='post' action='saveBlogSettings.php?add=1'>

                                        <div id="addBlogCatError" class="error">
                                            <?php 
                                                if (isset($_SESSION['addBlogCatError'])) {
                                                    echo $_SESSION['addBlogCatError'];
                                                }
                                            ?>
                                        </div>

                                        <div id="addBlogCatSuccess" class="success">
                                            <?php 
                                                if (isset($_SESSION['addBlogCatSuccess'])) {
                                                    echo $_SESSION['addBlogCatSuccess'];
                                                }
                                            ?>
                                        </div>

                                        <h1 id="addc" class="page-header">Add/Edit Blog Category</h1>

                                        <input type='hidden' name='editid' id='editid' value='<?php 
                                                if (isset($crow['id'])) {
                                                    echo $crow['id'];
                                                }
                                               ?>'/>
                                        <input type='hidden' name='submitted' id='submitted' value='1'/>

                                        Name*:
                                        <input type='text' name='name' id='name'  maxlength="50" value='<?php 
                                                if (isset($_SESSION['name'])) {
                                                    echo $_SESSION['name'];
                                                } else if (isset($crow['name'])) {
                                                    echo $crow['name'];
                                                }
                                               ?>'/>

                                        <input type='submit' name='submit' value='Save' />

                                    </form>
                                </p>
                            </div>
                            <div id="menu1" class="tab-pane fade">
                                <h1 class="page-header">Manage Blog Authors</h1>
                                
                                <div id="updateAuthorError" class="error">
                                    <?php 
                                        if (isset($_SESSION['updateAuthorError'])) {
                                            echo $_SESSION['updateAuthorError'];
                                        }
                                    ?>
                                </div>
                                <p id='nanError' style="display: none;">Please enter numbers only</p>
                                <div id="updateAuthorSuccess" class="success">
                                    <?php 
                                        if (isset($_SESSION['updateAuthorSuccess'])) {
                                            echo $_SESSION['updateAuthorSuccess'];
                                        }
                                    ?>
                                </div>
                                
                                <p>
                                    <?php
                                        $authorSql = "Select * from staff where type='author';";

                                        $result = mysqli_query($link, $authorSql);

                                        if (!mysqli_query($link,$authorSql)) {
                                            echo("Error description: " . mysqli_error($link));
                                        } else {
                                            if ($result->num_rows === 0) {
                                                echo "You have not added any authors yet.<br>";
                                            } else {
                                    ?>
                                    
                                    <p class="text-right">
                                        <a href="#adda"><i class="fa fa-fw fa-plus"></i> Add Author</a>
                                    </p>

                                    <div class="pull-left filter-align">Filter: </div>
                                    <div style="overflow:hidden">
                                        <input type="text" id="filter" class="pull-right" placeholder="Type here to search">
                                    </div>

                                    <table id ="example">
                                        <thead>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Phone</th>
                                        <th>Edit</th>
                                        <th>Delete</th>
                                        </thead>
                                        <tbody class="searchable">
                                        <?php
                                            while ($row = mysqli_fetch_assoc($result)) {
                                                echo "<tr>";
                                                echo "<td>".$row['firstname']." ".$row['lastname']."</td>";
                                                echo "<td>".$row['email']."</td>";
                                                echo "<td>".$row['phone']."</td>";
                                                echo '<td><button onClick="window.location.href=`blogSettings.php?aid='.$row['id'].'#menu1`">E</button>';
                                                echo '<td><button onClick="deleteAuthFunction('.$row['id'].')">D</button></td>';

                                                echo "</tr>";
                                           } 
                                        ?>
                                        </tbody>
                                    </table>
                                    <?php
                                            }
                                        }
                                    ?>
        
                                    <form id='addAuthor' method='post' action='saveBlogSettings.php?update=1'>
                                        
                                        <div id="addAuthorError" class="error">
                                            <?php 
                                                if (isset($_SESSION['addAuthorError'])) {
                                                    echo $_SESSION['addAuthorError'];
                                                }
                                            ?>
                                        </div>
                                        <p id='nanError' style="display: none;">Please enter numbers only</p>
                                        <div id="addAuthorSuccess" class="success">
                                            <?php 
                                                if (isset($_SESSION['addAuthorSuccess'])) {
                                                    echo $_SESSION['addAuthorSuccess'];
                                                }
                                            ?>
                                        </div>
            
                                        <h1 id="adda" class="page-header">Add/Edit Blog Author</h1>
            
                                        <input type='hidden' name='editid' id='editid' value='<?php 
                                                if (isset($erow['id'])) {
                                                    echo $erow['id'];
                                                }
                                               ?>'/>
                                        <input type='hidden' name='submitted' id='submitted' value='1'/>
            
                                        <table class="content">
                                            <tr>
                                                <td>
                                                    First Name*:
                                                    <input type='text' name='firstname' id='firstname'  maxlength="50" value='<?php 
                                                            if (isset($_SESSION['firstname'])) {
                                                                echo $_SESSION['firstname'];
                                                            } else if (isset($erow['firstname'])) {
                                                                echo $erow['firstname'];
                                                            }
                                                           ?>'/>
                                                </td>
                                                <td>
                                                    Last Name*:
                                                    <input type='text' name='lastname' id='lastname'  maxlength="50" value='<?php 
                                                            if (isset($_SESSION['lastname'])) {
                                                                echo $_SESSION['lastname'];
                                                            } else if (isset($erow['lastname'])) {
                                                                echo $erow['lastname'];
                                                            }
                                                           ?>'/>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    Email*:
                                                    <input type='text' name='email' id='email'  maxlength="50" value='<?php 
                                                            if (isset($_SESSION['email'])) {
                                                                echo $_SESSION['email'];
                                                            } else if (isset($erow['email'])) {
                                                                echo $erow['email'];
                                                            }
                                                           ?>'/>
                                                </td>
                                                <td>
                                                    Phone*:
                                                    <input type='text' name='phone' id='phone'  maxlength="50" 
                                                           onkeypress="return isNumber(event)" value='<?php 
                                                            if (isset($_SESSION['phone'])) {
                                                                echo $_SESSION['phone'];
                                                            } else if (isset($erow['phone'])) {
                                                                echo $erow['phone'];
                                                            }
                                                           ?>'/>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="2">
                                                    Date Joined:
                                                    <input type="text" id="date3" name="date3" value='<?php 
                                                            if (isset($_SESSION['date'])) {
                                                                echo $_SESSION['date'];
                                                            } else if (isset($erow['datejoined'])) {
                                                                echo $erow['datejoined'];
                                                            }
                                                           ?>'>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="2">
                                                    <input type='submit' name='submit' value='Save' />
                                                </td>
                                            </tr>
                                        </table>
                                    </form>
                                </p>
                            </div>
                        </div>         
                    </div>
                </div>
                <!-- /.row -->

            </div>
            <!-- /.container-fluid -->

        </div>
        <!-- /#page-wrapper -->
    </div>
</html>
<?php } ?>

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

    function deleteAuthFunction(empId) {
        var r = confirm("Are you sure you wish to delete this author?");
        if (r === true) {
            window.location="saveBlogSettings.php?delete=1&aid=" + empId;
        } else if (r === false) {
            <?php
//                unset($_SESSION['addAuthorSuccess']);
//                unset($_SESSION['addAuthorError']);
//                unset($_SESSION['updateAuthorSuccess']);
//                $_SESSION['updateAuthorError'] = "Nothing was deleted";
            ?>
            window.location='blogSettings.php#menu1';
        }
    }

    function deleteFunction(empId) {
        var r = confirm("Are you sure you wish to delete this blog category?");
        if (r === true) {
            window.location="saveBlogSettings.php?delete=1&id=" + empId;
        } else if (r === false) {
            <?php
//                unset($_SESSION['addBlogCatSuccess']);
//                unset($_SESSION['addBlogCatError']);
//                unset($_SESSION['updateBlogCatSuccess']);
//                $_SESSION['updateBlogCatError'] = "Nothing was deleted";
            ?>
            window.location='blogSettings.php';
        }
    }
    
    $(document).ready(function() {
        if(location.hash) {
            $('a[href=' + location.hash + ']').tab('show');
        }
        $(document.body).on("click", "a[data-toggle]", function(event) {
            location.hash = this.getAttribute("href");
        });
    });
    
    $(window).on('popstate', function() {
        var anchor = location.hash || $("a[data-toggle=tab]").first().attr("href");
        $('a[href=' + anchor + ']').tab('show');
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