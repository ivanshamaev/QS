<?php
	$host = 'https://online.moysklad.ru/api/remap/1.1/entity/'.$methodName.'?limit='.$limit.'&offset='.$offset;
	$headers = array(
	    'Content-Type:application/json',
	    'Authorization: Basic '. base64_encode($username . ":" . $password) // <---
	);
?>