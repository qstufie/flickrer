<?php
/**
 * I like centralised object control
 * for certain layers such as service/dao
 * so this one manages the instances and ensure minimal memory usage
 * and on top of that, better debug potentials
 *
 */
namespace Flickrer\Utility;

class Object
{
    /**
     * instances of objects
     * @var array
     */
    protected static $instances = [];


    /**
     * singleton retriever
     * @param $className
     * @param array $opts
     * @return mixed
     */
    public static function singleton($className, $opts = [])
    {
        if (empty(self::$instances[$className])) {
            self::$instances[$className] = new $className($opts);
        }

        return self::$instances[$className];

    }


    /**
     * export all instances
     * @return array
     */
    public static function getInstances()
    {
        return self::$instances;

    }

}