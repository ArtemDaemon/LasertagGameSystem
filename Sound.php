<?php 
  // Создадим новую базу данных 
//$link=mysqli_connect("localhost","phpmyadmin","malina2018","log_query");
   
$var1=json_encode($_GET);
$d1=date("m.d.y h:i:s");
$quer = "INSERT INTO TableSound(Name, Time) VALUES ('".$var1."','".$d1."');";
//echo file_get_contents("http://192.168.0.108:5001/sound?file_name=$_GET[Name]");
echo ($var1);   
echo "<br><br>";

$str_1="http://192.168.0.108:5001/?file_name=$_GET[Name].ogg";

    echo "Запрос события $_GET[Name]";
    echo "<br><br>";
    echo file_get_contents($str_1);

?>


