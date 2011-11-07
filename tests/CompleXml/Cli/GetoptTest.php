<?php

/**
 *
 * TestCase for CompleXml_Cli_Getopt
 *
 * @author Andrey Kucherenko
 */
class CompleXml_Cli_GetoptTest  extends PHPUnit_Framework_TestCase {


    public static function optionsProvider()
    {
        $option = new CompleXml_Cli_Option('--test1');
        return
        array(
            array(
                array('--test-option'=>array(
                            'aliases'=>array('-to', '--testoptions'),
                            'type'=>'Int',
                            'help'=>'Test Message',
                            'value'=>5
                    ),
                    $option
                )
            )
        );
    }

    /**
     * @dataProvider optionsProvider
     */
    public function testGetoptInitializeObject($init)
    {
        $getopt = new CompleXml_Cli_Getopt();
        $getopt->init($init);
        $this->assertEquals($getopt->getCount(), 2);
    }

    /**
     * @dataProvider optionsProvider
     */
    public function testHelpGetOpt($init)
    {
        $getopt = new CompleXml_Cli_Getopt();
        $getopt->init($init);
        $help = 'Help message';
        $getopt->setHelp($help);
        $options = $getopt->getOptions();
        $res = $help.PHP_EOL.PHP_EOL;
        foreach ($options as $option) {
            $res.=$option->getHelp();
        }

        $this->assertEquals($getopt->getHelp(), $res);
    }

    /**
     * @dataProvider optionsProvider
     */
    public function testAliasesGetOpt($init)
    {
        $getopt = new CompleXml_Cli_Getopt();
        $getopt->init($init);

        $option1 = $getopt->getOption('--test-option');
        $option2 = $getopt->getOption('-to');

        $this->assertEquals($option1, $option2);
    }

    /**
     * @dataProvider optionsProvider
     */
    public function testGetOptArg($init)
    {
        $getopt = new CompleXml_Cli_Getopt();
        $getopt->init($init);
        $arg = array('test.php', '--test-option=5', '--test1');

        $getopt->parse($arg);

        $value = $getopt->getValue('--test-option');
        $this->assertEquals($value, 5);
        $value = $getopt->getValue('-to');
        $this->assertEquals($value, 5);
        $value = $getopt->getValue('--test1');
        $this->assertTrue($value);

        $getopt = new CompleXml_Cli_Getopt();
        $arg = array();
        $getopt->parse($arg);
        $value = $getopt->getValue('--test-option');
        $this->assertNull($value);
    }

    public function testSetOption()
    {
        $option = new CompleXml_Cli_Option('--test2');
        $option->setAliases(array('-t2'));
        $option->setType('Int');
        $option->setHelp('Test2 option message');
        $option->setValue(5);
        $option->setRequired(true);

        $getopt = new CompleXml_Cli_Getopt();
        $getopt->setOption($option);
        $this->assertEquals($getopt->getOption('-t2'), $option);
    }

    /**
     * @expectedException CompleXml_Cli_Exception
     */
    public function testParseException()
    {
        $option = new CompleXml_Cli_Option('--test2');
        $option->setAliases(array('-t2'));
        $option->setType('Int');
        $option->setHelp('Test2 option message');
        $option->setRequired(true);
        $getopt = new CompleXml_Cli_Getopt();
        $getopt->setOption($option);
        $getopt->parse(array('--t2=test'));
    }

}