<?php 
	$html = '<!DOCTYPE html>
	
		<html>
	
			<head>
		
				<meta charset="utf-8">
				<title>Test Page</title>
			
			</head>
		
			<body>
			
				<p>hi <span style="color: green">АБВГД</span>!</p>
				<p>hi <span style="color: green">ABCDE</span>!</p>
			
			</body>
		
		</html>
	
';

require Kohana::find_file('vendor', 'dompdf/src/Autoloader','php');

Dompdf\Autoloader::register();
use Dompdf\Dompdf;


$dompdf = new Dompdf();
//echo Debug::vars('34', $html); exit;
//	$dompdf->load_html('hello world<br>');
	$dompdf->setPaper('A4', 'portrait');
	//$dompdf->load_html($html);
	$dompdf->loadHtml($html);
	$dompdf->render();
	$dompdf->stream("new_file.pdf");