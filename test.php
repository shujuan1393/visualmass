<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

//split code and digits (if any)
preg_match_all('/([\d]+)/', "wNasd023", $match);
                        
print_r($match[0]);
echo "<br>";

if (count($match[0]) === 0) {
    echo "Empty!";
} else {
    $arr = explode($match[0][0], "wNasd023");
    print_r($arr);
}

if ($match[0][0] <= "1000") {
    echo "true";
}
?>
