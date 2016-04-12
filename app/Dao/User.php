<?php
/**
 * user dao
 * @use Base
 */
namespace Flickrer\Dao;

use Flickrer\Model\User as oneUser;

class User extends Base
{
    /**
     * retrieve user object
     * @param $username
     * @param $password
     * @return oneUser or NULL
     */
    public function getUserByUsernameAndPassword($username, $password)
    {


    }
}
