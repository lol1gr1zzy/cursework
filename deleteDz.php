<?php
    include 'functions/config.php';
?>
<?php session_start();
$id_dz = $_GET['delete'];
$sql = "DELETE FROM dz WHERE `id` = '$id_dz'";
$result = mysqli_query($db, $sql);
$sql = "DELETE FROM rate WHERE `id_dz` = '$id_dz'";
$result = mysqli_query($db, $sql);
$sql = "DELETE FROM files WHERE `id_dz` = '$id_dz'";
$result = mysqli_query($db, $sql);
$_SESSION['msg'] = 'ДЗ удалено';

// $succes  = 'succes';
// echo $succes;
header("Location: teacher.php");
exit();
?>