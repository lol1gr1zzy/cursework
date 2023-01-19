<?php
    include 'functions/config.php';
?>
<?php session_start();
$redate = $_POST['redate'];
$id_dz = $_POST['idDz'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Редактировать дз</title>
</head>
<body>
    <form action="redateDz.php" method="POST">
    <h1> Редактировать запись</h1>
    <textarea name="values" cols="30" rows="10" placeholder="Ваш текст"><?php echo $redate;?></textarea>
    <br><input type="submit" id="redate" value="Изменить"></br>
    <input type='hidden' name='idDz' value='<?php echo $id_dz;?>'> 
    <br><input type="file" name="file"></br>
    </form>
</body>
</html>