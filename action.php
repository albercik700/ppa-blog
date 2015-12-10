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
Nick:<input type=\"text\" name=\"login\"/><br/>
Hasło:<Input type=\"password\" name=\"passw\"/><br/>
E-mail:<input type=\"text\" name=\"mail\"/><br/>
<input type=\"submit\" value=\"Zarejestruj\"/>
</form>";
	}
}

if($_SERVER['REQUEST_METHOD']=='POST'){
	if(isset($_POST['login']) && isset($_POST['passw'])){
		if(!isset($_SESSION['login']) and $db->logIn($_POST['login'],$_POST['passw'])==1){
			echo "Witaj!<br/>";
		}else if(isset($_SESSION['login']) and $db->logStatus($_SESSION['login'])==1){
			echo "Jesteś juz zalogowany<br/>";
			echo "<a href='login.php?act=logout'>Wyloguj</a>";
		}else{
			echo "Nieprawidłowy login lub hasło!<br/>";
			echo formularz("logowanie");
		}
	}
}else if($_SERVER['REQUEST_METHOD']=='GET'){
	if(isset($_GET['act']) and $_GET['act']=='logout'){
		if($db->logOut()==1){
			echo "Zostałeś wylogowany<br/>";
			echo formularz("logowanie");
		}
	}
}
?>
