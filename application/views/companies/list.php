<script type="text/javascript">


 
  	$(function() {		
  		$("#tablesorter").tablesorter({ headers: { 7:{sorter: false}},  widgets: ['zebra']});
		
  	});	
	
</script>
<style>
.tree{
  --spacing : 1.5rem;
  --radius  : 6px;
  --radius  : 6px;
}

.tree li{
  display      : block;
  position     : relative;
  padding-left : calc(2 * var(--spacing) - var(--radius) - 2px);
}

.tree ul{
  margin-left  : calc(var(--radius) - var(--spacing));
  padding-left : 0;
}

.tree ul li{
  border-left : 2px solid #ddd;
}

.tree ul li:last-child{
  border-color : transparent;
}

.tree ul li::before{
  content      : '';
  display      : block;
  position     : absolute;
  top          : calc(var(--spacing) / -2);
  left         : -2px;
  width        : calc(var(--spacing) + 2px);
  height       : calc(var(--spacing) + 1px);
  border       : solid #ddd;
  border-width : 0 0 2px 2px;
}

.tree summary{
  display : block;
  cursor  : pointer;
}

.tree summary::marker,
.tree summary::-webkit-details-marker{
  display : none;
}

.tree summary:focus{
  outline : none;
}

.tree summary:focus-visible{
  outline : 1px dotted #000;
}

.tree li::after,
.tree summary::before{
  content       : '';
  display       : block;
  position      : absolute;
  top           : calc(var(--spacing) / 2 - var(--radius));
  left          : calc(var(--spacing) - var(--radius) - 1px);
  width         : calc(2 * var(--radius));
  height        : calc(2 * var(--radius));
  border-radius : 50%;
  background    : #ddd;
}

.tree summary::before{
  content     : '+';
  z-index     : 1;
  background  : #696;
  color       : #fff;
  line-height : calc(2 * var(--radius) - 2px);
  text-align  : center;
}

.tree details[open] > summary::before{
  content : '−';
}
</style>
<?php 
include Kohana::find_file('views','alert');
if ($alert) { ?>
<div class="alert_success">
	<p>
		<img class="mid_align" alt="success" src="images/icon_accept.png" />
		<?php echo $alert; ?>
	</p>
</div>
<?php } ?>
<div class="onecolumn">
	<div class="header">
		<div id="search">
			<form action="companies/search" method="post">
				<input type="text" class="search noshadow" title="<?php echo __('search'); ?>" name="q" id="q" value="<?php if (isset($filter)) echo $filter; ?>" />
			</form>
		</div>
		<span><?php echo __('companies.title'); ?></span>
	</div>

	<?php
		//echo Debug::vars('19', $org_tree);
		echo '<br><div class="content">'.$org_tree.'</div>';//прорисовка дерева орг
	?>
	<br class="clear"/>
	<div class="content">
		<form id="form_data" name="form_data" action="" method="post">
			<table class="data tablesorter-blue" width="100%" cellpadding="0" cellspacing="0" id="tablesorter" >
				<thead>
					<tr>
						<!--
						<th style="width:10px">
							<input type="checkbox" id="check_all" name="check_all"/>
						</th>
						-->
						<?php

						echo '<th>' . __('companies.id') . '</th>';
						echo '<th>' . __('companies.name') . '</th>';
						echo '<th>' . __('companies.countChildren') . '</th>';
						echo '<th>' . __('companies.countContact') . '</th>';
						echo '<th>' . __('companies.code') . '</th>';
						echo '<th>' . __('companies.parent') . '</th>';
						//if ($col1['ACCESSNAME'])	echo '<th>' . __('companies.access') . '</th>';
						echo '<th>' . __('companies.action') . '</th>';
						?>
					</tr>
				</thead>
				<tbody>
					<?php foreach ($companies as $c) {
						$company=new Company(Arr::get($c,'ID_ORG'));
						?>
					
					<tr>
						<!--
						<td>
							<input type="checkbox"/>
						</td>
						-->
						<?php 
						if ($col1['ID_ORG'])		echo '<td align="center">' . $c['ID_ORG'] . '</td>';
						if ($col1['NAME'])			{
							if (Auth::instance()->logged_in('admin') || $c['SUMOEDIT'] > 0)
								echo '<td>' . HTML::anchor('companies/?parent=' . $c['ID_ORG'], iconv('CP1251', 'UTF-8', $c['NAME']));
							else 
								echo '<td>' . HTML::anchor('companies/view/' . $c['ID_ORG'], iconv('CP1251', 'UTF-8', $c['NAME']));
							//echo '<td>' . iconv('CP1251', 'UTF-8', $c['NAME']) . '</td>';
						}
						echo '<td>' . count($company->getChildIdOrg()) . '</td>';
						echo '<td>' . count($company->getChildId_pepList()) . '</td>';
						if ($col1['DIVCODE'])		echo '<td>' . iconv('CP1251', 'UTF-8', $c['DIVCODE']) . '</td>';
						
						if ($col1['PARENT'])		echo '<td>' . HTML::anchor('companies/edit/' . $c['PARENTID'], iconv('CP1251', 'UTF-8', $c['PARENT'])) . '</td>';
						//if ($col1['ACCESSNAME'])	echo '<td>' . iconv('CP1251', 'UTF-8', $c['ACCESSNAME']) . '</td>';
						
						if (Auth::instance()->logged_in('admin') || $c['SUMOEDIT'] > 0)
							echo '<td>' . HTML::anchor('companies/edit/' . $c['ID_ORG'], HTML::image('images/icon_edit.png', array('title' => __('tip.edit'), 'class' => 'help')));
						else
							echo '<td>' . HTML::anchor('companies/view/' . $c['ID_ORG'], HTML::image('images/icon_view.png', array('title' => __('tip.view'), 'class' => 'help')));
						if (Auth::instance()->logged_in('admin') || $c['SUMODELETE'] > 0 || true) { ?>
							<a href="javascript:" onclick="if (confirm('<?php echo __('companies.confirmdelete'); ?>')) location.href='<?php echo URL::base() . 'companies/delete/' . $c['ID_ORG'].'/'.$c['PARENTID']; ?>';">
								<?php echo HTML::image('images/icon_delete.png', array('title' => __('tip.delete'), 'class' => 'help')); ?>
							</a>
						<?php	
							echo HTML::anchor('companies/people/' . $c['ID_ORG'], HTML::image('images/icon_contacts.png', array('title' => __('tip.view'), 'class' => 'help')));
						 } ?>
						</td>
					</tr>
					<?php } ?>
				</tbody>
			</table>
			<div id="chart_wrapper" class="chart_wrapper"></div>
		<!-- End bar chart table-->
		</form>
		
		<?php echo $pagination; ?>
	</div>
</div>
