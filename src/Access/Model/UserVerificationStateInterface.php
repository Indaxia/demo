<?php
namespace App\Access\Model;

interface UserVerificationStateInterface
{
    /**
     * Provides overall verification state.
     * The ability to perform authentication depends on this state.
     *
     * @return bool
     */
    public function isVerified(): bool;

    /**
     * Set overall verification state. It affects authentication mechanics.
     * @see \App\Access\Security\AccessControlInterface
     *
     * @param bool $value
     * @return UserVerificationStateInterface
     */
    public function setVerified(bool $value): UserVerificationStateInterface;

    /**
     * Returns identification or authentication factor data. Returns null for non-existing records.
     * Examples:
     *  - the $id can be "email" and the value is true that means the email is verified
     *  - the $id can be "phone" and the value is ['number' => '+123456789', 'code' => 123456] 
     *    that means it awaits for the SMS with the code
     *
     * @param string $id
     * @return array|bool|string|int|float|null Returns null for non-existing records.
     */
    public function getFactor(string $id);

    /**
     * Sets identification or authentication factor data.
     * Examples:
     *  - the $id can be "email" and the value is true that means the email is verified
     *  - the $id can be "phone" and the value is ['number' => '+123456789', 'code' => 123456] 
     *    that means it awaits for the SMS with the code
     *
     * @param string $id
     * @param array|bool|string|int|float|null $data Specify null to remove the record
     */
    public function setFactor(string $id, $data);

    public function getFactors(): array;
}