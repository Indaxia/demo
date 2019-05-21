<?php
namespace App\Access\Security;

use App\Access\Model\UserInterface;
use App\Access\Exception\AccessControlException;

interface AccessControlInterface {
    /**
     * @return \App\Access\Security\UserInterface|null Authenticated user if so
     */
    public function getAuthUser(): ?UserInterface; 

    /**
     * Checks authenticated user's access and returns authorized user. If access denied throws an exception.
     * @param string|array $roles Roles. Passes if the user has at least one role. Set to [] to skip role check
     * @param bool $checkEnabled Set to false to skip UserInterface::getEnabled check
     * @param bool $checkEmailConfirmation Set to false to skip UserInterface::getEmailConfirmed check
     * @param bool $checkFullRoleSubset Set to true to pass if the user has all the specified $roles only
     * @param \App\Access\Security\UserInterface|null $checkAnotherUser Set to null to use current session user
     * @return \App\Access\Security\UserInterface Authorized user
     * @throws \App\Access\Exception\AccessControlException Containing the valid HTTP status code
     */
    public function authorize(
        $roles = [],
        bool $checkEnabled = true,
        bool $checkEmailConfirmation = true,
        bool $checkFullRoleSubset = false
    ): UserInterface;

    /**
     * Checks the specified user's access and returns authorized user. If access denied throws an exception.
     * @param \App\Access\Security\UserInterface $subject User to authorize
     * @param string|array $roles Roles. Passes if the user has at least one role. Set to [] to skip role check
     * @param bool $checkEnabled Set to false to skip UserInterface::getEnabled check
     * @param bool $checkEmailConfirmation Set to false to skip UserInterface::getEmailConfirmed check
     * @param bool $checkFullRoleSubset Set to true to pass if the user has all the specified $roles only
     * @return \App\Access\Security\UserInterface Authorized user
     * @throws \App\Access\Exception\AccessControlException Containing the valid HTTP status code
     */
    public function authorizeUser(
        UserInterface $subject,
        $roles = [],
        bool $checkEnabled = true,
        bool $checkEmailConfirmation = true,
        bool $checkFullRoleSubset = false
    ): UserInterface;
}