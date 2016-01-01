<?php
if($_SERVER['REQUEST_METHOD']=='POST'){
	if(isset($_POST['login']) && isset($_POST['passw'])){
		if(!isset($_SESSION['login']) and $db->logIn($_POST['login'],$_POST['passw'])==1){
			//wpisy
			echo "</div>\n<div id=\"kol_prawa\">";
			echo "Witaj ".$_SESSION['nazwa']."!<br/>";
			echo "<a href='login.php?act=logout'>Wyloguj</a>";
		}else if(isset($_SESSION['login']) and $db->logStatus($_SESSION['login'])==1){
			//wpisy
			echo "</div>\n<div id=\"kol_prawa\">";
			echo "Jesteś juz zalogowany ".$_SESSION['nazwa']."<br/>";
			echo "<a href='.?act=logout'>Wyloguj</a>";
		}else{
			//wpisy
			echo "</div>\n<div id=\"kol_prawa\">";
			echo "Nieprawidłowy login lub hasło!<br/>";
		}
	}else if(!isset($_SESSION['login']) && isset($_POST['r_login']) && isset($_POST['r_passw']) && isset($_POST['r_mail'])){
		if($db->register($_POST['r_login'],$_POST['r_passw'],$_POST['r_mail'])==1){
			//wpisy
			echo "</div>\n<div id=\"kol_prawa\">";
			echo "Zostałeś zarejestrowany!<br/>";
			echo "<form method=\"POST\" action=\".\">
					Nick:<input type=\"text\" name=\"login\"/><br/>
					Hasło:<Input type=\"password\" name=\"passw\"/><br/>
					<input type=\"submit\" value=\"Zaloguj\"/>
					</form>";
		}else{
			//wpisy
			echo "</div>\n<div id=\"kol_prawa\">\n";
			echo "Rejestracja nie powiodła się!<br/>\n";
		}
	}
}else if($_SERVER['REQUEST_METHOD']=='GET'){
	if(isset($_GET['act']) and $_GET['act']=='login'){
		//wpisy
		echo "</div>\n<div id=\"kol_prawa\">";
		echo "<form method=\"POST\" action=\".\">
		Nick:<input type=\"text\" name=\"login\"/><br/>
		Hasło:<Input type=\"password\" name=\"passw\"/><br/>
		<input type=\"submit\" value=\"Zaloguj\"/>
		</form>";
	}else if(isset($_GET['act']) and $_GET['act']=='logout'){
		//wpisy
		echo "</div>\n<div id=\"kol_prawa\">";
		if($db->logOut()==1){
			echo "Zostałeś wylogowany<br/>";
			header("Location:index.php");	
		}
	}else if(isset($_GET['act']) and $_GET['act']=='reg'){
		//wpisy
		echo "</div>\n<div id=\"kol_prawa\">";
		echo "<form method=\"POST\" action=\".\">
		Nick:<input type=\"text\" name=\"r_login\"/><br/>
		Hasło:<Input type=\"password\" name=\"r_passw\"/><br/>
		E-mail:<input type=\"text\" name=\"r_mail\"/><br/>
		<input type=\"submit\" value=\"Zarejestruj\"/>
		</form>";
	}else{
		//wpisy
		echo "</div>\n<div id=\"kol_prawa\">";
	}
}

if(!isset($_SESSION['login'])){
	//wpisy
	echo "</div>\n<div id=\"kol_prawa\">";
	echo "<p><a href=\"?act=login\">Zaloguj</a></p>\n
			<p><a href=\"?act=reg\">Zarejestruj</a></p>\n<br/>\n";
}else
	//wpisy
	echo "</div>\n<div id=\"kol_prawa\">";

?>
