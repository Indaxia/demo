<?php
namespace App\Access\Factory\UserIdentity;

use App\Access\Factory\UserIdentityFactoryInterface;
use App\Access\Exception\UserIdentityFactoryException;
use App\Access\Document\UserIdentity;
use App\Access\Model\UserIdentityInterface;

class UserIdentityEmailFactory implements UserIdentityFactoryInterface {
    /**
     * @param array|string|int|float $rawIdentifier
     * @return UserIdentityInterface
     * @throws UserIdentityFactoryException
     */
    public function create($rawIdentifier): UserIdentityInterface
    {
        if(!is_string($rawIdentifier)) {
            throw new UserIdentityFactoryException('Identifier expected to be a string, got ' . gettype($rawIdentifier));
        }

        $rawIdentifier = filter_var($rawIdentifier, \FILTER_VALIDATE_EMAIL);
        if($rawIdentifier === false) {
            throw new UserIdentityFactoryException('Identifier is not a valid email');
        }

        return new UserIdentity(strtolower($rawIdentifier));
    }
}