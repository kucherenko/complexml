<?php

/**
 * CompleXml_Validate_Int_TestCase is a test case for CompleXml_Validate_Int
 *
 * @author Andrey Kucherenko
 * @group Validate
 */
class CompleXml_Validate_IntTest extends PHPUnit_Framework_TestCase {

    public static function trueIntProvider()
    {
        return array(
            array(1),
            array(2),
            array(0),
            array(5),
            array(10000),
            array(9994),
            array(-100),
            array(456)
        );
    }

    public static function falseIntProvider()
    {
        return array(
            array('1a'),
            array('a2'),
            array(5.5),
            array('1000022a'),
            array('+-100'),
            array('aaa')
        );
    }


    /**
     * @dataProvider trueIntProvider
     */
    public function testIsValidInt($int)
    {
        $validate = new CompleXml_Validate_Int();
        $this->assertTrue($validate->isValid($int));

    }

    /**
     * @dataProvider falseIntProvider
     */
    public function testIsNotValidInt($int)
    {
        $validate = new CompleXml_Validate_Int();
        $this->assertFalse($validate->isValid($int));

    }

    /**
     * @expectedException CompleXml_Validate_Exception
     */
    public function testSetMaxInt()
    {
        $validate = new CompleXml_Validate_Int();
        $validate->setMin(100);
        $validate->setMax(10);
    }

    /**
     * @expectedException CompleXml_Validate_Exception
     */
    public function testSetMinInt()
    {
        $validate = new CompleXml_Validate_Int();
        $validate->setMax(10);
        $validate->setMin(100);
    }

    /**
     * @dataProvider trueIntProvider
     */
    public function testRightIntervalInt($int)
    {
        $validate = new CompleXml_Validate_Int();
        $validate->setMax(10000);
        $validate->setMin(-100);
        $this->assertTrue($validate->isValid($int));
    }

    /**
     * @dataProvider trueIntProvider
     */
    public function testNotRightIntervalInt($int)
    {
        $validate = new CompleXml_Validate_Int();
        $validate->setMax(9999);
        $validate->setMin(9995);
        $this->assertFalse($validate->isValid($int));
    }
}

