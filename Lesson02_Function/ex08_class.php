<?php
    //1. class declaration - class [name]
    class alpha{
        //1.1. attributes
        var $code;
        var $name;
        
        //1.2. parameter Constructor
        function __construct($x, $y){
           $this -> code = $x;
           $this -> name = $y;
        }
        
        //1.3. Methods
        function display(){
            echo $this -> code . '-' . $this -> name;
        }
    }

    //2. class acessing
        //2.1. intitial
        $alpha = new alpha('a', 'b');
        
        //2.2. attribute 
        echo $alpha -> code. '<br>';
        
        //2.3. method
        $alpha -> display();