<?php
namespace App\Access\Factory;

use App\Access\Model\UserInterface;
use App\Access\Exception\UserIdentityFactoryException;
use App\Access\Exception\UserAuthenticatorException;
use App\Access\Exception\UserFactoryException;

interface UserFactoryInterface 
{
    /**
     * @param string|null $rawIdentity
     * @param string|null $rawAuthenticity
     * @return UserInterface
     * @throws UserIdentityFactoryException
     * @throws UserAuthenticatorException
     * @throws UserFactoryException
     */
    public function create(
        string $rawIdentity = null,
        string $rawAuthenticity = null
    ): UserInterface;
}