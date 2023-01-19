<?php
    include 'functions/config.php';
?>
<?php session_start();
$username = $_SESSION['log'];
$email = $_POST['email']; 
$newpassword = $_POST['newpassword'];
$newhash = password_hash($newpassword, PASSWORD_DEFAULT);
if(empty($email)){
    header('Location: forgetpass.php?msg=Поле должно быть заполнено');
    exit();
}

if(empty($newpassword)){
    header('Location: forgetpass.php?msg=Поле должно быть заполнено');
    exit();
}
if (preg_match("/^[a-zA-Z0-9\!@#\/\$%\^&\*\(\)\[\]\{\}\-=_\+\.,'\"<>\?]+$/", $newpassword) == 0){
    $err_message_pass = urldecode('Пароль не должен содержать спец символы');
    header('Location: forgetpass.php?err_message_pass='.$err_message_pass);
    exit();
}
$sql = "SELECT * FROM emails";
$result = mysqli_query($db, $sql);
foreach($result as $value){
    if ($email == $value['email']){
        $result = mysqli_query($db, "UPDATE users SET password = '$newhash' WHERE username = '$username'");
        $_SESSION['msg'] = 'Пароль успешно изменён!';
        header("Location: index.php");
        exit();
    }
}
$Message = urlencode("Такого email не существует или неверный пароль");
header("Location:forgetpass.php?Message=".$Message);
exit();
?>