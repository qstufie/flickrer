<?php
/**
 * user dao
 * @use Base
 */
namespace Flickrer\Dao;

use Flickrer\Model\User as oneUser;
use Flickrer\Utility\Object;

class User extends Base
{
    /**
     * singleton of user service
     * @return User
     */
    public static function singleton()
    {
        return Object::singleton(__CLASS__);
    }

    /**
     * retrieve user object
     * @param $username
     * @param $password
     * @return oneUser or NULL
     */
    public function getUserByUsernameAndPassword($username, $password)
    {
        $user = new oneUser([
            'username' => $username,
            'password' => $password
        ]);

        $r = $this->fetchOne('select * from users where username = :username and passhash = :passhash', $user->getData());
        if (!empty($r)) {
            return new oneUser($r);
        }
    }

    /**
     * register a new user
     * @param $username
     * @param $password
     * @param $name
     * @return oneUser
     */
    public function register($username, $password, $name)
    {
        $user = new oneUser([
            'username' => $username,
            'password' => $password,
            'name' => $name
        ]);
        $this->insert($user->getData(), 'users');
        // read from db just to ensure
        return $this->getUserByUsernameAndPassword($username, $password);
    }

}
