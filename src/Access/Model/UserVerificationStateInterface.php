<?php
namespace App\Access\Model;

use App\Access\Security\User\VerificationState\FactorInterface;
use App\Access\Exception\VerificationFactorException;
use App\Access\Exception\VerificationStateException;

interface UserVerificationStateInterface
{
    /**
     * Provides overall verification state.
     * The ability to perform authentication depends on this state.
     *
     * @throws VerificationFactorException On verification error
     * @throws VerificationStateException On system problem in verification process
     */
    public function verify(UserInterface $user);

    /**
     * Returns identification or authentication factor data. Returns null for non-existing records.
     *
     * @param string $class
     * @return FactorInterface|null Returns null if not found.
     * @throws VerificationStateException On system problem
     */
    public function getFactor(string $class): ?FactorInterface;

    /**
     * Sets identification or authentication factor data. There can be a single factor of one type at a time
     *
     * @param FactorInterface $factor
     */
    public function setFactor(FactorInterface $factor): UserVerificationStateInterface;

    public function removeFactor(string $class): UserVerificationStateInterface;
}