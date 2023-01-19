<?php
    include 'functions/config.php';
?>
<?php session_start();
if (isset($_POST['id'])){
    $rate_id = $_POST['id'];
}

if (isset($_POST['rate'])){
    $rate = $_POST['rate'];
}
if(empty($rate)){
    $_SESSION['msg'] = 'Все поля должно быть заполнены!';
    header("Location: rate.php");
    exit();
}
if(empty($rate)){
    $_SESSION['msg'] = 'Все поля должно быть заполнены!';
    header("Location: rate.php");
    exit();
}
$sql = mysqli_query($db, 'UPDATE rate SET value = '.$rate.' WHERE id = '.$rate_id.'');
header("Location: teacher.php");
exit( ); 
?>