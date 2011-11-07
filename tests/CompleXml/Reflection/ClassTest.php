<?php
/**
 * TestCase for CompleXml_Reflection_Class_TestCase object
 *
 * @author Andrey Kucherenko
 * @author apk
 *
 * @test testmessage
 * @group Reflection
 */
class CompleXml_Reflection_ClassTest extends PHPUnit_Framework_TestCase {

    public function testArrayAnnotation()
    {
        $refClass = new CompleXml_Reflection_Class(__CLASS__);
        $this->assertEquals($refClass->getAnnotation('author'), array('Andrey Kucherenko', 'apk') );
    }

    public function testStringAnnotation()
    {
        $refClass = new CompleXml_Reflection_Class(__CLASS__);
        $this->assertEquals($refClass->getAnnotation('test'), 'testmessage' );
    }

    public function testWrongAnnotation()
    {
        $refClass = new CompleXml_Reflection_Class(__CLASS__);
        $this->assertNull($refClass->getAnnotation('qwerty'));
    }
}
