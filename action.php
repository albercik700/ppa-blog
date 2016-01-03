<?php
//Funkcja obslugujaca paginację
function pagination($count,$page,$all=0){
	$action_tab=array("ShowPosts","ShowMyPosts");
	if($all==0)
		$action=$action_tab[0];
	else
		$action=$action_tab[1];
	echo "<center>Strony ";
	if(($count/3-(int)($count/3))>0){
		for($i=0;$i<(int)($count/3)+1;$i++){
			if($i==$page)
				echo "<b>$i</b>\n";
			else
				echo "<a href=\"?act=$action&page=$i\">$i</a>\n";
		}
	}else{
		for($i=0;$i<$count/3;$i++){
			if($i==$page)
				echo "<b>$i</b>\n";
			else
				echo "<a href=\"?act=$action&page=$i\">$i</a>\n";
		}
	}
	echo "</center><br/>\n";
}
//Funkcja wyświetlająca wpisy pojedynczo
function poster($wpis){
	if(isset($_SESSION['login']) && $wpis->getAuthor()==$_SESSION['nazwa'])
		echo "<p class=\"tytul\"><a href=\"?post=".$wpis->getID()."\"><i>[".$wpis->getStatus()."]</i> ".$wpis->getTitle()."</a></p>\n";
	else
		echo "<p class=\"tytul\"><a href=\"?post=".$wpis->getID()."\">".$wpis->getTitle()."</a></p>\n";
	echo "<p class=\"meta\">".$wpis->getAuthor()." ".$wpis->getCreateDate();
	if($wpis->getEditDate()!="")
		echo "<br/>Data edycji: ".$wpis->getEditDate();
	if(isset($_SESSION['login']) && $wpis->getAuthor()==$_SESSION['nazwa']){
		echo "  <a href=\"?post=".$wpis->getID()."&edit\">[Edytuj]</a></p>";
	}else
		echo "</p>\n";
	echo "<p class=\"tresc\">".substr($wpis->getContent(),0,700)." <a href=\"?post=".$wpis->getID()."\">(...)</a></p>\n";
	echo "<p class=\"meta\">\n";
	foreach($wpis->getCategory() as $k=>$v){
		echo "<a class=\"meta\" href=\"\">".$v."</a>\n";
	}
	echo "</p>\n";
	echo "<hr/>\n";
}
//////////////////////////////////////////////////////////////////////////////////////////////
echo "<div id=\"kol_lewa\">\n";
if($_SERVER['REQUEST_METHOD']=='POST'){
	//Obsługa logowania
	if(isset($_POST['login']) && isset($_POST['passw'])){
		if($db->logIn($_POST['login'],$_POST['passw'])==1 && $db->logStatus($_SESSION['login'])==1){
			echo "<p class=\"alert\">Zostałeś zalogowany</p>\n";
		}else{
			echo "<p class=\"alert\">Nieprawidłowy login lub hasło</p>\n";
		}
	//Obsługa Rejestracji
	}else if(isset($_POST['r_login']) && isset($_POST['r_passw']) && isset($_POST['r_mail'])){
		if($db->register($_POST['r_login'],$_POST['r_passw'],$_POST['r_mail'])==1){
			echo "<p class=\"alert\">Zostałeś zarejestrowany</p>\n";
		}else{
			echo "<p class=\"alert\">Rejestracja nie powiodła się</p>\n";
		}
	//Aktualizacja profilu
	}else if(isset($_POST['ch_passw']) && isset($_POST['ch_mail'])){
		if($db->updateUser($_POST['ch_passw'],$_POST['ch_mail'])==1){
			echo "<p class=\"alert\">Profil został zaktualizowany</p>\n";
		}else{
			echo "<p class=\"alert\">Aktualizacja profilu nie powiodła się</p>\n";
		}
	//Dodawanie wpisów
	}else if(isset($_POST['title']) && isset($_POST['status']) && isset($_POST['tresc']) && isset($_POST['tagi'])){
		if($db->addPost($_POST['title'],$_POST['status'],$_POST['tagi'],$_POST['tresc'])==1){
			echo "<p class=\"alert\">Wpis został dodany</p>\n";
		}else{
			echo "<p class=\"alert\">Dodanie wpisu nie powiodło się</p>\n";
		}
	//Edycja wpisów
	}else if(isset($_POST['ch_id']) && isset($_POST['ch_title']) && isset($_POST['ch_status']) && isset($_POST['ch_tresc'])){
		if(isset($_POST['ch_tagi'])){
			if($db->editPost($_POST['ch_id'],$_POST['ch_title'],$_POST['ch_status'],$_POST['ch_tresc'],$_POST['ch_tagi'])==1)
				echo "<p class=\"alert\">Wpis został zmieniony</p>\n";
			else
				echo "<p class=\"alert\">Zmiana wpisu nie powiodło się</p>\n";
		}else{
			if($db->editPost($_POST['ch_id'],$_POST['ch_title'],$_POST['ch_status'],$_POST['ch_tresc'])==1)
				echo "<p class=\"alert\">Wpis został zmieniony</p>\n";
			else
				echo "<p class=\"alert\">Zmiana wpisu nie powiodło się</p>\n";
		}
		
	}
	$count=current($db->showPosts(0,3));
	foreach($db->showPosts(0,3) as $key=>$wpis){
		if($key<1)
			continue;
		poster($wpis);
	}
	pagination($count,0);
}if($_SERVER['REQUEST_METHOD']=='GET'){
	$db->checkSession();
	//Wylogowanie
	if(isset($_GET['act']) and $_GET['act']=='LogOut'){
		if($db->logOut()==1){
			echo "<p class=\"alert\">Zostałeś wylogowany</p>";
		}
		$count=current($db->showPosts(0,3));
		foreach($db->showPosts(0,3) as $key=>$wpis){
			if($key<1)
				continue;
			poster($wpis);
		}
		pagination($count,0);
	//Formularz edycji profilu
	}else if(isset($_GET['act']) and $_GET['act']=='EditProfile'){
		if(isset($_SESSION['login']) && $db->logStatus($_SESSION['login'])==1){
			echo "<h3><b>Zarządzanie profilem</b></h3><br/>\n";
			echo "<form method=\"POST\" action=\".\">\nHasło:<Input type=\"password\" name=\"ch_passw\"/><br/>\nE-mail:<input type=\"text\" name=\"ch_mail\"/><br/>\n<input type=\"submit\" value=\"Zmień\"/>\n</form>";
		}else{
			header("Location:index.php");
		}
	//Formularz dodawania wpisów
	}else if(isset($_GET['act']) and $_GET['act']=='AddPost'){
		if(isset($_SESSION['login']) && $db->logStatus($_SESSION['login'])==1){
			echo "<h3><b>Dodawanie wpisu</b></h3><br/>\n";
			echo "<form method=\"POST\" action=\".\">\nTytul\n<Input type=\"text\" name=\"title\"/><br/>\n";
			echo "Status\n<select name=\"status\">\n";
			foreach($db->getStatus() as $key=>$val){
				echo "<option value=\"$key\">$val</option>\n";
			}
			echo "</select><br/>\n";
			echo "Tagi<br/>\n";
			foreach($db->getTags() as $key=>$val){
				echo "<input type=\"checkbox\" name=\"tagi[]\" value=\"$key\">$val</input>\n";
			}
			echo "<textarea rows=\"20\" cols=\"70\" name=\"tresc\"></textarea>\n";
			echo "<br/>\n<input type=\"submit\" value=\"Dodaj\"/>\n</form>";
		}else{
			header("Location:index.php");
		}
	//Wyswietlenie wszystkich postow uzytkownika
	}else if(isset($_GET['act']) and $_GET['act']=='ShowMyPosts' and !isset($_GET['page'])){
		if(isset($_SESSION['login']) && $db->logStatus($_SESSION['login'])==1){
			$count=current($db->showPosts($_SESSION['id'],3));
			foreach($db->showPosts($_SESSION['id'],3) as $key=>$wpis){
				if($key<1)
					continue;
				poster($wpis);
			}
			pagination($count,$_GET['page'],1);
		}else{
			header("Location:index.php");
		}
	//Wyswietlenie wszystkich postow uzytkownika + stronicowanie
	}else if(isset($_GET['act']) and $_GET['act']=='ShowMyPosts' and isset($_GET['page']) and ctype_digit($_GET['page'])){
		if(isset($_SESSION['login']) && $db->logStatus($_SESSION['login'])==1){
			$count=current($db->showPosts($_SESSION['id'],3));
			foreach($db->showPosts($_SESSION['id'],3,$_GET['page']*3) as $key=>$wpis){
				if($key<1)
					continue;
				poster($wpis);
			}
			pagination($count,$_GET['page'],1);
		}else{
			header("Location:index.php");
		}
	//Wyswietlenie wszystkich postow + stronicowanie
	}else if(isset($_GET['act']) and $_GET['act']=='ShowPosts' and isset($_GET['page']) and ctype_digit($_GET['page'])){
		$count=current($db->showPosts(0,3));
		foreach($db->showPosts(0,3,$_GET['page']*3) as $key=>$wpis){
			if($key<1)
				continue;
			poster($wpis);
		}
		pagination($count,$_GET['page']);
	//Wyswietlenie wpisu
	}else if(isset($_GET['post']) and ctype_digit($_GET['post']) and !isset($_GET['edit'])){
		foreach($db->showPost($_GET['post']) as $key=>$wpis){
			echo "<p class=\"tytul\"><a href=\"?post=".$wpis->getID()."\">".$wpis->getTitle()."</a></p>\n";
			echo "<p class=\"meta\">".$wpis->getAuthor()." ".$wpis->getCreateDate(); 
			echo "<br/>\nStatus: ".$wpis->getStatus();
			if(isset($_SESSION['login']) && $db->logStatus($_SESSION['login'])==1 && $wpis->getAuthor()==$_SESSION['nazwa'])
				echo "  <a href=\"?post=".$wpis->getID()."&edit\">[Edytuj]</a></p>\n";
			else
				echo "</p>\n";
			echo "<p class=\"tresc\">".$wpis->getContent()."</p>\n";
			echo "<p class=\"meta\">\n";
			foreach($wpis->getCategory() as $k=>$v){
				echo "<a class=\"meta\" href=\"\">".$v."</a>\n";
			}
			echo "</p>\n";
			echo "<hr/>\n";
		}
	//Formularz edycji wpisu
	}else if(isset($_GET['post']) and ctype_digit($_GET['post']) and isset($_GET['edit']) and isset($_SESSION['login']) and $db->logStatus($_SESSION['login'])==1){
		foreach($db->showPost($_GET['post']) as $key=>$wpis){
			if($wpis->getAuthor()==$_SESSION['nazwa']){
				echo "<h3><b>Edycja wpisu</b></h3><br/>\n";
				echo "<form method=\"POST\" action=\".\">\n";
				echo "<input type=\"hidden\" name=\"ch_id\" value=\"".$wpis->getID()."\"/>";
				echo "Tytul\n<Input type=\"text\" id=\"title\"name=\"ch_title\" value=\"".$wpis->getTitle()."\"/><br/>\n";
				echo "Status\n<select name=\"ch_status\">\n";
				foreach($db->getStatus() as $key=>$val){
					echo "<option value=\"$key\">$val</option>\n";
				}
				echo "</select><br/>\n";
				echo "Tagi<br/>\n";
				foreach($db->getTags() as $key=>$val){
					if(in_array($val,$wpis->getCategory(),true))
						echo "<input type=\"checkbox\" name=\"ch_tagi[]\" value=\"$key\" checked>$val</input>\n";
					else
						echo "<input type=\"checkbox\" name=\"ch_tagi[]\" value=\"$key\">$val</input>\n";
				}
				echo "<textarea rows=\"20\" cols=\"70\" name=\"ch_tresc\">".$wpis->getContent()."</textarea>\n";
				echo "<br/>\n<input type=\"submit\" value=\"Zmień\"/>\n</form>";
			}else{
				echo "there you are";
				header("Location:index.php?post=".$_GET['post']);
			}
		}
	}else{
		$count=current($db->showPosts(0,3));
		foreach($db->showPosts(0,3) as $key=>$wpis){
			if($key<1)
				continue;
			poster($wpis);
		}
		pagination($count,0);
	}
	
}else{
	echo "";
}
//Widok prawej kolumny zalogowany/niezalogowany
if(isset($_SESSION['login']) && $db->logStatus($_SESSION['login'])==1){
	echo "</div>\n<div id=\"kol_prawa\">\nProfil\n<ul>";
	foreach($db->getUser() as $key=>$value){
		echo "<li title=\"$key\"><b>$value</b></li>\n";
	}
	echo "</ul>";
	echo "<ul>\n<li><a href=\"?act=EditProfile\">Edytuj profil</a></li>\n";
	echo "<li><a href=\"?act=ShowMyPosts&page=0\">Wyświetl moje wpisy</a></li>\n";
	echo "<li><a href=\"?act=AddPost\">Dodaj wpis</a></li>\n";
	echo "<li><a href=\"?act=LogOut\">Wyloguj się</a></li>\n</ul>\n</div>\n";
}else{
	echo "</div>\n<div id=\"kol_prawa\">\n";
	echo "Panel użytkownika	<ul>\n<li><a href=\"javascript:void(0)\" onclick=\"document.getElementById('log').style.display='block';\" onDblclick=\"document.getElementById('log').style.display='none';\">Zaloguj</a></li>\n";
	echo "<form class=\"log_reg\" method=\"POST\" id=\"log\" action=\".\">\nNick:<input type=\"text\" name=\"login\"/><br/>\nHasło:<Input type=\"password\" name=\"passw\"/><br/>\n<input type=\"submit\" value=\"Zaloguj\"/>\n</form>\n";
	echo "<li><a href=\"javascript:void(0)\" onclick=\"document.getElementById('reg').style.display='block';\" onDblclick=\"document.getElementById('reg').style.display='none';\">Zarejestruj</a></li>\n";
	echo "<form class=\"log_reg\" method=\"POST\" id=\"reg\" action=\".\">Nick:<input type=\"text\" name=\"r_login\"/><br/>\nHasło:<Input type=\"password\" name=\"r_passw\"/><br/>\nE-mail:<input type=\"text\" name=\"r_mail\"/><br/>\n<input type=\"submit\" value=\"Zarejestruj\"/>\n</form></ul>\n</div>\n";
}
?>
<div id="kol_prawa">
	Zalogowani:
	<p id="mini"><?php echo $db->showLoggedIn(); ?></p>
</div>
