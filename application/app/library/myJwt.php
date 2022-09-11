<?php

use Phalcon\Security\JWT\Builder;
use Phalcon\Security\JWT\Signer\Hmac;
use Phalcon\Security\JWT\Token\Parser;
use Phalcon\Security\JWT\Validator;
use Phalcon\Security\JWT\Exceptions\ValidatorException;


class myJwt{

    public $signer;
    public $builder;
    private $passphrase;
    public $now;
    public $issued;
    public $notBefore;
    public $expires;
    private $id;
    
    public function __construct() { 
        $this->signer =  new Hmac();
        $this->builder =  new Builder($this->signer);
        $this->passphrase = 'QcMpZ&b&mo3TPsPk668J6QH8JA$&U&m2';
        $this->now        = new DateTimeImmutable();
        $this->issued     = $this->now->getTimestamp();
        $this->notBefore  = $this->now->modify('-1 minute')->getTimestamp();
        $this->expires    = $this->now->modify('+1 day')->getTimestamp();
        $this->id    = "abcd123456789";

        $now        = new DateTimeImmutable();
        $issued     = $now->getTimestamp();
        $notBefore  = $now->modify('-1 minute')->getTimestamp();
        $expires    = $now->modify('+1 day')->getTimestamp();
    }

    public function token($id){
        
        $this->builder
            //->setAudience('https://target.phalcon.io')  // aud
            ->setContentType('application/json')        // cty - header
            ->setExpirationTime($this->expires)               // exp 
            ->setId($this->id)                    // JTI id 
            ->setIssuedAt($this->issued)                      // iat 
            //->setIssuer('https://phalcon.io')           // iss 
            ->setNotBefore($this->notBefore)                  // nbf
            ->setSubject($id)   // sub
            ->setPassphrase($this->passphrase);            // password 
       
        $tokenObject = $this->builder->getToken();

        $data = array(
                "token"=>$tokenObject->getToken(),
            );

        
        return $data;
  
    }

    public function validateToken($token){

        try{
            $now           = new DateTimeImmutable();
            $expires       = $now->getTimestamp();
            $parser      = new Parser();
            
            $tokenObject = $parser->parse($token);
            
            $validator = new Validator($tokenObject, 100); // allow for a time shift of 100
  
            $validator
                //->validateAudience('https://target.phalcon.io')
                ->validateExpiration($expires)
                ->validateId($this->id)
                ->validateIssuedAt($this->issued)
                //->validateIssuer('https://phalcon.io')
                ->validateNotBefore($this->notBefore)
                ->validateSignature($this->signer, $this->passphrase);
            
            $user = $tokenObject->getClaims()->getPayload()["sub"];
            return $user;
        }
        catch (Exception $ex) {
            return false;
        }
       
        
        
    }



}