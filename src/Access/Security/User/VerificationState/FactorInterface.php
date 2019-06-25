<?php
namespace App\Access\Security\User\VerificationState;

use App\Access\Exception\VerificationFactorException;
use App\Access\Model\UserInterface;

interface FactorInterface 
{
    /**
     * Verifies it's state. Throws an exception if it cannot verify the user and the authentication must be rejected
     * @param UserInterface $user
     * @throws VerificationFactorException
     */
    public function verify(UserInterface $user);
}