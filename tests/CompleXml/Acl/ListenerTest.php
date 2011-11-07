<?php

require_once '_files/TestAcl.php';

/**
 * Test class for CompleXml_Acl_Listener
 *
 * @group Acl
 */
class CompleXml_Acl_ListenerTest extends PHPUnit_Framework_TestCase
{

    /**
     * @expectedException CompleXml_Acl_Listener_Exception
     */
    public function testNotAllowedListener()
    {
        CompleXml_Auth::getInstance()->clearIdentity();
        $Router = $this->getMock('CompleXml_Router');
        $Object = new TestAclController($Router);
        $listener = new CompleXml_Acl_Listener();
        $listener->beforeAction('methodAction', $Object);
    }
    
    public function testAllowedListener()
    {
        $user = array(1, 2, 3);
        CompleXml_Auth::getInstance()->clearIdentity();
        CompleXml_Auth::getInstance()->addRole('user1');
        CompleXml_Auth::getInstance()->addRole('user2');
        CompleXml_Auth::getInstance()->authenticate($user);

        $Router = $this->getMock('CompleXml_Router');
        $Object = new TestAclController($Router);
        $listener = new CompleXml_Acl_Listener();

        $listener->beforeAction('methodAction', $Object);

        $this->assertEquals(CompleXml_Auth::getInstance()->getUser(), $user);
    }

    /**
     * @expectedException CompleXml_Acl_Listener_Exception
     */
    public function testDanyListener()
    {
        $user = array(1, 2, 3);
        CompleXml_Auth::getInstance()->clearIdentity();
        CompleXml_Auth::getInstance()->addRole('user2');
        CompleXml_Auth::getInstance()->authenticate($user);

        $Router = $this->getMock('CompleXml_Router');
        $Object = new TestAclController($Router);
        $listener = new CompleXml_Acl_Listener();

        $listener->beforeAction('methodAction', $Object);
    }

    /**
     * @expectedException CompleXml_Acl_Listener_Exception
     */
    public function testDenyAllListener()
    {
        $user = array(1, 2, 3);
        CompleXml_Auth::getInstance()->clearIdentity();
        CompleXml_Auth::getInstance()->addRole('user1');
        CompleXml_Auth::getInstance()->authenticate($user);

        CompleXml_Config::setSettings('Acl', array('default'=>'allow'));

        $Router = $this->getMock('CompleXml_Router');
        $Object = new TestAclController($Router);
        $listener = new CompleXml_Acl_Listener();

        $listener->beforeAction('methodDenyAllAction', $Object);        
    }

    /**
     * @expectedException CompleXml_Acl_Listener_Exception
     */
    public function testAllowAllListener()
    {
        $user = array(1, 2, 3);
        CompleXml_Auth::getInstance()->clearIdentity();
        CompleXml_Auth::getInstance()->addRole('user2');
        CompleXml_Auth::getInstance()->authenticate($user);

        CompleXml_Config::setSettings('Acl', array('default'=>'allow'));

        $Router = $this->getMock('CompleXml_Router');
        $Object = new TestAclController($Router);
        $listener = new CompleXml_Acl_Listener();

        $listener->beforeAction('methodAllowAllAction', $Object);        
    }
}
