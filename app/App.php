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
                $response->getBody()->write('Please browse into /index.html, thank you');
                return $response;
            });

            // user related
            $user = User::singleton();

            $this->app->get('/user', function (Request $request, Response $response) use ($user, $app) {
                $response->withJson($user->getInfo());
            });

            // user register
            $this->app->post('/user/rego', function (Request $request, Response $response) use ($user, $app) {
                try {
                    $data = $request->getParsedBody();

                    if (empty($data['name'])) {
                        $data['name'] = 'N/A';
                    }
                    if (empty($data['username']) || empty($data['password'])) {
                        throw new \Exception('Invalid input, please enter valid username/password');
                    }
                    $u = $user->register($data['username'], $data['password'], $data['name']);
                    if ($u instanceof \Flickrer\Model\User || $u === true) {
                        $response->withJson([
                            'success' => true,
                            'user' => $user->getInfo()
                        ]);
                    }
                } catch (\Exception $e) {
                    $response->withJson([
                        'success' => false,
                        'error' => $e->getMessage()
                    ]);
                }
            });

            // logout
            $this->app->get('/user/logout', function (Request $request, Response $response) {
                unset($_SESSION['isLoggedIn'], $_SESSION['user']);
                $response->withJson([
                    'message'=> 'user has logged out'
                ]);
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
