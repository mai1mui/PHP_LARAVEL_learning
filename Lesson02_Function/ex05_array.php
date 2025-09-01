<?php
    //1. single dimension
    $fruits = array('apple', 'banana');
    $color = ['red', 'green'];
    
        //1.1. join() method
        echo join(', ', $fruits);
        echo '<hr>';
        
        //1.2. for loop
        for($i = 0; $i < count($color); $i++):
            echo $color[$i].', ';
        endfor;
        echo '<hr>';
        
        //1.3. foreach
        foreach($color as $cnt):
            echo $cnt.'- ';
        endforeach;
        echo '<hr>';

    //2. multiple dimension
        $item = [
            ['a', 'abc', 123],
            ['b', 'bcd', 234],
            ['c', 'cde', 345]
        ];
        
        foreach($item as $rs): //resultset, recordset
            foreach($rs as $data):
                echo $data.', ';
            endforeach;
            echo '<br>';
        endforeach;
    