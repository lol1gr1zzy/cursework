# Отчёт о курсовой работе
#### *По курсу "Основы Программирования"*
#### *Работу выполнил студент группы №3136 Шмидт А.Г.*


## Изучение предметной области

Журнал успеваемости учащихся. Админский интерфейс позволяет добавлять перподавателей и учеников. Учителя выкладывают задания и выставляют оценки.


## Составление ТЗ

### Возможности учеников:
- Просматривать ДЗ и их оценки
- Скачивать прикрпленные файлы к ДЗ
- Смены пароля
- Восстановления пароля
### Возможности учителей:
- Создавать ДЗ по выбранному предмету для конкретного класса
- Удалять ДЗ
- Редактировать ДЗ
- Выставлять оценки
- Смены пароля
- Восстановления пароля
- Скачивать прикрпленные файлы к ДЗ


## Выбор технологий

#### *Платформа:*

#### *Среда разработки:*
VS Code, OpenServer.

#### *Языки программирования:*
PHP, HTML, JS, SQL.

## Реализация

### Пользовательский интерфейс:
ЛК преподавателя:

ЛК ученика:

Выставление оценки ученику:

Админка:

### Пользовательский сценарий:
- Пользователь (ученик/преподаватель) зайдет в ЛК
- Пользователь введет неверный пароль
- У пользователя закончатся куки он будет перенаправлен на *index.php*
- Пользователь ничего не введет и будет перенаправлен на *index.php*
- Пользователь сменить пароль
- Пользователь восстановит пароль

### API сервера и хореография:




### Структура базы данных

Браузерное приложение phpMyAdmin используется для просмотра содержимого базы данных. Всего 8 таблиц:

Первая таблица users для хранения данных о пользователях:
1. "id" с автоинкрементом для выдачи уникальных id всем пользователям
2. "name"  для хранения ФИО пользователя
3. "username"  для хранения логина
4. "password" для хранения пароля (захэшированного уже с солью)
5. "status" для хранения типа пользователя
6. "id_class" для хранения id класса
7. "userToken" для хранения куки

Вторая таблица для хранения email:
1. "id" с автоинкрементом для выдачи уникальных id всем email
2. "email" для хранения самого email 
3. "log_user" для хранения логина пользователя

Третья таблица для хранения данных о классах:
1. "id" типа int с автоинкрементом для выдачи уникальных id всем классам
2. "num_class" для хранения названия класса 

Четвертая таблица для хранения данных о предметах:
1. "id" типа int с автоинкрементом для выдачи уникальных id всем предметам
2. "name" для хранения названия предмета

Пятая таблица для хранения данных о ДЗ:
1. "id" типа int с автоинкрементом для выдачи уникальных id всем ДЗ
2. "values" для хранения значения
3. "id_subject" для хранения id предмета
4. "id_class" для хранения id класса
5. "date" для хранения даты

Шестая таблица для хранения данных о файлах:
1. "id" типа int с автоинкрементом для выдачи уникальных id всем файлам
2. "filename" для хранения имени файла
3. "id_dz" для хранения id ДЗ к которому прикреплен данный файл

Седьмая таблица для хранения данных об оценках:
1. "id" типа int с автоинкрементом для выдачи уникальных id всем оценкам
2. "value" для хранения значения
3. "id_subject" для хранения id предмета
4. "id_dz" для хранения id ДЗ к которому относится эта оценка
5. "log_user" для хранения логина пользователя

Восьмая таблица для хранения логина и пароля для админки:
1. "login"
2. "password"

### Алгоритмы


### Значимые фрагменты кода
**Авторизация**:
```php
$login = $_POST['login']; 
$password = $_POST['password'];
$newhash = '';

if(empty($login)){
    $_SESSION['msg'] = 'Все поля должно быть заполнены!';
    header("Location: index.php");
    exit();
}
if(empty($password)){
    $_SESSION['msg'] = 'Все поля должно быть заполнены!';
    header("Location: index.php");
    exit();
}


$_SESSION['log'] = $login;
$sql = "SELECT `username`, `password`, `status`, `id_class` `userToken` FROM users";
if($result = mysqli_query($db, $sql)){
    foreach($result as $row){
        if($login == $row['username']){
            $newhash = $row['password'];
            if($login == $row['username'] && password_verify($password, $newhash) == 1){
                $token = bin2hex(random_bytes(10));
                setcookie('userToken', $token, time() + 500, '/');
                $cookie = mysqli_query($db, "UPDATE users SET userToken = '$token' WHERE username = '$row[username]'");
                if($row['status'] == 0){
                    header('Location: lk.php');
                    exit();
                }
                if($row['status'] == 1){
                    $id_class = $row['id_class'];
                    $_SESSION['id_class'] = $id_class;
                    header('Location: teacher.php');
                    exit();
                }
            }
        }
    }
    $_SESSION['msg'] = 'Неверный логин или пароль!';
    header("Location: index.php");
    exit( );    
}
```
**Проверка на куки**:
```php
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
```
**Вывод ДЗ у ученика в таблице**
```php
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
```
**Создание ДЗ**
```php
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
```
