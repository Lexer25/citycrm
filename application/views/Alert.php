<?php
//этот файла должен выводить список сообщений (alert), которые передаются как массив.

if (isset($arrAlert)) { 
//echo Debug::vars('5', $arrAlert);
	//$arrayType=array('alert_warning', 'alert_info', 'alert_success', 'alert_error');
	include Kohana::find_file('views', 'alertState');
	//$arrayImage=array('images/icon_warning.png', 'images/icon_info.png', 'images/icon_accept.png', 'images/alert_error');
	foreach($arrAlert as $key=>$value){
		
		echo '<div class="'.Arr::get($arrayType, Arr::get($value, 'actionResult')).'"><p>';
		echo HTML::image(Arr::get($arrayImage, Arr::get($value, 'actionResult')), array('class'=>'mid_align',  'alt'=>Arr::get($arrayAlt, Arr::get($value, 'actionResult'))));
		echo Arr::get($value, 'actionDesc');
		echo '</p></div>';
		
	//	echo Debug::vars('6', $value);
		
	}
} else {
	
	//echo __('no_arrAlert');
}

?>