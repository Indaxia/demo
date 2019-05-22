<?php
namespace App\Access\Factory;

use App\Access\Model\UserIdentityInterface;
use App\Access\Exception\UserIdentityFactoryException;

interface UserIdentityFactoryInterface {
    /**
     * @param array|string|int|float $rawIdentifier
     * @return UserIdentityInterface
     * @throws UserIdentityFactoryException
     */
    public function create($rawIdentifier): UserIdentityInterface;
}