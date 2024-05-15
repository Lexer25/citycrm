<?php
//	echo Debug::vars('32', $_SESSION, $_COOKIE);
//	echo Debug::vars('3', Auth::instance()->logged_in('admin'));
//	echo Debug::vars('3', Auth::instance()->logged_in('aa'));
//echo Kohana::version();
//echo phpinfo(INFO_GENERAL);
?>
<div id="header">
	<div id="logo"><img src="images/logo2.png" alt="logo"/></div>
	

	<div id="search">
		<?php 
		//echo Debug::vars('14', Kohana::$config->load('config_newcrm')->view_settings );
		if(Kohana::$config->load('config_newcrm')->view_settings) echo HTML::anchor('settings/list', __('settings')) . ' | ';
			echo HTML::anchor('logout', __('logout')); ?>
	</div>
	<div id="search">
		<?php echo  __('system.version'). Arr::get(Kohana::$config->load('config_newcrm')->version, 'minor').'.'.Arr::get(Kohana::$config->load('config_newcrm')->version, 'major'); ?>
	</div>

	<div id="account_info">
		<img src="images/icon_online.png" alt="Online" class="mid_align"/>
		<?php 
			$huser=Session::instance()->get('auth_user_crm');
			$userAdmin=new Contact(Arr::get($huser, 'ID_PEP'));
			//echo Debug::vars($huser, Arr::get($huser, 'ID_PEP'), iconv('CP1251', 'UTF-8', $userAdmin->surname));
			echo __('welcome') . ', <strong><i>' . iconv('CP1251', 'UTF-8', 
				$userAdmin->name. ' '
				.$userAdmin->surname. ' '
				.$userAdmin->patronymic)
				. '</i></strong>'; ?> 
	</div>
	<div>
	<?php
	if(isset(Kohana::$config->load('database')->fb)){
	 echo __('string_about :db, PHP :phpver, :fver', array(
      		'db'=> Arr::get(
      			Arr::get(
      					Kohana::$config->load('database')->fb,
      					'connection' 
      					),
      		'dsn'),
      		'ver'=> Kohana::$config->load('artonitcity_config')->ver,
      		'developer'=> Kohana::$config->load('artonitcity_config')->developer,
			'phpver'=>phpversion(),
			'fver'=>Kohana::version(),
			)).'<br>';
		} else {
		echo __('No_db_config');
		}
				
				?>
				
			
				
	</div>
	<div>
	<?php
	 echo __('Lic', array(
      		'Lic'=> Kohana::$config->load('lic')->sn
      		)).' ';
	$format_rfid='unset';
	if(Kohana::$config->load('system')->get('baseFormatRfid')==0) $format_rfid='0 HEX';
	if(Kohana::$config->load('system')->get('baseFormatRfid')==1) $format_rfid='1 001A';
	echo __('baseFormatRfid :data', array(':data'=>$format_rfid));	
	echo ' ';
	$regformat_rfid='unset';
	if(Kohana::$config->load('system')->get('screenFormatRFID')==0) $regformat_rfid='0 HEX';
	if(Kohana::$config->load('system')->get('screenFormatRFID')==1) $regformat_rfid='1 001A';
	if(Kohana::$config->load('system')->get('screenFormatRFID')==2) $regformat_rfid='2 longDEC';
	echo __('screenFormatRFID :data', array(':data'=>$regformat_rfid));	
	echo __(' identifier :data', array(':data'=>Session::instance()->get('identifier', 1)));	

	
	?>
				
	</div>
	
</div>
