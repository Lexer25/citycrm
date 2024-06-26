<script type="text/javascript">


 
  	$(function() {		
  		$("#tablesorter").tablesorter({ headers: { 7:{sorter: false}},  widgets: ['zebra']});
		
  	});	
	
</script>
<?php
//echo Debug::vars('2', Session::instance()); 
//echo Debug::vars('2', Session::instance()); 
?>
<?php if ($alert) { ?>
<div class="alert_success">
	<p>
		<img class="mid_align" alt="success" src="images/icon_accept.png" />
		<?php echo $alert; ?>
	</p>
</div>
<?php } ?>
<div class="onecolumn">
	<div class="header">
		<div id="search"<?php if (isset($hidesearch)) echo ' style="display: none;"'; ?>>
			<form action="contacts/search" method="post">
				<input type="text" class="search noshadow" title="<?php echo __('search'); ?>" name="q" id="q" value="<?php if (isset($filter)) echo $filter; ?>" />
			</form>
		</div>
		<span><?php 
			if (isset($filter)){
				echo __('contacts.titleSearch', array(':filter'=>$filter));
			} else {
			echo __('contacts.title'); 
			}
			if (isset($company)) echo ' - ' . iconv('CP1251', 'UTF-8', $company['NAME']);
			?></span>
		<?php if (isset($company)) { ?>
		<div class="switch">
			<table cellpadding="0" cellspacing="0">
			<tbody>
				<tr>
					<td>
						<?php
							if ($company['CANEDIT'] != 0)
						 		echo HTML::anchor('companies/edit/' . $company['ID_ORG'], __('company.data'), array('class' => 'left_switch')); 
						 	else 
						 		echo HTML::anchor('companies/view/' . $company['ID_ORG'], __('company.data'), array('class' => 'left_switch'));
						?>
					</td>
					<td>
						<a href="javascript:" class="right_switch active"><?php echo __('company.contacts'); ?></a>
					</td>
				</tr>
			</tbody>
			</table>
		</div>
		<?php } ?>
	</div>
	<br class="clear"/>
	<div class="content">
	
		<?php if (count($people) > 0) { ?>
		<form id="form_data" name="form_data" action="" method="post">
			<table class="data tablesorter-blue" width="100%" cellpadding="0" cellspacing="0" id="tablesorter" >
				<thead>
					<tr>
						<!--
						<th style="width:10px">
							<input type="checkbox" id="check_all" name="check_all"/>
						</th>
						-->
						<?php if(Kohana::$config->load('config_newcrm')->get('contactListIdView')) echo '<th>'.__('contacts.id_pep').'</th>'?>
						<th><?php echo __('contact.active'); ?></th>
						<th><?php echo __('contacts.compareacl'); ?></th>
						<?php if(Kohana::$config->load('config_newcrm')->get('contactListTabNumView')) echo '<th>'.__('contacts.code').'</th>'?>
						<th><?php echo __('contacts.name'); ?></th>
						<?php if ($showphone == 1) echo '<th>' . __('contacts.phone') . '</th>'; ?>
						<th><?php echo __('contacts.company'); ?></th>
						<th><?php echo __('contacts.action'); ?></th>
					</tr>
				</thead>
				<tbody>
					<?php foreach ($people as $pep) { ?>
					<tr>
						<!--
						<td>
							<input type="checkbox" />
						</td>
						-->
						<?php if(Kohana::$config->load('config_newcrm')->get('contactListIdView')) echo '<td>'.Arr::get($pep,'ID_PEP').'</td>'?>
						
						<td><?php echo Arr::get($pep,'IS_ACTIVE')? 'Да':'Нет'; ?></td>
						
						<td><?php switch(Model::factory('contact')->check_acl(Arr::get($pep,'ID_PEP'))){

							case 0:
								echo __('acl.equalDefaultOrg');//совпадает с умолчательной
							break;

							case 1:
								echo '<b>'.__('acl.moreTheDefaultOrg').'</b>';//отличается, больше чем в умолчательной
							break;

							case 2:
								echo '<b>'.__('acl.lessTheDefaultOrg').'</b>';// отличается, меньше чем в умолчательной
							break;
						}							
						
						
						if(Kohana::$config->load('config_newcrm')->get('contactListTabNumView')) echo '<td>'.Arr::get($pep,'TABNUM').'</td>'?>
						
						<td><?php 
							if (Auth::instance()->logged_in('admin') && $pep['ID_PEP'] <>1)
								echo HTML::anchor('contacts/edit/' . $pep['ID_PEP'], iconv('CP1251', 'UTF-8', $pep['NAME'] . ' ' . $pep['SURNAME']));
							else
								echo iconv('CP1251', 'UTF-8', $pep['SURNAME'] . ' ' . $pep['NAME']);
							//echo iconv('CP1251', 'UTF-8', $pep['NAME'] . ' ' . $pep['SURNAME']); 
						
						?></td>

						<td><?php 
							if (Auth::instance()->logged_in('admin') && $pep['ID_PEP'] <>1)
								echo HTML::anchor('companies/edit/' . $pep['ID_ORG'], iconv('CP1251', 'UTF-8', Arr::get($pep,'ONAME', 'orgname'))); 
							else 
								echo iconv('CP1251', 'UTF-8',  Arr::get($pep,'ONAME','orgname'));
						?></td>
						<td>
							<?php if (Auth::instance()->logged_in('admin') && $pep['ID_PEP'] <>1) { ?>
							<a href="contacts/edit/<?php echo $pep['ID_PEP']; ?>"><img src="images/icon_edit.png" alt="edit" class="help" title="<?php echo __('tip.edit'); ?>"/></a> 
								<?php if (Arr::get($pep,'IS_ACTIVE') == 1) { ?>
										<a href="javascript:" onclick="if (confirm('<?php echo __('contacts.confirmSetNotActive'); ?>')) location.href='<?php echo URL::base() . 'contacts/fired/' . $pep['ID_PEP']; ?>';">
										<?php echo HTML::image('images/icon_delete.png', array('title' => __('tip.fired'), 'class' => 'help')); ?>
								</a>
								<?php } else {?>
									<a href="javascript:" onclick="if (confirm('<?php echo __('contacts.restore'); ?>')) location.href='<?php echo URL::base() . 'contacts/restore/' . Arr::get($pep,'ID_PEP'); ?>';">
										<?php echo HTML::image('images/restore_16.png', array('title' => __('tip.restore'), 'class' => 'help')); ?>
								</a>
								<?php }
							}?>
						</td>
					</tr>
					<?php } ?>
				</tbody>
			</table>
			<div id="chart_wrapper" class="chart_wrapper"></div>
		<!-- End bar chart table-->
		</form>
		<?php echo $pagination; ?>
		<?php } else { ?>
		<div style="margin: 100px 0; text-align: center;">
			<?php echo __('contacts.empty'); ?><br /><br />
		</div>
		<?php } ?>
	</div>
</div>
