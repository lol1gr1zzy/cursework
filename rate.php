<?php
    include 'functions/config.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Поставить оценку</title>
</head>
<body>
<strong><?php session_start();
        echo $_SESSION['msg'];
        unset($_SESSION['msg']);
    ?></strong>
<?php session_start();
if (isset($_POST['id'])){
    $rate_id = $_POST['id'];
}
if (isset($_POST['class'])){
    $rate_class = $_POST['class'];
}
if (isset($_POST['fio'])){
    $rate_fio = $_POST['fio'];
}
if(empty($rate_id)){
    $_SESSION['msg'] = 'Все поля должно быть заполнены!';
    header("Location: teacher.php");
}
if(empty($rate_class)){
    $_SESSION['msg'] = 'Все поля должно быть заполнены!';
    header("Location: teacher.php");
}
if(empty($rate_fio)){
    $_SESSION['msg'] = 'Все поля должно быть заполнены!';
    header("Location: teacher.php");
}
$sql = "SELECT subject.name, dz.date FROM subject JOIN rate ON rate.id_subject = subject.id JOIN dz ON dz.id = rate.id_dz WHERE subject.id = '$_SESSION[subject]' AND rate.id = '$rate_id'";
$result = mysqli_query($db, $sql);
foreach($result as $row){
    $num_subject = $row['name'];
    $date = $row['date'];
    $_SESSION['num_subject'] = $num_subject;
    $_SESSION['date'] = $date;
    
}
?>
<form action="createRate.php" method="POST">
    <h1> Поставить оценку</h1>
    <h2><?php echo $rate_fio;?></h2>
    <h2>Ученику: <?php echo $rate_class;?></h2>
    <h2>Предмет <?php session_start(); echo $_SESSION['num_subject']?>:</h2>
    <h2> ДЗ от <?php session_start(); echo $_SESSION['date']?>:</h2>
<input type='hidden' name='id' value='<?php echo $rate_id; ?>'>
<input type="text" name="rate">
<input type="submit">

</form>
</body>
</html>