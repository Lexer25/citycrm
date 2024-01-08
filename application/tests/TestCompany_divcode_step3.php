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

*/

 
 public function addNameOrg()
    {
        return [
           ['Бухгалтерия', 723, 'div_art_buh'],
            ['Отдел разработок', 723, 'div_art_res'],
            ['Производство монтажных работ', 723, 'div_art_pmr'],
            ['Маркетинг', 723, 'div_art_mark'],
          
        ];
    }


	
	 /**
     * @dataProvider addNameOrg
     */
	
	public function testAddOrg($nameOrg, $id_parent, $divcode)//проверка добавления организаций в родителя 723 (это Артсек)
	{
		//$this->markTestSkipped('must be revisited.');
		
		$company=new Company();
			
					$company->name=$nameOrg;
					$company->id_parent=$id_parent;
					$company->divcode=$divcode;
					$_result=$company->addOrgWithDivcodeInParentId();
					$this->assertEquals(0, $_result);

	
	}

}