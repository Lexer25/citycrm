<?php
 
Class convertFormat extends Unittest_TestCase
{
    function providerStrLen()
    {
        // входящие данные: строка для поиска, формат базы данных, формат входящий, ожидаемый результат
		//0 - HEX8
		//1 - 001A
		//2-DEC
		return array(
            array('123', 0, 0, '123'),//считыватель настроен на HEX, база HEX
            array('8271360', 0, 2, '007E3600'),//считыватель настроен на DEC, база HEX
            array('4422639',1,2, 'F7DEC2001A'),
          
            
        );
    }
 
    /**
     * @dataProvider providerStrLen
     */
    function testgetName($pattern, $bf, $rf, $out)
    {
        $my = new Keyk();
		$my->convertFormat($pattern, $bf, $rf);
		$this->assertEquals($out, $my->id_card); 
        
    }





}