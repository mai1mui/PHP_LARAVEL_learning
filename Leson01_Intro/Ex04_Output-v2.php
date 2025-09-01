<?php
#1. Variable - Biến 
$tenbien = 100;

#2. Expression 
echo '+ Kết xuất biến với một String: ' . $tenbien;

#3. Đặt trực tiếp biến vào chuỗi 
echo '<br>+ Đặt trực tiếp biến vào chuỗi nháy đơn: $tenbien';//sd dấu ngoặc đơn, các biến phải được chèn bằng .toán tử
echo "<br>+ Đặt trực tiếp biến vào chuỗi  nháy kép: $tenbien";//sd dấu ngoặc kép, các biến có thể được chèn vào chuỗi 

#4. ECHO với echo là như nhau -ko phân biệt hoa thường
ECHO '<div style="color:blue; font-size:20pt">+ ECHO với echo là như nhau</div>';

#5. Output với print 
print'<div style="color:blue; font-size:20pt">+ Output với print</div>';

#6. Kiết xuất đoạn mã HTML trong khối PHP 
echo '<h2>Kiết xuất đoạn mã HTML trong khối PHP </h2>';
echo '<table class="table table-bordered table-hover">';
echo '<tr>'; 
    echo '<th>Type 1</th>';
    echo '<th>Type 2</th>';
echo '</tr>'; 

echo '<tr>'; 
    echo "<td>$tenbien</td>";
     echo "<td>$tenbien</td>";
echo '</tr>'; 
echo'</table>'
#7. Binding data với HTML 
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
        <title>Ex04_Output-v2</title>
    </head>
    <body>
        <table class="table table-bordered table-hover"> 
            <caption><h2>Binding data với HTML </h2></caption>
            <tr> 
                <th>Cách 1</th>
                <th>Cách 2</th>
            </tr>
            <tr> 
                <td><?php echo $tenbien; ?></td>
                <td><?= $tenbien; ?></td>
            </tr>
        </table>
    </body>
</html>
