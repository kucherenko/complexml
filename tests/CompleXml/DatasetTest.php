<?php
/**
 * Test class for CompleXml_Dataset.
 * @group Dataset
 */
class CompleXml_DatasetTest extends PHPUnit_Framework_TestCase
{

    /**
     * @var CompleXml_Dataset
     */
    protected $o;
    
    protected $source = array(
        'int'=>123,
        'string'=>'string',
        'email'=> 'test@test.com',
        'domain'=> 'test.com',
        'boolean'=> true
    );
    
    protected function setUp()
    {
        $this->o = new CompleXml_Dataset($this->source);
    }
    
    public function testSetValue()
    {
        $this->o->setValue('test', 'test');
        $this->o->test1 = 'test1';
        $this->assertEquals($this->o->getValue('test'), 'test');
        $this->assertEquals($this->o->getValue('test1'), 'test1');
        $this->assertEquals($this->o->test, 'test');
        $this->assertEquals($this->o->test1, 'test1');
    }
    
    public function testGetString()
    {
        $default = '123123123';
        $this->assertEquals($this->o->getString('string'), $this->source['string']);
        $this->assertNull($this->o->getString('string', null, strlen($this->source['string'])+1 ));
        $this->assertNull($this->o->getString('string', null, null, strlen($this->source['string'])-1 ));
        $this->assertEquals($this->o->getString('zzz', $default), $default);
        $this->assertEquals($this->o->getString('string', $default, strlen($this->source['string'])+1), $default);
        $default = '123';
        $this->assertEquals($this->o->getString('string', $default, null, strlen($this->source['string'])-1), $default);
    }

    public function testGetInt()
    {
        $this->assertEquals($this->o->getInt('int'), $this->source['int']);
        $this->assertEquals($this->o->getInt('int', null, $this->source['int']-1, $this->source['int']+1), $this->source['int'] );
        $this->assertNull($this->o->getInt('int', null, $this->source['int']+1, $this->source['int']+2));
        $this->assertNull($this->o->getInt('int', null, $this->source['int']-2, $this->source['int']-1));
        $this->assertNull($this->o->getInt('int', 'null', $this->source['int']-2, $this->source['int']-1));
    }

    public function testGetEmail()
    {
        $this->assertEquals($this->o->getEmail('email'), $this->source['email']);
        $this->assertNull($this->o->getEmail('string'));
    }

    public function testGetDomainName()
    {
        $this->assertEquals($this->o->getDomainName('domain'), $this->source['domain']);
        $this->assertNull($this->o->getDomainName('string'));
    }

    public function testGetBool()
    {
        $this->assertEquals($this->o->getBool('boolean'), $this->source['boolean']);
        $this->assertFalse($this->o->getBool('qwerty'));
        $this->assertFalse($this->o->getBool('string'));
    }

    public function testGetAllValues()
    {
        $this->assertEquals($this->o->getAllValues(), $this->source);
    }

    public function testIsset()
    {
        $this->assertFalse(isset($this->o->qwe));
        $this->assertTrue(isset($this->o->string));
    }

    public function testCount()
    {
        $this->assertEquals(count($this->o), count($this->source));
    }

    public function testForeach()
    {
        foreach ($this->o as $key => $value) {
            $this->assertEquals($value, $this->source[$key]);
        }
    }

    public function testUnset()
    {
        unset($this->o->string);
        $this->assertNull($this->o->string);
    }

    public function testSetValues()
    {
        $array = array('1'=>1,'2'=>2, '3'=>3);
        $this->o->setValues($array, CompleXml_Dataset::RENEW_SOURCE);
        $this->assertEquals($this->o->getAllValues(), $array);
        $array = array('1'=>3);
        $this->o->setValues($array, CompleXml_Dataset::UPDATE_SOURCE);
        $this->assertEquals($this->o->getAllValues(), array('1'=>3,'2'=>2, '3'=>3));
    }
}
