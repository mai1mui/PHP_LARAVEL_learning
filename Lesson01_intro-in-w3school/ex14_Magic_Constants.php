*Hằng số ma thuật không phân biệt chữ hoa chữ thường
//1.__CLASS__Nếu sử dụng bên trong một lớp, tên lớp sẽ được trả về.<br>
<?php
class Fruits {
    public function myValue() {
        return __CLASS__;
    }
}
$kiwi = new Fruits();
echo $kiwi->myValue();
echo '<br>';
?>

//2.__DIR__Thư mục của tập tin.<br>
<?php
echo __DIR__;
echo '<br>';
?>

//3.__FILE__<br>
//4.__FUNCTION__<br>
//5.__LINE__<br>
//6.__METHOD__Nếu được sử dụng bên trong một hàm thuộc một lớp, cả tên lớp và tên hàm đều được trả về.<br>
<?php

class Fruits1 {
    public function myValue() {
        return __METHOD__;
    }
}
$kiwi = new Fruits1();
echo $kiwi->myValue();
echo '<br>';
?>
//7.__NAMESPACE__<br>
//8.__TRAIT__<br>