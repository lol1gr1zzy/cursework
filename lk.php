<?php
    include 'functions/config.php';
?>
<?php session_start();
if (!isset($_COOKIE['userToken'])){
    $_SESSION['msg'] = 'Авторизируйтесь чтобы продолжить';
    header("Location: index.php");
    exit();  
}
$flag = 0;
$cookie = mysqli_query($db, "SELECT `status`, `userToken` FROM users WHERE username = '$_SESSION[log]'");
foreach($cookie as $value){
    if(!empty($_COOKIE['userToken']) && $value['status'] == 0){
        if($_COOKIE['userToken'] == $value['userToken']){
            $flag = 1;
            //echo 'проверка прошла, токен совпадает';  
        }
    }
}
if(isset($_COOKIE['userToken']) && $flag == 1){
    //echo 'проверка прошла';
}
else {
    //token не совпал с токеном в БД
    header('Location: index.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <title>ЛК УЧЕНИКА</title>
</head>
<body>
    <?php session_start();
    if(isset($_SESSION['log'])){
        $log = $_SESSION['log'];
        $sql = "SELECT users.name, users.username, class.num_class, users.id_class FROM users JOIN class ON users.id_class = class.id";
        $result = mysqli_query($db, $sql);
        foreach($result as $row){
            if ($log == $row['username']){
                $id_class = $row['id_class'];
                echo '<div class="hello"><h1>Привет ' .$row['name']. ' из<br>' .$row['num_class']. ' класса!</h1></div>';
            }
        }
    }
    ?>
    <table align="center" border="1px">
        <tr>
            <th> Дата </th>
            <th> Предмет </th>
            <th> Домашнее задание </th>
            <th> Оценка </th>
        </tr>
    <?php
    $count = 2;
    if (isset($_GET['page'])){
        $page = $_GET['page'];
    }
    else{
        $page = 0;
    }
    $start_from = $page * 2;
    
     $sql2 = "SELECT dz.id, files.id_dz, files.filename, dz.date, subject.name, dz.values, rate.value FROM dz JOIN files ON dz.id = files.id_dz JOIN subject ON dz.id_subject = subject.id JOIN rate ON dz.id = rate.id_dz JOIN class ON class.num_class = dz.id_class JOIN users ON users.id_class = class.id WHERE rate.log_user = '$log' and users.status = 0 and users.id_class = '$id_class' and users.username = '$log'  
     ORDER BY `dz`.`date` DESC LIMIT $start_from, $count";
    $result2 = mysqli_query($db, $sql2);
    $rowsCount = mysqli_num_rows($result2); // количество полученных строк
    $page_count = ceil($rowsCount / $count);
    foreach($result2 as $row){
        echo '<tr>';
        echo '<br>';
        echo '<td>';
        echo $row['date'];
        echo '</td>';
        echo '                   ';
        echo '<td>';
        echo $row['name'];
        echo '</td>';
        echo '                   ';
        echo '<td>';
        echo $row['values'];
        echo '</td>';
        echo '                   ';
        echo '<td>';
        echo $row['value'];
        echo '</td>';
        echo '                   ';
        echo '<td>';
        if ($row['filename']!= null){
            if ($row['id'] == $row['id_dz']){
            echo '<form action="fileopener.php" method="POST">';
            echo "<input type='hidden' name='file' value='$row[filename]'>"; 
            echo '<input type=submit value="скачать">';
            //echo $row['filename'];
            echo '</form>';
            echo '</tr>';
            echo '<br>';
            }
        }
        else{
            echo '</tr>';
            echo '<br>'; 
        }

    }
    ?>
    </table>
    <?php for ($p = 0; $p <= $page_count; $p++) :?>
    <a href="?page=<?php echo $p; ?>"><button class="btn btn-outline-info"><?php echo $p + 1; ?></button></a>
    <?php endfor; ?>
    <div><p><a href = "changepass.php" class="change"> Сменить пароль </a></p></div>
    <form class ="form-group"action="sessionkiller.php" method="POST">
        <p><button name="logout" type="submit" class="btn btn-primary mb-2">Выйти</button></p>
    </form>
</body>
</html>