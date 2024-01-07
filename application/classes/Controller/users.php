<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Users extends Controller_Template
{
	public $template = 'template';
	private $listsize;
	private $session;
	
	public function before()
	{
		parent::before();

		if (!Auth::instance()->logged_in('admin')) $this->request->redirect('/');
		
		$this->session = Session::instance();
		$name = $this->session->get('username', false);
		
		I18n::$lang = $this->session->get('language', 'en-us');
		$this->listsize = $this->session->get('listsize', 10);
	}
	
	public function action_search()
	{
		$pattern = Arr::get($_POST, 'q', null);
		if ($pattern) {
			$this->session->set('search_user', $pattern);
		} else {
			$pattern = $this->session->get('search_user', '');
		}
		$this->action_index($pattern);
	}
	
	public function action_index($filter = null)
	{
		
		$users = Model::factory('User');
		
		$qty = $users->getCount($filter);
		
		//echo Debug::vars('39'); exit;	
		$pagination = new Pagination(array(
			'uri_segment' => 2,
			'total_items' => $qty,
			'style' => 'classic',
			'items_per_page' => $this->listsize,
			'auto_hide' => false,
		));
		
		$list = $users->getList(Arr::get($_GET, 'page', 1),	$this->listsize, $filter);
		
		$fl = $this->session->get('alert');
		$this->session->delete('alert');
		
		$showphone = $this->session->get('showphone', 0);
		
		$this->template->content = View::factory('users/list')
			->bind('users', $list)
			->bind('alert', $fl)
			->bind('showphone', $showphone)
			->bind('filter', $filter)
			->bind('pagination', $pagination);
	}

	public function action_edit($id = 0)
	{
		$fl = null;
		
		if ($_POST) {
			$id			= Arr::get($_POST, 'id');
			$surname	= Arr::get($_POST, 'surname');
			$name		= Arr::get($_POST, 'name');
			$login		= Arr::get($_POST, 'username');
			$password	= Arr::get($_POST, 'password');
			$email		= Arr::get($_POST, 'email');

			$user = ORM::factory('user');
			if ($id != 0) $user->find($id);

			$user->surname	= $surname;
			$user->name		= $name;
			$user->username	= $login;
			$user->email	= $email;
			
			if ($password != '') $user->password = $password;// если пароль указан, то передать его значение переменной $user->password
			if ($user->check()) {
				$user->save();
				if ($id == 0) {
				$user->add('roles', ORM::factory('role', array('name' => 'login')));// если пользователя нет, то по умолчанию ему присвоить роль login
				//$user->add('roles', ORM::factory('role', array('name' => 'admin')));// если пользователя нет, то по умолчанию ему присвоить роль login
				}
				Session::instance()->set('alert', __('user.saved'));
				$this->request->redirect('users');
			} else {
				$err = $user->validate()->errors('error');
				$fl = __('user.saveerror') . '<ul style="margin-left: 30px; padding-left: 30px;">';
				foreach ($err as $k => $v) $fl .= "<li>$v</li>";
				$fl .= '</ul><br />';
			}
		} else {
			$user = Model::factory('User')->getUser($id);
			if (!$user) $this->request->redirect('users');
		}
		
		$this->template->content = View::factory('users/edit')
			->bind('alert', $fl)
			->bind('user', $user);
	}
	
	public function action_acl($id)
	{
		$user = Model::factory('user')->find($id);
		//Kohana::$log->add(Kohana::ERROR, 'model::factory("user")->find ('.$id.') is '.$user);
		if (!$user) $this->request->redirect('users');
		$acls = $user->getUserACL($id);
		//Kohana::$log->add(Kohana::ERROR, 'model::factory("user")->getUserACL ('.$id.') is '.$acls);
		//echo Kohana::Debug($acls);
		if ($_POST) {
			echo "<hr><pre>";
			print_r($_POST);
			echo "</pre><hr>";
			$data = array();
			$modes = array('o_view', 'o_edit', 'o_add', 'o_delete', 'p_edit', 'p_add', 'p_delete', 'c_edit', 'c_add', 'c_delete');
			$gids = Arr::get($_POST, 'gid');
			print_r($gids);
			foreach ($gids as $gid)
				$data[$gid] = array(
					'o_view'		=> 0,
					'o_edit'		=> 0,
					'o_add'			=> 0,
					'o_delete'		=> 0,
					'p_edit'		=> 0,
					'p_add'			=> 0,
					'p_delete'		=> 0,
					'c_edit'		=> 0,
					'c_add'			=> 0,
					'c_delete'		=> 0);
			foreach ($modes as $mode) {
				$gids = Arr::get($_POST, $mode, array());
				foreach ($gids as $gid)
					$data[$gid][$mode] = 1;
			}
			foreach ($data as $gid => $acl)
				Model::factory('user')->setUserACL($id, $gid, $acl);//запись вновь установленных прав

			Session::instance()->set('alert', __('acl.saved'));
			$this->request->redirect('users');
			
		}

		$fl = $this->session->get('alert');
		$this->session->delete('alert');
		$isadmin = $user->has('roles', ORM::factory('role', array('name' => 'admin')));
		
		$this->template->content = View::factory('users/acl')
			->bind('isadmin', $isadmin)
			->bind('alert', $fl)
			->bind('acls', $acls)
			->bind('user', $user);
		
	}
	
	public function action_delete($id)
	{
		$user = ORM::factory('user');
		$user->find($id)->delete();

		Session::instance()->set('alert', __('user.deleted'));
		$this->request->redirect('users');
	}
	
}
