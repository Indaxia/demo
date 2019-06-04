<?php
namespace App\Access\Factory\UserIdentity;

use App\Access\Factory\UserIdentityFactoryInterface;
use App\Access\Exception\UserIdentityFactoryException;
use App\Access\Model\UserIdentityInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class UserIdentityUsernameFactory extends GenericUserIdentityFactory implements UserIdentityFactoryInterface {
    protected $regexp;
    protected $convertToLowercase;

    /**
     * @param bool $convertToLowercase If false the user won't be able to login using another letter case
     * @param string $regexp
     */
    public function __construct(ContainerInterface $container, bool $convertToLowercase = true, string $regexp = "/^[\w\d\-\_]{3,32}$/")
    {
        parent::__construct($container);
        $this->regexp = $regexp;
        $this->convertToLowercase = $convertToLowercase;
    }

    /**
     * @param array|string|int|float $rawIdentifier
     * @return UserIdentityInterface
     * @throws UserIdentityFactoryException
     */
    public function create($rawIdentifier): UserIdentityInterface
    {
        if(!is_string($rawIdentifier)) {
            throw new UserIdentityFactoryException('Identifier expected to be a string, got ' . gettype($rawIdentifier));
        }

        if(!preg_match($this->regexp, $rawIdentifier)) {
            throw new UserIdentityFactoryException('Wrong Identifier format');
        }

        return $this->create($this->convertToLowercase ? strtolower($rawIdentifier) : $rawIdentifier);
    }
}
