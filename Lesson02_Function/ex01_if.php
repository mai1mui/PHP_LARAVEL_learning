<?php
    $num = 1;

    //1. common syntax
    if($num >= 0){
        echo 'number +';
    }else{
        echo 'number -';
    }
    
    //2. alternative struct
    if($num >= 0):
        echo 'number +';
    else:
        echo 'number -';
    endif;