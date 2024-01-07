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
                <?php if (sizeof($objects) > 0) : ?>
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown"><?= __('object');?><span class="caret"></span></a>
                        <ul class="dropdown-menu" role="menu">
                            <li><?= HTML::anchor(Route::get('admin')->uri(array('controller' => 'user', 'action' => 'index')), __('show_all')); ?></li>
                            <?php foreach ($objects as $object) : ?>
                                <li><?= HTML::anchor(Route::get('admin')->uri(array('controller' => 'user', 'action' => 'index')) . '?object=' . $object->id, $object->name); ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </li>
                <?php endif; ?>

                <li><?= HTML::anchor(Route::get('admin')->uri(array('controller' => 'user', 'action' => 'edit')), '<span class="glyphicon glyphicon-plus"></span> ' . __('add')); ?></li>
            </ul>
        </div>
    </div>
</nav>


<table class="table table-hover">
    <thead>
    <tr>
        <th style="width:5%">ID</th>
        <th style="width:15%"><?= __('users.username'); ?></th>
        <th style="width:30%"><?= __('users.role'); ?></th>
        <th style="width:40%"><?= __('users.object'); ?></th>
        <th style="width:10%"><?= __('users.action'); ?></th>
    </tr>
    </thead>

    <tbody>
        <?php foreach ($users as $user) : ?>
            <tr>
                <td><?= $user['id']; ?></td>
                <td><?= $user['username']; ?></td>
                <td><?= $user['role']; ?></td>
                <td><?= $user['object']; ?></td>
                <td>
                    <?= HTML::anchor(Route::get('admin')->uri(array('controller' => 'user', 'action' => 'login', 'id' => $user['id'])), '<span class="glyphicon glyphicon-log-in"></span>'); ?>
                    <?= HTML::anchor(Route::get('admin')->uri(array('controller' => 'user', 'action' => 'edit', 'id' => $user['id'])), '<span class="glyphicon glyphicon-edit"></span>'); ?>
                    <?= HTML::anchor(Route::get('admin')->uri(array('controller' => 'user', 'action' => 'delete', 'id' => $user['id'])), '<span class="glyphicon glyphicon-minus"></span>'); ?>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<?= $pagination; ?>