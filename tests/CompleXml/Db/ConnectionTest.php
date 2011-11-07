<?php
/**
 * @group Db
 */
class CompleXml_Db_ConnectionTest  extends PHPUnit_Framework_TestCase  {

    protected function setUp()
    {
        if (!extension_loaded('pdo_sqlite')) {
            $this->markTestSkipped(
              'The pdo_sqlite extension is not available.'
            );
        }
    }

    public function testConnection()
    {
        $connection = new CompleXml_Db_Connection('sqlite::memory:');

    }
    /**
     * @expectedException CompleXml_Db_Exception
     */
    public function testFailConnection()
    {
        $connection = new CompleXml_Db_Connection('~!!!');

    }


    public function testExec()
    {
        $connection = new CompleXml_Db_Connection('sqlite::memory:');

        $res = $connection->exec('CREATE TABLE t(x INTEGER PRIMARY KEY DESC, y, z);');

        $this->assertEquals($res, 0);

        $res = $connection->exec('INSERT INTO t (x, y, z) VALUES (1, "111", 123);');

        $this->assertEquals($res, 1);

    }

    public function testQuery()
    {
        $connection = new CompleXml_Db_Connection('sqlite::memory:');
        $connection->exec('CREATE TABLE t(x INTEGER PRIMARY KEY DESC, y, z);');
        $connection->exec('INSERT INTO t (x,y,z) VALUES (1,"111", 123);');
        $connection->exec('INSERT INTO t (x,y,z) VALUES (2,"112", 223);');
        $connection->exec('INSERT INTO t (x,y,z) VALUES (3,"113", 323);');

        $res = $connection->query('SELECT count(*) as cnt FROM t')->fetchAll();
        $this->assertEquals($res[0]['cnt'], 3);
    }

    /**
     * @expectedException CompleXml_Db_Exception
     */
    public function testFailExec()
    {
        $connection = new CompleXml_Db_Connection('sqlite::memory:');
        $connection->exec('CREATE TABLE t(x INTEGER PRIMARY KEY DESC, y, z);');
        $connection->exec('INSERT INTO t (x, y, z) VALUES (1, "111", 123);');
        $connection->exec('INSERT INTO t (x, y, z) VALUES (1, "111", 123);');
    }
    
    /**
     * @expectedException CompleXml_Db_Exception
     */
    public function testFailQuery()
    {
        $connection = new CompleXml_Db_Connection('sqlite::memory:');
        $connection->exec('CREATE TABLE t(x INTEGER PRIMARY KEY DESC, y, z);');
        $connection->exec('INSERT INTO t (x, y, z) VALUES (1, "111", 123);');
        $res = $connection->query('SELECT * FROM z');
    }

    public function testSetGetFetchMode()
    {
        $connection = new CompleXml_Db_Connection('sqlite::memory:');
        $connection->setFetchMode(PDO::FETCH_ASSOC);
        $this->assertEquals($connection->getFetchMode(), PDO::FETCH_ASSOC);
    }

    public function testQuote()
    {
        $connection = new CompleXml_Db_Connection('sqlite::memory:');
        $unquoted_array = array("''", "'\'\'");
        $quoted_array = array("''''''", "'''\''\'''");
        $this->assertEquals($connection->quote($unquoted_array), $quoted_array);
    }

}
