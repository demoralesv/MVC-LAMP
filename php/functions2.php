<?php 

$servername = "localhost";
$username = "root";
$password = "LmP_2k26";
$dbname = "urlsdb";

$conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

function getUrls($db){
 $sql = "select * from url";
 $result = $db->query($sql);
 $row = $result->fetchAll();
 return json_encode($row);
}

function getStats($db){
 $sql = "select id, shortUrl, baseUrl, originalUrl, createdAt from url";
 $u = $db->query($sql);
 $urlsf = $u->fetchAll(PDO::FETCH_ASSOC);
 $urlagro = [];
 foreach ($urlsf as $urls) {
  $idUrl = $urls['id'];
  $urlagro[$idUrl] = [
   "id" => $idUrl,
   "urloriginal" => $urls['originalUrl'],
   "countries" => [],
   "dates" => [],
   "shortUrl" => $urls['shortURL']
 ];
}

$sqlcountry = "select UC.idurl, C.name, UC.frequency from countryxurl UC join country C on UC.idcountry = C.id"; 
$CU = $db->query($sqlcountry);
while($row = $CU->fetch(PDO::FETCH_ASSOC)){
 $idUrl = $row['idurl'];
 if(isset($urlagro[$idUrl])) {
    $urlagro[$idUrl]['countries'][] = [
	   "name" => $row['name'],
           "frequency" => $row['frequency']
	];
   }
}

$sqldates = "select UD.idurl, D.dates, UD.frequency from datexurl UD join Dates D on UD.iddate = D.id";
$DU = $db->query($sqldates);
while($rowd = $DU->fetch(PDO::FETCH_ASSOC)) {
 $idUrl = $rowd['idurl'];
 if(isset($urlagro[$idUrl])) {
    $urlagro[$idUrl]['dates'][] = [
           "date" => $rowd['dates'],
           "frequency" => $rowd['frequency']
        ];
   }
}

return json_encode(array_values($urlagro));
}
?>


