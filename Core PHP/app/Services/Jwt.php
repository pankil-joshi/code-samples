<?php

namespace App\Services;

use Lcobucci\JWT\Builder;
use Lcobucci\JWT\Signer\Key;
use Lcobucci\JWT\Signer\Rsa\Sha256;

class Jwt {
    
    private $config;
    
    function __construct() {
        
        $this->config = load_config_one('jwt', array('url' , 'path'));    
        
    }
    
    public function genrateUserToken($userId, $issuer, $audience, $guest = 0) {  
        
        $signer = new Sha256();  
        $token = (new Builder())->setIssuer($issuer) // Configures the issuer (iss claim)
            ->setAudience($audience) // Configures the audience (aud claim)
            ->setId('mobile', true) // Configures the id (jti claim), replicating as a header item
            ->setIssuedAt(time()) // Configures the time that the token was issue (iat claim)
            ->setNotBefore(time()) // Configures the time that the token can be used (nbf claim)
            ->setExpiration(time() + 86400) // Configures the expiration time of the token (exp claim)
            ->set('user', array('id' => $userId)) // Configures a new claim, called "uid"
            ->set('guest', $guest) // Configures a new claim, called "uid"    
            ->sign($signer, $this->_getPrivateKey()) // creates a signature using your private key
            ->getToken(); // Retrieves the generated token
        
        return (string)$token;
        
    }
    
    private function _getPrivateKey() {      
        
        return  new Key($this->config['private_key_path'], $this->config['passphrase']);
        
    }

}