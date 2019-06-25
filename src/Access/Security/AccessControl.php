<?php
namespace App\Access\Security;

use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Symfony\Component\DependencyInjection\ContainerInterface;
use App\Access\Model\UserInterface;
use App\Access\Exception\AccessControlException;
use App\Access\Exception\AccessControlUserNotFoundException;
use App\Access\Exception\AccessControlUserDisabledException;
use App\Access\Exception\AccessControlNotVerifiedException;
use App\Access\Exception\VerificationFactorException;

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
     * Retrieve authenticated user of the current session
     * @return \App\Access\Model\UserInterface|null Authenticated user if so
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
     * @param bool $checkBan Set to false to skip UserInterface::getBannedAt check
     * @param bool $checkVerificationState Set to false to skip UserVerificationStateInterface::verify check
     * @param bool $checkFullRoleSubset Set to true to pass if the user has all the specified $roles only
     * @param \App\Access\Model\UserInterface|null $checkAnotherUser Set to null to use current session user
     * @return \App\Access\Model\UserInterface Authorized user
     * @throws \App\Access\Exception\AccessControlException Containing the valid HTTP status code
     */
    public function authorize(
        $roles = [],
        bool $checkBan = true,
        bool $checkVerificationState = true,
        bool $checkFullRoleSubset = false
    ): UserInterface {
        $subject = $this->getAuthUser();
        if(! $subject) {
            throw new AccessControlUserNotFoundException(401, 'User is not authenticated');
        }
        return $this->doAuthorize($subject, $roles, $checkBan, $checkVerificationState, $checkFullRoleSubset);
    }

    /**
     * Checks the specified user's access and returns authorized user. If access denied throws an exception.
     * @param \App\Access\Model\UserInterface|null $subject User to authorize
     * @param string|array $roles Roles. Passes if the user has at least one role. Set to [] to skip role check
     * @param bool $checkBan Set to false to skip UserInterface::getBannedAt check
     * @param bool $checkVerificationState Set to false to skip UserVerificationStateInterface::verify check
     * @param bool $checkFullRoleSubset Set to true to pass if the user has all the specified $roles only
     * @return \App\Access\Model\UserInterface Authorized user
     * @throws \App\Access\Exception\AccessControlException Containing the valid HTTP status code
     */
    public function authorizeUser(
        ?UserInterface $subject,
        $roles = [],
        bool $checkBan = true,
        bool $checkVerificationState = true,
        bool $checkFullRoleSubset = false
    ): UserInterface {
        if(! $subject) {
            throw new AccessControlUserNotFoundException(401, 'Target user not found');
        }
        return $this->doAuthorize($subject, $roles, $checkBan, $checkVerificationState, $checkFullRoleSubset);
    }

    /**
     * @param \App\Access\Model\UserInterface $subject
     * @param string|array $roles Roles. Passes if the user has at least one role. Set to [] to skip role check
     * @param bool $checkBan Set to false to skip UserInterface::getBannedAt check
     * @param bool $checkVerificationState Set to false to skip UserVerificationStateInterface::verify check
     * @param bool $checkFullRoleSubset Set to true to pass if the user has all the specified $roles only
     * @return \App\Access\Model\UserInterface Authorized user
     * @throws \App\Access\Exception\AccessControlException
     */
    protected function doAuthorize(
        UserInterface $subject, 
        $roles, 
        bool $checkBan, 
        bool $checkVerificationState,
        bool $checkFullRoleSubset
    ) {
        if (is_string($roles)) {
            $roles = [$roles];
        }

        if($checkBan && $subject->getBannedAt()) {
            $text = 'Access denied by administration';
            $reason = $subject->getBanReason();
            throw new AccessControlUserDisabledException(403,  $text . ($reason ? '. Reason: '.$reason : ''));
        }

        if($checkVerificationState) {
            try {
                $subject->getVerificationState()->verify($subject);
            } catch(VerificationFactorException $e) {
                throw new AccessControlNotVerifiedException(403, 'Not verified: ' . $e->getMessage(), $e);
            }
        }
        if (empty($roles) || ($checkFullRoleSubset ? $subject->hasAllRoles($roles) : $subject->hasAnyRole($roles))) {
            return $subject;
        }

        throw new AccessControlException(403, 'Access denied');
    }
}