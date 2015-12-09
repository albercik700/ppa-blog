<?php
require_once "db.php";
session_start();
/*$db=connect();
$result=$db->query("select * from uzytkownicy");
while($row=$result->fetch_assoc()){
	echo $row['nazwa']."<br/>";
	echo $row['pass']."<br/>";
	echo $row['email']."<br/>";
	echo $row['data_rejestracji'];
}
$db->close();
*/
$db = @new BlogManager("localhost","root","","blog");
echo $db->login("admin","admin");
?>