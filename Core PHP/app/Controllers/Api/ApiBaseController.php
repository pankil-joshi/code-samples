<?php

namespace App\Controllers\Api;

use App\Controllers\BaseController as BaseController;
use Quill\Exceptions\BaseException;

class ApiBaseController extends BaseController {

    protected $validator, $jsonRequest;

    function __construct($app = NULL) {

        parent::__construct($app);

        if (!$app->config('is_cli') && !$app->is_callback) {

            $app->slim->config('debug', false);

            $app->slim->error(function (\Exception $exception) use ($app) {
                $app->slim->render('jsonException.php', array('exception' => $exception));
            });

            if ($this->request->isPost()) {

                $this->jsonRequest = json_decode($this->request->getBody(), TRUE);

                if ($this->request->headers->get('CONTENT_TYPE') == 'application/json') {

                    if (!$this->jsonRequest) {

                        throw new BaseException('Invalid request format.');
                    }
                }
            }
        }

        $this->_loadJsonExceptionHandler();
    }

    private function _loadJsonExceptionHandler() {
        
    }

}
