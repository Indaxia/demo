<?php
namespace App\Access\Factory\UserIdentity;

use App\Access\Factory\UserIdentityFactoryInterface;
use App\Access\Exception\UserIdentityFactoryException;
use App\Access\Document\UserIdentity;
use App\Access\Model\UserIdentityInterface;

class UserIdentityPhoneFactory implements UserIdentityFactoryInterface {
    protected $regexp;
    protected $filterRegexp;

    /**
     * @param bool $convertToLowercase If false the user won't be able to login using another letter case
     * @param string $regexp
     */
    public function __construct(
        string $regexp = "/^(?!(?:\d*-){5,})(?!(?:\d* ){5,})\+?[\d- ]+$/",
        string $filterRegexp = '/\s+/'
    ) {
        $this->regexp = $regexp;
        $this->filterRegexp = $filterRegexp;
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

        return new UserIdentity(preg_replace($this->filterRegexp, '', $rawIdentifier));
    }
}