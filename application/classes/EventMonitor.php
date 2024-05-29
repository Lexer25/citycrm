<?php defined('SYSPATH') OR die('No direct access allowed.');
/**
*29.05.2024
*Класс для формирования строки события в зависимости от кода события.

*/
class EventMonitor
{
	public $id_event;//id события, берется из базы  данных
    public $timestamp;//метка времени
    public $eventCode;//код события
    public $eventPlace;//где произшло событие. Тут или точка прохода, или база данных, или другие сущности могут быть...
    public $note;//запасные комментарии к событию
    public $name;//Название события


	public function __construct($id_event)
	{
        $this->id_event=$id_event;
        $sql='select e.ID_EVENT,e.ID_DB,e.ID_EVENTTYPE,e.ID_DEV,e.ID_PLAN,e.DATETIME,e.ID_CARD,e.NOTE,e.ID_VIDEO,e.ID_PEP,e.ESS1,e.ESS2,e.IDSOURCE,e.IDSERVERTS,e.TIME_STAMP,e.READDATA,e.ANALIT,e.EXEC_TIME, et.name , d.name as place from events e
		join eventtype et on et.id_eventtype=e.id_eventtype
		left join device d on d.id_dev=e.id_dev
		where e.id_event='.$this->id_event;
		//echo Debug::vars('24',$sql);exit;

        $query =Arr::flatten(DB::query(Database::SELECT,
            $sql)
            ->execute(Database::instance('fb'))
            ->as_array()
        );

        $this->eventCode=Arr::get($query,'ID_EVENTTYPE');
        $this->timestamp=Arr::get($query,'DATETIME');
        $this->eventPlace=Arr::get($query,'ID_DEV');
        $this->name=Arr::get($query,'NAME');


        switch($this->eventCode){
			
			
            case 17:
            case 18:
			case 19:
			
				//добавляю номер идентификатора, который был добавлен/удален
				$key=new Keyk(Arr::get($query,'ID_CARD'));
				$this->note=$key->id_card_on_screen;
				$this->eventPlace='System';
		
				break;
			
			case 46:
            case 50:
            case 65:
            $sql='select e.id_event, e.id_eventtype, e.id_dev, e.datetime from events e where e.id_event='.$this->id_event;

            $query =Arr::flatten(DB::query(Database::SELECT,
                $sql)
                ->execute(Database::instance('fb'))
                ->as_array()
            );
                break;
			default:
			break;
        }
       


	}
	
}
