<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <title>Восстановление пароля</title>
</head>
<body>
<form class='form-group' action="updatepass.php" method="POST">
        <h1 class="index-h1">Восстановление пароля</h1>
        <strong><?php if(isset($_GET['msg'])){
        print $_GET['msg'];
    }
    if (isset($_GET['err_message_pass'])){
        echo $_GET['err_message_pass'];
    }
    if(isset($_GET['Message'])){
        echo $_GET['Message'];
    }
    ?></strong>
    <p><input type="email" name="email" placeholder="Ваш email"></p>
    <p><input type="password" name="newpassword" placeholder="Ваш новый пароль"></p>
    <p><button class="btn btn-primary mb-2" type="submit">Обновить пароль</button></p>
    <p><a href = "index.php" class = "login"> Войти </a></p>
    </form>
</body>
</html>