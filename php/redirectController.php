<?php
require_once __DIR__ . '/../vendor/autoload.php';
use ipinfo\ipinfo\IPinfo;

$access_token = '0c678a2f046db3';
$client = new IPinfo($access_token);
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
	$urlid = $row['id'];
	$infoC = $client->getDetails($ip);
	$country = $infoC->country_name;
	unset($result);
	$sql = "select id from country where name = '$country'";
	$result = $conn->query($sql);
	if ($result->rowCount() > 0){
		$row = $result->fetch();
		$Cid = $row['id'];
	}else{	
		unset($result);
		$sql = "INSERT INTO country (name) VALUES ('$country')";
		$conn->exec($sql);
		$Cid = $conn->lastInsertId();
	}
	unset($result);
	$sql = "insert into countryxurl (idurl, idcountry, frequency) values ('$urlid','$Cid', 1) on duplicate key update frequency = frequency + 1";
	$conn->exec($sql);
	$sql = "select id from Dates where dates = curdate()";
	$result = $conn->query($sql);
	if ($result->rowCount() > 0) {
		$row = $result->fetch();
		$Did = $row['id'];
	}else{
		unset($result);
		$sql = "insert into Dates (dates) values (curdate())";
		$conn->exec($sql);
		$Did = $conn->lastInsertId();
	}
	unset($result);
	$sql = "insert into datexurl (idurl, iddate, frequency) values ('$urlid', '$Did', 1) on duplicate key update frequency = frequency + 1";
	$conn->exec($sql);
	$sql = "insert into accesslog (IP, idCountry, idDate, idUrl) values ('$ip','$Cid','$Did','$urlid')";
	$conn->exec($sql);
}else{
header("Location: /index.php");
}
exit();

?>
