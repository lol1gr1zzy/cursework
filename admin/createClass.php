<?php
    include 'functions/config.php';
?>
<?php
$name = $_POST['nameClass'];
$id = 0;
$result = mysqli_query ($db,"INSERT INTO class (`id`, `num_class`) VALUES ('$id', '$name')");
header("Location:Iadmin.php");
exit(); 

?>