<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <title>Вход в админку</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
</head>
<body>
<strong><?php session_start();
        echo $_SESSION['msg'];
        unset($_SESSION['msg']);
    ?></strong>
    <h2 style="text-align:center;padding-top:100px"> Вход в админку </h2>
    <form class="form-group"action="admin/admin.php" method="POST">
        <input type="text" name='login' placeholder="Login">
        <input type="password" name='password' placeholder="Pass">
        <button class="btn btn-primary mb-2" type="submit">Enter</button>

    </form>
</body>
</html>