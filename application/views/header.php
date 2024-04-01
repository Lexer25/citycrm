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
		<?php echo HTML::anchor('settings/list', __('settings')) . ' | ' . HTML::anchor('logout', __('logout')); ?>
	</div>
	<div id="search">
		<?php echo  __('system.version'). Arr::get(Kohana::$config->load('config_newcrm')->version, 'minor').'.'.Arr::get(Kohana::$config->load('config_newcrm')->version, 'major'); ?>
	</div>

	<div id="account_info">
		<img src="images/icon_online.png" alt="Online" class="mid_align"/>
		<?php echo __('welcome') . ', <strong><i>' . iconv('CP1251', 'UTF-8', Session::instance()->get('username') .Session::instance()->get('role')). '</i></strong>'; ?> 
	</div>
	<div>
	<?php
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
				
				?>
				
			
				
	</div>
	<div>
	<?php
	 echo __('Lic', array(
      		'Lic'=> Kohana::$config->load('lic')->sn
      		)).'<br>';
				
				?>
				
	</div>
	
</div>
