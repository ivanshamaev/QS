<?php
	// параметр вывода, сделаем так, чтобы файл загружался, а не отображался
	header('Content-Type:text/csv;charset=utf-8');
	header('Content-Disposition:attachment;filename='.$printNameCSV.'.csv');
 
	// создаем указатель файла, подключенный к выходному потоку
	$output = fopen('php://output', 'w');
 
	// Первая строк необходима, чтобы Excel понял, что формат CSV на UTF-8 кодировке
	//fwrite($output,b"\xEF\xBB\xBF" ) ;
	
	// Название колонок (заголовки)
	fputcsv($output, array_keys($stat_result[0]), ";");
        
	// Перебираем строки и печатаем в файл csv
	foreach($stat_result as $array2){
        	fputcsv($output, $array2, ";");
	}
?>