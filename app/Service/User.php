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
    }

    /**
     * get current user info
     * @return array
     */
    public function getInfo()
    {
        if (!empty($_SESSION['user']) && $_SESSION['user'] instanceof oneUser) {
            return $_SESSION['user']->getData();
        }
        return [];
    }

    /**
     * user registration
     * @param $userName
     * @param $password
     */
    public function register($userName, $password)
    {


    }


}
