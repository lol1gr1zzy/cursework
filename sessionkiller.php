<?php
    include 'functions/config.php';
?>
<?php session_start();
    if(isset($_POST['logout'])){
        $taskList = mysqli_query($db, "SELECT `userToken` FROM users WHERE `username` = '$_SESSION[log]'");
        foreach ($taskList as $value){        
            if ($_COOKIE['userToken'] == $value['userToken']) {       
                    $cookie = mysqli_query($db, "UPDATE users SET userToken = '' WHERE `username` = '$_SESSION[log]'");;
                    unset($_COOKIE['userToken']);
                    setcookie('userToken', null, -1, '/');
            }
        } 
    }
session_destroy();
header("Location:index.php");
exit();
?>