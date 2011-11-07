<?php
/**
 * @group Form
 */
class CompleXml_Form_ElementTestCase extends PHPUnit_Framework_TestCase
{
    
    public function testRenderTextElement()
    {
        $view = new CompleXml_View();
        $textElement = new CompleXml_Form_Element_Text();
        $textElement->setName('test');
        $textElement->setValue('test');
        $textElement->setLabel('test_label');
        $textElement->addAttribute('test', 'test_attr');
        $textElement->addError('test_error');
        $textElement->addError('test_error1');

        $textElement->render($view);
        $actual = '<?xml version="1.0" encoding="UTF-8"?>' . PHP_EOL .
                '<input test="test_attr" type="text" name="test" value="test"/><ul><li>test_error</li><li>test_error1</li></ul>';
        $this->assertEquals($view->outputMemory(), $actual);
    }
    
    public function testRenderSubmitElement()
    {
        $view = new CompleXml_View();
        $_el = new CompleXml_Form_Element_Submit();
        $_el->setValue('Go go go');
        $_el->setName('test');
        $_el->render($view);
        $actual = '<?xml version="1.0" encoding="UTF-8"?>' . PHP_EOL .
                '<input type="submit" name="test" value="Go go go"/>';
        $this->assertEquals($view->outputMemory(), $actual);
    }
    
    public function testRenderButtonElement()
    {

        $view = new CompleXml_View();
        $_el = new CompleXml_Form_Element_Button();
        $_el->setValue('Go go go');
        $_el->setName('test');
        $_el->render($view);
        $actual = '<?xml version="1.0" encoding="UTF-8"?>' . PHP_EOL .
                '<input type="button" name="test" value="Go go go"/>';
        $this->assertEquals($view->outputMemory(), $actual);
    }
    
    public function testRenderHiddenElement()
    {

        $view = new CompleXml_View();
        $_el = new CompleXml_Form_Element_Hidden();
        $_el->setValue('Go go go');
        $_el->setName('test');
        $_el->render($view);
        $actual = '<?xml version="1.0" encoding="UTF-8"?>' . PHP_EOL .
                '<input type="hidden" name="test" value="Go go go"/>';
        $this->assertEquals($view->outputMemory(), $actual);
    }
    
    public function testRenderImageElement()
    {

        $view = new CompleXml_View();
        $_el = new CompleXml_Form_Element_Image();
        $_el->setValue('Go go go');
        $_el->setName('test');
        $_el->render($view);
        $actual = '<?xml version="1.0" encoding="UTF-8"?>' . PHP_EOL .
                '<input type="image" name="test" value="Go go go"/>';
        $this->assertEquals($view->outputMemory(), $actual);
    }
    
    public function testRenderPasswordElement()
    {
        $view = new CompleXml_View();
        $_el = new CompleXml_Form_Element_Password();
        $_el->setValue('Go go go');
        $_el->setName('test');
        $_el->render($view);
        $actual = '<?xml version="1.0" encoding="UTF-8"?>' . PHP_EOL .
                '<input type="password" name="test" value="Go go go"/>';
        $this->assertEquals($view->outputMemory(), $actual);
    }
    
    public function testRenderResetElement()
    {
        $view = new CompleXml_View();
        $_el = new CompleXml_Form_Element_Reset();
        $_el->setValue('Go go go');
        $_el->setName('test');
        $_el->render($view);
        $actual = '<?xml version="1.0" encoding="UTF-8"?>' . PHP_EOL .
                '<input type="reset" name="test" value="Go go go"/>';
        $this->assertEquals($view->outputMemory(), $actual);
    }
    
    public function testRenderCheckboxElement()
    {
        $view = new CompleXml_View();
        $_el = new CompleXml_Form_Element_Checkbox();
        $_el->setValue('Go go go');
        $_el->setName('test');
        $_el->render($view);
        $actual = '<?xml version="1.0" encoding="UTF-8"?>' . PHP_EOL .
                '<label><input type="checkbox" name="test" value="Go go go"/></label>';
        $this->assertEquals($view->outputMemory(), $actual);
    }
    
    public function testRenderTextareaElement()
    {
        $view = new CompleXml_View();
        $textElement = new CompleXml_Form_Element_Textarea();
        $textElement->setValue('Go go go');
        $textElement->setName('test');
        $textElement->addError('error');
        $textElement->render($view);

        $actual = '<?xml version="1.0" encoding="UTF-8"?>' . PHP_EOL .
                '<textarea name="test">Go go go</textarea><ul><li>error</li></ul>';
        $this->assertEquals($view->outputMemory(), $actual);
    }
    
    public function testMultipleElement()
    {
        
        $view = new CompleXml_View();
        $_el = new CompleXml_Form_Element_Multiple('test_select');
        $_el->addOption('test', 'test1');
        $_el->setOptions(array('qw'=>'1', 'er'=>'2'));
        $_el->setValue('test');
        $_el->addError('error');
        $_el->render($view);

        $actual = '<?xml version="1.0" encoding="UTF-8"?>' . PHP_EOL .
                '<select multiple="multiple" name="test_select"><option value="test" selected="selected">test1</option><option value="qw">1</option><option value="er">2</option></select>' .
                '<ul><li>error</li></ul>';
        $this->assertEquals($view->outputMemory(), $actual);
    }
    
    public function testSelectElement()
    {
        $view = new CompleXml_View();
        $_el = new CompleXml_Form_Element_Select('test_select');
        $_el->addOption('test', 'test1');
        $_el->setOptions(array('qw'=>'1', 'er'=>'2'));
        $_el->setValue('test');
        $_el->addError('error');
        $_el->render($view);

        $actual = '<?xml version="1.0" encoding="UTF-8"?>' . PHP_EOL .
                '<select name="test_select"><option value="test" selected="selected">test1</option><option value="qw">1</option><option value="er">2</option></select>' .
                '<ul><li>error</li></ul>';
        $this->assertEquals($view->outputMemory(), $actual);

        $view = new CompleXml_View();
        $_el->flushOptions();
        $_el->render($view);
        $actual = '<?xml version="1.0" encoding="UTF-8"?>' . PHP_EOL .
                '<select name="test_select"/>' .
                '<ul><li>error</li></ul>';
        $this->assertEquals($view->outputMemory(), $actual);
    }
    
    public function testGetNameAttribute()
    {
        $textElement = new CompleXml_Form_Element_Text();
        $textElement->setName('test');
        $this->assertEquals($textElement->getNameAttribute(), 'test');
        $textElement->setParentFormName('test');
        $this->assertEquals($textElement->getNameAttribute(), 'test[test]');
        $textElement->setParentFormName('test[test]');
        $this->assertEquals($textElement->getNameAttribute(), 'test[test][test]');
    }
    
    public function testAttributes()
    {
        $textElement = new CompleXml_Form_Element_Text();
        $textElement->addAttribute('test', 'test');
        $this->assertEquals($textElement->getAttribute('test'), 'test');
        $this->assertNull($textElement->getAttribute('test1'));

        $source = array('qwer'=>'qwer', 'qwwe'=>1);
        $textElement->setAttributes($source);
        $this->assertNull($textElement->getAttribute('test'));
        $this->assertEquals($textElement->getAttribute('qwer'), 'qwer');
        $this->assertEquals($textElement->getAttributes(), $source);
        $textElement->removeAttribute('qwer');
        $this->assertNull($textElement->getAttribute('qwer'));
    }
    
    public function testSetValues()
    {
        $textElement = new CompleXml_Form_Element_Text('test');
        $textElement->setValues(array('qwe'=>1));
        $this->assertNull($textElement->getValue());
        $textElement->setValues(array('test'=>1));
        $this->assertEquals($textElement->getValue(), 1);
    }
    
    public function testGetSetLabel()
    {
        $textElement = new CompleXml_Form_Element_Text('test');
        $textElement->setLabel('test');
        $this->assertEquals($textElement->getLabel(), 'test');
    }
    
    public function testValidator()
    {
        $textElement = new CompleXml_Form_Element_Text('test');
        $this->assertFalse($textElement->hasValidators());
        $textElement->addValidator(new CompleXml_Validate_Ip(), 'not right ip');
        $textElement->addValidator(new CompleXml_Validate_Email(), 'not right email');
        $this->assertTrue($textElement->hasValidators());
        $textElement->flushValidators();
        $this->assertFalse($textElement->hasValidators());
    }
}