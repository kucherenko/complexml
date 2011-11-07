<?php

/**
 *
 * TestCase for CompleXml_Cli_Option
 *
 * @author apk
 */
class CompleXml_Cli_OptionTest  extends PHPUnit_Framework_TestCase {

    public function testMakeOption()
    {
        $name = '--test-option';
        $option = new CompleXml_Cli_Option($name);
        $this->assertEquals($name, $option->getName());
    }

    public function testHelpMessage()
    {
        $name = '--test-option';
        $message = 'Message';
        $type = 'Int';
        $value = 5;
        $aliases = array('-to', '--testoption');

        $option = new CompleXml_Cli_Option($name);
        $option->setHelp($message);
        $this->assertEquals($name."\t".$message.PHP_EOL.PHP_EOL, $option->getHelp());
        $option->setRequired(true);
        $this->assertEquals($option->getHelp(), $name." (requared)\t".$message.PHP_EOL.PHP_EOL);
        $option->setRequired(false);
        $option->setType($type);
        $this->assertEquals($option->getHelp(), $name."=<".$type.">\t".$message.PHP_EOL.PHP_EOL);
        $option->setType(null);
        $option->setAliases($aliases);
        $this->assertEquals($option->getHelp(), $name." (".implode(', ', $aliases).")\t".$message.PHP_EOL.PHP_EOL);

        $option->setValue($value);
        $this->assertEquals($option->getHelp(), $name." (".implode(', ', $aliases).") default:".$value."\t".$message.PHP_EOL.PHP_EOL);

        $name = '--test-option';
        $option = new CompleXml_Cli_Option($name);
        $option->setHelp($message);
        $option->setRequired(true);
        $option->setType($type);
        $option->setValue($value);
        $option->setAliases($aliases);
        $this->assertEquals($option->getHelp(),$name."=<".$type."> (".implode(', ', $aliases).") default:".$value." (requared)\t".$message.PHP_EOL.PHP_EOL);

        $name = '--test-option';
        $option = new CompleXml_Cli_Option($name);
        $option->setHelp($message);
        $option->setRequired(true);
        $option->setType(new CompleXml_Validate_Int());
        $option->setValue($value);
        $option->setAliases($aliases);
        $this->assertEquals($option->getHelp(), $name."=<".$type."> (".implode(', ', $aliases).") default:".$value." (requared)\t".$message.PHP_EOL.PHP_EOL);
    }

    public function testParseOption()
    {
        $name = '--test-option';
        $type = 'Int';
        $aliases = array('-to');

        $option = new CompleXml_Cli_Option($name);

        try{
            $option->parse(array('--test-option'));
        }catch(Exception $e){
            $this->assertTrue(false);
        }
        $option->setAliases($aliases);
        try{
            $option->parse(array('-to'));
        }catch(Exception $e){
            $this->assertTrue(false);
        }

        $option->setRequired(true);
        try{
            $option->parse(array('-to'));
            $option->parse(array('--test-option'));
        }catch(Exception $e){
            $this->assertTrue(false);
        }

        $option->setType('Int');
        try{
            $option->parse(array('-to=5'));
            $option->parse(array('--test-option=3'));
        }catch(Exception $e){
            $this->assertTrue(false);
        }

        $option->setType(new CompleXml_Validate_Int());
        try{
            $option->parse(array('-to=5'));
            $option->parse(array('--test-option=3'));
        }catch(Exception $e){
            $this->assertTrue(false);
        }
    }
    /**
     * @expectedException CompleXml_Cli_Exception
     */
    public function testParseOptionException()
    {
        $name = '--test-option';
        $type = 'Int';
        $option = new CompleXml_Cli_Option($name);
        $option->setType($type);
        $option->parse(array('--test-option=test'));
    }

    /**
     * @expectedException CompleXml_Validate_Exception
     */
    public function testSetTypeException()
    {
        $name = '--test-option';
        $type = 'Int32';
        $option = new CompleXml_Cli_Option($name);
        $option->setType($type);
    }
    /**
     *
     * @expectedException CompleXml_Validate_Exception
     */
    public function testSetType1Exception()
    {
        $name = '--test-option';
        $type = new CompleXml_Cli_Option($name);
        $option = new CompleXml_Cli_Option($name);
        $option->setType($type);
    }

    public function testGetAliases()
    {
        $name = '--test-option';
        $aliases = array('-to', '--testoption');
        $option = new CompleXml_Cli_Option($name);
        $option->setAliases($aliases);

        $this->assertEquals($option->getAliases(), $aliases);
    }

    public function testSetGetPosition()
    {
        $name = '--test-option';
        $aliases = array('-to', '--testoption');
        $option = new CompleXml_Cli_Option($name);
        $option->setPosition(1);
        $this->assertEquals($option->getPosition(), 1);
    }

    public function testSetGetDefault()
    {
        $name = '--test-option';
        $aliases = array('-to', '--testoption');
        $option = new CompleXml_Cli_Option($name);
        $option->setDefault('default');
        $this->assertEquals($option->getDefault(), 'default');
        $this->assertEquals($option->getValue(), 'default');
    }

    public function testSetGetValue()
    {
        $name = '--test-option';
        $type = 'Int';
        $aliases = array('-to');
        $option = new CompleXml_Cli_Option($name);
        $option->setAliases($aliases);
        $option->setType($type);
        $option->parse(array('-to=5'));

        $this->assertEquals($option->getValue(), 5);

        $name = '--test-option';
        $type = 'String';
        $option = new CompleXml_Cli_Option($name);
        $option->setAliases($aliases);
        $option->setType($type);
        $option->setPosition(0);
        $option->parse(array('qwerty'));

        $this->assertEquals($option->getValue(), 'qwerty');

        $name = '--test-option';
        $type = 'String';
        $option = new CompleXml_Cli_Option($name);
        $option->setAliases($aliases);
        $option->setType($type);
        $option->parse(array('--test-option', 'qwerty'));

        $this->assertEquals($option->getValue(), 'qwerty');

     }
     /**
      * @expectedException CompleXml_Cli_Exception
      */
     public function testRequiredOption()
     {
        $name = '--test-option';
        $type = 'String';
        $aliases = array('-to');
        $option = new CompleXml_Cli_Option($name);
        $option->setAliases($aliases);
        $option->setType($type);
        $option->setRequired(true);
        $option->parse(array('qwerty'));         
     }
     /**
      * @expectedException CompleXml_Validate_Exception
      */
     public function testDefaultNotValid()
     {
        $name = '--test-option';
        $type = 'Int';
        $option = new CompleXml_Cli_Option($name);
        $aliases = array('-to');
        $option->setAliases($aliases);
        $option->setType($type);
        $option->setDefault('qwerty');
     }
}