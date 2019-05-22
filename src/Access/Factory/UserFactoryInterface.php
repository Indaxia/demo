<?php
namespace App\Access\Factory;

use App\Access\Model\UserInterface;

interface UserFactoryInterface {
    public function create(
        string $rawIdentity = null,
        string $rawAuthenticity = null,
        bool $initiallyVerified = false
    ): UserInterface;
}