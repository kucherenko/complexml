<?php

/**
 * CompleXml_Validate_String_TestCase is a test case for CompleXml_Validate_Email
 *
 * @author Andrey Kucherenko
 * @group Validate
 */
class CompleXml_Validate_StringTest extends PHPUnit_Framework_TestCase
{
    
    public function testIsValidString()
    {
        $validate = new CompleXml_Validate_String();
        $this->assertTrue($validate->isValid('$int'));
        $this->assertNull($validate->getMaxLength());
        $this->assertNull($validate->getMinLength());
    }
    
    public function testIsNotValidString()
    {
        $validate = new CompleXml_Validate_String();
        $this->assertFalse($validate->isValid(true));
        $this->assertFalse($validate->isValid(null));
    }

    /**
     * @expectedException CompleXml_Validate_Exception
     */
    public function testSetMaxLength()
    {
        $validate = new CompleXml_Validate_String();
        $validate->setMinLength(100);
        $validate->setMaxLength(10);
    }

    /**
     * @expectedException CompleXml_Validate_Exception
     */
    public function testSetMinLength()
    {
        $validate = new CompleXml_Validate_String();
        $validate->setMaxLength(10);
        $validate->setMinLength(100);
    }
    
    /**
     * @expectedException CompleXml_Validate_Exception
     */
    public function testSetMinNegativelyLength()
    {
        $validate = new CompleXml_Validate_String();
        $validate->setMinLength(- 100);
    }
    
    public function testSetMinLengthAndIsValid()
    {
        $validate = new CompleXml_Validate_String();
        $validate->setMinLength(5);
        $this->assertFalse($validate->isValid('qw'));
        $this->assertEquals($validate->getMinLength(), 5);
    }
    
    public function testSetMaxLengthAndIsValid()
    {
        $validate = new CompleXml_Validate_String();
        $validate->setMaxLength(5);
        $this->assertFalse($validate->isValid('qwerty'));
        $this->assertEquals($validate->getMaxLength(), 5);
    }
    
    public function testSetMinLengthNull()
    {
        $validate = new CompleXml_Validate_String();
        $validate->setMinLength(5);
        $this->assertFalse($validate->isValid('qw'));
        $validate->setMinLength(null);
        $this->assertTrue($validate->isValid('qw'));
    }
    
    public function testSetMaxLengthNull()
    {
        $validate = new CompleXml_Validate_String();
        $validate->setMaxLength(5);
        $this->assertFalse($validate->isValid('qwerty'));
        $validate->setMaxLength(null);
        $this->assertTrue($validate->isValid('qwerty'));
    }

    /**
     * @expectedException CompleXml_Validate_Exception
     */
    public function testSetMaxNegativelyLength()
    {
        $validate = new CompleXml_Validate_String();
        $validate->setMaxLength(- 1);
    }
    
    public function testRightLength()
    {
        $validate = new CompleXml_Validate_String();
        $validate->setMaxLength(10);
        $validate->setMinLength(0);
        $this->assertTrue($validate->isValid('1234567890'));
        $this->assertTrue($validate->isValid('йцукенгшщз'));
    }
    
    public function testNotRightIntervalInt()
    {
        $validate = new CompleXml_Validate_String();
        $validate->setMinLength(0);
        $validate->setMaxLength(3);
        $this->assertFalse($validate->isValid('1234'));
        $this->assertFalse($validate->isValid('цуке'));
    }
}

