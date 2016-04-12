<?php
/**
 * User object
 * that retains a single user's data
 */
namespace Flickrer\Model;

use Flickrer\App;

class User extends Base
{
    /**
     * setup the base data here
     * @param $data
     */
    public function __construct($data)
    {
        $this->allowedKeys = ['id', 'username', 'name', 'passhash', 'updated_at', 'created_at'];
        parent::__construct($data);
        if (!empty($data['password'])) {
            $this->set('passhash', self::hash($data['password']));
        }
    }

    /**
     * hash the pass
     * @param $v
     * @return string
     */
    public static function hash($v)
    {
        return md5(App::getSetting('salt') . '_' . $v);
    }

}
