<?php
 
Class DoorTest extends Unittest_TestCase
{
    function providerStrLen()
    {
        return array(
            array('Дверь №3-0', 111),
            array('Дверь №9-0', 129),
            array('Дверь №9-0', 0),
        );
    }
 
    /**
     * @dataProvider providerStrLen
     */
    function testgetName($string, $length)
    {
        $my = new Door($length);
		$this->assertEquals($string, iconv('windows-1251','UTF-8', $my->getName())); 
        
    }





}