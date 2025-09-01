<?php
    $today = date('D'); //date format 3 kí tự đầu ngẫu nhiên

    //1. common syntax
    switch ($today) {
        case 'mon':
            echo 'monday';
            break;
        case 'wed':
            echo 'wednesday';
            break;
        case 'Fri':
            echo 'friday';
            break;

    default:
        echo 'off';
        break;
    }
    
    //2. alternative struct
    switch ($today):
        case 'mon':
            echo 'monday';
            break;
        case 'wed':
            echo 'wednesday';
            break;
        case 'Fri':
            echo 'friday';
            break;
        default:
            echo 'off';
            break;
    endswitch;