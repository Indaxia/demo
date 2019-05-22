<?php
namespace App\Access\Factory;

use App\Access\Model\UserAuthenticityInterface;

interface UserAuthenticityFactoryInterface {
    /**
     * Creates UserAuthenticity. 
     * It must be created the same way as the others, e.g. provide salt for user authentication.
     * @param $rawAuthenticator Data depends on the implementation. 
     * @return UserAuthenticityInterface
     */
    public function create($rawAuthenticator): UserAuthenticityInterface;
}