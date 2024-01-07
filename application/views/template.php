<!DOCTYPE HTML> 
<html> 
<head> 
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"> 
	<title><?php echo Kohana::$config->load('main')->get('orgname');?> </title>
	<base href="http://localhost/citycrm/" />
	<link rel="stylesheet" type="text/css" media="all" href="css/screen.css">
	<link rel="stylesheet" type="text/css" media="all" href="css/datepicker.css">
	<link rel="stylesheet" type="text/css" media="all" href="css/tipsy.css">
	<link rel="stylesheet" type="text/css" media="all" href="js/fancybox/jquery.fancybox-1.3.0.css">
	<link rel="stylesheet" type="text/css" media="all" href="js/visualize/visualize.css">
	
	 <?php // echo HTML::style('static/css/bootstrap.css'); ?>
	<?php //echo HTML::style('static/css/modal.css'); ?>
 
	
	<link rel="stylesheet" href="static/css/themes/blue/style.css" type="text/css" media="print, projection, screen" />
	
	
	<!--[if IE]>
	<link rel="stylesheet" type="text/css" media="all" href="css/ie.css" >
	<script type="text/javascript" src="js/excanvas.js"></script>
	<![endif]-->
	<script type="text/javascript" src="js/jquery.js"></script>
	<script type="text/javascript" src="js/jquery-ui.js"></script>
	<script type="text/javascript" src="js/jquery.img.preload.js"></script>
	<script type="text/javascript" src="js/hint.js"></script>
	<script type="text/javascript" src="js/visualize/jquery.visualize.js"></script>
	<script type="text/javascript" src="js/fancybox/jquery.fancybox-1.3.0.js"></script>
	<script type="text/javascript" src="js/jquery.tipsy.js"></script>
	<script type="text/javascript" src="js/custom_blue.js"></script>
	
	
  
  
</head>
<body>
<?php include Kohana::find_file('views', 'header'); ?>
	<div class="content_wrapper">
		
		<br>
		<br>
		<?php include Kohana::find_file('views', 'sidebar'); ?>
		<div id="content">
			<div class="inner">
				<?php echo $content; ?>
				
			</div>
		</div>
		
	</div>

</body>

</html>
