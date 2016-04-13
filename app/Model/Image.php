<?php
/**
 * I'm the simple image object
 * that converts data into useful links and other info
 * @see https://www.flickr.com/services/api/misc.urls.html for more info on flickr image stuff
 */
namespace Flickrer\Model;

class Image extends Base
{
    /**
     * setup the base data here
     * @param $data
     */
    public function __construct($data)
    {
        $this->allowedKeys = ['id', 'owner', 'secret', 'server', 'farm', 'title'];
        parent::__construct($data);
    }

    public function getFullSrc()
    {
        return "https://farm{$this->data['farm']}.staticflickr.com/{$this->data['server']}/{$this->data['id']}_{$this->data['secret']}.jpg";
    }

    public function getThumbSrc()
    {
        return "https://farm{$this->data['farm']}.staticflickr.com/{$this->data['server']}/{$this->data['id']}_{$this->data['secret']}_q.jpg";
    }

    public function getPhotoPage()
    {
        return "https://www.flickr.com/photos/{$this->data['owner']}/{$this->data['id']}";
    }

    public function getCaption()
    {
        return htmlentities($this->get('title'));
    }
}
