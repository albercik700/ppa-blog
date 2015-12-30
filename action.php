<?php
if($_SERVER['REQUEST_METHOD']=='POST'){
	if(isset($_POST['login']) && isset($_POST['passw'])){
		if(!isset($_SESSION['login']) && $db->logIn($_POST['login'],$_POST['passw'])==1 && $db->logStatus($_SESSION['login'])==1){
			echo "<p class=\"alert\">Zostałeś zalogowany</p>\n";
		}else{
			echo "<p class=\"alert\">Nieprawidłowy login lub hasło</p>\n";
		}
	}else if(!isset($_SESSION['login']) && isset($_POST['r_login']) && isset($_POST['r_passw']) && isset($_POST['r_mail'])){
		if($db->register($_POST['r_login'],$_POST['r_passw'],$_POST['r_mail'])==1){
			echo "<p class=\"alert\">Zostałeś zarejestrowany</p>\n";
		}else{
			echo "<p class=\"alert\">Rejestracja nie powiodła się</p>\n";
		}
	}
}else if($_SERVER['REQUEST_METHOD']=='GET'){
	if(isset($_GET['act']) and $_GET['act']=='logout'){
		if($db->logOut()==1){
			echo "<p class=\"alert\">Zostałeś wylogowany</p>";	
		}
	}else{

	}
}

if(!isset($_SESSION['login'])){
	echo "<div id=\"kol_lewa\">\n";
	//wpisy
	echo "</div>\n<div id=\"kol_prawa\">\n";
	echo "Panel użytkownika	<ul>\n<li><a href=\"javascript:void(0)\" onclick=\"document.getElementById('log').style.display='block';\" onDblclick=\"document.getElementById('log').style.display='none';\">Zaloguj</a></li>\n";
	echo "<form class=\"log_reg\" method=\"POST\" id=\"log\" action=\".\">\nNick:<input type=\"text\" name=\"login\"/><br/>\nHasło:<Input type=\"password\" name=\"passw\"/><br/>\n<input type=\"submit\" value=\"Zaloguj\"/>\n</form>\n";
	echo "<li><a href=\"javascript:void(0)\" onclick=\"document.getElementById('reg').style.display='block';\" onDblclick=\"document.getElementById('reg').style.display='none';\">Zarejestruj</a></li>\n";
	echo "<form class=\"log_reg\" method=\"POST\" id=\"reg\" action=\".\">Nick:<input type=\"text\" name=\"r_login\"/><br/>\nHasło:<Input type=\"password\" name=\"r_passw\"/><br/>\nE-mail:<input type=\"text\" name=\"r_mail\"/><br/>\n<input type=\"submit\" value=\"Zarejestruj\"/>\n</form></ul>\n</div>\n";
}else{
	echo "<div id=\"kol_lewa\">lala\n";
	echo "div class=\"menu\">Dodaj wpis<";
	//wpisy
	echo "</div>\n<div id=\"kol_prawa\">lala\n</div>";
}
?>
<div id="kol_prawa">
	Zalogowani:
	<p id="mini">admin, kamilos1, kamilos2, kamilos3, admin, kamilos1, kamilos2, kamilos3, admin, kamilos1, kamilos2, kamilos3</p>
</div>
