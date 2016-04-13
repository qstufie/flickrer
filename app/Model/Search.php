<?php
/**
 * User object
 * that retains a single user's data
 */
namespace Flickrer\Model;

use Flickrer\App;

class Search extends Base
{
    /**
     * setup the base data here
     * @param $data
     */
    public function __construct($data)
    {
        $this->allowedKeys = ['id', 'user_id', 'search_params', 'updated_at', 'created_at'];
        // flatten search params
        if (!empty($data['search_params']) && !is_string($data['search_params'])) {
            $data['search_params'] = json_encode($data['search_params']);
        }
        parent::__construct($data);
    }

    /**
     * get search params in array form
     */
    public function getSearchParams()
    {
        $params = $this->get('search_params');
        if (is_string($params)) {
            $params = json_decode($params, true);
        }
        return $params;
    }

}
