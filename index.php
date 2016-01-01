<?php
	require_once "db.php";
	session_start();
	$db = @new BlogManager("localhost","root","","blog");
?>
<html>
<head>
	<title></title>
	<link href="styl/styl.css" rel="stylesheet" type="text/css"/>
</head>
<body>
	<div id="kontener">
		<div id="naglowek">
			<a id="logo" href=".">Kamil Falkiewicz's blog</a>
			<hr/>
		</div>
		<div id="srodek">
			<div id="kol_lewa">
				<?php
					require_once "action.php";
				?>
			</div>
		</div>
	</div>
</body>
</html>