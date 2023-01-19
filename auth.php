<?php
    include 'functions/config.php';
?>
<?php
session_start();
$login = $_POST['login']; 
$password = $_POST['password'];
$newhash = '';

if(empty($login)){
    $_SESSION['msg'] = 'Все поля должно быть заполнены!';
    header("Location: index.php");
    exit();
}
if(empty($password)){
    $_SESSION['msg'] = 'Все поля должно быть заполнены!';
    header("Location: index.php");
    exit();
}


$_SESSION['log'] = $login;
$sql = "SELECT `username`, `password`, `status`, `id_class` `userToken` FROM users";
if($result = mysqli_query($db, $sql)){
    foreach($result as $row){
        if($login == $row['username']){
            $newhash = $row['password'];
            if($login == $row['username'] && password_verify($password, $newhash) == 1){
                $token = bin2hex(random_bytes(10));
                setcookie('userToken', $token, time() + 500, '/');
                $cookie = mysqli_query($db, "UPDATE users SET userToken = '$token' WHERE username = '$row[username]'");
                if($row['status'] == 0){
                    header('Location: lk.php');
                    exit();
                }
                if($row['status'] == 1){
                    $id_class = $row['id_class'];
                    $_SESSION['id_class'] = $id_class;
                    header('Location: teacher.php');
                    exit();
                }
            }
        }
    }
    $_SESSION['msg'] = 'Неверный логин или пароль!';
    header("Location: index.php");
    exit( );    
}
?>