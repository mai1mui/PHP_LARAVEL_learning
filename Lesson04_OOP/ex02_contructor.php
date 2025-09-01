<?php
class Fruit {
    //khai bao bien
    public $name;
    public $weight;
    //methods
    function __construct($name,$weight){
        $this->name=$name; 
        $this->weight=$weight;
    }
    function get_name(){
        return $this->name;
    }
    function get_weight(){
        return $this->weight;
    }
}
//xac dinh doi tuong
    $apple=new Fruit('Apple','200g');//bat buoc truyen tham so Apple
//hien thi
    echo $apple->get_name()." has ".$apple->get_weight();
?>