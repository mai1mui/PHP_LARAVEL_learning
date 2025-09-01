
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
        <title>New Patient List</title>
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
            <h2>Add New Patient</h2>
            <form action="list.php" method="POST"> 
                <table>
                    
                    <tr>
                        <td>Name:</td>
                        <td><input type="text" name="name" required placeholder="Enter name"></td>
                    </tr>
                    <tr>
                        <td>Age:</td>
                        <td><input type="number" name="age" required placeholder="Enter age"></td>
                    </tr>
                    <tr>
                        <td>Email:</td>
                        <td><input type="text" name="email" required placeholder="Enter email"></td>
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
