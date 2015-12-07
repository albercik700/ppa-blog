<?php
function connect(){
	$db = @new mysqli("localhost","root","","blog");
	if(mysqli_connect_errno()!=0){
		die("Błąd połączenia z bazą danych.");
	}
	return $db;
}
function register($username,$password,$email){
	$password=hash("sha512",'31337|'.$password);
	$date=date("Y-m-d H:i:s");	
	$result=$db->query("insert into uzytkownicy(nazwa,pass,email,data_rejestracji) values('$username','$password','$email','$date');");
	if(!$result){
		echo $db->error();
	}else{
		echo "Rejestracja zakończona pomyślnie";
	}
}
?>