		<div class="container">
				<!-- Static navbar -->

			<div class="panel panel-primary">
			  <div class="panel-heading">
				<h3 class="panel-title"><?echo __('err_mess')?></h3>
			  </div>
			  <div class="panel-body">
				
				<?
				
			
				echo date('Y.m.d H:m', time()). '<br>'. iconv('CP1251', 'UTF-8',$err);
				
				?>
				
			  </div>
			</div>
		</div>

