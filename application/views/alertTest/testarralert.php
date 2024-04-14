<?php
/*
11.04.2024 тестовая программа для проверки работы механизма arrAlert.
для вызова теста надо вставить в программу конструкцию
include Kohana::find_file('views','testarralert');
*/
if(Kohana::$config->load('test')->get('testArrAlert')){
	$alert='Test alert. Time is '. time();
	$arrAlert[]=array('actionResult'=>2, 'actionDesc'=>$alert);
	$this->session->delete('arrAlert');
}
?>