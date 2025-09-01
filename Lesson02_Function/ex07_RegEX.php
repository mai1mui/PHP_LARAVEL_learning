<?php
    $email = 'abc@abc.com';
    $remail = '/^[A-Za-z]\w*[@]\w+[.]\w{2,3}([.]\w{2,3})?$/';

    if(!preg_match($remail, $email)):
        echo 'invalid!';
    else:
        echo 'welcome';
    endif;
    
    
    $class = 't5.2410.e0';
    $reClass = '/^t\d[.]\d{4}[.][mae]\d$/';

    if(!preg_match($reClass, $class)):
        echo 'invalid!';
    else:
        echo 'welcome';
    endif;