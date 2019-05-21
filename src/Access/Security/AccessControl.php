<?php
namespace App\Access\Security;

use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Symfony\Component\DependencyInjection\ContainerInterface;
use App\Access\Model\UserInterface;
use App\Access\Exception\AccessControlException;
use App\Access\Exception\AccessControlUserNotFoundException;
use App\Access\Exception\AccessControlUserDisabledException;
use App\Access\Exception\AccessControlEmailUnconfirmedException;

/**
 * Provides Access Control and Authorization mechanics
 */
class AccessControl implements AccessControlInterface, ContainerAwareInterface {
    use ContainerAwareTrait;

    public function __construct(ContainerInterface $container)
    {
        $this->setContainer($container);        
    }

    /**
     * @return \App\Access\Security\UserInterface|null Authenticated user if so
     */
    public function getAuthUser(): ?UserInterface
    {
        /** @var \Symfony\Component\Security\Core\Authentication\Token\TokenInterface $token */
        $token = $this->container->get('security.token_storage')->getToken();
        if ($token) {
            $user = $token->getUser();
            return (is_object($user) && $user instanceof UserInterface) ? $user : null;
        }
        return null;
    }

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
    ): UserInterface {
        return $this->doAuthorize($this->getAuthUser(), $roles, $checkEnabled, $checkEmailConfirmation, $checkFullRoleSubset);
    }

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
    ): UserInterface {
        return $this->doAuthorize($subject, $roles, $checkEnabled, $checkEmailConfirmation, $checkFullRoleSubset);
    }

    /**
     * @param \App\Access\Security\UserInterface $subject
     * @param string|array $roles Roles. Passes if the user has at least one role. Set to [] to skip role check
     * @param bool $checkEnabled Set to false to skip UserInterface::getEnabled check
     * @param bool $checkEmailConfirmation Set to false to skip UserInterface::getEmailConfirmed check
     * @param bool $checkFullRoleSubset Set to true to pass if the user has all the specified $roles only
     * @return \App\Access\Security\UserInterface Authorized user
     * @throws \App\Access\Exception\AccessControlException
     */
    protected function doAuthorize(
        UserInterface $subject, 
        $roles, 
        bool $checkEnabled, 
        bool $checkEmailConfirmation,
        bool $checkFullRoleSubset
    ) {
        if (is_string($roles)) {
            $roles = [$roles];
        }

        if(! $subject) {
            throw new AccessControlUserNotFoundException(401, 'Authenticated user not found in token storage');
        }

        if($checkEnabled && !$subject->isEnabled()) {
            $text = 'Access denied by administration';
            $reason = $subject->getBanReason();
            throw new AccessControlUserDisabledException(403,  $text . ($reason ? '. Reason: '.$reason : ''));
        }

        if($checkEmailConfirmation && !$subject->getEmailConfirmed()) {
            throw new AccessControlEmailUnconfirmedException(403, 'Email is not confirmed');
        }
        if (empty($roles) || ($checkFullRoleSubset ? $subject->hasAllRoles($roles) : $subject->hasAnyRole($roles))) {
            return $subject;
        }

        throw new AccessControlException(403, 'Access denied');
    }
}