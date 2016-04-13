<?php
/**
 * user dao
 * @use Base
 */
namespace Flickrer\Dao;

use Flickrer\App;
use Flickrer\Model\Search as oneSearch;
use Flickrer\Utility\Object;

class Search extends Base
{
    /**
     * singleton of user dao
     * @return Search
     */
    public static function singleton()
    {
        return Object::singleton(__CLASS__);
    }

    /**
     * add search history
     * @param $uid
     * @param $params
     * @return oneSearch
     * @throws \Exception
     */
    public function addSearch($uid, $params)
    {
        if (empty($uid)) {
            throw new \Exception('invalid user id');
        }
        if (empty($params)) {
            // no need to proceed
            return null;
        }

        $s = new oneSearch([
            'user_id' => $uid,
            'search_params' => $params
        ]);

        // we need to ensure this param is not already in db...
        $existing = $this->fetchOne('select * from recent_searches where user_id = ? and search_params = ?', [$uid, $s->get('search_params')]);
        if (!empty($existing)) {
            // no need to create a new one
            return null;
        }

        $this->insert($s->getData(), 'recent_searches');
        $s->set('id', $this->getLastInsertId());

        return $s;
    }

    /**
     * retrieve all searches by the same user
     * @param $uid
     * @return array
     * @throws \Exception
     */
    public function getSearchesByUserId($uid)
    {
        $uid = (int)$uid;
        if (empty($uid)) {
            throw new \Exception('invalid user id');
        }
        $data = $this->fetchAll('select * from recent_searches where user_id = ? order by created_at desc limit ' .
            App::getSetting('max_recent_searches'), [$uid]);
        $r = [];
        if (!empty($data)) {
            foreach ($data as $row) {
                $r[$row['id']] = new oneSearch($row);
            }
            unset($data);
        }
        return $r;
    }

}
