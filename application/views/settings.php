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
		<span><?php echo __('settings.title') ?></span>
	</div>
	<br class="clear" />
	<div class="content">
		<form action="settings/save" method="post">
			<p>
				<label><?php echo __('settings.place'); ?></label>
				<br />
				<select name="place">
					<option value="1">Шуваловский</option>
					<option value="2"<?php echo ' selected="selected"'; ?>>Яуза</option>
					<option value="3">Роспечать</option>
					<option value="4">ЦКБ</option>
				</select>
			</p>
			<p>
				<label><?php echo __('settings.language'); ?></label>
				<br />
				<select name="language">
					<option value="en-us">English</option>
					<option value="ru-ru"<?php if ($lang == 'ru-ru') echo ' selected="selected"'; ?>>Русский</option>
				</select>
			</p>
			<br />
			<p>
				<label><?php echo __('settings.listsize'); ?></label>
				<br />
				<select name="listsize">
					<?php
					$sizes = array(10, 25, 50);
					foreach ($sizes as $s)
						if ($s == $size)
							echo '<option value="' . $s . '" selected="selected">' . $s . '</option>';
						else
							echo '<option value="' . $s . '">' . $s . '</option>';
					?>
				</select>
			</p>
									
			<p>
				<label><?php echo __('settings.password'); ?></label>
				<br />
				<input type="password" name="password" />
			</p>
			<br />
			
			
			<br />
			<input type="submit" value="<?php echo __('button.save'); ?>" />
			&nbsp;&nbsp;
			<input type="button" value="<?php echo __('button.cancel'); ?>" onclick="document.forms[0].reset();" />
		</form>
	</div>
</div>
