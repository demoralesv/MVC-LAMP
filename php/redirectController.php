<?php
$servername = "localhost";
$username = "root";
$password = "LmP_2k26";
$dbname = "urlsdb";

$conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$url = $_SERVER['REQUEST_URI'];
$ip = $_SERVER['REMOTE_ADDR'];
$urlcode = basename($url);
$sql = "select id, originalUrl from url where code = '$urlcode'";
$result = $conn->query($sql);
if($result->rowCount() > 0){
$row = $result->fetch();
header("Location: " . $row['originalUrl']);
}else{
header("Location: /index.php");
}
exit();


?>
