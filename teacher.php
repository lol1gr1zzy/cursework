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
    if(!empty($_COOKIE['userToken']) && $value['status'] == 1){
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
    <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>
    <script scr="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.6/umd/popper.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <title>ЛК УЧИТЕЛЯ</title>
</head>
<body>
<strong><?php session_start();
        echo $_SESSION['msg'];
        unset($_SESSION['msg']);
?></strong>
 <?php session_start();
    if(isset($_SESSION['log'])){
        $log = $_SESSION['log'];
        $sql = "SELECT users.name, users.username, class.num_class, users.id_class FROM users JOIN class ON users.id_class = class.id";
        $result = mysqli_query($db, $sql);
        foreach($result as $row){
            if ($log == $row['username']){
                $id_class = $row['id_class'];
                $_SESSION['id_class'] = $id_class;
                echo '<div class="hello"><h1>Привет ' .$row['name']. ' вы руководитель ' .$row['num_class']. ' класса!</h1></div>';
            }
        }
    }
    ?>
    <div class="dz"><h2>Создать ДЗ</h2></div>
    <div class="dz">
    <form class="formDZ" action="createDz.php" method="POST" enctype="multipart/form-data">
    <textarea name="value" id="value" cols="30" rows="10" placeholder="Ваш текст"></textarea>
    <br><input name="date" id="date" type="date"></br>
    <br><input type="file" id="file" name="file"></br>
    <select id="subject" name="subject">
    <option value="0">Выберите предмет</option>
    <?php
    $res = mysqli_query($db, "SELECT `name` FROM subject");
    $row = mysqli_fetch_assoc($res);

    echo $row['name'];
    foreach($res as $row){
        echo "<option value='{$row['name']}'>";
        echo $row['name'];
        echo "</option>";
    }
    ?>
    </select>
    <select id="class" name="class">
    <option value="0">Выберите класс</option>
    <?php
    $res = mysqli_query($db, "SELECT class.num_class, users.id_class FROM class JOIN users ON users.id_class = class.id WHERE users.username = '$_SESSION[log]' AND users.id_class = '$_SESSION[id_class]' AND status = 1;");
    $row = mysqli_fetch_assoc($res);
    print $row['num_class'];
    foreach($res as $row){
        echo "<option value='{$row['num_class']}'>";
        echo $row['num_class'];
        echo "</option>";
        $num_class = $row['num_class'];
    }
    ?>
    <br><input type="submit" id="send" value="Создать ДЗ"></br>
    </form>
    </div>

    <form class="select" action="" method="GET"></select>
    <select name="subject_table">
    <option value="0">Выберите предмет</option>
    <?php
    $res = mysqli_query($db, "SELECT * FROM subject");
    $row = mysqli_fetch_assoc($res);

    foreach($res as $row){
        echo "<option value='{$row['name']}'>";
        echo $row['name'];
        echo "</option>";
    }
    ?>
    </select>
    <br><input type="submit" value="Применить"></br>
    </form>
    <?php session_start(); 
    if(isset($_GET['subject_table'])){
        $subject_name = $_GET['subject_table'];
        $sql = "SELECT * FROM subject";
        $result = mysqli_query($db, $sql);
        foreach($result as $row){
            if ($subject_name == $row['name']){
                $id_subject = $row['id'];
                $_SESSION['subject'] = $id_subject;
                var_dump($_SESSION['subject']);
            }
        }
        header("Location: teacher.php");
        exit();
    }   
    ?>
    <form action="rate.php" method="POST">
    <table align="center" border="1px">
	<tbody>
		<tr>
			<td colspan="2">Список учеников</td>
			<td colspan="3">Домашнее задание</td>
		</tr>
		<tr>
			<td>№</td>
			<td>ФИО</td>

            <?php session_start(); 
             $sql_dz = "SELECT dz.date, dz.values, dz.id_class, dz.id_subject FROM dz JOIN class ON dz.id_class = class.num_class WHERE class.id = '$_SESSION[id_class]' AND dz.id_subject = '$_SESSION[subject]'";
    $list_dz = mysqli_query($db, $sql_dz);
    foreach($list_dz as $row){
        echo '<td>';
        echo $row['date'];
        echo '</td>';
        echo '<br>';
    }
		echo '</tr>';
        $sql_r = "SELECT rate.value, rate.id, users.name from users JOIN rate ON users.username = rate.log_user WHERE rate.id_subject = 1 AND users.id_class = 1 AND users.status = 0 AND  
ORDER BY `users`.`name` ASC";
        $sql_st  = "SELECT `name` FROM users WHERE id_class = '$_SESSION[id_class]' AND status = 0";
        $list = mysqli_query($db, $sql_st);
        $n = 1;
        foreach($list as $row){
            echo '<tr>';
        echo '<td>';
        echo $n;
        echo '</td>';
        echo '<td>';
        echo $row['name'];
        echo '</td>';
        $n++;
        $sql_r = "SELECT rate.value, rate.id, users.name from users JOIN rate ON users.username = rate.log_user WHERE rate.id_subject = '$_SESSION[subject]' AND users.id_class = '$_SESSION[id_class]' AND users.status = 0 AND users.name = '$row[name]'  
        ORDER BY `users`.`name` ASC";
        $list_st = mysqli_query($db, $sql_r);
        foreach ($list_st as $row){
        if ($row['value'] == 0){
            echo '<td>';
            echo '<form action="rate.php" method="POST">';
            echo "<input type='hidden' name='id' value='$row[id]'>";
            echo "<input type='hidden' name='class' value='$num_class'>";
            echo "<input type='hidden' name='fio' value='$row[name]'>";
            echo "<input type='submit' value='+' name='btn'>";
            echo '</form>';
            echo '</td>';
        }
        else{
            echo '<td>';
            echo $row['value'];
            echo '</td>';
        }

        }
    }
    ?>
        </tr>
	</tbody>
    </table>
    <table align="center" border="1px">
        <tr>
            <th> Дата </th>
            <th> Предмет </th>
            <th> Домашнее задание </th>
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
     $sql2 = "SELECT dz.id, files.id_dz, files.filename, dz.date, subject.name, dz.values FROM dz JOIN subject ON dz.id_subject = subject.id JOIN class ON class.num_class = dz.id_class JOIN users ON class.id = users.id_class JOIN files ON dz.id = files.id_dz WHERE users.username = '$_SESSION[log]' LIMIT $start_from, $count";
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
        if ($row['filename']!= ''){
            if ($row['id'] == $row['id_dz']){
            echo '<form action="fileopener.php" method="POST">';
            echo "<input type='hidden' name='file' value='$row[filename]'>"; 
            echo '<input type=submit value="скачать">';
            echo '</form>';
            }
        }
        echo '<td>';
        echo '<form action="deleteDz.php" method="GET">';
        echo "<input type='hidden' name='delete' value='$row[id]'>"; 
        echo '<button type="submit" class="btn btn-outline-danger">Удалить</button>';
        echo '</form>';
        echo '<td>';
        echo '<form action="createRedate.php" method="POST">';
        echo "<input type='hidden' name='idDz' value='$row[id]'>"; 
        echo "<input type='hidden' name='redate' value='$row[values]'>"; 
        echo '<button type="submit" id="redate" class="btn btn-outline-warning">Редак</button>';
        echo '</form>';
        echo '</tr>';
        echo '<br>';

    }
    ?>
    <h3 align="center">СПИСОК ВСЕХ ДЗ</h3>
    <strong><?php session_start();
        echo $_SESSION['msg'];
        unset($_SESSION['msg']);
    ?></strong>
    </table>




    </form>
    <div><p><a href = "changepass.php" class="change"> Сменить пароль </a></p></div>
    <form class="form-group" action="sessionkiller.php" method="POST">
        <p><button class="btn btn-primary mb-2" name="logout" type="submit">Выйти</button></p>
    </form>
    <?php for ($p = 0; $p <= $page_count; $p++) :?>
    <a href="?page=<?php echo $p; ?>"><button class="btn btn-outline-info"><?php echo $p + 1; ?></button></a>
    <?php endfor; ?>
</body>
</html>