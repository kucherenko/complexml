<?php

/**
 * CompleXml_Validate_Ip_TestCase is a test case for CompleXml_Validate_Ip
 *
 * @author Andrey Kucherenko
 * @group Validate
 */
class CompleXml_Validate_BooleanTest extends PHPUnit_Framework_TestCase {

    public static function validProvider()
    {
        return array(
            array(1),
            array(true),
            array('true'),
            array('TRUE'),
        );
    }

    public static function notValidProvider()
    {
        return array(
            array('qwretgher'),
            array(''),
            array('10..022.22.45'),
            array('256.0.0.1')
        );
    }


    /**
     *
     *  @dataProvider validProvider
     */
    public function testRightBool($value)
    {
        $validator = new CompleXml_Validate_Boolean();
        $this->assertTrue($validator->isValid($value));
    }
    /**
     *  @dataProvider notValidProvider
     */
    public function testNotRightIp($value)
    {
        $validator = new CompleXml_Validate_Boolean();
        $this->assertFalse($validator->isValid($value));
    }
}

