<?php
/*
8.01.2024 тестирование методов работы с организациями:
1. добавление дочерних организаций.
2. перемещение организаций
3. удаление организаций.


*/
 
Class TestCompany_step2 extends Unittest_TestCase
{
    
// C:\xampp\htdocs\citycrm\application\tests>c:\xampp\php\phpunit.bat GuestTest.php		

//https://habr.com/ru/articles/56289/

//https://phpunit-documentation-russian.readthedocs.io/ru/latest/organizing-tests.html описание на русском языке

//https://habr.com/ru/companies/vk/articles/549698/#12
/*

 в ходе теста в организацию 855 будут добавлены 4 организации

*/

 
 public function addNameOrg()
    {
        return [
            ['Орг 1', 864],
            ['Орг 2', 864],
            ['Орг 3', 864],
            ['Орг 4', 864],
           
          
        ];
    }


	
	 /**
     * @dataProvider addNameOrg
     */
	
	public function testAddOrg($nameOrg, $id_parent)//проверка добавления организаций в родителя 723 (это Артсек)
	{
		//$this->markTestSkipped('must be revisited.');
		
		$company=new Company();
			
					$company->name=$nameOrg;
					$company->id_parent=$id_parent;
					$_result=$company->addOrg();
					$this->assertEquals(0, $_result);

	
	}

}