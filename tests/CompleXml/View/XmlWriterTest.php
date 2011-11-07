<?php

/**
 * CompleXml_View_XmlWriter_TestCase is a test case for CompleXml_View_XmlWriter
 *
 * @author Andrey Kucherenko
 * @group View
 */
class CompleXml_View_XmlWriterTest extends PHPUnit_Framework_TestCase
{

    protected $_start_document;
    
    protected function setUp()
    {
        $this->_start_document = '<?xml version="1.0" encoding="UTF-8"?>' . PHP_EOL;
    }
    
    public function testWriteString()
    {
        $xmlWriter = new CompleXml_View_XmlWriter();
        $xmlWriter->writeElement('test', 'test');
        $this->assertEquals($xmlWriter->outputMemory(), $this->_start_document . '<test>test</test>');
    }
    
    public function testWriteNsString()
    {
        $xmlWriter = new CompleXml_View_XmlWriter();
        $xmlWriter->writeElementNs('qq', 'test', 'test', 'test');
        $this->assertEquals($xmlWriter->outputMemory(), $this->_start_document . '<qq:test xmlns:qq="test">test</qq:test>');

        $xmlWriter = new CompleXml_View_XmlWriter();
        $xmlWriter->writeElementNs('qq', 'test', 'test', array('q'=>'q'), array('id'=>'1'));
        $this->assertEquals($xmlWriter->outputMemory(), $this->_start_document . '<qq:test id="1" xmlns:qq="test"><q>q</q></qq:test>');
        $xmlWriter = new CompleXml_View_XmlWriter();
        $xmlWriter->writeElementNs('qq', 'test', 'test', array('q'=>'q', '__attributes'=>array('id'=>'1')));
        $this->assertEquals($xmlWriter->outputMemory(), $this->_start_document . '<qq:test id="1" xmlns:qq="test"><q>q</q></qq:test>');
    }

    public function testDefaultAttribute()
    {
        $xmlWriter = new CompleXml_View_XmlWriter();
        $xmlWriter->setDefaultAttributeName('q');
        $this->assertEquals($xmlWriter->getDefaultAttributeName(), 'q');
    }

    public function testWriteArray()
    {
        $xmlWriter = new CompleXml_View_XmlWriter();
        $xmlWriter->setDefaultNodeName('a');
        $test = array('test');
        $xmlWriter->writeElement('test', $test);
        $result = $this->_start_document . '<test><a a_id="0">test</a></test>';
        $this->assertEquals($xmlWriter->outputMemory(), $result);
    }
    
    public function testWriteAssocArray()
    {
        $xmlWriter = new CompleXml_View_XmlWriter();
        $test = array('test'=>'test');
        $xmlWriter->writeElement('test', $test);
        $result = $this->_start_document . '<test><test>test</test></test>';
        $this->assertEquals($xmlWriter->outputMemory(), $result);
    }
    
    public function testWriteBigArray()
    {
        $array = array("id"=> "1", "name"=>"Andrey", "is_active"=>"1",
            "created_at"=>"2009-01-21 16:38:15",
            "updated_at"=>"2009-01-21 16:38:15",
            "username"=>"test",
            "password"=>"test",
            "uid"=>"49773357c509c", "aa");
        $xmlWriter = new CompleXml_View_XmlWriter();
        $xmlWriter->writeElement('test', $array);
        $result = $this->_start_document . '<test><id>1</id><name>Andrey</name><is_active>1</is_active>' .
                '<created_at>2009-01-21 16:38:15</created_at>' .
                '<updated_at>2009-01-21 16:38:15</updated_at>' .
                '<username>test</username>' .
                '<password>test</password>' .
                '<uid>49773357c509c</uid><row row_id="8">aa</row>' .
                '</test>';
        $this->assertEquals($xmlWriter->outputMemory(), $result);
    }
    
    public function testSetWrite()
    {
        $xmlWriter = new CompleXml_View_XmlWriter();
        $xmlWriter->test = 'test';
        $this->assertEquals($xmlWriter->outputMemory(), $this->_start_document . '<test>test</test>');
    }
    
    public function testWriteAttributes()
    {
        $xmlWriter = new CompleXml_View_XmlWriter();
        $xmlWriter->startElement('test');
        $xmlWriter->writeAttribute('id', '1');
        $xmlWriter->endElement();
        $this->assertEquals($xmlWriter->outputMemory(), $this->_start_document . '<test id="1"/>');
    }
    
    public function testWriteElementWithAttributes()
    {
        $xmlWriter = new CompleXml_View_XmlWriter();
        $xmlWriter->writeElement('test', 'test', array('id'=>1));
        $this->assertEquals($xmlWriter->outputMemory(), $this->_start_document . '<test id="1">test</test>');
    }
    
    public function testWriteRawXml()
    {
        $xmlWriter = new CompleXml_View_XmlWriter();
        $string = "<br>&<ul><li>1<li>2<li>3</ul>";
        $xmlWriter->startElement('test');
        $xmlWriter->writeRawXml($string);
        $xmlWriter->endElement();
        $this->assertEquals($xmlWriter->outputMemory(), $this->_start_document . '<test><br/>&amp;<ul><li>1</li><li>2</li><li>3</li></ul></test>');
    }
    
    public function testSetNonStringAttributes()
    {
        $test = array('__attributes'=>array(2, 'test'));
        $xmlWriter = new CompleXml_View_XmlWriter();
        $xmlWriter->test = $test;
        $this->assertEquals($xmlWriter->outputMemory(), $this->_start_document . '<test ' . $xmlWriter->getDefaultAttributeName() . '0="2" ' . $xmlWriter->getDefaultAttributeName() . '1="test"/>');
    }
    
    public function testSetAttributesViaValue()
    {
        $test = array('__attributes'=>array('id'=>1, 'name'=>'test'));
        $xmlWriter = new CompleXml_View_XmlWriter();
        $xmlWriter->test = $test;
        $this->assertEquals($xmlWriter->outputMemory(), $this->_start_document . '<test id="1" name="test"/>');
    }
    
    public function testHasIncludedXml()
    {
        $xmlWriter = new CompleXml_View_XmlWriter();
        $this->assertFalse($xmlWriter->hasIncludedXml());
        $xmlWriter->includeXML('_files/test.xml');
        $this->assertTrue($xmlWriter->hasIncludedXml());
    }
    
    public function testIncludeXml()
    {
        $xmlWriter = new CompleXml_View_XmlWriter();
        $xmlWriter->startElement('test');
        $xmlWriter->includeXML('_files/test.xml');
        $xmlWriter->endElement();
        $this->assertEquals($xmlWriter->outputMemory(), $this->_start_document . '<test><xi:include href="_files/test.xml" parse="xml" xmlns:xi="http://www.w3.org/2001/XInclude"><xi:fallback xmlns:xi="http://www.w3.org/2001/XInclude"><error>xinclude: _files/test.xml not found</error></xi:fallback></xi:include></test>');
    }
}

