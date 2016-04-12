<?php
/**
 * pdo adaptor
 *
 */
require_once __DIR__ . '/../header.php';

/**
 * test core
 */
class TestDaoBase extends PHPUnit_Framework_TestCase
{
    /**
     * dao base
     * @var \Flickrer\Dao\Base
     */
    protected $dao;

    public function tearDown()
    {
        // delete all
        $this->dao->query('truncate table users;');
        $this->dao->query('truncate table recent_searches;');
        // remove object
        $this->dao = null;
        parent::tearDown();

    }

    public function setUp()
    {
        $this->dao = new \Flickrer\Dao\Base();
        parent::setUp();
    }

    public function testBaseAdaptor()
    {
        $this->assertInstanceOf('\Flickrer\Dao\Base', $this->dao);
        // now, must be connected
        $pdo = $this->dao->db();
        $this->assertInstanceOf('\PDO', $pdo);
    }

    public function testInsert()
    {
        $data = [
            'name' => 'John Doe',
            'username' => 'jd',
            'passhash' => md5(time())
        ];
        $this->dao->insert($data, 'users');
        // there should be only 1 user in there...
        $users = $this->dao->fetchAll('select * from users');
        $this->assertEquals(count($users), 1, 'should only have 1 user');
        // and it should match...
        $user = $this->dao->fetchOne('select * from users');
        foreach ($data as $n => $v) {
            $this->assertEquals($v, $user[$n]);
        }

    }

    public function testDelete()
    {
        $data = [
            'name' => 'John Doe',
            'username' => 'jd',
            'passhash' => md5(time())
        ];
        $this->dao->insert($data, 'users');
        $data['id'] = $this->dao->getLastInsertId();
        // now, delete then retrieve again, it should be null
        $this->dao->delete('users', 'id = ?', array($data['id']));
        $u = $this->dao->fetchOne('select * from users where id = ?', array($data['id']));
        $this->assertNull($u);

    }
}

?>
