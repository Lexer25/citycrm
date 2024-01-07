<?= Form::open(); ?>
    <div class="panel panel-default" id="object-form">
        <div class="panel-heading"><?= $heading_title; ?></div>
        <div class="panel-body">
            <div class="form-group">
                <?= Form::label('name', __('objects.name')); ?>
                <?= Form::input('name', $object->name, array('class' => 'form-control', 'id' => 'name')); ?>
            </div>

            <div class="form-group">
                <?= Form::label('description', __('objects.description')); ?>
                <?= Form::textarea('description', $object->description, array('class' => 'form-control', 'rows' => '3')); ?>
            </div>
        </div>

        <div class="panel-heading"><?= __('objects.characteristics'); ?></div>
        <div class="panel-body">
            <div class="form-group">
                <?= Form::label('config_server', __('objects.config_server')); ?>
                <?= Form::input('config_server', $object->config_server, array('class' => 'form-control', 'id' => 'config_server')); ?>
            </div>

            <div class="form-group">
                <?= Form::label('config_bduser', __('objects.config_bduser')); ?>
                <?= Form::input('config_bduser', $object->config_bduser, array('class' => 'form-control', 'id' => 'config_bduser')); ?>
            </div>

            <div class="form-group">
                <?= Form::label('config_bdpass', __('objects.config_bdpass')); ?>
                <?= Form::input('config_bdpass', $object->config_bdpass, array('class' => 'form-control', 'id' => 'config_bdpass')); ?>
            </div>

            <div class="form-group">
                <?= Form::label('config_bdfile', __('objects.config_bdfile')); ?>
                <?= Form::input('config_bdfile', $object->config_bdfile, array('class' => 'form-control', 'id' => 'config_bdfile')); ?>
            </div>

            <?= Form::submit('save', __('save'), array('class' => 'btn btn-default')); ?>
            <?= Form::button('check', __('check'), array('class' => 'btn btn-default pull-right', 'id' => 'db-check')); ?>
            <?= Html::anchor(Route::get('admin')->uri(array('controller' => 'object', 'action' => 'index')), __('cancel'), array('class' => 'btn btn-default')); ?>
        </div>

        <div class="alert hidden" role="alert"></div>
    </div>
<?= Form::close(); ?>