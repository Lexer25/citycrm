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
        <div id="search">
            <?php echo Form::open('objects/search', array('method' => 'post')); ?>
                <?php echo Form::input('q', $filter, array('class' => 'search noshadow', 'id' => 'q', 'title' => __('search'))); ?>
            <?php echo Form::close(); ?>
        </div>
        <span><?php echo __('objects.title'); ?></span>
    </div>
    <br class="clear" />

    <div class="content">
        <table class="data" width="100%" cellpadding="0" cellspacing="0">
            <thead>
                <tr>
                    <th style="width:5%">ID</th>
                    <th style="width:15%"><?php echo __('objects.name'); ?></th>
                    <th style="width:30%"><?php echo __('objects.config_server'); ?></th>
                    <th style="width:30%"><?php echo __('objects.config_bdpath'); ?></th>
                    <th style="width:20%"><?php echo __('objects.config_bdfile'); ?></th>
                    <th style="width:10%"><?php echo __('actions'); ?></th>

                </tr>
            </thead>
            <tbody>
                <?php foreach ($objects as $object) : ?>
                    <tr>
                        <td><?php echo $object->id; ?></td>
                        <td><?php echo $object->name; ?></td>
                        <td><?php echo $object->config_server; ?></td>
                        <td><?php echo $object->config_bdpath; ?></td>
                        <td><?php echo $object->config_bdfile; ?></td>
                        <td>
                            <?php echo HTML::anchor('objects/edit/' . $object->id, HTML::image('images/icon_edit.png', array('title' => __('tip.edit')))); ?>
                            <?php echo HTML::anchor('javascript:', HTML::image('images/icon_delete.png', array('title' => __('tip.delete'), 'class' => 'help')), array('onclick' => "if (confirm('" . __('objects.confirmdelete') . "')) { location.replace='" . URL::site('objects/delete/' . $object->id, 'http') . "'; }")); ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <div id="chart_wrapper" class="chart_wrapper"></div>

        <?php echo $pagination; ?>
    </div>
</div>
