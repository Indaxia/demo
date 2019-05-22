<?php

namespace App\Access\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use App\Access\Model\UserContactsInterface;

/**
 * @ODM\EmbeddedDocument
 */
class UserContacts implements UserContactsInterface
{
    /**
     * @var string
     * @ODM\Field(type="string")
     */
	protected $fullName = '';

    /**
     * @var string
     * @ODM\Field(type="string", nullable=true)
     */
    protected $country = null;
	
    /**
     * @var string
     * @ODM\Field(type="string")
     */
    protected $email = '';
    
    /**
     * @var string
     * @ODM\Field(type="string")
     */
    protected $phone = '';

	public function getFullName(): string
	{
		return $this->fullName; 
	}

	public function setFullName(string $value): UserContactsInterface
	{
		$this->fullName = $value;

		return $this;
	}

	public function getCountry(): ?string
	{
		return $this->country; 
	}

	public function setCountry(?string $value): UserContactsInterface
	{
		$this->country = $value;

		return $this;
	}

	public function getEmail(): string
	{
		return $this->email; 
	}

	public function setEmail(string $value): UserContactsInterface
	{
		$this->email = $value;

		return $this;
	}

	public function getPhone(): string
	{
		return $this->phone; 
	}

	public function setPhone(string $value): UserContactsInterface
	{
		$this->phone = $value;

		return $this;
	}
}