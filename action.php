<?php
echo "<div id=\"kol_lewa\">\n";
if($_SERVER['REQUEST_METHOD']=='POST'){
	if(isset($_POST['login']) && isset($_POST['passw'])){
		if($db->logIn($_POST['login'],$_POST['passw'])==1 && $db->logStatus($_SESSION['login'])==1){
			echo "<p class=\"alert\">Zostałeś zalogowany</p>\n";
		}else{
			echo "<p class=\"alert\">Nieprawidłowy login lub hasło</p>\n";
			}
	}else if(isset($_POST['r_login']) && isset($_POST['r_passw']) && isset($_POST['r_mail'])){
		if($db->register($_POST['r_login'],$_POST['r_passw'],$_POST['r_mail'])==1){
			echo "<p class=\"alert\">Zostałeś zarejestrowany</p>\n";
		}else{
			echo "<p class=\"alert\">Rejestracja nie powiodła się</p>\n";
		}
	}else if(isset($_POST['ch_passw']) && isset($_POST['ch_mail'])){
		if($db->updateUser($_POST['ch_passw'],$_POST['ch_mail'])==1){
			echo "<p class=\"alert\">Profil został zaktualizowany</p>\n";
		}else{
			echo "<p class=\"alert\">Aktualizacja profilu nie powiodła się</p>\n";
		}
	}
}else if($_SERVER['REQUEST_METHOD']=='GET'){
	if(isset($_GET['act']) and $_GET['act']=='LogOut'){
		if($db->logOut()==1){
			echo "<p class=\"alert\">Zostałeś wylogowany</p>";
		}
	}else if(isset($_GET['act']) and $_GET['act']=='EditProfile'){
		if(isset($_SESSION['login']) && $db->logStatus($_SESSION['login'])==1){
			echo "<h3><b>Zarządzanie profilem</b></h3><br/>\n";
			echo "<form method=\"POST\" action=\".\">\nHasło:<Input type=\"password\" name=\"ch_passw\"/><br/>\nE-mail:<input type=\"text\" name=\"ch_mail\"/><br/>\n<input type=\"submit\" value=\"Zmień\"/>\n</form>";
		}
	}else if(isset($_GET['act']) and $_GET['act']=='AddPost'){
		if(isset($_SESSION['login']) && $db->logStatus($_SESSION['login'])==1){
			echo "<h3><b>Dodawanie wpisu</b></h3><br/>\n";
			echo "<form method=\"POST\" action=\".\">\nTytul\n<Input type=\"text\" name=\"title\"/><br/>\n";
			echo $db->getStatus();
			echo "Status\n<select>\n";
			foreach($db->getStatus() as $x){
				echo "<option value=\"$x\">$x</option>\n";
			}
			echo "</select>\n";
			echo "<textarea rows=\"20\" cols=\"70\" name=\"tresc\"></textarea>\n";
			echo "<br/>\n<input type=\"submit\" value=\"Dodaj\"/>\n</form>";
		}
	}
}else{
	include "http://hmpg.net/";
}
if(isset($_SESSION['login']) && $db->logStatus($_SESSION['login'])==1){
	//wpisy
	echo "</div>\n<div id=\"kol_prawa\">\nProfil\n<ul>";
	foreach($db->getUser() as $key=>$value){
		echo "<li title=\"$key\"><b>$value</b></li>\n";
	}
	echo "</ul>";
	echo "<ul>\n<li><a href=\"?act=EditProfile\">Edytuj profil</a></li>\n";
	echo "<li><a href=\"?act=ShowMyPosts\">Wyświetl moje wpisy</a></li>\n";
	echo "<li><a href=\"?act=AddPost\">Dodaj wpis</a></li>\n";
	echo "<li><a href=\"?act=LogOut\">Wyloguj się</a></li>\n</ul>\n</div>\n";
}else{
	//wpisy
	echo "</div>\n<div id=\"kol_prawa\">\n";
	echo "Panel użytkownika	<ul>\n<li><a href=\"javascript:void(0)\" onclick=\"document.getElementById('log').style.display='block';\" onDblclick=\"document.etElementById('log').style.display='none';\">Zaloguj</a></li>\n";
	echo "<form class=\"log_reg\" method=\"POST\" id=\"log\" action=\".\">\nNick:<input type=\"text\" name=\"login\"/><br/>\nHasło:<Input type=\"password\" name=\"passw\"/><br/>\n<input type=\"submit\" value=\"Zaloguj\"/>\n</form>\n";
	echo "<li><a href=\"javascript:void(0)\" onclick=\"document.getElementById('reg').style.display='block';\" onDblclick=\"document.getElementById('reg').style.display='none';\">Zarejestruj</a></li>\n";
	echo "<form class=\"log_reg\" method=\"POST\" id=\"reg\" action=\".\">Nick:<input type=\"text\" name=\"r_login\"/><br/>\nHasło:<Input type=\"password\" name=\"r_passw\"/><br/>\nE-mail:<input type=\"text\" name=\"r_mail\"/><br/>\n<input type=\"submit\" value=\"Zarejestruj\"/>\n</form></ul>\n</div>\n";
}
?>
<div id="kol_prawa">
	Zalogowani:
	<p id="mini"><?php echo $db->showLoggedIn(); ?></p>
</div>
