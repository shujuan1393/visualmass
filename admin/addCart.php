<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

require_once '../config/db.php';
?>
<head>
    <link href="../styles.css" rel="stylesheet" type="text/css" />
</head>
<html>
    <body>
        <div id="cartWrapper" class='full_section'>
            <div class="rightheader close_modal">
                <button type="button" id='closeCart' class="close" data-dismiss="modal" aria-hidden="true">X</button>
            </div>
            <div class="logo"></div>
            <div id='posAddCart'>
                <?php
                    if (isset($_GET['id']) && strcmp($_GET['type'], "purchase") === 0) {
                ?>
                <form id='addCartForm'>
                    <input type='hidden' name='submitted' id='submitted' value='1'/>
                <?php
                    $prod = "Select * from products where pid='".$_GET['id']."' and status='active';";
                    $res = mysqli_query($link, $prod);

                    if (!mysqli_query($link, $prod)) {
                        die(mysqli_error($link));
                    } else {
                        if ($res -> num_rows === 0) {
                            echo "<h4>Sorry, this product is not available</h4>";
                        } else {
                            $row = mysqli_fetch_assoc($res);
                            $feat = $row['featured'];

//                                echo "<div class='pos col-lg-10'>";
                            if (!empty($feat[0])) {
                                echo "<img src='".$feat[0]."' class='img-responsive'><br>";
                            }
                            echo "<div class='col-lg-5'>".$row['name']."</div>";
                            echo "<input type='hidden' name='price' value='".$row['price']."'>";
                            echo "<div class='col-lg-5'><input type='textbox' id='quantity' name='quantity' value='1'></div>";
                            echo "<br><br>";

                            if (!empty($row['availability'])) {
                                echo "Type: ";
                                echo "<select name='type' id='type'>";

                                //check type
                                $ptype = explode(",", $row['availability']);
                                if (in_array("sale", $ptype)) {
                                    echo "<option value='purchase'>Purchase</option>";
                                } else if (in_array("tryon", $ptype)) {
                                    echo "<option value='hometry'>Home Try-on</option>";
                                }
                                echo "</select>";
                                echo "<br><br>";
                            }
                            echo "Colour: ";
                            echo "<select name='colour' id='colour'>";

                            //for each product
                            $rel = "Select * from products where status ='active' and pid LIKE '%".$row['pid']."%';";
                            $relres = mysqli_query($link, $rel);

                            if (!mysqli_query($link, $rel)) {
                                die(mysqli_error($link));
                            } else {
                                if ($relres -> num_rows === 0) {
                                    echo "<option disabled value='null'>No other colours</option>";
                                } else {
                                    while($relrow = mysqli_fetch_assoc($relres)) {
                                        $colour = explode("-", $relrow['pid']);
                                        $col;
                                        if (empty($colour[1])) {
                                            $col = "Default";
                                        } else {
                                            $col = $colour[1];
                                        }

                                        echo "<option value='".$relrow['pid']."' ";

                                        if (strcmp($_GET['id'], $relrow['pid']) === 0) {
                                            echo " selected";
                                        }
                                        echo ">".$col."</option>";
                                    }
                                }
                            }

                            echo "</select>";

                            echo "Lens: ";

                            echo "<select name='lens' id='lens'>";
                            $lens = "Select * from products where status='active' and type='Lens';";
                            $lres = mysqli_query($link, $lens);

                            if (!mysqli_query($link, $lens)) {
                                die(mysqli_error($link));
                            } else {
                                if ($lres -> num_rows === 0) {
                                    echo "<option disabled value='null'>No Lens</option>";
                                } else {
                                    while($lrow = mysqli_fetch_assoc($lres)) {
                                        echo "<option value='".$lrow['pid']."'>".$lrow['name']."</option>";
                                    }
                                }
                            }

                            echo "</select>";
//                                echo "</div>";
                        }
                    }
                ?>  
                    <br>
                    <input type='submit' name='Submit' value='Add Item' />
                </form>
                <?php } ?>
            </div>
        </div>
    </body>
    <script>
        <?php 
            if (isset($_SESSION['addCartSuccess'])) {
                if (strcmp($_SESSION['addCartSuccess'], "yes") === 0) {
                    unset($_SESSION['addCartSuccess']);
        ?>
                    document.getElementById('closeCart').click();
        <?php   
                } 
            }        
        ?>
        $('#addCartForm').validate({
            rules: {
                quantity: {
                    required: true
                },
                colour: {
                    required: true
                },
                lens: {
                    required: true
                },
                type: {
                    required: true
                }
            },
            highlight: function(element) {
            $(element).closest('.control-group').removeClass('success').addClass('error');
            },
            submitHandler: function(form) {
                $.ajax({
                    type:"POST",
                    url: "processCart.php",
                    data: $(form).serialize(),
                    }).done(function(data) {
                    $('#cartWrapper').replaceWith(data);
                    });
            }
        });
    </script>
</html>