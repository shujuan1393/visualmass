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
                <div id='banner' class='giftcard'>
                    <h1>IMAGE</h1>
                    <h3>Makes great presents</h3>
                    <p>Surprise someone special by giving them a Visual Mass gift card.</p>
                    <a class='button' href='#start'>SHOP NOW</a>
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
                                <div id='physical' class='col-md-2 col-md-offset-3 giftbox'>
                                    <h5>PHYSICAL</h5>
                                    <p>Some Text</p>
                                </div>
                                <div id='ecard' class='col-md-2 col-md-offset-2 giftbox'>
                                    <h5>E-GIFT</h5>  
                                    <p>Some Text</p>                              
                                </div>
                            </div>
                        </div>
                    </div>

                    <div id='amount' class='full_section giftcard col-md-12'>
                        <h4>CIRCLE CIRCLE THING HERE</h4>
                        <h4>WHAT AMOUNT?</h4>
                        <div id='giftcardamounts'>
                            <input type='hidden' name='selectedAmount' id='selectedAmount'>
                            <?php 
                                if (isset($_SESSION['type'])) {
                                    $giftcard = "Select * from giftcards where type LIKE '%".$_SESSION['type']."%'";
                                    $result = mysqli_query($link, $giftcard);

                                    if(!mysqli_query($link, $giftcard)) {
                                        echo "Error: ".mysqli_error($link);
                                    } else {
                                        $count = 0;
                                        while($row = mysqli_fetch_assoc($result)) {
                                            echo "<div id='amount".$count."' class='col-md-2 giftbox'>";
                                            echo "<input type='hidden' id='value".$count."' value='".$row['code']."'>";
                                            echo "<h5>$".$row['amount']."</h5>";
                                            echo html_entity_decode($row['description']);
                                            echo "</div>";
                                            $count++;
                                        }
                                    }
                                }
                            ?>
                        </div>
                    </div>

                    <div id='date' class='full_section giftcard col-md-12'>
                        <h4>CIRCLE CIRCLE THING HERE</h4>
                        <h4>WHEN DO YOU WANT IT?</h4>
                        <p>You pick the date, and we'll send it to you.</p>
                        <input type='hidden' name='selectedDate' id='selectedDate'>
                        <div id='calendar' class='col-md-2 col-md-offset-5' style='height: 300px;'></div>
                    </div>

                    <div id='message' class='full_section giftcard col-md-12 row'>
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
                </form>
            </div>
            
            <div id="footer"><?php require_once 'nav/footer.php';?></div>
            
            <script>
                var myCalendar = new dhtmlXCalendarObject("calendar");
                myCalendar.hideTime();
                myCalendar.show();
                
                var clientHeight = document.getElementById('header').clientHeight;
                var height = window.innerHeight || document.documentElement.clientHeight || document.body.clientHeight;
//                alert(clientHeight + " " + height);
                document.getElementById('banner').style.height = height - clientHeight;
                
                document.getElementById('physical').onclick = function() {
                    document.getElementById('selectedType').value = 'physical';
                    <?php $_SESSION['type'] = "physical"; ?>
                    window.location = "giftcard.php#amount";
                };
                
                document.getElementById('ecard').onclick = function() {
                    document.getElementById('selectedType').value = 'ecard';
                    <?php $_SESSION['type'] = "ecard"; ?>
                    window.location = "giftcard.php#amount";
                };
                
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