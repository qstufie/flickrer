<?php
/**
 * pdo adaptor
 *
 */
require_once __DIR__ . '/../header.php';

/**
 * test base
 */
class TestDaoUser extends PHPUnit_Framework_TestCase
{
    /**
     * dao base
     * @var \Flickrer\Dao\User
     */
    protected $dao;

    public function tearDown()
    {
        // delete all
        $this->dao->query('truncate table users;');
        // remove object
        $this->dao = null;
        parent::tearDown();

    }

    public function setUp()
    {
        $this->dao = \Flickrer\Dao\User::singleton();
        parent::setUp();
    }

    public function testGetuser()
    {
        $data = [
            'name' => 'John Doe',
            'username' => 'jd',
            'password' => 'pass'
        ];
        $u = new \Flickrer\Model\User($data);
        $this->dao->insert($u->getData(), 'users');
        // retrieve
        $u = $this->dao->getUserByUsernameAndPassword($data['username'], $data['password']);
        $this->assertInstanceOf('\Flickrer\Model\User', $u);
        $this->assertEquals('jd', $u->get('username'));
    }

    public function testRegister()
    {
        $u = $this->dao->register('jd', 'pass', 'John Doe');
        $this->assertEquals($u->getData(), $this->dao->getUserByUsernameAndPassword('jd', 'pass')->getData());
    }

}