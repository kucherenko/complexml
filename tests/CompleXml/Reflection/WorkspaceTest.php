<?php
require_once '_res/ResClass.php';
/**
 * TestCase for CompleXml_Reflection_Workspace
 *
 * @author Andrey Kucherenko
 *
 * @group Reflection
 */
class CompleXml_Reflection_WorkspaceTest extends PHPUnit_Framework_TestCase {

    public static function classData()
    {
        $B = new CompleXml_Reflection_Class('B');
        $C = new CompleXml_Reflection_Class('C');
        $D = new CompleXml_Reflection_Class('D');
        return array(
            array('A', null, $B),
            array('A', array('author'=>'Andrey Kucherenko'), $C),
            array('A', array('author'=>'Andrey Kucherenko', 'param1'=>'qwerty'), $D),
            array('A', array('param'=>'111 name'), $B),
            array('A', array('param'=>'Andrey name'), $C)
        );
    }

    public static function classesData()
    {
        $B = new CompleXml_Reflection_Class('B');
        $C = new CompleXml_Reflection_Class('C');
        $D = new CompleXml_Reflection_Class('D');
        return array(
            array('A', null, array($B, $C, $D)),
            array('A', array('author'=>'Andrey Kucherenko'), array($C,$D)),
            array('A', array('param'=>'apk name'), array($C)),
            array('A', array('param'=>'qwerty name'), array($B,$C)),
        );
    }

    public function testIsClassDeclarated() {
        $Reflection_Workspace = new CompleXml_Reflection_Workspace();
        $this->assertTrue($Reflection_Workspace->isClassDeclared('CompleXml_Reflection_WorkspaceTest'));
    }

    public function testDeclaratedClasses()
    {
        $classes = get_declared_classes();
        $Reflection_Workspace = new CompleXml_Reflection_Workspace();
        $this->assertEquals($Reflection_Workspace->getAllClasses(), $classes);
    }
    /**
     * @dataProvider classData
     */
    public function testGetClass($parent, $params, $result)
    {
        $Reflection_Workspace = new CompleXml_Reflection_Workspace();
        $this->assertEquals($Reflection_Workspace->getClass($parent, $params), $result);
    }

    /**
     * @dataProvider classesData
     */
    public function testGetClasses($parent, $params, $result)
    {
        $Reflection_Workspace = new CompleXml_Reflection_Workspace();
        $this->assertEquals($Reflection_Workspace->getClasses($parent, $params), $result);
    }

}

