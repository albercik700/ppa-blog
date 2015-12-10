<?php
require_once "db.php";
session_start();
$db = new BlogManager("localhost","root","","blog");
include "action.php";
?>
