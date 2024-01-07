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
		<span><?php echo __('groups.title'); ?></span>
	</div>
	<br class="clear"/>
	<div class="content">
		<?php if (count($groups) > 0) { ?>
		<form id="form_data" name="form_data" action="" method="post">
			<table class="data" width="100%" cellpadding="0" cellspacing="0">
				<thead>
					<tr>
						<!--
						<th style="width:10px">
							<input type="checkbox" id="check_all" name="check_all"/>
						</th>
						-->
						<th width="30"><?php echo __('groups.id'); ?></th>
						<th><?php echo __('groups.name'); ?></th>
						<th width="50%"><?php echo __('groups.desc'); ?></th>
						<th><?php echo __('groups.qty'); ?></th>
						<th width="70"><?php echo __('groups.action'); ?></th>
					</tr>
				</thead>
				<tbody>
					<?php foreach ($groups as $g) { ?>
					<tr>
						<!--
						<td>
							<input type="checkbox"/>
						</td>
						-->
						<td><?php echo $g['ID_GROUP']; ?></td>
						<td><?php echo HTML::anchor('companies/groupedit/' . $g['ID_GROUP'], iconv('CP1251', 'UTF-8', $g['NAME'])); ?></td>
						<td><?php echo iconv('CP1251', 'UTF-8', $g['DESCRIPTION']); ?></td>
						<td><?php echo $g['QTY']; ?></td>
						<td><?php echo HTML::anchor('companies/groupedit/' . $g['ID_GROUP'], HTML::image('images/icon_edit.png', array('title' => __('tip.edit'), 'class' => 'help'))); ?>
							<a href="javascript:" onclick="if (confirm('<?php echo __('groups.confirmdelete'); ?>')) location.href='<?php echo URL::base() . 'companies/groupdelete/' . $g['ID_GROUP']; ?>';">
								<?php echo HTML::image('images/icon_delete.png', array('title' => __('tip.delete'), 'class' => 'help')); ?>
							</a>
						</td>
					</tr>
					<?php } ?>
				</tbody>
			</table>
		</form>
		<?php } else { ?>
			<div style="margin: 100px 0; text-align: center;">
				<?php echo __('groups.none'); ?><br /><br />
			</div>
		<?php } ?>
	</div>
</div>
