<?php 
/**
 * @group Loader
 */
class CompleXml_LoaderTest extends PHPUnit_Framework_TestCase {

    public function testAutoloadLoadedClass()
    {
        $this->assertFalse(CompleXml_Loader::autoload('CompleXml_LoaderTest'));
    }

    public function testAutoloadLoadNonExistsClass()
    {
        $this->assertFalse(CompleXml_Loader::autoload('Not_Exists_Class'));
    }

    public function testGetLibraryPath()
    {
        $reflection = new ReflectionClass('CompleXml_Loader');
        $path = $reflection->getFileName();
        $this->assertEquals(CompleXml_Loader::getLibraryPath(), dirname(dirname($path)));
        $this->assertEquals(CompleXml_Loader::getLibraryPath(), dirname(dirname($path)));
    }
}
