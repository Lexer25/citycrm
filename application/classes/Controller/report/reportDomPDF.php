<?php 
	$html = '

		<!DOCTYPE html>
	
		<html>
	
			<head>
		
				<meta charset="utf-8">
				<title>Test Page</title>
			
			</head>
		
			<body>
			
				<p>Привет, <span style="color: green">Мир</span>!</p>
			
			</body>
		
		</html>
	
';


echo Debug::vars('3',Kohana::find_file('vendor\\dompdf\\src', 'Dompdf', 'php') ); //exit;
//echo Debug::vars('3',Kohana::find_file('vendor\\dompdf\\src', 'dompdf_config.inc', 'php') ); //exit;

//include_once Kohana::find_file('vendor', 'dompdf_config.inc', 'php');
include_once Kohana::find_file('vendor\\dompdf\\src', 'Dompdf', 'php'); //exit;
//require_once("dompdf/dompdf_config.inc.php");
//include_once 'C:\xampp\htdocs\citycrm\application\vendor\dompdf\src\Dompdf.php';

$dompdf = new Dompdf();
echo Debug::vars('34', $html); exit;
	$dompdf->load_html('hello world');
	$dompdf->render();
	$dompdf->stream("new_file.pdf");