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
        <span><?php echo __('objects.title') ?></span>
    </div>
    <br class="clear" />

    <div class="content">
        <?php echo FORM::open('objects/edit/' . $object->id, array()); ?>
            <p>
                <?php echo Form::label('name', __('objects.name')); ?>
                <br />
                <?php echo Form::input('name', $object->name, array('size' => '50', 'id' => 'name')); ?>
            </p>
            <br />

            <p>
                <?php echo Form::label('config_servere', __('objects.config_server')); ?>
                <br />
                <?php echo Form::input('config_server', $object->config_server, array('size' => '50', 'id' => 'config_server')); ?>
            </p>
            <br />

            <p>
                <?php echo Form::label('config_bdpath', __('objects.config_bdpath')); ?>
                <br />
                <?php echo Form::input('config_bdpath', $object->config_bdpath, array('size' => '50', 'id' => 'config_bdpath')); ?>
            </p>
            <br />

            <p>
                <?php echo Form::label('config_bdfile', __('objects.config_bdfile')); ?>
                <br />
                <?php echo Form::input('config_bdfile', $object->config_bdfile, array('size' => '50', 'id' => 'config_bdfile')); ?>
            </p>
            <br />

            <br />
            <input type="submit" value="<?php echo __('button.save'); ?>" />
            &nbsp;&nbsp;
            <input type="button" value="<?php echo __('button.cancel'); ?>" onclick="document.forms[0].reset()" />
            &nbsp;&nbsp;
            <input type="button" value="<?php echo __('button.backtolist'); ?>" onclick="location.href='<?php echo URL::site('objects', 'http'); ?>'" />
        <?php echo Form::close(); ?>
    </div>
</div>
