<?php
namespace App\Access\Security\User;

use App\Access\Model\UserAuthenticityInterface;

class Authenticator implements AuthenticatorInterface {
    /**
     * Performs timing attack safe authentication
     * @param UserAuthenticityInterface $known The authenticator of known length to compare against
     * @param UserAuthenticityInterface $known The user-supplied authenticator
     * @return bool Authenticated or not
     */
    public function authenticate(UserAuthenticityInterface $our, UserAuthenticityInterface $their): bool
    {
        return hash_equals($our->getAuthenticator(), $their->getAuthenticator());
    }
}