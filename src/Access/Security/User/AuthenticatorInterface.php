<?php
namespace App\Access\Security\User;

use App\Access\Model\UserAuthenticityInterface;
use App\Access\Exception\UserAuthenticatorException;

interface AuthenticatorInterface {
    /**
     * Generates UserAuthenticity from the original authenticity data
     * @param string $rawAuthenticity The user-supplied authenticity data, e.g. original password without salt and encryption type
     * @return string Encoded authenticity data, e.g. password hash with salt
     * @throws UserAuthenticatorException
     */
    public function generate(string $rawAuthenticity): string;

    /**
     * Performs authentication
     * @param string $rawAuthenticity The user-supplied authenticity data, e.g. original password without salt and encryption type
     * @param UserAuthenticityInterface $known The authenticity stored in our system and provided to compare with
     * @return bool Authenticated or not
     * @throws UserAuthenticatorException
     */
    public function authenticate(string $rawAuthenticity, UserAuthenticityInterface $known): bool;
}