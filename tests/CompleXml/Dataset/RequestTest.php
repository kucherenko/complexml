<?php
/**
 * Test class for CompleXml_Dataset_Request.
 * @group Dataset
 */
class CompleXml_Dataset_RequestTest extends PHPUnit_Framework_TestCase
{
    
    public function testRequestCreate()
    {
        $request = new CompleXml_Dataset_Request();

        $this->assertEquals($request->Post->getAllValues(), array());
        $this->assertEquals($request->Get->getAllValues(), array());
    }
    
    public function testIsPost()
    {
        $request = new CompleXml_Dataset_Request();
        $this->assertFalse($request->isPost());
        $_SERVER['REQUEST_METHOD'] = 'post';
        $this->assertTrue($request->isPost());
    }

    public function testIsGet()
    {
        $request = new CompleXml_Dataset_Request();
        $this->assertFalse($request->isGet());
        $_SERVER['REQUEST_METHOD'] = 'get';
        $this->assertTrue($request->isGet());
    }

    public function testIsAjax()
    {
        $request = new CompleXml_Dataset_Request();
        $this->assertFalse($request->isAjax());
        $_SERVER[ 'HTTP_X_REQUESTED_WITH' ] = 'XMLHttpRequest';
        $this->assertTrue($request->isAjax());
    }
}
