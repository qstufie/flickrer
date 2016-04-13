<?php
/**
 * user object
 *
 */
require_once __DIR__ . '/../header.php';

class TestModelUser extends PHPUnit_Framework_TestCase
{
    public function testUserObject()
    {
        $cleanData = ['id' => 1, 'username' => 'John Doe'];
        $tainted = $cleanData;
        $tainted['foo'] = 'bar';

        $user = new \Flickrer\Model\User($tainted);

        $this->assertEquals($user->getData(), $cleanData);

    }
}
