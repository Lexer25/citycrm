<?php
/*
22.04.2024 тестирование класса card:



*/
 
Class TestCompany_step1 extends Unittest_TestCase
{
    
// C:\xampp\htdocs\citycrm\application\tests>c:\xampp\php\phpunit.bat GuestTest.php		

//https://habr.com/ru/articles/56289/

//https://phpunit-documentation-russian.readthedocs.io/ru/latest/organizing-tests.html описание на русском языке

//https://habr.com/ru/companies/vk/articles/549698/#12
/*

 в ходе теста в организацию 273 (Артсек) будут добавлены 3 организации
 ошибки:
	0 - все успешно,
	2 - ошибка валидации данных, должно быть описание.
	3 - ошибка при работе с базой данных

*/
	private $delAll=1;//0 - не удалять результат тестирования, 1 - удалять результаты тестирования
	
	/*
	22.04.2025 Добавляю  в корень организацию для тестирования.
	*/
	public function testAddTestOrg()
	{
		$company=new Company();
		$company->name='TEST';
		$company->id_parent=1;
		$_result=$company->addOrg();
		
		if($_result == 0)
		{
			Kohana::$config->_write_config('test', 'idOrgTest',$company->id_org);
			
		} else {
			Kohana::$config->_write_config('test', 'idOrgTest',$_result);
			Kohana::$config->_write_config('test', 'errMess',$company->errors);
		}
		
		$this->assertEquals(0, $_result);
		
	}
	
	/*
	22.04.2024 массив для отработки вставок организаций.
	*/
	public function addNameOrg()
    {
		$id_org=Kohana::$config->load('test')->get('idOrgTest')  ;     
	   return [
            ['aaaaaaaaaaaaa', $id_org, 0],//ожидаю ответ 0 - все правильно.
            ['bbbbbbbbb', $id_org+1000000, 3],// ожидаю ответ ошибки базы данных 3, т.к. такого родителя нет
            ['ccccccccccc'.'01234567890123456789012345678901234567890123456789', $id_org, 2],//ожидаю ответ ошибки валидации, т.к. длина организации превышает 50 символов
            ['ddddddddddddddddddd', $id_org, 0],//ожидаю ответ 0 - все правильно.
          
        ];
    }
	
	
	
	
	
	 /**
     * @dataProvider addNameOrg
     */
	
	public function testAddOrg($nameOrg, $id_parent, $result)//проверка штатного добавления организаций в родителя
	{
		//$this->markTestSkipped('must be revisited.');
		
		$company=new Company();
			//Log::instance()->add(Log::DEBUG, '65 '.$this->id_org_for_test);
					$company->name=$nameOrg;
					$company->id_parent=$id_parent;
					$_result=$company->addOrg();
					$this->assertEquals($result, $_result);

	
	}
	
	

	public function testDelChild()
	{
		if($this->delAll) {
			
			$company=new Company(Kohana::$config->load('test')->get('idOrgTest'));
			
			$this->assertEquals(0, $this->DelChild(Kohana::$config->load('test')->get('idOrgTest')));
			$this->assertEquals(1, $company->delOrgId(Kohana::$config->load('test')->get('idOrgTest')));
			
		}
	}
	
	

		public function DelChild($id_org)//удаление всех дочек
	{
			
		$sql='delete from organization o
				where o.id_parent='.$id_org;
		try {
				DB::query(Database::DELETE,$sql)	
				->execute(Database::instance('fb'));
				Log::instance()->add(Log::DEBUG, '66 Deleted all');
				return 0;	
				
				
			} catch (Exception $e) {
				Log::instance()->add(Log::DEBUG, $e->getMessage());
				
				return 3;
			}
				

	
	}
}

