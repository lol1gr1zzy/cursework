<?php
    include 'functions/config.php';
?>
<?php session_start();
$redate = $_POST['values'];
$id_dz = $_POST['idDz'];
$sql = "UPDATE dz SET `values` = '$redate' WHERE `id` = '$id_dz'";
$result = mysqli_query($db, $sql);
if(isset($_FILES['file'])){
    $file = $_FILES['file']['name'];
    $tmp_name = $_FILES['file']['tmp_name'];
    $pathFile = __DIR__.'/uploads/'. $file;
    move_uploaded_file($tmp_name, "uploads/" . $file);
    $sqlF = "UPDATE files SET `filename` = '$file' WHERE `id_dz` = '$id_dz'";
    $result = mysqli_query($db, $sqlF);
    unset($_FILES);
}
$_SESSION['msg'] = 'ДЗ изменено';
header("Location: teacher.php");
exit();
?>