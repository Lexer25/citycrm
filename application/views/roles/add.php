<?php if (isset($alert)) : ?>
    <div class="alert_error">
        <p>
            <img class="mid_align" alt="error" src="images/icon_error.png" />
            <?php echo $alert; ?>
        </p>
    </div>
<?php endif; ?>
<div class="onecolumn">
    <div class="header">
        <span><?php echo __('roles.title') ?></span>
    </div>
    <br class="clear" />

    <div class="content">
        <?php echo FORM::open('roles/add', array()); ?>
            <p>
                <?php echo Form::label('name', __('roles.name')); ?>
                <br />
                <?php echo Form::input('name', $role->name, array('size' => '50', 'id' => 'name')); ?>
            </p>
            <br />

            <p>
                <?php echo Form::label('description', __('roles.description')); ?>
                <br />
                <?php echo Form::input('description', $role->description, array('size' => '50', 'id' => 'description')); ?>
            </p>
            <br />

            <br />
            <input type="submit" value="<?php echo __('button.save'); ?>" />
            &nbsp;&nbsp;
            <input type="button" value="<?php echo __('button.cancel'); ?>" onclick="document.forms[0].reset()" />
            &nbsp;&nbsp;
            <input type="button" value="<?php echo __('button.backtolist'); ?>" onclick="location.href='<?php echo URL::site('roles', 'http'); ?>'" />
        <?php echo Form::close(); ?>
    </div>
</div>
