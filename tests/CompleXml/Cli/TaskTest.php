<?php

/**
 * TestCase for CompleXml_Cli object
 *
 * @author Andrey Kucherenko
 */
class CompleXml_Cli_TaskTest extends PHPUnit_Framework_TestCase {
    /**
     * @var CompleXml_Cli_Getopt
     */
    protected $_Getopt = null;

    protected function setUp()
    {
        $this->_Getopt = new CompleXml_Cli_Getopt();
    }

    /**
     * @expectedException CompleXml_Cli_Exception
     */
    public function testStartCli()
    {
        $cli = new  CompleXml_Cli_Task($this->_Getopt);
        $cli->start();
        $cli1 = new  CompleXml_Cli_Task($this->_Getopt);
        $cli1->setProcessCount(1);
        $cli1->start();
        $cli->finish();
        $cli1->finish();
    }

    public function testMultiStartCli()
    {
        $cli = new  CompleXml_Cli_Task($this->_Getopt);
        $cli->start();

        $cli1 = new  CompleXml_Cli_Task($this->_Getopt);
        $cli1->setProcessCount(2);
        $cli1->start();

        $cli->finish();
        $cli1->finish();
    }

    public function testGetProccessCount()
    {
        $cli = new CompleXml_Cli_Task($this->_Getopt);

        $cli->setProcessCount(10);

        $this->assertEquals($cli->getProcessCount(), 10);
    }
}
