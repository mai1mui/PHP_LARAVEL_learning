<?php
//1.Dictionary
    $dictionary = new class {
        function translate ($key, $value){
            echo $key.'translate into VN'.$value;
        }
    };
//2.gọi anonymous class
$dictionary -> translate('Hi','xin chào');
echo '<br>';
$dictionary -> translate ('say','nói chuyện . vd say my name');