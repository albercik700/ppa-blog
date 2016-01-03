<?php
	require_once "db.php";
	session_start();
	session_regenerate_id(true);
	$db = @new BlogManager("localhost","root","","blog");
?>
<html>
<head>
	<title></title>
	<link href="styl/styl.css" rel="stylesheet" type="text/css"/>
	<script src="http://code.jquery.com/jquery-1.11.3.min.js"></script>
	<script>
		$().ready(function(){
			$('p.alert').fadeTo(2500,0);
		})
	</script>
</head>
<body>
	<div id="kontener">
		<div id="naglowek">
			<a id="logo" href=".">Kamil Falkiewicz's blog</a>
			<hr/>
		</div>
		<div id="srodek">
			<?php
				require_once "action.php";
			?>
		</div>
	</div>
</body>
</html>