<?php require_once '../functions/connect.php';?>
<?php session_start();?>
<?php
$login = $_POST['login'];
$password = $_POST['password'];

$sql = $pdo->prepare("SELECT id, login FROM admin WHERE login=:login AND password=:password");
$sql->execute(array('login' =>$login, 'password' =>$password));
$array = $sql->fetch(PDO::FETCH_ASSOC);
if($array['id'] > 0){
    $_SESSION['login'] = $array['id'];
    //echo 'вошел';
    // header('Location:Iadmin.php');
}
else{
    header('Location:/login.php'); 
}
?>
<?php echo "<h1> Вы в админке !</h1>"; ?>
<strong><?php session_start();
        echo $_SESSION['msg'];
        unset($_SESSION['msg']);
    ?></strong>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <title>СОЗДАТЬ ПОЛЬЗОВАТЕЛЯ</title>
</head>
<body>
    <h1> Создать пользователя </h1>
    <h2>0 - ученик. 1 - учитель</h2>
    <form action="createUser.php" method="POST">
        <br><input type="text" name='name' placeholder="name"></br>
        <br><input type="text" name='username' placeholder="login"></br>
        <br><input type="password" name='password' placeholder="pass"></br>
        <br><input type="email" name='email' placeholder="email"></br>
        <br><input type="text" name='IDclass' placeholder="IDclass"></br>
        <br><input type="number" name='status' placeholder="status"></br>
        <br><button type="submit">Enter</button></br>
    </form>
    <h2>Создать класс</h2>
    <form action="createClass.php" method="POST">
    <br><input type="text" name='nameClass' placeholder="name"></br>
    <br><button type="submit">Enter</button></br>
    </form>
    <form class ="form-group"action="/sessionkiller.php" method="POST">
        <p><button name="logout" type="submit" class="btn btn-primary mb-2">Выйти</button></p>
    </form>
</body>
</html>