<?php
namespace App\Access\Factory;

use App\Access\Model\UserAuthenticityInterface;
use App\Access\Exception\UserAuthenticatorException;

interface UserAuthenticityFactoryInterface {
    /**
     * @param string $rawAuthenticity The user-supplied authenticity data, e.g. original password without salt and encryption type
     * @param bool $alreadyEncoded Set to true to skip the encoding step
     * @return UserAuthenticityInterface
     * @throws UserAuthenticatorException
     */
    public function create(string $rawAuthenticity, bool $alreadyEncoded = false): UserAuthenticityInterface;
}