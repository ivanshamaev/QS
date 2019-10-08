<?php
	if (isset($_GET['username']) and isset($_GET['password']) and isset($_GET['limit']) and isset($_GET['offset']) and isset($_GET['mode'])) {
		$username = htmlspecialchars($_GET["username"]);
		$password = htmlspecialchars($_GET["password"]);	
		$limit = htmlspecialchars($_GET["limit"]);
		$offset = htmlspecialchars($_GET["offset"]);
		$mode = htmlspecialchars($_GET["mode"]);
	}
	else {
		err();
	}
?>