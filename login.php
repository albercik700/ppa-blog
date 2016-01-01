<?php
function formularz($typ){
	if($typ=='logowanie'){
		return "<form method=\"POST\" action=\"login.php\">
		Nick:<input type=\"text\" name=\"login\"/><br/>
		Hasło:<Input type=\"password\" name=\"passw\"/><br/>
		<input type=\"submit\" value=\"Zaloguj\"/>
		</form>";
	}else{
		return "<form method=\"POST\" action=\"login.php\">
		Nick:<input type=\"text\" name=\"r_login\"/><br/>
		Hasło:<Input type=\"password\" name=\"r_passw\"/><br/>
		E-mail:<input type=\"text\" name=\"r_mail\"/><br/>
		<input type=\"submit\" value=\"Zarejestruj\"/>
		</form>";
	}
}

require_once "db.php";
session_start();
$db = new BlogManager("localhost","root","","blog");
require "action.php";
echo formularz("");
?>
