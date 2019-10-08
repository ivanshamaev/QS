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

	//======================================================================
	// Блок 1: Получаем JSON данные от сервера/сервиса/скрипта php и т.д.
	//======================================================================

	$methodName = "loss";

	$host = 'https://online.moysklad.ru/api/remap/1.1/entity/'.$methodName.'?limit='.$limit.'&offset='.$offset;
	$headers = array(
	    'Content-Type:application/json',
	    'Authorization: Basic '. base64_encode($username . ":" . $password) // <---
	);

	if( $mode == "data" ) {
		//Результирующий массив
		$stat_result = array();
		//Счетчик для нового массива
		$resultCount = 0;


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

			$stat_result[$resultCount]['id'] = $value[id]; 
			$stat_result[$resultCount]['accountId'] = $value[accountId];
			$stat_result[$resultCount]['updated'] = $value[updated];
			$stat_result[$resultCount]['moment'] = $value[moment];
			$stat_result[$resultCount]['name'] = $value[name];
			$stat_result[$resultCount]['description'] = $value[description];
			$stat_result[$resultCount]['externalCode'] = $value[externalCode];
			$stat_result[$resultCount]['group_href'] = $value[group][meta][href];
			$stat_result[$resultCount]['owner_href'] = $value[owner][meta][href];
			$stat_result[$resultCount]['store_href'] = $value[store][meta][href];
			$stat_result[$resultCount]['organization_href'] = $value[organization][meta][href];
			$stat_result[$resultCount]['positions_href'] = $value[positions][meta][href];

			$resultCount = $resultCount + 1;
		}

	} elseif ($mode == "size") {

		//Результирующий массив
		$stat_result = array();
		//Счетчик для нового массива
		$resultCount = 0;

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

		$stat_result[$resultCount]['size'] = $result[meta][size]; 
	}


	//======================================================================
	// БЛОК 2: Печатаем массив в CSV файл, который затем будет скачиваться
	//======================================================================

	$printNameCSV = "Списания";
	
	// параметр вывода, сделаем так, чтобы файл загружался, а не отображался
	header('Content-Type:text/csv;charset=utf-8');
	header('Content-Disposition:attachment;filename='.$printNameCSV.'.csv');
 
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