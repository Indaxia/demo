<?php
namespace App\Access\Model;

use Symfony\Component\Security\Core\User\UserInterface as SymfonyUserInterface;
use App\Access\Model\UserIdentityInterface;
use App\Access\Model\UserAuthenticityInterface;
use App\Access\Model\UserContactsInterface;
use App\Access\Model\UserVerificationStateInterface;

interface UserInterface extends SymfonyUserInterface 
{
    /** 
     * @return mixed Database layer identifier
     */
    public function getId();

    public function getCreatedAt(): \DateTime;
    public function getUpdatedAt(): \DateTime;

    public function getIdentity(): UserIdentityInterface;
    public function setIdentity(UserIdentityInterface $value): UserInterface;

    public function getAuthenticity(): UserAuthenticityInterface;
    public function setAuthenticity(UserAuthenticityInterface $value): UserInterface;

    public function getVerificationState(): UserVerificationStateInterface;
    public function setVerificationState(UserVerificationStateInterface $value): UserInterface;

    public function getContacts(): UserContactsInterface;
    public function setContacts(UserContactsInterface $value): UserInterface;

    public function getSettings(): UserSettingsInterface;
    public function setSettings(UserSettingsInterface $settings): UserInterface;

    /**
     * Determines whether the user's access is denied by administration. 
     *
     * @return \DateTime|null
     */
    public function getBannedAt(): ?\DateTime;

    /**
     * Denies user's access to the system. This is an administrative tool.
     *
     * @return \DateTime|null
     */
    public function setBannedAt(?\DateTime $value): UserInterface;

    public function getBanReason(): string;
    public function setBanReason(string $value): UserInterface;

    /**
     * Determines whether the given user's id equals to current user's id
     * @param \App\Access\Interface\UserInterface|null $anotherUser
     * @return bool
     */
    public function is(?UserInterface $anotherUser): bool;

    /**
     * Determines whether the user has any of the given role/roles.
     * @param array|string $roleOrRoles
     * @return bool
     */
    public function hasAnyRole($roles): bool;

    /**
     * Determines whether the user has all of the given roles.
     * @param array $roles
     * @return bool
     */
    public function hasAllRoles(array $roles): bool;
}