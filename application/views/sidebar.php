<?php
$configModule=Kohana::$config->load('config_newcrm')->module;
//echo Debug::vars('3', Arr::get($configModule, 'org'));

?>
<a href="javascript:;" id="show_menu">&raquo;</a>
<div id="left_menu">
	<a href="javascript:;" id="hide_menu">&laquo;</a>
	<ul id="main_menu">
		<li>
			<?php echo HTML::anchor('/', HTML::image('images/icon_home.png') . __('home')); ?>
		</li>

		<?php if (Auth::instance()->logged_in('admin') || Auth::instance()->logged_in('owner') ) { 
		
			if(Arr::get($configModule, 'org'))
			{ ?>
				<li>
					<a id="sidebar_companies" href="javascript:"><img src="images/icon_companies.png" /><?php echo __('companies'); ?></a>
					<ul>
						<li><? echo HTML::anchor ('companies', __('sidebar.companieslist')); ?></li>
						<?php if (Auth::instance()->logged_in('admin')) { ?>
						<li><? echo HTML::anchor ('companies/edit/0', __('sidebar.addcompany')); ?></li>
						<?php }?>
					</ul>
				</li>
			<?php };
			
			if(Arr::get($configModule, 'contact'))
				{ ?>

				<li>
					<a id="sidebar_contacts" href="javascript:"><img src="images/icon_contacts.png" /><?php echo __('contacts'); ?></a>
					<ul>
						<li><? echo HTML::anchor ('contacts/activeOnlyList', __('sidebar.contactslist')); ?></li>
						<?php if (Auth::instance()->logged_in('admin')) { ?>
						<li><? echo HTML::anchor ('contacts/edit/0', __('sidebar.addcontact')); ?></li>
						<li><? echo HTML::anchor ('contacts/deletedList', __('sidebar.deletedcontact')); ?></li>
						<?php } ?>
					</ul>
				</li>
			<?php }
			;
			
			if(Arr::get($configModule, 'card'))
			{ ?>

            <li>
                <a id="sidebar_cards" href="javascript:"><img src="images/icon_card.png" /><?php echo __('cards'); ?></a>
                <ul>
                    <li><? echo HTML::anchor ('cards', __('sidebar.cardslist')); ?></li>
                </ul>
            </li>

			<?php }
				
			;
			
			if(Arr::get($configModule, 'guest'))
			{ ?>

            <li>
                <a id="sidebar_guests" href="javascript:"><img src="images/icon_guest.png" /><?php echo __('guests'); ?></a>
                <ul>
                   
						<li><? echo HTML::anchor ('guests/guest', __('sidebar.guestslist')); ?></li>
						<?php if (Auth::instance()->logged_in('admin')) { ?>
						<li><? echo HTML::anchor ('guests/archive', __('sidebar.archive')); ?></li>
						<li><? //echo HTML::anchor ('guests/edit/0', __('sidebar.addguest')); ?></li>
						<li><? echo HTML::anchor ('guests/issue', __('sidebar.addguest')); ?></li>
						<li><? echo HTML::anchor ('guests/config', __('sidebar.config')); ?></li>
						<?php } ?>
					
                </ul>
            </li>

			<?php }
		}?>
			
			<?php 
			if(Arr::get($configModule, 'event'))
			{ ?>

				<li>
					<a id="sidebar_eventlog" href="javascript:"><img src="images/icon_note_view.png" /><?php echo __('eventlog'); ?></a>
					<ul>
						<li><? echo HTML::anchor ('eventlog/alarm', __('eventlog.alarmlog')); ?></li>
						<li><? echo HTML::anchor ('eventlog/index', __('eventlog.full')); ?></li>
					</ul>
					 </ul>
				 </li>
			<?php 
			};
			if(Arr::get($configModule, 'event'))
			{ ?>	
				<li>
				<a id="sidebar_queue" href="javascript:"><img src="images/icon_data_out.png" /><?php echo __('queue.full'); ?></a>
				<ul>
						<li><? echo HTML::anchor ('queue/index', __('queue.full')); ?></li>
						<li><? echo HTML::anchor ('queue/listqueue', __('queue.list')); ?></li>

				</ul>
				</li>
			<?php };?>
		

		<?php if (Auth::instance()->logged_in('admin')) { 
			
			if(Arr::get($configModule, 'user'))
			{ ?>	
				<li>
					<a id="sidebar_users" href="javascript:"><img src="images/icon_users.png" /><?php echo __('users'); ?></a>
					<ul>
						<li><? echo HTML::anchor ('users', __('sidebar.userlist')); ?></li>
						<li><? echo HTML::anchor ('users/edit/0', __('sidebar.adduser')); ?></li>
					</ul>
				</li>
			<?php };?>
				
			
			<?php	if(Arr::get($configModule, 'devices'))
			{ ?>	
				<li>
					<a id="sidebar_users" href="javascript:"><img src="images/icon_users.png" /><?php echo __('device'); ?></a>
					<ul>
						<li><? echo HTML::anchor ('devices', __('device.devicelist')); ?></li>
						<li><? echo HTML::anchor ('devices/edit/0', __('device.adddevice')); ?></li>
					</ul>
				</li>
			<?php };?>
		<?php } ;
		if(Arr::get($configModule, 'user'))
		{ ?>	
			<li>
				<a id="sidebar_stats" href="javascript:"><img src="images/icon_stat.png" /><?php echo __('stat'); ?></a>
				<ul>
					<li><? echo HTML::anchor ('stats/about', __('stat.form1')); ?></li>
					<li><? echo HTML::anchor ('stats/queue_message', __('stat.title.que_but')); ?></li>
					<li><? echo HTML::anchor ('stats/device', __('stat.form2')); ?></li>
					<li><? echo HTML::anchor ('stats/events', __('stat.form3')); ?></li>
				</ul>
			</li>
		<?php };?>
	</ul>
	
	<br class="clear"/>
	<div id="calendar"></div>


</div>
