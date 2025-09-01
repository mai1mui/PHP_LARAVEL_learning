<?php
    $element1 = '<hr width="';
    $element2 = '">';
    
    //1. Common syntax
    $i = 10;
    
    while ($i <= 100){
        echo $element1 . $i . $element2;
        $i += 10;
    }
    
    //2. Alternative struct
    $i = 100;
    
    while ($i >= 10){
        echo $element1 . $i . $element2;
        $i -= 10;
    }
    