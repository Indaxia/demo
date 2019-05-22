<?php

namespace App\Access\Document;

use App\Access\Model\UserInterface;
use App\Access\Model\UserIdentityInterface;
use App\Access\Model\UserAuthenticityInterface;
use App\Access\Model\UserSettingsInterface;
use App\Access\Model\UserContactsInterface;
use App\Common\Document\Traits\HasCreatedAt;
use App\Common\Document\Traits\HasUpdatedAt;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use App\Access\Model\UserVerificationStateInterface;

/**
 * @ODM\Document(collection="access_users", repositoryClass="App\Access\Repository\UserRepository")
 * @ODM\HasLifecycleCallbacks
 */
class User implements UserInterface
{
    use HasCreatedAt, HasUpdatedAt;

    /**
     * @var string|null
     * @ODM\Id
     */
    protected $id;

    /**
     * @var \App\Access\Document\UserIdentity
     * @ODM\EmbedOne(targetDocument="UserIdentity")
     */
    protected $identity;

    /**
     * @var \App\Access\Document\UserAuthenticity
     * @ODM\EmbedOne(targetDocument="UserAuthenticity")
     */
    protected $authenticity;

    /**
     * @var \App\Access\Document\UserVerificationState
     * @ODM\EmbedOne(targetDocument="UserVerificationState")
     */
    protected $verificationState;

    /**
     * @var \App\Access\Document\UserContacts
     * @ODM\EmbedOne(targetDocument="UserContacts")
     */
    protected $contacts;

    /**
     * @var \App\Access\Document\UserSettings
     * @ODM\EmbedOne(targetDocument="UserSettings")
     */
    protected $settings;

    /**
     * @var \DateTime|null
     * @ODM\Field(type="date", nullable=true)
     */
    protected $bannedAt;

    /**
     * @var string
     * @ODM\Field(type="string")
     */
    protected $banReason;

    /**
     * @var string[]
     * @ODM\Field(type="collection")
     */
    protected $roles;


    /**
     * User constructor.
     */
    public function __construct() {
        $this->identity = new UserIdentity(null);
        $this->authenticity = new UserAuthenticity(null);
        $this->verificationState = new UserVerificationState(false);
        $this->contacts = new UserContacts();
        $this->settings = new UserSettings();
        $this->banReason = '';
        $this->roles = [];
    }

    public function getId()
    {
        return $this->id;
    }

	public function getIdentity(): UserIdentityInterface
	{
		return $this->identity;
	}

	public function setIdentity(UserIdentityInterface $value): UserInterface
	{
		$this->identity = $value;

		return $this;
	}

	public function getAuthenticity(): UserAuthenticityInterface
	{
		return $this->authenticity; 
	}

	public function setAuthenticity(UserAuthenticityInterface $value): UserInterface
	{
		$this->authenticity = $value;

		return $this;
	}

	public function getVerificationState(): UserVerificationStateInterface
	{
		return $this->verificationState; 
	}

	public function setVerificationState(UserVerificationStateInterface $value): UserInterface
	{
		$this->verificationState = $value;

		return $this;
	}

	public function getContacts(): UserContactsInterface
	{
		return $this->contacts; 
	}

	public function setContacts(UserContactsInterface $value): UserInterface
	{
		$this->contacts = $value;

		return $this;
    }

    public function getSettings(): UserSettingsInterface
    {
        return $this->settings;
    }

    public function setSettings(UserSettingsInterface $settings): UserInterface
    {
        $this->settings = $settings;

        return $this;
    }

	public function getBannedAt(): ?\DateTime
	{
		return $this->bannedAt; 
	}

	public function setBannedAt(?\DateTime $value): UserInterface
	{
		$this->bannedAt = $value;

		return $this;
	}

    public function getBanReason(): string
    {
        return $this->banReason;
    }

    public function setBanReason(string $banReason): UserInterface
    {
        $this->banReason = $banReason;

        return $this;
    }

    public function is(?UserInterface $anotherUser): bool
    {
        return $anotherUser ? $this->getId() === $anotherUser->getId() : false;
    }

    public function hasAnyRole($roleOrRoles): bool
    {
        return is_string($roleOrRoles) ? in_array($roleOrRoles, $this->roles) : count(array_intersect($roleOrRoles, $this->getRoles())) > 0;
    }

    public function hasAllRoles(array $roles): bool
    {
        return array_intersect($roles, $this->getRoles()) == $roles;
    }

    // Symfony UserInterface compatibility

	public function getRoles(): array
	{
		return $this->roles; 
	}

    /**
     * @param string[] $value
     * @return UserInterface
     */
	public function setRoles(array $value)
	{
		$this->roles = $value;

		return $this;
	}
    
    public function getPassword() 
    {
        return $this->getAuthenticity()->getAuthenticator();
    }

    public function getSalt()
    {
        return '';
    }

    public function getUsername()
    {
        return $this->getIdentity()->getIdentifier();
    }
    
    public function eraseCredentials()
    {        
    }

}