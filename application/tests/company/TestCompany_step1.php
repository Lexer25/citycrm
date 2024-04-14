<?php
/*
8.01.2024 тестирование методов работы с организациями:
1. добавление дочерних организаций.
2. перемещение организаций
3. удаление организаций.


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
	public $id_org_for_test=1436;
 
	
	public function addNameOrg()
    {
        return [
            ['aaaaaaaaaaaaa', $this->id_org_for_test, 0],
            ['bbbbbbbbb', $this->id_org_for_test+10000, 3],// такого родителя нет
            ['ccccccccccc'.'01234567890123456789012345678901234567890123456789', $this->id_org_for_test, 2],
            ['ddddddddddddddddddd', $this->id_org_for_test, 0],
          
        ];
    }
	

	
	public function testDelChild()
	{
		$this->assertEquals(0, $this->DelChild($this->id_org_for_test));
	}
	
	
	
	 /**
     * @dataProvider addNameOrg
     */
	
	public function testAddOrg($nameOrg, $id_parent, $result)//проверка штатного добавления организаций в родителя 723 (это Артсек)
	{
		//$this->markTestSkipped('must be revisited.');
		
		$company=new Company();
			Log::instance()->add(Log::DEBUG, '65 '.$this->id_org_for_test);
					$company->name=$nameOrg;
					$company->id_parent=$id_parent;
					//echo Debug::vars('91', $nameOrg, $id_parent); exit;
					$_result=$company->addOrg();
					$this->assertEquals($result, $_result);

	
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
