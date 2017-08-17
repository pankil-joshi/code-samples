<?php

namespace App\Middlewares;

use Quill\Exceptions\BaseException;
use Quill\Exceptions\StartupException;
use App\Middlewares\BaseMiddleware as BaseMiddleware;
use Lcobucci\JWT\Parser;
use Lcobucci\JWT\ValidationData;
use Lcobucci\JWT\Signer\Rsa\Sha256;
use Lcobucci\JWT\Signer\Key;

class AuthenticationMiddleware extends BaseMiddleware {

    private $ignore = array();

    function __construct(Array $ignore = array()) {

        $this->ignore = $ignore;
    }

    public function call() {

        $config = load_config_one('jwt', array('url', 'path', 'app'));

        $app = $this->app;

        session_start();
        $isAuthorized = function () use ($app, $config) {

            if (strpos($app->request()->getPathInfo(), '/api') === 0) {

                foreach ($this->ignore as $route) {

                    $allowed = ($app->request()->getPathInfo() == $route);

                    if ($allowed)
                        break;
                }

                if ($allowed === FALSE) {
                    
                    $app->config('debug', false);
                    $app->error(function (\Exception $exception) use ($app) {
                        $app->render('jsonException.php', array('exception' => $exception));
                    });
                    $userAgent = $app->request->getUserAgent();

                    $userAgent = explode('/', $userAgent);

//                    if ($userAgent[0] == 'TagzieAppAndroid') {
//
//                        if ($userAgent[1] < $config['min_android_app_version']) {
//
//                            throw new StartupException('Please update your app.', 'force_upgrade', array('android_url' => $config['android_play_url'], 'ios_url' => $config['ios_appstore_url']));
//                        }
//                    }
//
//                    if ($userAgent[0] == 'TagzieAppiOS') {
//
//                        if ($userAgent[1] < $config['min_ios_app_version']) {
//
//                            throw new StartupException('Please update your app.', 'force_upgrade', array('android_url' => $config['android_play_url'], 'ios_url' => $config['ios_appstore_url']));
//                        }
//                    }
                    
                    $token = (!empty($_COOKIE['token'])) ? $_COOKIE['token'] : $app->request->headers->get('token');
                    $signer = new Sha256();
                    $token = (new Parser())->parse((string) $token); // Parses from a string

                    $_SERVER['HTTP_TOKEN'] = '';

                    $publicKey = new Key($config['public_key_path']);

                    try {

                        if ($token->verify($signer, $publicKey)) {

                            $data = new ValidationData(); // It will use the current time to validate (iat, nbf and exp)

                            $data->setIssuer($config['token_issuer']);
                            $data->setAudience($config['token_audience']);
                            $data->setId('mobile');

                            if ($token->validate($data)) {

                                $user = $token->getClaim('user');
                                
                                $userObj = new \App\Models\User();
                                
                                $user = $userObj->validateCustomer($user->id);
                                
                                if(empty($user)) {
                                    
                                    throw new BaseException('Access to your account has been restricted.', array(), 403);
                                }

                                $app->container->singleton('user', function () use ($user) {

                                    return $user;
                                });
                            } else {

                                throw new BaseException('Token validation failed.');
                            }
                        } else {

                            throw new BaseException('Token verification failed.');
                        }
                    } catch (\Exception $ex) {

                        throw new BaseException($ex->getMessage());
                    }
                }
            }
        };

        $app->hook('slim.before.dispatch', $isAuthorized);
        // Run inner middleware and application
        $this->next->call();
    }

}
