<?php

/**
 * CompleXml_Utils_PlaceParser_TestCase is a test case for CompleXml_Utils_PlaceParser
 *
 * @author Andrey Kucherenko
 * @group Utils
 */
class CompleXml_Utils_PlaceParserTest extends PHPUnit_Framework_TestCase
{

    public static function validMatch()
    {
        return array(
            array('aa.com.ua', '<module:w>.com.ua'),
            array('aa.11.com.ua', '<module:w>.<server:d>.com.ua'),
            array('aa.111.11.com.ua', '<module:w>.111.<server:d>.com.ua'),
            array('/index/action/test/this', '/<controller:w>/<action:w>/*'),
            array('/index/action/2004-10-10/this', '/<controller:w>/<action:w>/<year:d>-<month:d>-<day:d>/this'),
        );
    }


    public static function regularParams()
    {
        return array(
            array('aa.11.com.ua', '<string:w>.<digit:d>.com.ua', 'aa', '11'),
            array('aa.11.111.com.ua', '<string:w>.11.<digit:d>.com.ua', 'aa', '111'),
            array('aa.com.ua', '<string:w>.com.ua', 'aa', null),
            array('333.com.ua', '<string:w>.com.ua', '333', null),
            array('333.com.ua', '<digit:d>.com.ua', null, 333),
            array('/test/index/?name1234=test', '/<string:w>/index/?name<digit:d>*', 'test', '1234'),
        );
    }


    public function testGetRegex()
    {
        $this->assertEquals(
            CompleXml_Utils_PlaceParser::getRegex('<qqqq:w>.<test:d>.domain.com/<url:w>'),
            '/(?<qqqq>\w+)\.(?<test>\d+)\.domain\.com\/(?<url>\w+)/i');
    }

    /**
     * @dataProvider validMatch
     */
    public function testIsMatch($string, $regex)
    {
        $this->assertTrue(CompleXml_Utils_PlaceParser::isMatch($regex, $string));
    }


    /**
     * @dataProvider regularParams
     */
    public function testGetParams($string, $regex, $param1, $param2)
    {
        $result = CompleXml_Utils_PlaceParser::getParams($regex, $string);
        if (isset($result['string'])) {
            $this->assertEquals($result['string'], $param1);
        }
        if (isset($result['digit'])) {
            $this->assertEquals($result['digit'], $param2);
        }
    }
}

