<?php
    $element1 = '<hr width="';
    $element2 = '">';
   
    //1. Common syntax
    for($i = 5; $i <= 100; $i += 5){
        echo "<p>$element1 $i $element2";
    }
    
    //2. Alternative struct
    
    for($i = 100; $i >=5; $i -= 5){
        echo "<p>$element1 $i $element2";
    }