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
			<form action="eventlog/search" method="post">
				<input type="text" class="search noshadow" title="<?php echo __('search'); ?>" name="q" id="q" value="<?php if (isset($filter)) echo $filter; ?>" />
			</form>
		</div>
		<span><?php echo __($title);?></span>
	</div>

    <br class="clear"/>

    <div class="filters">
        <ul id="filter_menu">
            <a href="javascript:"><?php echo __('eventlog.filters.button'); ?></a>
            <ul>
                <li class="div">
                    <form method="post">
                        <?php foreach ($devices as $device) :?>
                            <div class="filter-field">
                                <?php if (in_array($device['ID_DEV'], $filters['device'])) : ?>
                                    <input id="filter-dev-<?php echo $device['ID_DEV'];?>" name="filter[device][]" value="<?php echo $device['ID_DEV'];?>" type="checkbox" checked="checked" />
                                    <label for="filter-dev-<?php echo $device['ID_DEV'];?>"><?php echo iconv('CP1251', 'UTF-8', $device['NAME']);?></label>
                                <?php else: ?>
                                    <input id="filter-dev-<?php echo $device['ID_DEV'];?>" name="filter[device][]" value="<?php echo $device['ID_DEV'];?>" type="checkbox" />
                                    <label for="filter-dev-<?php echo $device['ID_DEV'];?>"><?php echo iconv('CP1251', 'UTF-8', $device['NAME']);?></label>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>

						<input type="submit" value="<?php echo __('eventlog.filters.submit'); ?>" />
                    </form>
                </li>
            </ul>
        </ul>
    </div>
	<br class="clear"/>
	<div class="content">
		<?php if ( 1) { ?>
		<form id="form_data" name="form_data_day_for_list" action="" method="post">
			<?php echo __('eventlog.day_for_list');?>
			<input type="text" name="hour_for_list" value="<?php echo $hour;?>" size="3">
			<input type="submit" name="rrr_ttt"><br>
		</form>
		<form id="form_data" name="form_data" action="" method="post">
			
			
			<table class="data" width="100%" cellpadding="0" cellspacing="0">
				<thead>
					<tr>
						<!--
						<th style="width:10px">
							<input type="checkbox" id="check_all" name="check_all"/>
						</th>
						-->
						<th><?php echo __('eventlog.select'); ?></th>
						<th><?php echo __('eventlog.event_id'); ?></th>
						<th><?php echo __('eventlog.event'); ?></th>
						<th><?php echo __('eventlog.source'); ?></th>
						<th><?php echo __('eventlog.timestamp'); ?></th>
						<th><?php echo __('eventlog.card_id'); ?></th>
						<th><?php echo __('eventlog.user'); ?></th>
						<th><?php echo __('eventlog.load_date'); ?></th>
						<th><?php echo __('eventlog.tab_num'); ?></th>
						<th><?php echo __('eventlog.org_parent'); ?></th>
						<th><?php echo __('eventlog.error_maybe'); ?></th>
						
					</tr>
				</thead>
				<tbody>
					<?php 
					$k=0;
					foreach ($eventlog as $event) { 
					$k++;
					switch ($event['ANALYTIC1']){
					case "0";
						$color="unic_green";
					break;
					case "1";
						$color="unic_red";
					break;
					case "2";
						$color="unic_grey";
					break;
					case "3";
						$color="unic_yellow";
					break;
					default:
						$color="white";
					break;
					}
									
					?>
					
					<tr id="<?php echo $color;?>">
						
					<!--	
						<td>
							<input type="checkbox" />
						</td>
						
						<td><?php 
							echo HTML::anchor('contacts/card/' . $event['ID_CARD'], $event['ID_CARD']); ?></td>
						<tr>
						-->
						<td id="<?php echo $color;?>"><?php echo "&nbsp;".$k; ?></td> 
						<td id="<?php echo $color;?>"><?php if($event['ID_EVENT'] !=null) {echo iconv('CP1251', 'UTF-8', $event['ID_EVENT']);} else {echo "&nbsp;";} ?></td>
						<td id="<?php echo $color;?>"><?php if($event['NAME'] !=null) {echo iconv('CP1251', 'UTF-8', $event['NAME']);} else {echo "&nbsp;";}  ?></td> 
						<td id="<?php echo $color;?>"><?php if($event['DEVNAME'] !=null) {echo iconv('CP1251', 'UTF-8', $event['DEVNAME']);} else {echo "&nbsp;";}  ?></td> 
						<td id="<?php echo $color;?>"><?php if($event['DATETIME'] !=null) {echo iconv('CP1251', 'UTF-8', $event['DATETIME']);} else {echo "&nbsp;";}  ?></td> 
						<td id="<?php echo $color;?>"><?php if($event['ID_CARD'] !=null) {echo HTML::anchor('contacts/card/'. iconv('CP1251', 'UTF-8',$event['ID_CARD']), iconv('CP1251', 'UTF-8',$event['ID_CARD']));} else {echo "&nbsp;";}  ?></td> 
						<td id="<?php echo $color;?>"><?php if($event['NOTE'] !=null) {echo HTML::anchor('contacts/edit/'.iconv('CP1251', 'UTF-8', $event['ID_PEP']),iconv('CP1251', 'UTF-8', $event['NOTE']));} else {echo "&nbsp;";}  ?></td> 
						<td id="<?php echo $color;?>"><?php if($event['LOAD_RESULT'] !=null) {echo iconv('CP1251', 'UTF-8', ($event['LOAD_RESULT']." ".$event['LOAD_TIME']." ".$event['DEVIDX']));} else {echo "&nbsp;";}  ?></td>
						<td id="<?php echo $color;?>"><?php if($event['TABNUM'] !=null) {echo iconv('CP1251', 'UTF-8', $event['TABNUM']);} else {echo "&nbsp;";}  ?></td> 
						<td id="<?php echo $color;?>"><?php if($event['ORGNAME'] !=null) {echo iconv('CP1251', 'UTF-8', $event['ORGNAME']);} else {echo "&nbsp;";}  ?></td> 
						<td id="<?php echo $color;?>"><?php if($event['ANALYTIC1'] !=null) echo $event['ID_EVENTTYPE']."-".$event['ANALYTIC1']." ". __($event['ANALYTIC2']);  ?></td> 
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
