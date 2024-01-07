<!DOCTYPE html>
<html lang="ru">
    <head>
        <title><?= $title ?></title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">

        <?= HTML::style('media/bootstrap/css/bootstrap.min.css'); ?>
        <?= HTML::style('media/css/style.css'); ?>

        <?= HTML::script('http://code.jquery.com/jquery-1.11.1.min.js'); ?>
        <?= HTML::script('media/bootstrap/js/bootstrap.min.js'); ?>
        <?= HTML::script('media/js/script.js'); ?>
    </head>

    <body>
        <div class="well">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-6 col-md-offset-3">
                        <? if (Auth::instance()->get_user()) : ?>
                            <nav class="navbar navbar-default" role="navigation">
                                <div class="container-fluid">
                                    <div class="navbar-header"></div>

                                    <div class="collapse navbar-collapse">
                                        <ul class="nav navbar-nav">
                                            <li<?= strpos($current, 'object') !== false ? ' class="active"' : '' ?>><?= HTML::anchor(Route::get('admin')->uri(array('controller' => 'object')), __('objects')); ?></li>
                                            <li<?= strpos($current, 'role') !== false ? ' class="active"' : '' ?>><?= HTML::anchor(Route::get('admin')->uri(array('controller' => 'role')), __('roles')); ?></a></li>
                                            <li<?= strpos($current, 'user') !== false ? ' class="active"' : '' ?>><?= HTML::anchor(Route::get('admin')->uri(array('controller' => 'user')), __('users')); ?></a></li>
                                        </ul>

                                        <ul class="nav navbar-nav navbar-right">
                                            <li><p class="navbar-text"><?= __('signed_in') . $user->name . ' ' . $user->surname;?></p></li>
                                            <li><?= HTML::anchor(Route::get('admin')->uri(array('controller' => 'auth', 'action' => 'logout')), '<span class="glyphicon glyphicon-log-out"></span> ' . __('logout')); ?></li>
                                        </ul>
                                    </div>
                                </div>
                            </nav>
                        <? endif; ?>

                        <?= $content;?>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>