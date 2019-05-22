<?php
namespace App\Access\Security\User;

use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use App\Access\Model\UserInterface;
use App\Access\Repository\UserRepository;
use App\Access\Factory\UserIdentityFactoryInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\User\UserInterface as SymfonyUserInterface;

class Provider implements UserProviderInterface
{
    /**
     * @var UserRepository
     */
    protected $repository;

    /**
     * @var UserIdentityFactoryInterface
     */
    protected $identityFactory;

    public function __construct(UserRepository $repository, UserIdentityFactoryInterface $identityFactory)
    {
        $this->repository = $repository;
        $this->identityFactory = $identityFactory;
    }

    /**
     * Symfony calls this method if you use features like switch_user
     * or remember_me.
     *
     * The name "username" is for compatibility only
     * 
     * @param string $rawIdentifier
     * @return SymfonyUserInterface
     *
     * @throws UsernameNotFoundException if the user is not found
     */
    public function loadUserByUsername($rawIdentifier)
    {
        $user = $this->repository->findByIdentity(
            $this->identityFactory->create($rawIdentifier)
        );

        if($user) {
            return $user;
        }

        throw new UsernameNotFoundException();
    }

    /**
     * Refreshes the user after being reloaded from the session.
     *
     * When a user is logged in, at the beginning of each request, the
     * User object is loaded from the session and then this method is
     * called. Your job is to make sure the user's data is still fresh by,
     * for example, re-querying for fresh User data.
     *
     * If your firewall is "stateless: true" (for a pure API), this
     * method is not called.
     *
     * @return SymfonyUserInterface
     */
    public function refreshUser(SymfonyUserInterface $user)
    {
        /** @var UserInterface $user */

        $id = $user->getId();
        if(! $id) {
            throw new \InvalidArgumentException('User Provider cannot refresh a user without the database Id');
        }

        $refreshedUser = $this->repository->find($id);
        if (null === $refreshedUser) {
            throw new UsernameNotFoundException(sprintf('User with id %s not found', json_encode($id)));
        }

        return $refreshedUser;
    }

    /**
     * Tells Symfony to use this provider for this User class.
     */
    public function supportsClass($class)
    {
        return is_subclass_of($class, UserInterface::class);
    }
}