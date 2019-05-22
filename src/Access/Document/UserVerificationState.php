<?php
namespace App\Access\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use App\Access\Model\UserVerificationStateInterface;

/**
 * @ODM\EmbeddedDocument
 */
class UserVerificationState implements UserVerificationStateInterface
{
    /**
     * @var bool
     * @ODM\Field(type="bool")
     * @ODM\Index
     */
    protected $verified;

    /**
     * @var array
     * @ODM\Field(type="hash")
     */
    protected $factors;

    public function __construct(bool $verified = false)
    {
        $this->verified = $verified;
        $this->factors = [];
    }

	/**
	 * @return string 
	 */
	public function isVerified(): bool
	{
		return $this->verified; 
	}

	/**
	 * @param bool $value 
	 * @return UserVerificationStateInterface
	 */
	public function setVerified(bool $value): UserVerificationStateInterface
	{
		$this->verified = $value;

		return $this;
    }
    
    public function getFactor(string $id)
    {
        return $this->factors[$id] ?? null;
    }

    public function setFactor(string $id, $data)
    {
        $this->factors[$id] = $data;
    }

    public function getFactors(): array
    {
        return $this->factors;
    }
}