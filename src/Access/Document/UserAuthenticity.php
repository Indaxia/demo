<?php
namespace App\Access\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use App\Access\Model\UserAuthenticityInterface;

/**
 * Supports "password" and "password~salt" format
 * @ODM\EmbeddedDocument
 */
class UserAuthenticity implements UserAuthenticityInterface
{
    /**
     * @var string
     * @ODM\Field(type="string", nullable=true)
     */
    protected $authenticationData;

    public function __construct(?string $authenticator)
    {
        $this->authenticator = $authenticator;
    }

    public function getAuthenticator(): ?string
    {
        
        return null;
    }

    public function getAuthenticationDataRaw(): ?string
    {
        return $this->authenticationData;
    }

    public function getAuthenticationData(): array
    {
        if($this->authenticator !== null) {
            $parsed = explode('~', $this->authenticator);
            $count = count($parsed);
            if($count > 1) {
                return [
                    'password' => $parsed[0],
                    'salt' => $count > 2 ? $parsed[1] : ''
                ];
            }
        }
        return [];
    }

    public static function createWithPassword($password, $salt = null)
    {
        return new static($password . ($salt === null ? '' : '~'.$salt));
    }
}