
<?php 
   
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
        <title>New Item List</title>
         <style>
            body {
                font-family: Arial, sans-serif;
                display: flex;
                justify-content: center;
                align-items: center;
                width: 50%;
                height: 100vh;
                background-color: #f4f4f4;
                padding: 100px
            }
            .container {width: 100%;
                background: white;
                padding: 20px;
                border-radius: 10px;
                box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
            }
            table {
                width: 100%;
            }
            td {
                padding: 10px;
            }
            input {
                width: 100%;
                padding: 8px;
                border: 1px solid #ccc;
                border-radius: 5px;
            }
            button {
                background-color: #28a745;
                color: white;
                padding: 10px 10px;
                border: none;
                border-radius: 5px;
                cursor: pointer;
                width: 100px;
            }
            button:hover {
                background-color: #218838;
            }
        </style>
    </head>
    <body>
        <div class="container">
            <h2>Add New Car</h2>
            <form action="zRead.php" method="GET"> <!--Khi nhấn nút "Add New", dữ liệu sẽ gửi đến file zRead.php.
                                                       Dữ liệu được gửi qua URL (hiển thị trên thanh địa chỉ).-->
                <table>
                    <tr>
                        <td>Code:</td>
                        <td><input type="text" name="txtCode" required autofocus placeholder="Enter car code"></td>
                    </tr>
                    <tr>
                        <td>Name:</td>
                        <td><input type="text" name="txtName" required placeholder="Enter car name"></td>
                    </tr>
                    <tr>
                        <td>Price:</td>
                        <td><input type="number" name="txtPrice" required placeholder="Enter car price"></td>
                    </tr>
                    <tr>
                        <td colspan="2" style="text-align: center;">
                            <button type="submit">Add New</button>
                        </td>
                    </tr>
                </table>
            </form>
        </div>
    </body>
</html>
<!--GET và POST
+ đều được dùng để gửi dữ liệu từ client (trình duyệt) lên server
+khác nhau
    1.Hiển thị dữ liệu trên URL
        GET: Dữ liệu được gửi qua URL dưới dạng query string (ví dụ: ?name=Toyota&price=50000).
        POST: Dữ liệu không hiển thị trên URL, mà được gửi ẩn bên trong request body.
      Ví dụ:
        GET → example.com/form.php?name=Toyota&price=50000
        POST → Không thấy dữ liệu trên URL.
      ⏩ Kết luận:
        GET thích hợp khi dữ liệu không nhạy cảm (tìm kiếm, lọc dữ liệu,...).
        POST phù hợp khi gửi thông tin nhạy cảm (mật khẩu, dữ liệu đăng nhập, file,...).
    2. Giới hạn độ dài dữ liệu
        GET: Giới hạn khoảng 2048 ký tự (tùy trình duyệt).
        POST: Không giới hạn kích thước dữ liệu (tùy server cấu hình).
      ⏩ Kết luận:
        POST phù hợp khi gửi dữ liệu lớn (file, văn bản dài,...).
    3. Bảo mật
        GET: Kém bảo mật vì dữ liệu hiển thị trên URL, dễ bị xem hoặc bookmark lại.
        POST: Bảo mật hơn, dữ liệu không hiển thị công khai.
      ⏩ Kết luận:
        Đăng nhập, thanh toán nên dùng POST để tránh lộ thông tin.
    4. Lưu cache
        GET: Có thể được cache bởi trình duyệt hoặc server (khi nhấn "Back", dữ liệu vẫn giữ nguyên).
        POST: Không được cache (sau khi gửi, nếu nhấn "Back", dữ liệu sẽ bị mất).
      ⏩ Kết luận:
        GET thích hợp cho tìm kiếm, lọc dữ liệu.
        POST thích hợp khi cập nhật, xóa dữ liệu để tránh lỗi gửi lại.
    Khi nào dùng GET và POST?
        GET :
            +Tìm kiếm trên web
            +API lấy dữ liệu
        POST:
            +Gửi biểu mẫu đăng ký
            +Đăng nhập, mật khẩu
            +Gửi dữ liệu lớn (file)
            +Xóa dữ liệu (DELETE)-->