<?php 
/**
 * @group Form
 */
class CompleXml_Form_TestCase extends PHPUnit_Framework_TestCase
{

    /**
     * @var CompleXml_Form
     */
    protected $form;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $this->form = new CompleXml_Form();
    }
    
    public function testBindValues()
    {
        $array = array('name1'=>1, 'test2'=>'qwerty');

        $this->form->setValues($array);

        $array = array('name'=>1, 'test'=>'qwerty');

        $this->form->bind($array);

        $this->assertEquals($this->form->getValues(), $array);
    }

    /**
     * @expectedException CompleXml_Form_Exception
     */
    public function testCallUndefinedMethod()
    {
        $this->form->testtest();
    }
    
    public function testCallElementValidator()
    {
        $element = new CompleXml_Form_Element_Text();
        $validator = new CompleXml_Validate_Ip();
        $element->addValidator($validator);
        $this->form->name = $element;
        $value = '123';
        $this->assertEquals($element->isValid($value), $this->form->validateName($value));
    }

    /**
     * @expectedException CompleXml_Form_Exception
     */
    public function testCallUndefinedElementValidator()
    {
        $this->form->validateTest();
    }
    
    public function testIsValid()
    {

        $source = array('test1'=>1, 'test2'=>2);

        $this->form = new CompleXml_Form();
        $this->assertTrue($this->form->isValid($source));

        $validator = new CompleXml_Validate_Email();
        $element = new CompleXml_Form_Element_Text();
        $element->addValidator($validator);

        $this->assertFalse($validator->isValid($source['test1']));
        $this->assertFalse($element->isValid($source['test1']));

        $this->form->test1 = $element;

        $this->assertEquals($element->isValid($source['test1']), $this->form->isValid($source));
    }
    
    public function testAddElement()
    {
        $element = new CompleXml_Form_Element_Text('test');

        $this->form->addElement($element);

        $this->assertEquals($element, $this->form->test);
        $this->assertEquals($element, $this->form->getElement('test'));

        $this->form->removeElement('test');

        $this->assertNull($this->form->test);
        $this->assertNull($this->form->getElement('test'));
        $element = new CompleXml_Form_Element_Text();
        $this->form->test1 = $element;

        $this->assertEquals($element, $this->form->test1);
        $this->assertEquals($element, $this->form->getElement('test1'));
        $this->assertEquals('test1', $this->form->getElement('test1')->getName());
    }
    
    public function testFlushValues()
    {
        $source = array('test'=>1);

        $element = new CompleXml_Form_Element_Text('test');
        $this->form->addElement($element);

        $this->form->setValues($source);

        $this->assertEquals($element->getValue(), $source['test']);
        $this->assertEquals($source, $this->form->getValues());
        $this->form->flushValues();
        $this->assertNull($element->getValue());
        $this->assertEquals(array(), $this->form->getValues());
    }

    public function testIsset()
    {
        $this->assertFalse(isset ($this->form->test));
        $element = new CompleXml_Form_Element_Text('test');
        $this->form->addElement($element);
        $this->assertTrue(isset ($this->form->test));
    }

    public function testUnset()
    {
        $element = new CompleXml_Form_Element_Text('test');
        $this->form->addElement($element);
        $this->assertTrue(isset ($this->form->test));
        unset($this->form->test);
        $this->assertFalse(isset ($this->form->test));
    }

    public function testCountable()
    {
        $element = new CompleXml_Form_Element_Text('test');
        $this->form->addElement($element);
        $this->assertEquals(count($this->form), 1);
    }

    public function testIterator()
    {
        $a['test'] = new CompleXml_Form_Element_Text('test');
        $a['test1'] = new CompleXml_Form_Element_Text('test1');
        $this->form->addElement($a['test']);
        $this->form->addElement($a['test1']);

        foreach ($this->form as $name=>$value){
            $this->assertEquals($value, $a[$name]);
        }
    }
    
    public function testSetGetAction()
    {
        $this->form->setAction('test');
        $this->assertEquals($this->form->getAction(), 'test');
    }

    public function testSetGetEnctype()
    {
        $this->form->setEnctype('multipart/form-data');
        $this->assertEquals($this->form->getEnctype(), 'multipart/form-data');
    }

    public function testSetGetParentForm()
    {
        $this->form->setParentFormName('qqqq');
        $this->assertEquals($this->form->getParentFormName(), 'qqqq');
    }
    
    public function testSetGetName()
    {
        $this->form->setName('test');
        $this->assertEquals($this->form->getName(), 'test');
    }
    
    public function testSetGetMethod()
    {
        $this->form->setMethod('POST');
        $this->assertEquals($this->form->getMethod(), 'post');
        $this->form->setMethod('geT');
        $this->assertEquals($this->form->getMethod(), 'get');
    }

    /**
     * @expectedException CompleXml_Form_Exception
     */
    public function testSetWrongMethod()
    {
        $this->form->setMethod('delete');
    }
    
    public function testSetValue()
    {
        $element = new CompleXml_Form_Element_Text('test');
        $this->form->addElement($element);
        $this->form->setValue('test', 'test_text');

        $this->assertEquals($this->form->getElement('test')->getValue(), $this->form->getValue('test'));
        $this->assertNull($this->form->getValue('test1'));
    }

    public function testRenderForm()
    {
        $view = new CompleXml_View();
        $this->form->setName('test_name');
        $this->form->setLabel('test_label');
        $this->form->setAction('/');
        $this->form->setEnctype('multipart/form-data');
        $this->form->setMethod('POST');
        $this->form->render($view);
        $actual = '<?xml version="1.0" encoding="UTF-8"?>'.PHP_EOL.
        '<form method="post" action="/" enctype="multipart/form-data" name="test_name"><fieldset><legend>test_label</legend></fieldset></form>';
        $this->assertEquals($view->outputMemory(), $actual);
    }

    public function testRenderSubForm()
    {
        $view = new CompleXml_View();
        $form = new CompleXml_Form();
        $form->setLabel('subform');
        $form->addAttribute('test', 'test');
        $this->form->setName('test');
        $this->form->subtest = $form;
        $this->form->render($view);
        $actual = '<?xml version="1.0" encoding="UTF-8"?>'.PHP_EOL.
        '<form name="test"><fieldset><fieldset test="test" name="subtest"><legend>subform</legend></fieldset></fieldset></form>';
        $this->assertEquals($view->outputMemory(), $actual);
    }

    public function testRenderElement()
    {
        $view = new CompleXml_View();
        $view1 = new CompleXml_View();
        $element = new CompleXml_Form_Element_Text();
        $element->setAttributes(array('test'=>'test'));
        $element->setName('test_element');
        $element->setLabel('test_element_label');
        $element->setValue('qwert');
        $element->addError('error');
        $this->form->setName('test');
        $this->form->addElement($element);
        $this->form->render($view);

        $element->render($view1);
        $element_str = str_replace('<?xml version="1.0" encoding="UTF-8"?>'.PHP_EOL, '', $view1->outputMemory());

        $actual = '<?xml version="1.0" encoding="UTF-8"?>'.PHP_EOL.
        '<form name="test"><fieldset><fieldset>'.$element_str.'<legend>test_element_label</legend></fieldset></fieldset></form>';

        $this->assertEquals($view->outputMemory(), $actual);
    }

    public function testAttributes()
    {
        $this->form->addAttribute('test', 'test');
        $this->assertEquals($this->form->getAttribute('test'), 'test');
        $this->assertNull($this->form->getAttribute('test1'));

        $source = array('qwer'=>'qwer', 'qwwe'=>1);
        $this->form->setAttributes($source);
        $this->assertNull($this->form->getAttribute('test'));
        $this->assertEquals($this->form->getAttribute('qwer'), 'qwer');
        $this->assertEquals($this->form->getAttributes(), $source);
        $this->form->removeAttribute('qwer');
        $this->assertNull($this->form->getAttribute('qwer'));
    }
}
