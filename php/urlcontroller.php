<?php
	$json = file_get_contents('php://input');
	$url = json_decode($json); //obtain the json object
	$servername = "localhost";
	$username = "root";
	$password = "LmP_2k26";
	$dbname = "urlsdb";
	try {
	$conn = new PDO("mysql:host=$servername;dbname=$dbname",$username,$password);	
	$conn->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
		echo "Connected successfully";
	}catch(PDOEXception $e) {
		echo "Connection failed: " . $e->getMessage();
	}

	$chars = "1234567890qwertyuioplkjhgfdsazxcvbnm";
	$code = "";
	for ($x = 0; $x <= 5; $x++) {
		$code = $code . $chars[rand(0,36)];
	}
	$baseurl = "150.136.86.214/la";
	$shorturl = $baseurl . "/" . $code;
	$sql = "Select code from url where code = '$code'";
	$result = $conn->query($sql);
	while ($result->rowCount() > 0){
		$code = "";
		for ($x = 0; $x <= 5; $x++){
			$code = $code . $chars[rand(0,36)];
		}
		$result = $conn->query($sql);
	}
	$sql = "Select * from url where originalUrl = '$url->url'";
	$result = $conn->query($sql);
	if ($result->rowCount() == 0){
		$sql = "INSERT INTO url (code, shortUrl,baseUrl,originalUrl, createdAt, updatedAt) VALUES('$code', '$shorturl','$baseurl','$url->url',now(),now())";
		$conn->exec($sql);
		echo "New URL saved" . $shorturl; //NEW URL IN $SHORTURL
	}else{
		$row = $result->fetch();
		echo "Url already in the DB: ". $row['shortUrl']; //OLD URL IN $ROW['shortUrl']
	}
?>
