

<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>My Timetable</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    </head>
    <body>
        <div class="container mt-3">
            <h2>My Timetable</h2>
            <form method="POST">
                <table class="table-control">
                    <tr>
                        <td><label for="time">TIME: </label></td>
                        <td><input type="time" class="form-control" id="time" name="txtTime"></td>
                    </tr>
                    <tr>
                        <td><label for="action">ACTION: </label></td>
                        <td><input type="text" class="form-control" id="action" name="txtAction"></td>
                    </tr>
                    <tr>
                        <td><label for="note">NOTE: </label></td>
                        <td><input type="email" class="form-control" id="note" name="txtNote"></td>
                    </tr>

                </table>
                <input type="submit" value="Add" class="btn-add" name="btnAdd" href="tRead.php">
            </form>
        </div>
    </body>
</html>
