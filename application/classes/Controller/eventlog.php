<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Eventlog extends Controller_Template
{
	public $template = 'template';
	private $listsize;
	private $session;
	
	public function before()
	{
		parent::before();
		if (!Auth::instance()->logged_in()) $this->request->redirect('/');

		$this->session = Session::instance();
		I18n::$lang = $this->session->get('language', 'en-us');
		$this->listsize = $this->session->get('listsize', 10);
		//$config = Kohana::$config->load('config_newcrm');//подключил файл конфигурации.
	}
	
	public function action_search()
	{
		$pattern = Arr::get($_POST, 'q', null);
		if ($pattern) {
			$this->session->set('search_card', $pattern);
		} else {
			$pattern = $this->session->get('search_card', '');
		}
		$this->action_index($pattern);
	}
	
	public function action_index($filter = null)
	{
		$isAdmin = Auth::instance()->logged_in('admin');
		
		$eventlist = Model::factory('Eventlog');//вызов модели Eventlog. Запрос данных - ниже.
		$hour = Arr::get($_POST, 'hour_for_list', 24); // получил количество часов для запроса.
		$q=$eventlist->getCountEvent($hour);
		$alert=__('eventlog.count_event_alarm').$q;

        $pagination = new Pagination(array(
			'uri_segment' => 2,
			'total_items' => $q,
			'style' => 'classic',
			'items_per_page' => $this->listsize,
			'auto_hide' => true,
		));

        $devices  = $eventlist->getDeviceList();

        if (isset($_POST)) {
            if (isset($_POST['filter'])) {
                $filters = $_POST['filter'];
            } else {
                foreach ($devices as $device) {
                    $filters['device'][] = $device['ID_DEV'];
                }
            }
        }
		
		$event_list = $eventlist->getEventList(Arr::get($_GET, 'page', 1), $this->listsize, $hour, $filters); 	//получаю список событий
		$title='eventlog.full';
		$this->template->content = View::factory('eventlog/eventlist')
			->bind('eventlog', $event_list)
            ->bind('devices', $devices)
			->bind('hour', $hour)
			->bind('alert', $alert)
            ->bind('filters', $filters)
			->bind('title', $title)
			->bind('pagination', $pagination);
	}
	public function action_alarm($filter = null)
	{
        $isAdmin   = Auth::instance()
                         ->logged_in('admin'); //получил признак того, что пользователь - Админ
        $eventlist = Model::factory('Eventlog'); //вызов модели Eventlog. Запрос данных - ниже.
        $hour      = Arr::get($_POST, 'hour_for_list', 24); //получил "глубину" журнала из POST. если пусто, то беру за последние 24 часа.
		//$q = $eventlist->getCountAdminAlarm($hour);       // считаю количество записей с ошибками

        $devices  = $eventlist->getDeviceList();

        if (isset($_POST)) {
            if (isset($_POST['filter'])) {
                $filters = $_POST['filter'];
            } else {
                foreach ($devices as $device) {
                    $filters['device'][] = $device['ID_DEV'];
                }
            }
        }

        $event_list = $eventlist->getEventAlarmList(Arr::get($_GET, 'page', 1), $this->listsize, $hour, $filters);// получаю журнал для вывода на экран

		$pagination = new Pagination(array(
			'uri_segment' => 2,
			'total_items' => $event_list['alarm_event_count'],
			'style' => 'classic',
			'items_per_page' => $this->listsize,
			'auto_hide' => true,
		));
        $alert = __('eventlog.count_event_alarm') . $event_list['alarm_event_count'];
		$title='eventlog.alarmlog';
		$this->template->content = View::factory('eventlog/eventlist')
			->bind('eventlog', $event_list['alarm_event_list'])
			->bind('devices', $devices)
			->bind('hour', $hour)
			->bind('alert',$alert )
			->bind('filters', $filters)
			->bind('title', $title)
			->bind('pagination', $pagination);
	}
}
