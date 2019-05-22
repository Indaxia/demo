<?php
namespace App\Access\Model;

/**
 * Declares user contact data. 
 * This data cannot be used in identification processes as identifiers
 * but the identifiers can be copied here to provide further communication.
 */
interface UserContactsInterface
{
    public function getEmail(): string;
    public function setEmail(string $value): UserContactsInterface;
    public function getPhone(): string;
    public function setPhone(string $value): UserContactsInterface;
    public function getFullName(): string;
    public function setFullName(string $fullName): UserContactsInterface;
    public function getCountry(): ?string;
    public function setCountry(?string $value): UserContactsInterface;
}