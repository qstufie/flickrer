<?php
/**
 * Flickrer App
 * @author Bruce B li
 */
namespace Flickrer {

    use Flickrer\Service\User;
    use Flickrer\Utility\Object;
    use Slim\Http\Request;
    use Slim\Http\Response;

    /**
     * Class Flick
     * main entry point for flickrer app
     * @package Flickrer
     */
    class App
    {
        /**
         * the single app
         * @var \Slim\App
         */
        protected $app;

        /**
         * settings object
         * @var \Slim\Collection
         */
        public $settings;


        /**
         * Single instance of flickrer
         */
        public static function singleton()
        {
            return Object::singleton(__CLASS__);
        }

        /**
         * retrieve setting
         * @param $k
         * @return mixed
         */
        public static function getSetting($k)
        {
            return self::singleton()->settings->get($k);
        }


        /**
         * constructor
         * init the slim app
         */
        public function __construct()
        {
            $env = getenv('AppEnv');
            if (empty($env)) $env = 'dev';

            $conf = require_once __DIR__ . '/config.php';

            $this->app = new \Slim\App(['settings' => $conf[$env]]);

            $container = $this->app->getContainer();
            $this->settings = $container->get('settings');

            $this->routers();
        }

        /**
         * setup routers
         */
        public function routers()
        {
            $app = $this->app;
            // index pure html
            $this->app->get('/', function (Request $request, Response $response) {
                $response->getBody()->write('adfsaf');
                return $response;
            });

            // user related
            $user = User::singleton();

            $this->app->get('/user', function (Request $request, Response $response) use ($user, $app) {
                $response->withJson(['user' => $user->getInfo(), 'db' => App::getSetting('db')]);
            });
        }

        /**
         * run app
         */
        public function run()
        {
            $this->app->run();
        }


    }

}
