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
                <li><?= HTML::anchor(Route::get('admin')->uri(array('controller' => 'role', 'action' => 'edit')), '<span class="glyphicon glyphicon-plus"></span> ' . __('add')); ?></li>
            </ul>
        </div>
    </div>
</nav>


<table class="table table-hover">
    <thead>
    <tr>
        <th style="width:5%">ID</th>
        <th><?= __('roles.name'); ?></th>
        <th><?= __('roles.description'); ?></th>
        <th style="width:10%"><?= __('actions'); ?></th>
    </tr>
    </thead>

    <tbody>
        <?php foreach ($roles as $role) : ?>
            <tr>
                <td><?= $role->id; ?></td>
                <td><?= $role->name; ?></td>
                <td><?= $role->description; ?></td>
                <td>
                    <?= HTML::anchor(Route::get('admin')->uri(array('controller' => 'role', 'action' => 'edit', 'id' => $role->id)), '<span class="glyphicon glyphicon-edit"></span>'); ?>
                    <?= HTML::anchor(Route::get('admin')->uri(array('controller' => 'role', 'action' => 'delete', 'id' => $role->id)), '<span class="glyphicon glyphicon-minus"></span>', array('onclick' => 'return confirmDelete()')); ?>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<?= $pagination; ?>