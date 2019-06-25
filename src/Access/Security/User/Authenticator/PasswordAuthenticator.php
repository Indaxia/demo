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

    /**
     * @param int $algorithm
     * @param array $options
     * @see https://www.php.net/manual/en/function.password-hash.php
     */
    public function __construct(int $algorithm = \PASSWORD_BCRYPT, array $options = null) {
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
        return password_verify($rawAuthenticity, $known->getHash());
    }
}