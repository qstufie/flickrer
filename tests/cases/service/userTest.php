<?php
/**
 * pdo adaptor
 *
 */
require_once __DIR__ . '/../header.php';
/**
 * test core
 */
class TestServiceUser extends PHPUnit_Framework_TestCase
{
    public function tearDown()
    {
        parent::tearDown();
    }

    public function setUp()
    {
        parent::setUp();
    }

    public function testUserObject()
    {
        $user = \Flickrer\Service\User::singleton();
        $this->assertInstanceOf('\Flickrer\Service\User', $user);
        // and test it's always a single instance
        $user2 = \Flickrer\Service\User::singleton();
        $this->assertEquals($user, $user2);

    }
}
?>
