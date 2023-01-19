<?php
    include 'functions/config.php';
?>
<?php session_start();
$value = $_POST['value'];
$subject = $_POST['subject'];
$class = $_POST['class'];
$date = $_POST['date'];

if(empty($value)){
    $_SESSION['msg'] = 'Все поля должны быть заполнены!';
    header("Location: teacher.php");
    exit();
}
if(empty($subject)){
    $_SESSION['msg'] = 'Все поля должны быть заполнены';
    header("Location: teacher.php");
    exit();
}
if(empty($class)){
    $_SESSION['msg'] = 'Все поля должны быть заполнены';
    header("Location: teacher.php");
    exit();
}
if(empty($date)){
    $_SESSION['msg'] = 'Все поля должны быть заполнены';
    header("Location: teacher.php");
    exit();
}
$sql = "SELECT * FROM subject";
$result = mysqli_query($db, $sql);
foreach($result as $row){
    if ($subject == $row['name']){
        $id_subject = $row['id'];     
    }
}
$sql = "SELECT * FROM class";
$result = mysqli_query($db, $sql);
foreach($result as $row){
    if ($class == $row['num_class']){
        $id_class = $row['id'];
    }
}

$id = 0;
$sql2 = "INSERT INTO dz (`id`, `values`, `id_subject`, `id_class`, `date`) VALUES ('$id', '$value', '$id_subject', '$class', '$date')";
$result2 = mysqli_query($db, $sql2);
$sql4 = "SELECT `id` FROM dz WHERE `values` = '$value'";
$result4 = mysqli_query($db, $sql4);
foreach ($result4 as $row){
    $id_dz_file = $row['id'];
}
$file = $_FILES['file']['name'];
$tmp_name = $_FILES['file']['tmp_name'];
$pathFile = __DIR__.'/uploads/'. $file;
move_uploaded_file($tmp_name, "uploads/" . $file);
$idFile = 0;
$sql3 = "INSERT INTO files (`id`, `id_dz`, `filename`) VALUES ('$idFile', '$id_dz_file', '$file')";
$result3 = mysqli_query($db, $sql3);
$id_r = 0;
$sql = "SELECT `id` FROM dz WHERE `values` = '$value'";
$result = mysqli_query($db, $sql);
foreach ($result as $row){
    $id_dz = $row['id'];
}
$sql = "SELECT `username` FROM users WHERE `id_class` = '$id_class' and status = 0;";
$result = mysqli_query($db, $sql);
foreach($result as $row){
    $log_user = $row['username'];
    $sql_r = "INSERT INTO rate (`id`, `id_dz`, `log_user`, `id_subject`) VALUES('$id_r', '$id_dz', '$log_user', '$id_subject')";
    $result_r = mysqli_query($db, $sql_r);
}

header("Location:teacher.php");
exit(); 

?>