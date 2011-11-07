<?php

require_once '_files/TestAuth.php';

/**
 * Test class for CompleXml_Auth_Listener.
 *
 * @group Auth
 */
class CompleXml_Auth_ListenerTest extends PHPUnit_Framework_TestCase
{

    /**
     * @expectedException CompleXml_Auth_Listener_Exception
     */
    public function testNotAuthListener()
    {
        CompleXml_Auth::getInstance()->clearIdentity();
        $Router = $this->getMock('CompleXml_Router');
        $Object = new TestAuthController($Router);
        $listener = new CompleXml_Auth_Listener();
        $listener->beforeAction('methodAction', $Object);
    }

    public function testAuthListener()
    {
        $user = array(1,2,3);
        CompleXml_Auth::getInstance()->authenticate($user);
        $Router = $this->getMock('CompleXml_Router');
        $Object = new TestAuthController($Router);
        $listener = new CompleXml_Auth_Listener();
        $listener->beforeAction('methodAction', $Object);
        $this->assertEquals(CompleXml_Auth::getInstance()->getUser(),  $user);
    }

}
