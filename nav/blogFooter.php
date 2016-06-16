<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
?>

<html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
    </head>
    <body>
        <div id='whole_footer' class='blog_footer'>
            
            <form id='writeForm' action='processBlogFooter.php' method='post' accept-charset='UTF-8'>
                
                <h1>Write with us at Visual Mass</h1>

                <div id="blogError" style="color:red">
                    <?php 
                        if (isset($_SESSION['blogError'])) {
                            echo $_SESSION['blogError'];
                        }
                    ?>
                </div>

                <div id="addAuthorSuccess" style="color:green">
                    <?php 
                        if (isset($_SESSION['addAuthorSuccess'])) {
                            echo $_SESSION['addAuthorSuccess'];
                        }
                    ?>
                </div>
                
                <input type='hidden' name='submitted' id='submitted' value='1'/>
            
                <table class='content'>
                    <tr>
                        <td>
                            <input type='text' name='firstName' id='firstName' placeholder='First Name'>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <input type='text' name ='lastName' id ='lastName' placeholder='Last Name'>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <input type='text' name='email' id='email' placeholder='Email' value='<?php if (isset($_SESSION['bEmail'])) { echo $_SESSION['bEmail']; }?>'>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <input type='text' name='phone' id='phone' placeholder='Phone Number'>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <input type='submit' name='submit' value='Write with us!'>
                        </td>
                    </tr>
                </table>
            </form>
        </div>
    </body>
</html>