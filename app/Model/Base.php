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
     * allowed keys
     * @var array
     */
    protected $allowedKeys = [];

    /**
     * a good object needs to have a state storage
     * @var array
     */
    protected $data = [];

    /**
     * setup the base data here
     * @param $data
     */
    public function __construct($data)
    {
        $this->data = $data;
    }

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
    public function get($k, $default = null)
    {
        return isset($this->data[$k]) ? $this->data[$k] : $default;
    }

    /**
     * of course, toJson just so we can make one less call
     * if we need to output json string
     * @return string
     */
    public function toJson()
    {
        return json_encode($this->getData());

    }

    /**
     * retrieve all data
     * @return array
     */
    public function getData($allowedKeysOnly = true)
    {
        if ($allowedKeysOnly && !empty($this->allowedKeys)) {
            $data = [];
            foreach ($this->data as $k => $v) {
                if (in_array($k, $this->allowedKeys)) {
                    $data[$k] = $v;
                }
            }
            return $data;
        }

        return $this->data;
    }

}
