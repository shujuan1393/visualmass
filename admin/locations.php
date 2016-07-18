<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
require_once '../config/db.php';

if (isset($_GET['id'])) {
    $selectSql = "Select * from locations where id ='" .$_GET['id']."';";
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
                                Locations
                            </li>
                        </ol>
                        
                        <h1 class="page-header">Manage Locations</h1>

                        <?php 
                            $qry = "Select * from locations where name <> 'banner'";

                            $result = mysqli_query($link, $qry);

                            if (!mysqli_query($link,$qry))
                            {
                                echo("Error description: " . mysqli_error($link));
                            } else {
                                if ($result->num_rows === 0) {
                                    echo "You have not created any locations yet.";
                                } else {
                        ?>
                        
                        <p class="text-right">
                            <a href="#add"><i class="fa fa-fw fa-plus"></i> Add Location</a>
                        </p>
                        
                        
                        <div class="pull-left filter-align">Filter: </div>
                        <div style="overflow:hidden">
                            <input type="text" id="filter" class="pull-right" placeholder="Type here to search">
                        </div>
                        
                        <table id ="example">
                            <thead>
                                <th>Name</th>
                                <th>Address</th>
                                <th>Phone</th>
                                <th>Type</th>
                                <th>Services</th>
                                <th>Edit</th>
                                <th>Delete</th>                        
                            </thead>
                            <tbody class="searchable">
                            <?php
                                // output data of each row
                                while ($row = mysqli_fetch_assoc($result)) {
                                    echo "<tr>";
                                    echo "<td>".$row['name'] ."</td>";
                                    echo "<td>".$row['address'].", ".$row['city'].", ".$row['country']."</td>";                            
                                    echo "<td>".$row['phone']."</td>";                           
                                    echo "<td>".$row['type']."</td>";                           
                                    echo "<td>".$row['services']."</td>";                         
                                    echo '<td><button onClick="window.location.href=`locations.php?id='.$row['id'].'`">E</button>';
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
                        
                        <div id="updateLocSuccess" class="success">
                            <?php 
                                if (isset($_SESSION['updateLocSuccess'])) {
                                    echo $_SESSION['updateLocSuccess'];
                                }
                            ?>
                        </div>
                        <div id="updateLocError" class="error">
                            <?php 
                                if (isset($_SESSION['updateLocError'])) {
                                    echo $_SESSION['updateLocError'];
                                }
                            ?>
                        </div>
                        
                        <form id='addLocation' action='processLocations.php' method='post' accept-charset='UTF-8' enctype="multipart/form-data">
                            
                            <div id="addLocError" class="error">
                                <?php 
                                    if (isset($_SESSION['addLocError'])) {
                                        echo $_SESSION['addLocError'];
                                    }

                                    if (isset($_SESSION['uploadLocError'])) {
                                        echo $_SESSION['uploadLocError'];
                                    }
                                ?>
                            </div>
                            <p id='nanError' style="display: none;">Please enter numbers only</p>

                            <div id="addLocSuccess" class="success">
                                <?php 
                                    if (isset($_SESSION['addLocSuccess'])) {
                                        echo $_SESSION['addLocSuccess'];
                                    }
                                ?>
                            </div>
                            
                            <h1 id="add" class="page-header">Add/Edit Location</h1>
                            
                            <input type='hidden' name='submitted' id='submitted' value='1'/>
                            <input type='hidden' name='editid' id='editid' 
                                   value='<?php if (isset($_GET['id'])) { echo $erow['id']; }?>'/>
                            
                            <table class="content">
                                <tr>
                                    <td>
                                        Name*:
                                        <input type='text' name='name' id='name'  maxlength="50" value ="<?php 
                                        if(isset($_SESSION['name'])) { 
                                            echo $_SESSION['name']; 
                                        } else if (!empty($erow['name'])) { 
                                            echo $erow['name']; 
                                        }  ?>"/>
                                    </td>
                                    <td>
                                        Location Code*: <br/>
                                        <button type='button' onclick="randomString()" class="pull-right">Generate</button>
                                        <div style="overflow: hidden;" >
                                        <input type='text' name='code' id='code' value ="<?php 
                                        if(isset($_SESSION['randomString'])) { 
                                            echo $_SESSION['randomString']; 
                                        } else if (!empty($erow['code'])) { 
                                            echo $erow['code']; }?>" maxlength="50" />
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        Phone*:
                                        <input type='text' name='phone' id='phone'  maxlength="50"  
                                               onkeypress="return isNumber(event)" value ="<?php 
                                               if(isset($_SESSION['phone'])) { 
                                                echo $_SESSION['phone']; 
                                               } else if(!empty($erow['phone'])) { 
                                                   echo $erow['phone']; 
                                               } ?>"/>
                                    </td>
                                    <td>
                                        Type*:
                                        <select name="type">
                                            <option value="retail" <?php 
                                                if(isset($_SESSION['type'])) { 
                                                    if (strcmp($_SESSION['type'], "retail") === 0) {
                                                        echo " selected";
                                                    }
                                                } else if (!empty($erow['type'])) {
                                                    if (strcmp($erow['type'], "retail") === 0) {
                                                        echo " selected";
                                                    }
                                                } 
                                            ?>>Retail</option>
                                            <option value="popup" <?php 
                                                if(isset($_SESSION['type'])) { 
                                                    if (strcmp($_SESSION['type'], "popup") === 0) {
                                                        echo " selected";
                                                    }
                                                } else if (!empty($erow['type'])) {
                                                    if (strcmp($erow['type'], "popup") === 0) {
                                                        echo " selected";
                                                    }
                                                } 
                                            ?>>Pop-up</option>
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2">
                                        Address*:
                                        <input type='text' name='address' id='address'  maxlength="50" value ="<?php 
                                        if (isset($_SESSION['address'])) { 
                                            echo $_SESSION['address']; 
                                        } else if (!empty($erow['address'])) { 
                                            echo $erow['address']; 
                                        } else ?>"/>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        Apt, suite:
                                        <input type='text' name='apt' id='apt'  maxlength="50" value ="<?php 
                                        if(isset($_SESSION['apt'])) { 
                                            echo $_SESSION['apt']; 
                                        } else if (!empty($erow['apt'])) { 
                                            echo $erow['apt']; 
                                        } ?>"/>
                                    </td>
                                    <td>
                                        ZIP Code*:
                                        <input type='text' name='zip' id='zip'  maxlength="50"  
                                               onkeypress="return isNumber(event)" value ="<?php 
                                        if(isset($_SESSION['zip'])) { 
                                            echo $_SESSION['zip']; 
                                        } else if (!empty($erow['zip'])) { 
                                            echo $erow['zip'];
                                        } ?>"/>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        City*:
                                        <input type='text' name='city' id='city'  maxlength="50" value ="<?php 
                                        if(isset($_SESSION['city'])) { 
                                            echo $_SESSION['city']; 
                                        } else if (!empty($erow['city'])) { 
                                            echo $erow['city']; 
                                        } ?>"/>
                                    </td>
                                    <td>
                                        Country*:
                                        <input type='text' name='country' id='country'  maxlength="50" value ="<?php 
                                        if(isset($_SESSION['country'])) { 
                                            echo $_SESSION['country']; 
                                        } else if (!empty($erow['country'])) { 
                                            echo $erow['country'];
                                        } ?>"/>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        Status*:
                                        <select name='status' id='status'>
                                            <option value='active' <?php 
                                                if(isset($_SESSION['status'])) { 
                                                    if (strcmp($_SESSION['status'], "active") === 0) {
                                                        echo " selected";
                                                    }
                                                } else if (!empty($erow['status'])) {
                                                    if (strcmp($erow['status'], "active") === 0) {
                                                        echo " selected";
                                                    }
                                                }  
                                            ?>>Active</option>
                                            <option value='inactive' <?php 
                                                if(isset($_SESSION['status'])) { 
                                                    if (strcmp($_SESSION['status'], "inactive") === 0) {
                                                        echo " selected";
                                                    }
                                                } else if (!empty($erow['status'])) {
                                                    if (strcmp($erow['status'], "inactive") === 0) {
                                                        echo " selected";
                                                    }
                                                }  
                                            ?>>Inactive</option>
                                        </select>
                                    </td>
                                    <td id='scheduledposts' style='display:none;'>
                                        Scheduled Date/Time:<br>
                                        <input style='width:45%!important;' type="text" placeholder="DATE" id="date4" name="date4" value='<?php if(isset($_SESSION['date4'])) { 
                                            echo $_SESSION['date4']; } else if(!empty($erow['scheduled'])) {
                                            echo date('Y-m-d', strtotime($erow['scheduled']));
                                            }  ?>'>
                                        <input style='width:45%!important;' id="setTimeExample" name='scheduledtime' placeholder="TIME" 
                                               type="text" class="time" value='<?php if(isset($_SESSION['time'])) { 
                                            echo $_SESSION['time']; } else if(!empty($erow['scheduled'])) {
                                            echo date('H.i.s', strtotime($erow['scheduled']));
                                            } ?>'/><br>
                                        <button id="setTimeButton">Set current time</button>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2">
                                        Opening Hours:
                                        <textarea name="opening" id='opening'><?php 
                                            if(isset($_SESSION['opening'])) { 
                                            echo $_SESSION['opening']; 
                                            } else if(!empty($erow['opening'])) { 
                                                echo $erow['opening']; 
                                            } ?></textarea>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2">
                                        Description:
                                        <textarea name="desc" id='desc'><?php 
                                            if (isset($_SESSION['description'])) { 
                                                echo $_SESSION['description']; 
                                            } else if(!empty($erow['description'])) { 
                                                echo $erow['description']; 
                                            } ?></textarea>
                                        <script type="text/javascript">
                                            CKEDITOR.replace('desc');
                                        </script>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        Featured Image:
                                        <input type="file" name="image" id='image' accept="image/*" />
                                        <br>
                                        <?php 
                                            if (!empty($erow['images'])) {
                                                $imgArr = explode(",", $erow['images']);
                                                for($i =0; $i < count($imgArr); $i++) {
                                                    if (!empty($imgArr[$i])) {
                                                        echo '<div class="button addMore" onClick="unlinkImg(\''.$imgArr[$i].'\')"><img src="'.$imgArr[$i].'" width=200></div><br>';
                                                    }
                                                }
                                                echo "<br><input type='hidden' name='oldImages' value='".$erow['images']."'>";
                                            }
                                        ?>
                                        Image(s): 
                                        <input type="file" name="otherimage[]" id='images' multiple accept='image/*'/>
                                        <br>
                            <!--             Button trigger modal 
                                        <button type="button" id='showModal' class="btn btn-primary btn-lg" data-toggle="modal" data-target="#popupModal">
                                          Select from existing images
                                        </button>
                                        <br>

                                         Modal 
                                        <div class="modal modal-default" id="popupModal">
                                        <div class="modal-dialog">
                                          <div class="modal-content">
                                            <div class="modal-header" style="background-color: #00A388; color: white;" align="center">
                                              <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                              <h4 class="modal-title"><strong>Welcome!</strong></h4>
                                            </div>
                                            <div class="modal-body">
                                              //<?php
                            //                        $directory = "../uploads/*/";
                            //
                            //                        //get all image files with a .jpg extension.
                            //                        $images = glob("" . $directory . "*.*");
                            //
                            //                        $imgs = '';
                            //                        // create array
                            //                        foreach($images as $image){ $imgs[] = "$image"; }
                            //
                            //                        //shuffle array
                            //                        shuffle($imgs);
                            //
                            //                        //display images
                            //                        foreach ($imgs as $img) {
                            //                            echo "<input id='selectedImg' type='hidden' value='$img'><img src='$img' width=200/>";
                            //                        }
                            //                    ?>
                                            </div>
                                            <div class="modal-footer" style="background-color: #ECF0F1">
                                              <button type="button" class="btn btn-outline pull-left" data-dismiss="modal">Close</button>
                                              <button type="button" class="btn btn-primary" id="profileInfo">Submit</button>
                                            </div>
                                          </div> /.modal-content 
                                        </div> /.modal-dialog 
                                      </div> /.modal -->
                                    </td>
                                    <td>
                                        Services*: <br/>
                                        <?php
                                            if(isset($_SESSION['services'])) { 
                                                $serviceArr = explode(",", $_SESSION['services']);
                                            } else if(!empty($erow['services'])){
                                                $serviceArr = explode(",", $erow['services']);
                                            }  
                                            $serviceSql = "Select * from services";
                                            $serviceResult = mysqli_query($link, $serviceSql);

                                        if (!mysqli_query($link,$serviceSql))
                                        {
                                            echo("Error description: " . mysqli_error($link));
                                        } else {
                                            if ($serviceResult->num_rows === 0) {
                                        ?>
                                            <input type="checkbox" name="services[]" value="nil">No services
                                        <?php
                                            } else {
                                                $count = 0;
                                                while($row = mysqli_fetch_assoc($serviceResult)) {
                                                    echo '<input type="checkbox" name="services[]" 
                                                value="'.$row['servicecode'].'"';
                                                    if (!empty($erow['services']) || isset($_SESSION['services'])) {
                                                        if (in_array($row['servicecode'], $serviceArr)) {
                                                            echo " checked";
                                                        }
                                                    }        
                                                    echo '>'.
                                                            $row['servicename']." ";
                                                    $count++;

                                                    if ($count % 2 === 0) {
                                                        echo "<br/>";
                                                    }
                                                }
                                            }
                                        }
                                        ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2">
                                        <input type='submit' name='submit' value='Submit' />
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

<script>
    var myCalendar2 = new dhtmlXCalendarObject(["date4"]);
    myCalendar2.hideTime();

    $(function() {
        $('#setTimeExample').timepicker();
        $('#setTimeButton').on('click', function (event){
            event.preventDefault();
            $('#setTimeExample').timepicker('setTime', new Date());
        });
        
        var status = document.getElementById('status').value;
        
        if (status === "inactive") {
            document.getElementById('scheduledposts').style.display = "block";
        } else {
            document.getElementById('scheduledposts').style.display = "none";
        }
    });
    
    document.getElementById('status').onclick = function() {
        var val = this.value;
        
        if (val === "inactive") {
            document.getElementById('scheduledposts').style.display = "block";
        } else {
            document.getElementById('scheduledposts').style.display = "none";
        }
    };
    
    function unlinkImg(img) {
        var id;
        <?php if (isset($_GET['id'])) { ?> 
                id = <?php echo $_GET['id']; ?>; 
        <?php    } else { ?>;
                id = "";
        <?php } ?>
        window.location="processMedia.php?type=locations&id="+id+"&file=" + img;
    }
    
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
    function randomString() {
        var text = "";
        var possible = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";

        for( var i=0; i < 5; i++ )
            text += possible.charAt(Math.floor(Math.random() * possible.length));

        document.getElementById('code').value = text;
        return false;
    }

    function deleteFunction(locId) {
        var r = confirm("Are you sure you wish to delete this location?");
        if (r === true) {
            window.location="processLocations.php?delete=1&id=" + locId;
        } else if (r === false) {
            <?php
                unset($_SESSION['updateLocSuccess']);
                $_SESSION['updateLocError'] = "Nothing was deleted";
            ?>
            window.location='locations.php';
        }
    }
    
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

