<?php
namespace App\Access\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use App\Access\Model\UserAuthenticityInterface;

/**
 * @ODM\EmbeddedDocument
 */
class UserAuthenticity implements UserAuthenticityInterface
{
    /**
     * @var string
     * @ODM\Field(type="string", nullable=true)
     */
    protected $hash;

    public function __construct(?string $hash)
    {
        $this->hash = $hash;
    }

    public function getHash(): ?string
    {
        return $this->hash;
    }
}