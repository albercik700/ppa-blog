<?php
	require_once "db.php";
	session_start();
	$db = @new BlogManager("localhost","root","","blog");
	require_once "action.php"
?>
<html>
<head>
	<title></title>
	<link href="styl/styl.css" rel="stylesheet" type="text/css"/>
</head>
<body>
	<div id="kontener">
		<div id="naglowek">
			Kamil Falkiewicz's blog
			<hr/>
		</div>
		<div id="srodek">
			<div id="kol_lewa">
				<p class="tytul">Tytul wpis nr 1</p>
				<p class="meta">administrator 2015-12-07 13:37</p>
				<p class="tresc">Nunc ut est convallis, scelerisque tortor sed, facilisis lacus. Integer at augue posuere, egestas tellus sit amet, ornare nisl. Quisque nec massa non quam sodales vestibulum vitae eu mauris. Nunc aliquet leo elit, vel consequat magna consequat et. Curabitur luctus, ex id sollicitudin malesuada, erat risus ornare tortor, at cursus magna mi eget magna. Nunc accumsan ipsum nec augue viverra lacinia. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Sed pulvinar sagittis bibendum. Vivamus fringilla sapien enim, quis feugiat eros rutrum eu. Praesent ultrices libero in odio pulvinar, ut egestas urna convallis. Etiam lectus ligula, vehicula a quam nec, tincidunt aliquet lacus. Proin ac sem id enim pulvinar fringilla ut in enim. In facilisis tincidunt neque eget pellentesque. Integer posuere vitae odio sit amet aliquet. In hac habitasse platea dictumst. Vivamus et sem consequat magna vehicula venenatis.</p>
				<p class="wiecej">Czytaj dalej...</p>
				<hr/>
				<p class="tytul">Tytul wpis nr 2</p>
				<p class="meta">2015-12-07 13:37 administrator</p>
				<p class="tresc">Nunc ut est convallis, scelerisque tortor sed, facilisis lacus. Integer at augue posuere, egestas tellus sit amet, ornare nisl. Quisque nec massa non quam sodales vestibulum vitae eu mauris. Nunc aliquet leo elit, vel consequat magna consequat et. Curabitur luctus, ex id sollicitudin malesuada, erat risus ornare tortor, at cursus magna mi eget magna. Nunc accumsan ipsum nec augue viverra lacinia. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Sed pulvinar sagittis bibendum. Vivamus fringilla sapien enim, quis feugiat eros rutrum eu. Praesent ultrices libero in odio pulvinar, ut egestas urna convallis. Etiam lectus ligula, vehicula a quam nec, tincidunt aliquet lacus. Proin ac sem id enim pulvinar fringilla ut in enim. In facilisis tincidunt neque eget pellentesque. Integer posuere vitae odio sit amet aliquet. In hac habitasse platea dictumst. Vivamus et sem consequat magna vehicula venenatis.</p>
				<p class="wiecej">Czytaj dalej...</p>
				<hr/>
				<p class="tytul">Tytul wpis nr 3</p>
				<p class="meta">2015-12-07 13:37 administrator</p>
				<p class="tresc">Nunc ut est convallis, scelerisque tortor sed, facilisis lacus. Integer at augue posuere, egestas tellus sit amet, ornare nisl. Quisque nec massa non quam sodales vestibulum vitae eu mauris. Nunc aliquet leo elit, vel consequat magna consequat et. Curabitur luctus, ex id sollicitudin malesuada, erat risus ornare tortor, at cursus magna mi eget magna. Nunc accumsan ipsum nec augue viverra lacinia. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Sed pulvinar sagittis bibendum. Vivamus fringilla sapien enim, quis feugiat eros rutrum eu. Praesent ultrices libero in odio pulvinar, ut egestas urna convallis. Etiam lectus ligula, vehicula a quam nec, tincidunt aliquet lacus. Proin ac sem id enim pulvinar fringilla ut in enim. In facilisis tincidunt neque eget pellentesque. Integer posuere vitae odio sit amet aliquet. In hac habitasse platea dictumst. Vivamus et sem consequat magna vehicula venenatis.</p>
				<p class="wiecej">Czytaj dalej...</p>
				<hr/>
			</div>
			<div id="kol_prawa">
			<?php
				if(!isset($_SESSION['login'])){
			?>
				<p><a href="?act=login">Zaloguj</a></p>
				<p><a href="?act=reg">Zarejestruj</a></p>
			<?php
				}else{
					echo "Jestes zalogowany<br/>";
					echo "<a href='.?act=logout'>Wyloguj</a>";
				}
			?>
			</div>
		</div>
	</div>
</body>
</html>