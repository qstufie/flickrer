<?php
/**
 * flickr service layer
 * uses Dao\Flickr to store info that's required
 * and make simple requests to flickr
 */
namespace Flickrer\Service;

use Flickrer\App;
use Flickrer\Dao\Search;
use Flickrer\Model\Image;
use Flickrer\Utility\Object;

/**
 * Class Flickr
 * @package Flickrer\Service
 */
class Flickr
{
    /**
     * single instance of the service
     * @return Flickr
     */
    public static function singleton()
    {
        return Object::singleton(__CLASS__);
    }

    /**
     * verify and retrieve the right uid
     * @param $uid
     * @return int
     * @throws \Exception
     */
    private function verifyUid($uid)
    {
        $uid = (int)$uid;
        // retrieve uid from session if not specified
        if ($uid <= 0) {
            $uid = User::singleton()->getUserId();
        }
        // then check again to ensure no uid is wasted
        if (empty($uid)) {
            throw new \Exception('You must be logged in to use this service, currently, the info: ' . json_encode(User::singleton()->getInfo()));
        }
        return $uid;
    }

    /**
     * add a new search entry
     * NOTE: $uid is only used in unit tests as CLI doesn't have session, in no app
     * should $uid be specified directly, as it will cause a potential security risk (write data to someone else's entries)
     * @param $params
     * @param int $uid
     * @return \Flickrer\Model\Search
     * @throws \Exception
     */
    public function addSearch($params, $uid = 0)
    {
        $uid = $this->verifyUid($uid);
        return Search::singleton()->addSearch($uid, $params);

    }

    /**
     * retrieve searches by user id
     * @param int $uid
     * @return array
     * @throws \Exception
     */
    public function getSearches($uid = 0)
    {
        $uid = $this->verifyUid($uid);
        $searches = Search::singleton()->getSearchesByUserId($uid);
        $result = [];
        foreach ($searches as $search) {
            if ($search instanceof \Flickrer\Model\Search) {
                $tmp = [];
                $params = $search->getSearchParams();
                foreach ($params as $k => $v) {
                    $tmp['_' . $k] = $v;
                }
                $tmp['_created_at'] = $search->get('created_at');
                $result[] = $tmp;
            }
        }

        unset($searches);
        return ['element' => $result];

    }

    /**
     * search for data
     * @param $params (note, use per_page: <int> | page: <int page> to page it through
     * @param $params
     * @param int $uid
     * @return array
     * @throws \Exception
     */
    public function search($params, $uid = 0)
    {
        $uid = $this->verifyUid($uid);
        // default paging
        if (empty($params['per_page'])) {
            $params['per_page'] = App::getSetting('items_per_page');
        }

        $endpoint = App::getSetting('endpoint');
        foreach ($params as $k => $v) {
            $v = trim($v);
            if (!empty($v)) {
                $endpoint .= "&{$k}=" . urlencode($v);
            }
        }
        $jsonData = file_get_contents($endpoint);
        $data = @json_decode($jsonData, true);
        if (empty($data) || empty($data['photos']['photo'])) {
            throw new \Exception('invalid json response, url: ' . $endpoint);
        }

        // process data and make it SimpleJS friendly (use _varname to avoid html-attributes conflicts)
        $images = [];
        foreach ($data['photos']['photo'] as $rawImgData) {
            $image = new Image($rawImgData);
            $tmp = [];
            $tmp['_full_src'] = $image->getFullSrc();
            $tmp['_caption'] = $image->getCaption();
            $tmp['_thumb_src'] = $image->getThumbSrc();
            $tmp['_url'] = $image->getPhotoPage();
            $images[] = $tmp;
        }

        // since we have data now, we also add the recent search
        $this->addSearch($params, $uid);

        // then we free memory a bit
        unset($data['photos']['photo'], $jsonData);

        $meta = [];
        foreach ($data['photos'] as $k => $v) {
            $meta['_' . $k] = $v;
        }

        // now work out paging
        $paging = [];
        $page = $meta['_page'];
        $pages = $meta['_pages'];

        // google style paging! yay!
        $start = $page - 5;
        $end = $page + 5;

        if ($start <= 0) {
            $end = $end - $start + 1;
            $start = 1;
        }
        if ($end > $pages) {
            $end = $pages;
        }
        for ($i = $start; $i <= $end; $i++) {
            $tmp = [];
            // make it template friendly
            foreach ($params as $k => $v) {
                $tmp['_' . $k] = $v;
            }
            $tmp['_page'] = $i;
            $paging[] = $tmp;
        }

        return [
            'meta' => $meta,
            'images' => ['element' => $images],
            // also all recent searches
            'recent_searches' => $this->getSearches($uid),
            'paging' => ['element' => $paging]
        ];
    }

}
