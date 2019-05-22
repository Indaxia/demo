<?php
namespace App\Access\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use App\Access\Model\UserIdentityInterface;

/**
 * @ODM\EmbeddedDocument
 */
class UserIdentity implements UserIdentityInterface
{
    /**
     * @var string
     * @ODM\Field(type="string", nullable=true)
     * @ODM\Index(unique=true)
     */
    protected $identifier;

    public function __construct(?string $identifier)
    {
        $this->identifier = $identifier;
    }

    public function getIdentifier(): ?string
    {
        return $this->identifier;
    }
}