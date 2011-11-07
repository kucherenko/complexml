<?php

/**
 * Test class for CompleXml_Config.
 * Generated by PHPUnit on 2010-02-26 at 12:38:04.
 * @group Config
 */
class CompleXml_ConfigTest extends PHPUnit_Framework_TestCase
{
    
    public function testSet()
    {
        CompleXml_Config::set('test', 'test');
        $this->assertEquals('test', CompleXml_Config::get('test'));
    }
    
    public function testSetArray()
    {
        $array = array('11'=>'11', '112'=>'23');

        CompleXml_Config::setArray($array);
        $this->assertEquals($array['11'], CompleXml_Config::get('11'));
        $this->assertEquals($array['112'], CompleXml_Config::get('112'));

        $array1 = array(1, 2, 3);
        $array2 = array(4, 5, 6);

        CompleXml_Config::set('test-array', $array1);
        CompleXml_Config::set('test-array', $array2);

        $this->assertEquals(array_merge($array1, $array2), CompleXml_Config::get('test-array'));
    }

    /**
     * @expectedException CompleXml_Config_Exception
     */
    public function testGet()
    {
        CompleXml_Config::get('not-exists-name');
    }

    /**
     * @expectedException CompleXml_Config_Exception
     */
    public function testCreateObject()
    {
        $c = new CompleXml_Config();
    }
    
    public function testRead()
    {
        $filename = dirname(__FILE__) . '/Config/_files/test.ini';
        CompleXml_Config::read($filename, new CompleXml_Config_Reader_Ini());

        $ini = parse_ini_file($filename, true);
        foreach ($ini as $section=>$value) {
            $this->assertEquals($value, CompleXml_Config::get($section));
        }
    }
    /**
     * @expectedException CompleXml_Config_Exception
     */
    public function testCantRead()
    {
        $filename = dirname(__FILE__) . '/Config/_files/test1.ini';
        CompleXml_Config::read($filename, new CompleXml_Config_Reader_Ini());
    }
    
    public function testReadComponentSettings()
    {
        $Config_dir = CompleXml_Loader::getLibraryPath() . DIRECTORY_SEPARATOR . 'CompleXmlData';
        $application_config = @include_once $Config_dir . DIRECTORY_SEPARATOR . 'Application.php';

        $this->assertEquals($application_config, CompleXml_Config::readComponentSettings('Application'));
        $this->assertEquals($application_config, CompleXml_Config::readComponentSettings('CompleXml_Application'));
    }
    
    public function testSetGetSettings()
    {
        CompleXml_Config::setSettings('Application', array('test'=>'test'));
        $this->assertEquals('test', CompleXml_Config::getSettings('CompleXml_Application', 'test'));
        CompleXml_Config::setSettings('Config', array('test'=>'test'));
        $this->assertEquals('test', CompleXml_Config::getSettings('CompleXml_Config', 'test'));
        $this->assertEquals('test', CompleXml_Config::getSettings('Config', 'test'));

        $this->assertEquals(null, CompleXml_Config::getSettings('Acl', 'test'));
    }
}