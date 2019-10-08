<?php

	if (isset($_GET['username']) and isset($_GET['password'])) {
		$username = htmlspecialchars($_GET["username"]);
		$password = htmlspecialchars($_GET["password"]);	
	}
	else {
		err();
	}

	//======================================================================
	// Блок 1: Получаем JSON данные от сервера/сервиса/скрипта php и т.д.
	//======================================================================

	$host = 'https://online.moysklad.ru/api/remap/1.1/entity/project';
	$headers = array(
	    'Content-Type:application/json',
	    'Authorization: Basic '. base64_encode($username . ":" . $password) // <---
	);

	//Результирующий массив
	$stat_result = array();
    //Счетчик для нового массива
    $resultCount = 0;

    do {
		$return = array();
		$result = array();
		$process = array();
		$process = curl_init($host);
		curl_setopt($process, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($process, CURLOPT_HEADER, 0);
		curl_setopt($process, CURLOPT_USERPWD, $username . ":" . $password);
		curl_setopt($process, CURLOPT_TIMEOUT, 30);
		curl_setopt($process, CURLOPT_RETURNTRANSFER, TRUE);
		$return = curl_exec($process);
		curl_close($process);

		$result = json_decode($return, TRUE);

		foreach($result[rows] as $key => $value){

			$stat_result[$resultCount]['href'] = $value[meta][href]; 
			$stat_result[$resultCount]['type'] = $value[meta][type];
			$stat_result[$resultCount]['id'] = $value[id];
			$stat_result[$resultCount]['accountId'] = $value[accountId];
			$stat_result[$resultCount]['owner'] = $value[owner][meta][href];
			$stat_result[$resultCount]['updated'] = $value[updated];
			$stat_result[$resultCount]['name'] = $value[name];
			$stat_result[$resultCount]['description'] = $value[description];
			$stat_result[$resultCount]['code'] = $value[code];
			$stat_result[$resultCount]['externalCode'] = $value[externalCode];
			
		
			$resultCount = $resultCount + 1;
		}

		$host = $result[meta][nextHref];

	} while (isset($host));


	//======================================================================
	// БЛОК 2: Печатаем массив в CSV файл, который затем будет скачиваться
	//======================================================================

	// параметр вывода, сделаем так, чтобы файл загружался, а не отображался
	header('Content-Type: text/csv; charset=utf-8');
	header('Content-Disposition: attachment; filename=Проекты.csv');
 
	// создаем указатель файла, подключенный к выходному потоку
	$output = fopen('php://output', 'w');
 
	// Первая строк необходима, чтобы Excel понял, что формат CSV на UTF-8 кодировке
	fwrite($output,b"\xEF\xBB\xBF" ) ;
	
	// Название колонок (заголовки)
	fputcsv($output, array_keys($stat_result[0]), ";");
        
	// Перебираем строки и печатаем в файл csv
	foreach($stat_result as $array2){
        	fputcsv($output, $array2, ";");
	}

?> 