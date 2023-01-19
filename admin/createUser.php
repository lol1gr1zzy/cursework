<?php
    include 'functions/config.php';
?>
<?php
$name = $_POST['name'];
$username = $_POST['username'];
$password = $_POST['password'];
$status = $_POST['status'];
$IDclass = $_POST['IDclass'];
$email = $_POST['email'];
var_dump($status);
if (preg_match('/^[A-Za-z0-9 ]{4,10}$/i', $username) == 0){
    $_SESSION['msg'] = 'Логин должен содержать только латинские буквы. Не менее 4 и не более 10 символов';
    header("Location: /login.php");
}
if(empty($name)or $name == null){
    $_SESSION['msg'] = 'Все поля должно быть заполнены!';
    header("Location: /login.php");
}
if(empty($username)or $username == null){
    $_SESSION['msg'] = 'Все поля должно быть заполнены';
    header("Location: /login.php");
}
if(empty($password)or $password == null){
    $_SESSION['msg'] = 'Все поля должно быть заполнены';
    header("Location: /login.php");
}
if(empty($IDclass)or $IDclass == null){
    $_SESSION['msg'] = 'Все поля должно быть заполнены';
    header("Location: /login.php");
}if(empty($email)or $email == null){
    $_SESSION['msg'] = 'Все поля должно быть заполнены';
    header("Location: /login.php");
}
if (preg_match("/^[a-zA-Z0-9\!@#\/\$%\^&\*\(\)\[\]\{\}\-=_\+\.,'\"<>\?]+$/", $password) == 0){
    $_SESSION['msg'] = 'Пароль не должен содержать спец символы';
    header("Location: /login.php");
}
$userToken = '';
$hash = password_hash($password, PASSWORD_DEFAULT);
$id = 0;
$result = mysqli_query ($db,"INSERT INTO users (`id`, `name`, `username`, `password`, `status`, `id_class`, `userToken`) VALUES ('$id', '$name', '$username', '$hash', '$status', '$IDclass', '$userToken')");
$id_e = 0;
$sql = "INSERT INTO emails (`id`, `email`, `login_user`) VALUES ('$id_e', '$email', '$username')";
$result = mysqli_query($db, $sql);
header("Location:admin.php");
exit(); 
?>