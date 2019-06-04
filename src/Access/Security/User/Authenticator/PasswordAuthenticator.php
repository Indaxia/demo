<?php
namespace App\Access\Security\User\Authenticator;

use App\Access\Security\User\AuthenticatorInterface;
use App\Access\Exception\UserAuthenticatorException;
use App\Access\Model\UserAuthenticityInterface;

/**
 * Provides password authentication using password_hash function
 */
class PasswordAuthenticator implements AuthenticatorInterface {
    private $algorithm;
    private $options;
    private $min;
    private $max;

    /**
     * @param int $algorithm
     * @param array $options
     * @see https://www.php.net/manual/en/function.password-hash.php
     */
    public function __construct(int $min = 3, int $max = 32, int $algorithm = \PASSWORD_BCRYPT, array $options = null) {
        $this->algorithm = $algorithm;
        if($algorithm == \PASSWORD_BCRYPT && empty($options)) {
            $options = [
                'cost' => 12
            ];
        }
        $this->options = $options ?? [];
    }

    /**
     * Generates UserAuthenticity from the original authenticity data
     * @param string $rawAuthenticity The user-supplied authenticity data, e.g. original password without salt and encryption type
     * @return string Encoded authenticity data, e.g. password hash with salt
     * @throws UserAuthenticatorException
     */
    public function generate(string $rawAuthenticity): string
    {
        $this->validate($rawAuthenticity);
        $result = password_hash($rawAuthenticity, $this->algorithm, $this->options);
        if($result === false) {
            throw new UserAuthenticatorException('Cannot generate authenticity. The hash algorithm may not be supported.');
        }
        return $result;
    }

    /**
     * Performs authentication
     * @param string $rawAuthenticity The user-supplied authenticity data, e.g. original password without salt and encryption type
     * @param UserAuthenticityInterface $known The authenticity stored in our system and provided to compare with
     * @return bool Authenticated or not
     * @throws UserAuthenticatorException
     */
    public function authenticate(string $rawAuthenticity, UserAuthenticityInterface $known): bool
    {
        $this->validate($rawAuthenticity);
        return password_verify($rawAuthenticity, $known->getHash());
    }

    private function validate(string $rawAuthenticity)
    {
        $len = strlen($rawAuthenticity);
        if($len < $this->min || $len > $this->max) {
            throw new UserAuthenticatorException('Password length must be from ' . $this->min . ' to ' . $this->max .' symbol(s) long');
        }
    }
}