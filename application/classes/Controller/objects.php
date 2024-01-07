<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Objects extends Controller_Template {
    public $template = 'template';
    private $listsize;
    private $session;

    public function before() {
        parent::before();

        if (!Auth::instance()->logged_in('admin'))
            $this->request->redirect('/');

        $this->session = Session::instance();
        $name          = $this->session->get('username', false);

        I18n::$lang     = $this->session->get('language', 'en-us');
        $this->listsize = $this->session->get('listsize', 10);
    }

    public function action_index($filter = null) {
        $Objects = Model::factory('Objects');
        $total   = $Objects->count_all();

        $pagination = new Pagination(array(
            'uri_segment'    => 2,
            'total_items'    => $total,
            'style'          => 'classic',
            'items_per_page' => $this->listsize,
            'auto_hide'      => false,
        ));

        $page    = Arr::get($_GET, 'page', 1);
        $results = $Objects->getList($page, $this->listsize, $filter);

        $alert = false;

        $this->template->content = View::factory('objects/list')
                                       ->bind('objects', $results)
                                       ->bind('alert', $alert)
                                       ->bind('filter', $filter)
                                       ->bind('pagination', $pagination);
    }

    public function action_add () {
        $Object = Model::factory('Objects');

        if (!empty($_POST)) {
            $Object->name = Arr::get($_POST, 'name', false);
            $Object->config_server = Arr::get($_POST, 'config_server', false);
            $Object->config_bdpath = Arr::get($_POST, 'config_bdpath', false);
            $Object->config_bdfile = Arr::get($_POST, 'config_bdfile', false);

            if ($Object->check()) {
                $Object->save();

                Session::instance()->set('alert', __('objects.saved'));
                $this->request->redirect('objects');
            } else {
                $alert = implode('<br>', $Object->validate()->errors('error'));
            }
        }

        $this->template->content = View::factory('objects/add')
                                       ->bind('alert', $alert)
                                       ->bind('object', $Object);
    }

    public function action_edit ($id) {
        $Object = Model::factory('Objects', $id);

        if (!empty($_POST)) {
            $Object->name = Arr::get($_POST, 'name', false);
            $Object->config_server = Arr::get($_POST, 'config_server', false);
            $Object->config_bdpath = Arr::get($_POST, 'config_bdpath', false);
            $Object->config_bdfile = Arr::get($_POST, 'config_bdfile', false);

            if ($Object->check()) {
                $Object->save();

                Session::instance()->set('alert', __('objects.saved'));
                $this->request->redirect('objects');
            } else {
                $alert = implode('<br>', $Object->validate()->errors('error'));
            }
        }

        $this->template->content = View::factory('objects/edit')
                                       ->bind('alert', $alert)
                                       ->bind('object', $Object);
    }

    public function action_delete ($id) {
        $Object = Model::factory('Objects', $id);

        var_dump($this->_loaded);
    }
}