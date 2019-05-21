<?php
namespace App\Access\Model;

use FOS\UserBundle\Model\UserInterface as FOSUserInterface;
use App\Access\Document\UserSettings;

interface UserInterface extends FOSUserInterface {
    public function getFullName(): string;
    public function setFullName(string $fullName): UserInterface;
    public function getBanReason(): string;
    public function setBanReason(string $banReason): UserInterface;
    public function getEmailConfirmed(): bool;
    public function setEmailConfirmed(bool $emailConfirmed): UserInterface;
    public function is(?UserInterface $anotherUser): bool;
    public function hasAnyRole(array $roles): bool;
    public function hasAllRoles(array $roles): bool;
    public function getSettings(): UserSettings;
    public function setSettings(UserSettings $settings): UserInterface;
    public function getCountry(): ?string;
    public function setCountry(?string $value): UserInterface;
}