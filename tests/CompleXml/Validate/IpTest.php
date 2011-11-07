<?php

/**
 * CompleXml_Validate_Ip_TestCase is a test case for CompleXml_Validate_Ip
 *
 * @author Andrey Kucherenko
 * @group Validate
 */
class CompleXml_Validate_IpTest extends PHPUnit_Framework_TestCase {

    public static function validProvider()
    {
        return array(
            array('10.0.0.1'),
            array('123.123.123.123'),
            array('255.255.255.255')
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
    public function testRightIp($ip)
    {
        $validator = new CompleXml_Validate_Ip();
        $this->assertTrue($validator->isValid($ip));
    }
    /**
     *  @dataProvider notValidProvider
     */
    public function testNotRightIp($ip)
    {
        $validator = new CompleXml_Validate_Ip();
        $this->assertFalse($validator->isValid($ip));
    }
}

