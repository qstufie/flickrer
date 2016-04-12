<?php
/**
 * User service
 * will also manage session in here
 * and user rego/login etc
 */
namespace Flickrer\Service;

use Flickrer\Utility\Object;
use \Flickrer\Model\User as oneUser;

class User
{
    /**
     * user dao
     * @var \Flickrer\Dao\User;
     */
    protected $dao;

    /**
     * singleton of user service
     * @return User
     */
    public static function singleton()
    {
        return Object::singleton(__CLASS__);
    }

    /**
     * constructor
     */
    public function __construct()
    {
        // kick start session here - since it's singleton, it won't start again.
        // add @ for cli mode tests
        @session_start();
        // record the session start time for debug purpose
        if (empty($_SESSION['_started_at'])) {
            $_SESSION['_started_at'] = date('Y-m-d H:i:s');
        }
        $this->dao = \Flickrer\Dao\User::singleton();
    }

    /**
     * get current user info
     * @return array
     */
    public function getInfo()
    {
        if (!empty($_SESSION['user']) && $_SESSION['user'] instanceof oneUser) {
            return ['user' => $_SESSION['user']->getData(), 'isLoggedIn' => $_SESSION['isLoggedIn']];
        }
        return [];
    }

    /**
     * user registration
     * @param $username
     * @param $password
     * @param $name
     * @return bool
     */
    public function register($username, $password, $name)
    {
        // 1st, try login
        try {
            $u = $this->login($username, $password);
            if ($u instanceof oneUser) return true;
        } catch (\Exception $e) {
            // only do it here
            // insert new one here
            $u = $this->dao->register($username, $password, $name);
            // start session as well!
            if ($u instanceof oneUser) {
                $_SESSION['user'] = $u;
                $_SESSION['isLoggedIn'] = true;
            }
            return $u;
        }
    }


    /**
     * login of user
     * @param $username
     * @param $password
     * @return oneUser
     * @throws \Exception
     */
    public function login($username, $password)
    {
        $u = $this->dao->getUserByUsernameAndPassword($username, $password);
        if ($u instanceof oneUser) {
            // user is in!
            // start session
            $_SESSION['user'] = $u;
            $_SESSION['isLoggedIn'] = true;
            return $u;
        } else {
            throw new \Exception('Invalid username or password');
        }

    }


}
