<?php

namespace App\Services;

use Hashids\Hashids as Hashids;

class Uid {

    private $hashids;

    function __construct() {

        $appConfig = load_config_one('app', array('path'));
        
        if ($appConfig['env'] == 'production') {

            $this->hashids = new Hashids('VCXSUimRkU', 6);
        } else {

            $this->hashids = new Hashids('8BvwflK2Bh', 6);
        }
    }

    public function genrate($resourceId, $resourceType = '') {

        return $this->hashids->encode($resourceId);
    }

    public function decode($id, $resourceType = '') {

        return $this->hashids->decode($id);
    }

}
