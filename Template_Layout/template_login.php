<?php

?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Template Login</title>
  <style>
    .container {
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
    }

    .form-box {
      padding: 100px;
      border: 1px solid #ddd;
      border-radius: 10px;
      background-color: #f5f5f5;
    }

    .form-group {
      margin-bottom: 20px;
    }

    label {
      display: block;
      margin-bottom: 5px;
    }

    input[type="text"],
    input[type="password"] {
      width: 100%;
      padding: 20px;
      border: 1px solid #ccc;
      border-radius: 5px;
    }

    .btn-submit,
    .btn-cancel {
      padding: 10px 20px;
      border: none;
      border-radius: 10px;
      cursor: pointer;
    }

    .btn-submit {
      color: #fff;
      background-color: blue;
      margin-right: 10px;
    }

    .btn-submit:hover {
      color: blue;
      background-color: #fff;
    }

    .btn-cancel {
      color: #fff;
      background-color: gray;
    }

    .btn-cancel:hover {
      color: gray;
      background-color: #fff;
    }
  </style>
</head>

<body>
  <div class="container">
    <form class="form-box">
      <h1>Login</h1>

      <div class="form-group">
        <label for="id">ID*</label>
        <input id="id" type="text" required />
      </div>

      <div class="form-group">
        <label for="password">Password*</label>
        <input id="password" type="password" required />
      </div>

      <div class="form-group">
        <button class="btn-submit" type="submit">Submit</button>
        <button class="btn-cancel" type="reset">Cancel</button>
      </div>
    </form>
  </div>
</body>

</html>
