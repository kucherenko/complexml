<?php
/**
 * @group Acl
 */
class CompleXml_AclTest extends PHPUnit_Framework_TestCase
{

    /**
     * @var CompleXml_Acl
     */
    private $_acl;


    public function setUp()
    {
        CompleXml_Config::setSettings('Acl', array('default'=>'deny'));
        $this->_acl = new CompleXml_Acl();        
    }

    public function testSetDefaultAccessStyle()
    {
        $this->_acl->setDefaultAccessStyle('allow');
        $this->assertAttributeEquals('allow', '_default_access_style', $this->_acl);
        $this->_acl->setDefaultAccessStyle('deny');
        $this->assertAttributeEquals('deny', '_default_access_style', $this->_acl);
    }
    /**
     * @expectedException CompleXml_Acl_Exception
     */
    public function testSetWrongDefaultAccessStyle()
    {
        $test_style = 'test';
        $this->assertAttributeNotContains($test_style, '_permits_access_styles',  $this->_acl);
        $this->_acl->setDefaultAccessStyle($test_style);
    }

    public function testIsDefaultAllow()
    {
        $this->_acl->setDefaultAccessStyle('allow');
        $this->assertTrue($this->_acl->isDefaultAllow());

        $this->_acl->setDefaultAccessStyle('deny');
        $this->assertFalse($this->_acl->isDefaultAllow());
    }

    public function testIsDefaultDeny()
    {
        $this->_acl->setDefaultAccessStyle('deny');
        $this->assertTrue($this->_acl->isDefaultDeny());

        $this->_acl->setDefaultAccessStyle('allow');
        $this->assertFalse($this->_acl->isDefaultDeny());
    }

    public function testAddGetRole()
    {
        $role = new CompleXml_Acl_Role('test');

        $this->_acl->addRole($role);

        $testRole = $this->_acl->getRole('test');

        $this->assertEquals($role, $testRole);

        $this->assertFalse($this->_acl->getRole('not-exist'));
    }

    public function testAddRemoveRole()
    {
        $role = new CompleXml_Acl_Role('test');

        $this->_acl->addRole($role);

        $this->_acl->removeRole('test');

        $testRole = $this->_acl->getRole('test');

        $this->assertFalse($testRole);
    }

    /**
     * @expectedException CompleXml_Acl_Exception
     */
    public function testRemoveNotExistRole()
    {
        $this->_acl->removeRole('not-exist');
    }

    public function testAddGetResource()
    {
        $resource = new CompleXml_Acl_Resource('test');

        $this->_acl->addResource($resource);

        $testResource = $this->_acl->getResource('test');

        $this->assertEquals($resource, $testResource);
    }

    public function testAddRemoveResource()
    {
        $resource = new CompleXml_Acl_Resource('test');

        $this->_acl->addResource($resource);

        $this->_acl->removeResource('test');

        $testResource = $this->_acl->getResource('test');

        $this->assertFalse($testResource);
    }

    /**
     * @expectedException CompleXml_Acl_Exception
     */
    public function testRemoveNotExistResource()
    {
        $this->_acl->removeResource('not-exist');
    }

    public function testHasRole()
    {
        $role = new CompleXml_Acl_Role('test');

        $this->_acl->addRole($role);

        $this->assertTrue($this->_acl->hasRole('test'));
        $this->assertFalse($this->_acl->hasRole('not-exist'));

    }

    public function testHasResource()
    {
        $resource = new CompleXml_Acl_Resource('test');

        $this->_acl->addResource($resource);

        $this->assertTrue($this->_acl->hasResource('test'));
        $this->assertFalse($this->_acl->hasResource('not-exist'));
    }

    public function testAllowDeny()
    {
        $this->_acl->addRole(new CompleXml_Acl_Role('allow-role'))
                ->addRole(new CompleXml_acl_Role('other-role'))
                ->addResource(new CompleXml_Acl_Resource('allow-resource'));

        $this->_acl->deny('allow-resource', 'allow-role');
        $this->assertTrue($this->_acl->isDeny('allow-resource', 'allow-role'));
        $this->_acl->allow('allow-resource', 'allow-role');
        $this->assertTrue($this->_acl->isAllow('allow-resource', 'allow-role'));
        $this->assertFalse($this->_acl->isAllow('allow-resource', 'other-role'));
        $this->_acl->deny('allow-resource', 'allow-role');
        $this->assertFalse($this->_acl->isAllow('allow-resource', 'allow-role'));
        $this->assertTrue($this->_acl->isDeny('allow-resource', 'allow-role'));
    }

    public function testAllowDenyPermDenyToAll()
    {
        $this->_acl->setDefaultAccessStyle('deny');

        $this->_acl->addRole(new CompleXml_Acl_Role('role1'))
                ->addRole(new CompleXml_acl_Role('role2'))
                ->addResource(new CompleXml_Acl_Resource('resource1'));

        $this->assertTrue($this->_acl->isDeny('resource1', 'role1'));
        $this->assertFalse($this->_acl->isAllow('resource1', 'role1'));

        $this->_acl->allow('resource1', 'role2');

        $this->assertTrue($this->_acl->isAllow('resource1', 'role2'));
        $this->assertFalse($this->_acl->isDeny('resource1', 'role2'));

        $this->_acl->deny('resource1', 'role2');

        $this->assertFalse($this->_acl->isAllow('resource1', 'role2'));
        $this->assertTrue($this->_acl->isDeny('resource1', 'role2'));
    }

    public function testAllowDenyPermAllowToAll()
    {
        $this->_acl->setDefaultAccessStyle('allow');

        $this->_acl->addRole(new CompleXml_Acl_Role('role1'))
                ->addRole(new CompleXml_acl_Role('role2'))
                ->addResource(new CompleXml_Acl_Resource('resource1'));

        $this->assertFalse($this->_acl->isDeny('resource1', 'role1'));
        $this->assertTrue($this->_acl->isAllow('resource1', 'role1'));

        $this->_acl->allow('resource1', 'role2');

        $this->assertTrue($this->_acl->isAllow('resource1', 'role2'));
        $this->assertFalse($this->_acl->isDeny('resource1', 'role2'));

        $this->_acl->deny('resource1', 'role2');

        $this->assertFalse($this->_acl->isAllow('resource1', 'role2'));
        $this->assertTrue($this->_acl->isDeny('resource1', 'role2'));
    }


    /**
     * @expectedException CompleXml_Acl_Exception
     */
    public function testWrongAllow()
    {
        $this->_acl->allow('allow-wrong', 'allow-role-wrong');
    }

    /**
     * @expectedException CompleXml_Acl_Exception
     */
    public function testWrongAllow1()
    {
        $this->_acl->addResource(new CompleXml_Acl_Resource('allow-resource'));
        $this->_acl->allow('allow-resource', 'allow-role-wrong');
    }

    /**
     * @expectedException CompleXml_Acl_Exception
     */
    public function testWrongDeny()
    {
        $this->_acl->deny('allow-wrong', 'allow-role-wrong');
    }

    /**
     * @expectedException CompleXml_Acl_Exception
     */
    public function testWrongDeny1()
    {
        $this->_acl->addResource(new CompleXml_Acl_Resource('allow-resource'));
        $this->_acl->deny('allow-resource', 'allow-role-wrong');
    }
}
