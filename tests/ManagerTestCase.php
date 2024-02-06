<?php
/*
 *  $Id$
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
 * "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT
 * LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR
 * A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT
 * OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL,
 * SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT
 * LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE,
 * DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY
 * THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
 * (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE
 * OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 *
 * This software consists of voluntary contributions made by many individuals
 * and is licensed under the LGPL. For more information, see
 * <http://www.doctrine-project.org>.
 */

/**
 * Doctrine_Manager_TestCase.
 *
 * @author      Konsta Vesterinen <kvesteri@cc.hut.fi>
 *
 * @category    Object Relational Mapping
 *
 * @see        www.doctrine-project.org
 *
 * @internal
 *
 * @coversNothing
 */
class Doctrine_Manager_TestCase extends Doctrine_UnitTestCase
{
    protected $conn1_database;
    protected $conn2_database;

    protected $conn1;
    protected $conn2;

    public function testGetInstance()
    {
        $this->assertTrue(Doctrine_Manager::getInstance() instanceof Doctrine_Manager);
    }

    public function testOpenConnection()
    {
        $this->assertTrue($this->connection instanceof Doctrine_Connection);
    }

    public function testGetIterator()
    {
        $this->assertTrue($this->manager->getIterator() instanceof ArrayIterator);
    }

    public function testCount()
    {
        $this->assertTrue(is_integer(count($this->manager)));
    }

    public function testGetCurrentConnection()
    {
        $this->assertTrue($this->manager->getCurrentConnection() === $this->connection);
    }

    public function testGetConnections()
    {
        $this->assertTrue(is_integer(count($this->manager->getConnections())));
    }

    public function testClassifyTableize()
    {
        $name = 'Forum_Category';
        $this->assertEqual(Doctrine_Inflector::tableize($name), 'forum__category');
        $this->assertEqual(Doctrine_Inflector::classify(Doctrine_Inflector::tableize($name)), $name);
    }

    public function testDsnParser()
    {
        $mysql = 'mysql://user:pass@localhost/dbname';
        $sqlite = 'sqlite:////full/unix/path/to/file.db';
        $sqlitewin = 'sqlite:///c:/full/windows/path/to/file.db';
        $sqlitewin2 = 'sqlite:///D:\full\windows\path\to\file.db';

        $manager = Doctrine_Manager::getInstance();

        try {
            $res = $manager->parseDsn($mysql);
            $expectedMysqlDsn = array(
                'scheme' => 'mysql',
                'host' => 'localhost',
                'user' => 'user',
                'pass' => 'pass',
                'path' => '/dbname',
                'dsn' => 'mysql:host=localhost;dbname=dbname',
                'port' => null,
                'query' => null,
                'fragment' => null,
                'database' => 'dbname');
            $this->assertEqual($expectedMysqlDsn, $res);
        } catch (Exception $e) {
            $this->fail($e->getMessage());
        }

        try {
            $expectedDsn = array(
                'scheme' => 'sqlite',
                'host' => null,
                'user' => null,
                'pass' => null,
                'path' => '/full/unix/path/to/file.db',
                'dsn' => 'sqlite:/full/unix/path/to/file.db',
                'port' => null,
                'query' => null,
                'fragment' => null,
                'database' => '/full/unix/path/to/file.db');

            $res = $manager->parseDsn($sqlite);
            $this->assertEqual($expectedDsn, $res);
        } catch (Exception $e) {
            $this->fail($e->getMessage());
        }

        try {
            $expectedDsn = array(
                'scheme' => 'sqlite',
                'host' => null,
                'path' => 'c:/full/windows/path/to/file.db',
                'dsn' => 'sqlite:c:/full/windows/path/to/file.db',
                'port' => null,
                'user' => null,
                'pass' => null,
                'query' => null,
                'fragment' => null,
                'database' => 'c:/full/windows/path/to/file.db');
            $res = $manager->parseDsn($sqlitewin);
            $this->assertEqual($expectedDsn, $res);
        } catch (Exception $e) {
            $this->fail($e->getMessage());
        }

        try {
            $expectedDsn = array(
                'scheme' => 'sqlite',
                'host' => null,
                'path' => 'D:/full/windows/path/to/file.db',
                'dsn' => 'sqlite:D:/full/windows/path/to/file.db',
                'port' => null,
                'user' => null,
                'pass' => null,
                'query' => null,
                'fragment' => null,
                'database' => 'D:/full/windows/path/to/file.db');
            $res = $manager->parseDsn($sqlitewin2);
            $this->assertEqual($expectedDsn, $res);
        } catch (Exception $e) {
            $this->fail($e->getMessage());
        }
    }

    public function testCreateDatabases()
    {
        // We need to know if we're under Windows or *NIX
        $OS = strtoupper(substr(PHP_OS, 0, 3));

        $tmp_dir = ('WIN' == $OS) ? str_replace('\\', '/', sys_get_temp_dir()) : '/tmp';

        $this->conn1_database = $tmp_dir.'/doctrine1.db';
        $this->conn2_database = $tmp_dir.'/doctrine2.db';

        $this->conn1 = Doctrine_Manager::connection('sqlite:///'.$this->conn1_database, 'doctrine1');
        $this->conn2 = Doctrine_Manager::connection('sqlite:///'.$this->conn2_database, 'doctrine2');

        $result1 = $this->conn1->createDatabase();
        $result2 = $this->conn2->createDatabase();
    }

    public function testDropDatabases()
    {
        $result1 = $this->conn1->dropDatabase();
        $result2 = $this->conn2->dropDatabase();
    }

    public function testConnectionInformationDecoded()
    {
        $dsn = 'mysql://'.urlencode('test/t').':'.urlencode('p@ssword').'@localhost/'.urlencode('db/name');

        $conn = $this->openAdditionalConnection($dsn);
        $options = $conn->getOptions();

        $this->assertEqual($options['username'], 'test/t');
        $this->assertEqual($options['password'], 'p@ssword');
        $this->assertEqual($options['dsn'], 'mysql:host=localhost;dbname=db/name');
    }

    public function prepareData()
    {
    }

    public function prepareTables()
    {
    }

    public function testGetConnectionDrivers()
    {
        $this->assertTrue(is_array($this->manager->getConnectionDrivers()));
    }
}
