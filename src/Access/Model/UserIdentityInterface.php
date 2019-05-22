<?php
namespace App\Access\Model;

interface UserIdentityInterface 
{
    /**
     * Returns identification data encoded as a plain string
     * Examples: 
     *  - 'myUsername', 
     *  - 'myEmail', 
     *  - 'myUsername:myEmail', 
     *  - '{"email":"my-email","phone":"+123456789"}'
     *
     * @return string|null
     */
    public function getIdentifier(): ?string;
}