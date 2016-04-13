<?php
/**
 * service test
 *
 */
require_once __DIR__ . '/../header.php';

class TestServiceUser extends PHPUnit_Framework_TestCase
{
    /**
     * @var \Flickrer\Service\User
     */
    protected $service;

    public function tearDown()
    {
        $this->service = null;
        \Flickrer\Dao\User::singleton()->query('truncate table users;');
        parent::tearDown();
    }

    public function setUp()
    {
        $this->service = \Flickrer\Service\User::singleton();
        parent::setUp();
    }

    public function testRegister()
    {
        // 1st, rego fresh
        $u = $this->service->register('jd', 'pass123', 'John Doe');
        $this->assertTrue($u->get('id') > 0);
        $this->assertEquals($u->get('username'), 'jd');
        $this->assertEquals($u->get('name'), 'John Doe');
        $this->assertEquals($u->get('passhash'), \Flickrer\Model\User::hash('pass123'));

        // 2nd. test a fallback (we registered jd already, it should be loggedin)
        $u = $this->service->register('jd', 'pass123', 'John Doe');
        $this->assertTrue($u === true);

    }

}