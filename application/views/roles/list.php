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
        <span><?php echo __('roles.title'); ?></span>
    </div>
    <br class="clear" />

    <div class="content">
        <table class="data" width="100%" cellpadding="0" cellspacing="0">
            <thead>
                <tr>
                    <th style="width:5%">ID</th>
                    <th><?php echo __('roles.name'); ?></th>
                    <th><?php echo __('roles.description'); ?></th>
                    <th style="width:10%"><?php echo __('actions'); ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($roles as $role) : ?>
                    <tr>
                        <td><?php echo $role->id; ?></td>
                        <td><?php echo $role->name; ?></td>
                        <td><?php echo $role->description; ?></td>
                        <td>
                            <?php echo HTML::anchor('roles/edit/' . $role->id, HTML::image('images/icon_edit.png', array('title' => __('tip.edit')))); ?>
                            <?php echo HTML::anchor('javascript:', HTML::image('images/icon_delete.png', array('title' => __('tip.delete'), 'class' => 'help')), array('onclick' => "if (confirm('" . __('roles.confirmdelete') . "')) { location.replace='" . URL::site('roles/delete/' . $role->id, 'http') . "'; }")); ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <div id="chart_wrapper" class="chart_wrapper"></div>

        <?php echo $pagination; ?>
    </div>
</div>
