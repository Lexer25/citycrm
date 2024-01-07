<?= Form::open(); ?>
    <div class="panel panel-default" id="login-form">
        <div class="panel-heading"><?= $heading_title; ?></div>
        <div class="panel-body">
            <div class="form-group">
                <?= Form::label('name', __('roles.name')); ?>
                <?= Form::input('name', $role->name, array('class' => 'form-control', 'id' => 'name')); ?>
            </div>

            <div class="form-group">
                <?= Form::label('description', __('roles.description')); ?>
                <?= Form::input('description', $role->description, array('class' => 'form-control', 'id' => 'description')); ?>
            </div>
        </div>

        <div class="panel-heading"><?= $heading_title; ?></div>
        <div class="panel-body">
            <div class="checkbox">
                <label>
                    <?= Form::checkbox('rules[objects][edit]', 1, (isset($role->rules['objects']) ? (bool)Arr::get($role->rules['objects'], 'edit', false) : false)); ?><?= __('rules.objects.edit'); ?>
                </label>
            </div>

            <div class="checkbox">
                <label>
                    <?= Form::checkbox('rules[objects][delete]', 1, (isset($role->rules['objects']) ? (bool)Arr::get($role->rules['objects'], 'delete', false) : false)); ?><?= __('rules.objects.delete'); ?>
                </label>
            </div>

            <div class="checkbox">
                <label>
                    <?= Form::checkbox('rules[users][edit]', 1, (isset($role->rules['users']) ? (bool)Arr::get($role->rules['users'], 'edit', false) : false)); ?><?= __('rules.users.edit'); ?>
                </label>
            </div>

            <div class="checkbox">
                <label>
                    <?= Form::checkbox('rules[users][delete]', 1, (isset($role->rules['users']) ? (bool)Arr::get($role->rules['users'], 'delete', false) : false)); ?><?= __('rules.users.delete'); ?>
                </label>
            </div>

            <?= Form::submit('save', __('save'), array('class' => 'btn btn-default')); ?>
            <?= Html::anchor(Route::get('admin')->uri(array('controller' => 'object', 'action' => 'index')), __('cancel'), array('class' => 'btn btn-default')); ?>
        </div>
    </div>
<?= Form::close(); ?>