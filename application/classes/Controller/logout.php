<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Logout extends Controller {

	public function action_index()
	{
		Auth::instance()->logout();
		Session::instance()->delete('username');
		Session::instance()->delete('viewDeletePeopleOnly');
		$this->redirect('/');
	}

}

