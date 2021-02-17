<?php 
	header('Access-Control-Allow-Origin: *'); 
	
	//Decoding JSON
	$json = json_decode(file_get_contents('matricsheet.json', true));
	echo json_encode($json);die;
?>