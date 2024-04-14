<?php 
include Kohana::find_file('views','alert');
?>
<div class="onecolumn">
	<div class="header">
		<span class="error"><?php echo __('company.title') . ': ' . iconv('CP1251', 'UTF-8', $company['NAME']) ?></span>
		<div class="switch">
			<table cellpadding="0" cellspacing="0">
			<tbody>
				<tr>
					<td>
						<a href="javascript:" class="left_switch active"><?php echo __('company.data'); ?></a>
					</td>
					<td>
						<?php echo HTML::anchor('companies/people/' . $company['ID_ORG'], __('company.contacts'), array('class' => 'right_switch')); ?>
					</td>
				</tr>
			</tbody>
			</table>
		</div>
	</div>
	<br class="clear" />
	<div class="content">
			<p>
				<label for="name"><?php echo __('company.name'); ?></label>
				<br />
				<input type="text" id="name" name="name" size="50" disabled="disabled" value="<?php echo iconv('CP1251', 'UTF-8', $company['NAME']); ?>" />
				<br />
				<span class="error" id="error1" style="color: red; display: none;"><?php echo __('company.emptyname'); ?></span>
			</p>
			<br />
			<p>
				<label for="code"><?php echo __('company.code'); ?></label>
				<br />
				<input type="text" id="code" name="code" size="50" disabled="disabled" value="<?php echo iconv('CP1251', 'UTF-8', $company['DIVCODE']); ?>" />
				<br />
				<span class="error" id="error2" style="color: red; display: none;"><?php echo __('company.emptycode'); ?></span>
			</p>
			<br />
			<p>
				<?php echo Form::label('parent', __('company.parent')); ?>
				<br />
				<select name="parent" disabled="disabled" >
					<?php
					foreach ($parents as $p) 
						if ($p['ID_ORG'] == $company['ID_PARENT'])
							echo '<option value="' . $p['ID_ORG'] . '" selected="selected">' . iconv('CP1251', 'UTF-8', $p['NAME']) . '</option>';
						else
							echo '<option value="' . $p['ID_ORG'] . '">' . iconv('CP1251', 'UTF-8', $p['NAME']) . '</option>';
					?>
				</select>
			</p>
			<br />
			<p>
				<label for="access"><?php echo __('company.accessname'); ?></label>
				<br />
				<select name="access" disabled="disabled" >
					<?php 
					foreach ($acl as $ac)
						if ($ac['ID_ACCESSNAME'] == $company['ID_DEF_ACCESSNAME'])
							echo '<option value="' . $ac['ID_ACCESSNAME'] . '" selected="selected">' . iconv('CP1251', 'UTF-8', $ac['NAME']) . '</option>';
						else
							echo '<option value="' . $ac['ID_ACCESSNAME'] . '">' . iconv('CP1251', 'UTF-8', $ac['NAME']) . '</option>';
					?>
				</select>
			</p>
			<br />
			<br />
			<input type="button" value="<?php echo __('button.backtolist'); ?>" onclick="location.href='<?php echo URL::base(); ?>companies'" />
	</div>
</div>
