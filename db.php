<?php
function connect(){
	$db = @new mysqli("localhost","root","blog");
	if(mysqli_connect_errno()!=0){
		die("Błąd połączenia z bazą danych.");
	}
	return $db;
}
?>