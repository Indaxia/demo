<?php
namespace App\Access\Security\Symfony;

use Symfony\Component\Security\Core\Encoder\BasePasswordEncoder;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;
use App\Access\Security\User\AuthenticatorInterface;
use App\Access\Factory\UserAuthenticityFactoryInterface;
use App\Access\Exception\UserAuthenticatorException;

class PasswordEncoder extends BasePasswordEncoder
{
    /**
     * @var AuthenticatorInterface
     */
    private $authenticator;

    /**
     * @var UserAuthenticityFactoryInterface
     */
    private $authenticityFactory;

    public function __construct(AuthenticatorInterface $authenticator, UserAuthenticityFactoryInterface $authenticityFactory)
    {
        $this->authenticator = $authenticator;
        $this->authenticityFactory = $authenticityFactory;
    }

    public function encodePassword($raw, $salt)
    {
        if(! empty($salt)) {
            throw new \InvalidArgumentException('Separate salt field is not supported by AuthenticatorInterface');
        }
        try {
            return $this->authenticator->generate($raw);
        } catch(UserAuthenticatorException $e) {
            throw new BadCredentialsException($e->getMessage(), 400, $e);
        }
    }

    public function isPasswordValid($encoded, $raw, $salt)
    {
        if(! empty($salt)) {
            throw new \InvalidArgumentException('Separate salt field is not supported by AuthenticatorInterface');
        }
        return $this->authenticator->authenticate($raw, $this->authenticityFactory->create($encoded, true));
    }
}