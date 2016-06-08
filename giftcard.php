<?php 
    require_once 'config/db.php';
?>
<html>
    <head>
        <meta charset="UTF-8">
    </head>
    <body>
        
        <div id="wrapper">
            <div id="header"><?php require_once 'nav/header.php';?></div>
            
            <div id="content">
                <div id='giftBanner'>
                <div id='banner' class='giftcard'>
                    <h1>IMAGE</h1>
                    <h3>Makes great presents</h3>
                    <p>Surprise someone special by giving them a Visual Mass gift card.</p>
                    <p><a href='giftterms.php' data-toggle="modal" data-target="#giftModal">GIFT CARD FAQs ></a></p>
                    <input type='hidden' name='startShopping' id='startShopping'>
                    <div class='col-md-4 col-md-offset-4'><a class='button' href='#start'>SHOP NOW</a></div>
                </div>
                </div>
                <form id='giftcardForm' action='addCart.php?card=1' method='post'>
                    <div id='selGiftType' class='full_section giftcard'>
                        <div id='start' class='col-md-12'>
                            <div id="giftcardError" style="color:red">
                                <?php 
                                    if (isset($_SESSION['giftcardError'])) {
                                        echo $_SESSION['giftcardError'];
                                    }
                                ?>
                            </div>
                            <h4>CIRCLE CIRCLE THING HERE</h4>
                            <h5>Let's get started</h5>
                            <h4>WHAT TYPE OF GIFT CARD WOULD YOU LIKE?</h4>
                            <p>Some Text</p>

                            <div class='row'>
                                <input type='hidden' name='selectedType' id='selectedType'>
                                <div id='physical' onclick="displayAmounts('physical')" class='col-md-2 col-md-offset-3 giftbox'>
                                    <h5>PHYSICAL</h5>
                                    <p>Some Text</p>
                                </div>
                                <div id='ecard' onclick="displayAmounts('ecard')" class='col-md-2 col-md-offset-2 giftbox'>
                                    <h5>E-GIFT</h5>  
                                    <p>Some Text</p>                              
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div id='selAmount'>
                        <div id='amount' class='full_section giftcard col-md-12'>
                            <h4>CIRCLE CIRCLE THING HERE</h4>
                            <h4>WHAT AMOUNT?</h4>
                            <div id='giftcardamounts'>
                                <input type='hidden' name='selectedAmount' id='selectedAmount'>
                                <?php 
                                    $giftcard = "Select * from giftcards;";
                                    $result = mysqli_query($link, $giftcard);
                                    
                                    if(!mysqli_query($link, $giftcard)) {
                                        echo "Error: ".mysqli_error($link);
                                    } else {
                                        $count = 0;
                                        while($row = mysqli_fetch_assoc($result)) {
                                            echo "<div id='amount".$count."' class='col-md-2 giftbox' style='display:none;'>";
                                            echo "<input type='hidden' id='type".$count."' value='".$row['type']."'>";
                                            echo "<input type='hidden' id='value".$count."' value='".$row['code']."'>";
                                            echo "<h5>$".$row['amount']."</h5>";
                                            echo html_entity_decode($row['description']);
                                            echo "</div>";
                                            $count++;
                                        }
                                    }
                                ?>
                            </div>
                        </div>
                    </div>
                    <div id='selDate'>
                        <div id='date' class='full_section giftcard col-md-12'>
                            <h4>CIRCLE CIRCLE THING HERE</h4>
                            <h4>WHEN DO YOU WANT IT?</h4>
                            <p>You pick the date, and we'll send it to you.</p>
                            <input type='hidden' name='selectedDate' id='selectedDate'>
                            <div id='calendar' class='col-md-2 col-md-offset-5' style='height: 300px;'></div>
                        </div>
                    </div>
                    <div id='selMessage' class='full_section'>
                        <div id='message' class='giftcard col-md-12'>
                            <h4>CIRCLE CIRCLE THING HERE</h4>
                            <h4>WHO IS THE LUCKY RECIPIENT?</h4>
                            <p>Add a little note to go with your gift.</p>
                            <div class='col-md-6 col-md-offset-3'>
                                <table class="borderless">
                                    <tr>
                                        <td>
                                            <input type='text' name='recipientname' placeholder="Recipient's Name">
                                        </td>
                                        <td>
                                            <input type='text' name='yourname' placeholder="Your Name">
                                        </td>                                
                                    </tr>
                                    <tr>
                                        <td colspan="2">
                                            <input type='text' name='email' placeholder="Recipient's Email">
                                        </td>                               
                                    </tr>
                                    <tr>
                                        <td colspan="2">
                                            <textarea name='yourmessage' id='yourmessage' rows="10">Say something</textarea>
                                        </td>                               
                                    </tr>
                                    <tr>
                                        <td colspan="2">
                                            <input type='submit' name='submit' value="ADD TO CART">
                                        </td>                               
                                    </tr>
                                </table>
                            </div>
                        </div>
                        <div id='giftterms' class='col-md-12'>
                            <div class='col-md-10 col-md-offset-1'><p>Gift cards do not expire. Gift cards cannot be replaced or refunded if lost or stolen. 
                                Visual Mass gift cards cannot be purchased/used with other coupons, promo codes and other discounts.
                             As gift cards are non-refundable, if a purchase made using a gift card is returned, 
                             the original gift card value will be re-credited. </p>
                                <p><a href='giftterms.php' data-toggle="modal" data-target="#giftModal">MORE DETAILS ></a></p></div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal fade modal-fullscreen force-fullscreen" id="giftModal" tabindex="-1" 
                role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
               <div class="modal-dialog">
                 <div class="modal-content">
                   <div class="modal-header">
                     <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                     <h4 class="modal-title">Modal title</h4>
                   </div>
                   <div class="modal-body">
                   </div>
                   <div class="modal-footer">
                     <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                     <button type="button" class="btn btn-primary">Save changes</button>
                   </div>
                 </div><!-- /.modal-content -->
               </div><!-- /.modal-dialog -->
             </div><!-- /.modal -->
            
            <div id="footer"><?php require_once 'nav/footer.php';?></div>
            
            <script>
//                $(document).ready(function(){
//                    $('body').on({
//                        'mousewheel': function(e) {
//                            if (e.target.id === 'giftBanner' ||
//                                    e.target.id === 'selGiftType' || 
//                                    e.target.id === 'selAmount' || 
//                                    e.target.id === 'selDate' ||
//                                    e.target.id === 'selMessage') return;
//                            e.preventDefault();
//                            e.stopPropagation();
//                        }
//                    });
//                });
                $('html, body').bind('DOMMouseScroll mousewheel MozMousePixelScroll', function(e) {
                    var scrollTo = 0;

                  if (e.type === 'mousewheel') {
                      scrollTo = (e.originalEvent.wheelDelta * -1);
                  }
                  else if (e.type === 'DOMMouseScroll') {
                      // scrollTo = 20 * e.originalEvent.detail; // turns out, this sometimes works better as expected...
                      scrollTo = e.originalEvent.detail;
                  }

                  if (scrollTo > 0) {
                    e.preventDefault();
                    return false;
                  }
                });
                
                var myCalendar = new dhtmlXCalendarObject("calendar");
                myCalendar.hideTime();
                myCalendar.show();
                
                var clientHeight = document.getElementById('header').clientHeight;
                var height = window.innerHeight || document.documentElement.clientHeight || document.body.clientHeight;
//                alert(clientHeight + " " + height);
                document.getElementById('banner').style.height = height - clientHeight;
                
                function searchAmounts(num) {
                    var type="type" + num;
                    var typeObj = document.getElementById(type);
                    var val = typeObj.value;
                    var type = document.getElementById('selectedType').value;
                    var amt = "amount" + num;
                    var amtObj = document.getElementById(amt);
                    
                    if (val.indexOf(type) > -1) {
                        amtObj.style.display = "block";
                    } else {
                        amtObj.style.display = "none";                        
                    }
                }
                
                function displayAmounts(type) {
                    document.getElementById('selectedType').value = type;
                    for (var i = 0; i < <?php echo $count; ?>; i++) {
                        searchAmounts(i);
                    }
                    window.location = "giftcard.php#amount";
                }
                
                
                var myEvent = myCalendar.attachEvent("onClick", function(){
                    // your code here
                    var date = myCalendar.getDate(true);
                    document.getElementById('selectedDate').value = date;
                    window.location = "giftcard.php#message";
                });
                
                function handleElement(num) {
                    var str = "amount" + num;
                    document.getElementById(str).onclick = function() {
                        var val = "value" + num;
                        var value = document.getElementById(val).value;
                        document.getElementById('selectedAmount').value = value;
                        window.location = "giftcard.php#date";
                    };
                }
                
                for(var i = 0; i < <?php echo $count; ?>; i++) {
                    handleElement(i);
                }
                
            </script>
        </div>
    </body>
</html>