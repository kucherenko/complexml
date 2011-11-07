<?php

/**
 * CompleXml_Validate_Email_TestCase is a test case for CompleXml_Validate_Email
 *
 * @author Andrey Kucherenko
 * @group Validate
 */
class CompleXml_Validate_EmailTest extends PHPUnit_Framework_TestCase {

    public static function validEmailProvider()
    {
        return array(
            array('aa@gmail.com'),
            array('aa.aa@glamur.info'),
            array('aaaa@tralliance.travel'),
            array('aaaa@dns.com.ua')
        );
    }

    public static function notValidEmailProvider()
    {
        return array(
            array('aa@aa.com@'),
            array(''),
            array('@search.travel'),
            array('qwqre@@zzz.travel'),
        );
    }


    /**
     *
     *  @dataProvider validEmailProvider
     */
    public function testRightEmails($mail)
    {
        $validator = new CompleXml_Validate_Email();
        $this->assertTrue($validator->isValid($mail));
    }
    /**
     *  @dataProvider notValidEmailProvider
     */
    public function testNotRightEmails($mail)
    {
        $validator = new CompleXml_Validate_Email();
        $this->assertFalse($validator->isValid($mail));
    }
}

