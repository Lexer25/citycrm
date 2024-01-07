<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Stats extends Controller_Template
{
	public $template = 'template';
	private $listsize;
	private $session;
	
	public function before()
	{
		parent::before();

		//if (!Auth::instance()->logged_in('admin') || !Auth::instance()->logged_in('owner') || !Auth::instance()->logged_in('user')) $this->request->redirect('/');
		
		$this->session = Session::instance();
		$name = $this->session->get('username', false);
		
		I18n::$lang = $this->session->get('language', 'en-us');
		$this->listsize = $this->session->get('listsize', 10);
	}
	public function action_index($filter = null)
	{
		$this->request->redirect('stats/about');
	}
	
	public function action_save($filter = null)
	{
		$report_common='';
		$report_device='';
		$report_device='';
		$report_que='';
		$report_sys='';

		$about_sys=Model::Factory('stats');
		
		// статистка системы
		$list = $about_sys->getList();
		$report = View::factory('stat/common_pdf')
			->bind('list', $list)
			->bind('alert', $fl)
			;
		$report_common=$report->render();

		//Статистика по очереди
				
		$list_load = $about_sys->que_attempt_count(1);//выборка статистическая для загрузки карт
	$list_delete = $about_sys->que_attempt_count(2);//выборка статистическая для удаления карт
	$que_mess = $about_sys->que_mess();//выборка с сообщениями об ошибке
	
	$fl='';
	$report = View::factory('stat/que_stat_pdf')
			->bind('que_mess', $que_mess)
			->bind('list_load', $list_load)
			->bind('list_delete', $list_delete)
			->bind('alert', $fl);
		$report_que=$report->render();
			
		// статистка по контроллерам
		$list = $about_sys->controller();
		$report = View::factory('stat/device_pdf')
			->bind('list', $list)
			->bind('alert', $fl)
			;
		$report_device=$report->render();		
		
		// статистка по событиям
		$list = $about_sys->events();
		$report = View::factory('stat/events_pdf')
			->bind('list', $list)
			->bind('alert', $fl)
			;
		$report_events=$report->render();
		
		$report_sys .=$report_common;
		$report_sys .=$report_que;
		$report_sys .=$report_device.'<pagebreak />';
		//$report_sys .=$report_events;
		$about_sys->makereport($report_sys);
		//$save_in_file=$about_sys->save($report_sys);
		$this->request->redirect('stats/about');
	}
		 
		 
	public function action_about($filter = null)
	{
	
	$about_sys=Model::Factory('stats');
	$list = $about_sys->getList();
	$fl='';
	$application_about=$about_sys->getApplicationInfo();
	
	$this->template->content = View::factory('stat/common')
			->bind('list', $list)
			->bind('que_mess', $que_mess)
			->bind('alert', $fl)
			//->bind('application_about', $application_about)
			;
	}
	
	public function action_device($filter = null)
	{
	$about_sys=Model::Factory('stats');
	$list = $about_sys->controller();
	$fl='';
	$this->template->content = View::factory('stat/device')
			->bind('list', $list)
			->bind('alert', $fl);
	}
	
	public function action_events($filter = null)
	{
	$about_sys=Model::Factory('stats');
	$list = $about_sys->events();
	$fl='';
	$this->template->content = View::factory('stat/events')
			->bind('list', $list)
			->bind('alert', $fl);
	}
	
	public function action_queue_message($filter = null)
	{
	$about_sys=Model::Factory('stats');
	
	$list_load = $about_sys->que_attempt_count(1);//выборка статистическая для загрузки карт
	$list_delete = $about_sys->que_attempt_count(2);//выборка статистическая для удаления карт
	$que_mess = $about_sys->que_mess();//выборка с сообщениями об ошибке
	
	$fl='';
	$this->template->content = View::factory('stat/que_stat')
			->bind('que_mess', $que_mess)
			->bind('list_load', $list_load)
			->bind('list_delete', $list_delete)
			->bind('alert', $fl);
	}
	
	
	
}