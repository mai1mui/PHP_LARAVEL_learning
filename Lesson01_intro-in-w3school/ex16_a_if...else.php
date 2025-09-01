//1.if...else
    syntax
        if (condition) {
          // code to be executed if condition is true;
        } else {
          // code to be executed if condition is false;
        }
    
    <?php
    $a ="5";
    if($a > "1"){
        echo 'have a good day';
    }
    else {
        echo 'good night';
    };
    ?>
//2.if...elseif...else
    syntax:
        if (condition) {
           //code to be executed if this condition is true;
        } elseif (condition) {
           // code to be executed if first condition is false and this condition is true;
        } else {
           // code to be executed if all conditions are false;
        }