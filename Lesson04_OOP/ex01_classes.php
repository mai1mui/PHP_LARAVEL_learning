<?php
class fruit{
    //khai báo
    public $name;
    public $weight;
    //methods(phương thức)
    function set_name($name){
        $this->name=$name;
    }
    function set_weight($weight){
        $this->weight=$weight;
    }
    function get_name(){
       return $this->name;
    }
    function get_weight(){
        return $this->weight;
    }
}
//xác định đối tượng
$apple=new Fruit();
$banana=new Fruit();
$apple->set_name('Apple');
$apple->set_weight('200g');
$banana->set_name('Banana');
$banana->set_weight('1000g');
//hiển thị
echo $apple->get_name()." có khối lượng ".$apple->get_weight();
echo " thì nhẹ hơn ";
echo $banana->get_name()." có khối lượng ".$banana->get_weight();
?>