<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Login</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #f4f4f4;
        }
        .login-container {
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 300px;
        }
        .login-container h2 {
            text-align: center;
        }
        .file-upload {
            display: block;
            margin: 10px auto;
            border-radius: 50%;
            overflow: hidden;
            width: 80px;
            height: 80px;
            background-color: #f0f0f0;
        }
        .file-upload input {
            display: none;
        }
        .file-upload img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        .file-upload label {
            display: block;
            width: 100%;
            height: 100%;
            cursor: pointer;
        }
        .form-group {
            margin-bottom: 15px;
        }
        .form-group input {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        .form-group input[type="submit"] {
            background-color: #4CAF50;
            color: white;
            border: none;
            cursor: pointer;
        }
        .form-group input[type="submit"]:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h2>Đăng Nhập</h2>
        <form action="/login" method="POST" enctype="multipart/form-data">
            <!-- Logo Upload -->
            <div class="file-upload">
                <input type="file" id="avatar" name="avatar" accept="image/*" onchange="previewImage(event)">
                <label for="avatar">
                    <img id="preview" src="" alt="img">
                </label>
            </div>
            
            <!-- Username -->
            <div class="form-group">
                <input type="text" name="username" placeholder="Tên đăng nhập" required>
            </div>
            
            <!-- Password -->
            <div class="form-group">
                <input type="password" name="password" placeholder="Mật khẩu" required>
            </div>

            <!-- Submit -->
            <div class="form-group">
                <input type="submit" value="Đăng Nhập">
            </div>
        </form>
    </div>

    <script>
        function previewImage(event) {
            const reader = new FileReader();
            reader.onload = function() {
                const output = document.getElementById('preview');
                output.src = reader.result;
            };
            reader.readAsDataURL(event.target.files[0]);
        }
    </script>
</body>
</html>
