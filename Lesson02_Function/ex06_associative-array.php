<?php

    //1. single dimension
    $list = [
        'n1' => 'a',
        'n2' => 'b',
        'n3' => 'c'
    ];
    foreach($list as $key => $value):
        echo '<p>student '.$value.' has a key: '.$key;
    endforeach;
    
    echo '<hr>';
    
    //2. multiple dimension
    $car = [
        'i1' => ['brand' => '1', 'model' => '2', 5000],
        'i2' => ['brand' => '3', 'model' => '4', 1000],
        'i3' => ['brand' => '5', 'model' => '6', 2000]
    ];
    foreach($car as $key => $value): //$value or $key is just set name for u
        foreach($value as $data):
            echo $data,', ';
        endforeach;
        echo '<p>';
    endforeach;