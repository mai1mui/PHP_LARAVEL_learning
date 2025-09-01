
<?php
if($_SERVER ["REQUEST_METHOD"] == "POST"){
    $name = $_POST ["name"];
    $email = $_POST ["email"];
    $address = $_POST ["address"];
    $phone = $_POST ["phone"];
    $notes = $_POST ["notes"];
    $total = $_POST ["total"];
    session_start();
    $_SESSION ["name"] = $name;
    $_SESSION ["email"] = $email;
    $_SESSION ["address"] = $address;
    $_SESSION ["phone"] = $phone;
    $_SESSION ["notes"] = $notes;
    $_SESSION ["total"] = $total;
    header(header: "location: Confirm.php");
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopping Cart</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 800px;
            margin: auto;
            background: #fff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .form-group {
            margin: 50px;
        }
        h1, h2, h3 {
            color: #333;
        }
        form {
            margin-bottom: 20px;
        }
        label {
            display: block;
            margin-bottom: 5px;
        }
        .container input, textarea {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 3px;
        }
        .table-info {
            margin: auto;
        }
        .table-info input {
            width: 50px;
        }
        button {
            background: #5cb85c;
            color: #fff;
            border: none;
            padding: 10px 15px;
            cursor: pointer;
        }
        button:hover {
            background: #4cae4c;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }

        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 10px;
            text-align: left;
        }
        .total {
            margin-top: 20px;
            font-weight: bold;
        }
        .total input {
            width: 200px
        }
    </style>   
</head>
<body>
    <div class="container">
        <h1>Customer information</h1>
        <form class="form-group" action="" method="post">
            <label for="name">Name *</label>
            <input type="text" id="name" name="name" required>

            <label for="email">Email *</label>
            <input type="email" id="email" name="email" required>

            <label for="address">Address *</label>
            <input type="text" id="address" name="address" required>

            <label for="phone">Phone *</label>
            <input type="tel" id="phone" name="phone" required>

            <label for="notes">Notes</label>
            <textarea id="notes" name="notes"></textarea>

            <button type="submit">Send</button>
        </form>

        <h2>Information Shopping Cart</h2>
        <table class="table-info">
            <thead>
                <tr>
                    <th>Ảnh minh họa</th>
                    <th>Name item</th>
                    <th>Price</th>
                    <th>Quantity</th>
                    <th>Amount</th>
                </tr>
            </thead>
            <tbody id="cart-body">
                <tr>
                    <td><img src="image1.jpg" alt="Áo sơ mi nam" /></td>
                    <td>Áo sơ mi nam hàn quốc 1<br>Web ID: 1</td>
                    <td class="price" data-price="160000">160,000 VNĐ</td>
                    <td><input type="number" class="quantity" value="1" min="1" /></td>
                    <td class="amount">160,000 VNĐ</td>
                </tr>
                <tr>
                    <td><img src="image2.jpg" alt="Áo sơ mi nam" /></td>
                    <td>Áo sơ mi nam hàn quốc 2<br>Web ID: 1</td>
                    <td class="price" data-price="170000">170,000 VNĐ</td>
                    <td><input type="number" class="quantity" value="1" min="1" /></td>
                    <td class="amount">340,000 VNĐ</td>
                </tr>
            </tbody>
        </table>

        <div class="total">
            <div class="mb-3">
                <label class="form-label">Total:</label>
                <input type="text" id="total-amount" name="total" class="form-control" readonly>
            </div>
        </div>

    </div>
    <script>
    function updateAmounts() {
        let total = 0;
        document.querySelectorAll('#cart-body tr').forEach(row => {
            let price = parseInt(row.querySelector('.price').getAttribute('data-price'));
            let quantity = parseInt(row.querySelector('.quantity').value);
            let amount = price * quantity;
            row.querySelector('.amount').textContent = amount.toLocaleString('vi-VN') + ' VNĐ';
            total += amount;
        });
        document.getElementById('total-amount').value = total.toLocaleString('vi-VN') + ' VNĐ';
    }

    document.querySelectorAll('.quantity').forEach(input => {
        input.addEventListener('input', updateAmounts);
    });

    updateAmounts();
    </script>
</body>
</html>