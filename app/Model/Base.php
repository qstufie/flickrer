<?php
/**
 * Let's make a nice base
 * for other objects to take advantage of
 *
 */
namespace Flickrer\Model;

class Base
{
    /**
     * a good object needs to have a state storage
     * @var array
     */
    protected $data = [];

    /**
     * and it needs a setter
     * @param $k
     * @param $v
     * @return $this
     */
    public function set($k, $v)
    {
        $this->data[$k] = $v;
        return $this;
    }

    /**
     * then a getter
     * @param $k
     * @param $default
     * @return mixed
     */
    public function get($k, $default)
    {
        return isset($this->data[$k]) ? $default : $this->data[$k];
    }

    /**
     * of course, toJson just so we can make one less call
     * if we need to output json string
     * @return string
     */
    public function toJson()
    {
        return json_encode($this->data);

    }

    /**
     * retrieve all data
     * @return array
     */
    public function getData()
    {
        return $this->data;
    }

}
