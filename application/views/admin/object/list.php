<div class="page-header">
    <h1><?= $heading_title; ?></h1>
</div>

<nav class="navbar navbar-default" role="navigation">
    <div class="container-fluid">
        <div class="navbar-header">
            <a class="navbar-brand" href="#"><?= $heading_title; ?></a>
        </div>

        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
            <form class="navbar-form navbar-left" role="search">
                <div class="form-group">
                    <?= Form::input('search', $search, array('class' => 'form-control', 'placeholder' => 'Search')); ?>
                </div>
                <button type="submit" class="btn btn-default">Submit</button>
            </form>
            <ul class="nav navbar-nav navbar-right">
                <li><?= HTML::anchor(Route::get('admin')->uri(array('controller' => 'object', 'action' => 'edit')), '<span class="glyphicon glyphicon-plus"></span> ' . __('add')); ?></li>
            </ul>
        </div>
    </div>
</nav>


<table class="table table-hover">
    <thead>
        <tr>
            <th style="width:5%">ID</th>
            <th style="width:15%"><?= __('objects.name'); ?></th>
            <th style="width:30%"><?= __('objects.config_server'); ?></th>
            <th style="width:30%"><?= __('objects.config_bduser'); ?></th>
            <th style="width:20%"><?= __('objects.config_bdfile'); ?></th>
            <th style="width:10%"><?= __('actions'); ?></th>
        </tr>
    </thead>

    <tbody>
        <?php foreach ($objects as $object) : ?>
            <tr>
                <td><?php echo $object->id; ?></td>
                <td><?php echo $object->name; ?></td>
                <td><?php echo $object->config_server; ?></td>
                <td><?php echo $object->config_bduser; ?></td>
                <td><?php echo $object->config_bdfile; ?></td>
                <td>
                    <?= HTML::anchor(Route::get('admin')->uri(array('controller' => 'object', 'action' => 'edit', 'id' => $object->id)), '<span class="glyphicon glyphicon-edit"></span>'); ?>
                    <?= HTML::anchor(Route::get('admin')->uri(array('controller' => 'object', 'action' => 'delete', 'id' => $object->id)), '<span class="glyphicon glyphicon-minus"></span>', array('onclick' => 'return confirmDelete()')); ?>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<?php echo $pagination; ?>