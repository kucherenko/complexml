<?php
/**
 * CompleXml_Validate_DomainName_TestCase is a test case for CompleXml_Validate_DomainName
 *
 * @author Andrey Kucherenko
 * @group Validate
 */
class CompleXml_Validate_DomainNameTest extends PHPUnit_Framework_TestCase {

    public static function validProvider()
    {
        return array(
            array('aa.co.ua'),
            array('aasfsd.travel'),
            array('aa.ru'),
            array('local.com.ua')
        );
    }

    public static function notValidProvider()
    {
        return array(
            array('}.com.ua'),
            array(''),
            array('qwwe#.com'),
            array('256.0.0.1')
        );
    }


    /**
     *
     *  @dataProvider validProvider
     */
    public function testRightDomainName($domainName)
    {
        $validator = new CompleXml_Validate_DomainName();
        $this->assertTrue($validator->isValid($domainName));
    }
    /**
     *  @dataProvider notValidProvider
     */
    public function testNotRightDomainName($domainName)
    {
        $validator = new CompleXml_Validate_DomainName();
        $this->assertFalse($validator->isValid($domainName));
    }
}


