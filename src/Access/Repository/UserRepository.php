<?php

namespace App\Access\Repository;

use Doctrine\ODM\MongoDB\DocumentManager;
use Doctrine\ODM\MongoDB\Query\Builder;
use App\Access\Model\UserInterface;
use App\Access\Model\UserIdentityInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class UserRepository
{
    /**
     * @var DocumentManager
     */
    protected $dm;
    protected $class;

    public function __construct(DocumentManager $dm, ContainerInterface $container)
    {
        $this->dm = $dm;
        $this->class = $container->getParameter('app.access.user_class');
    }

    public function findByIdentity(UserIdentityInterface $identity): ?UserInterface
    {
        return $this->dm->createQueryBuilder($this->class)
            ->field('identity.identifier')->equals($identity->getIdentifier())
            ->getQuery()->getSingleResult();
    }

    /**
     * Saves the user to the database
     * @param UserInterface $user
     * @param bool $flushNow Set to false to defer transaction (e.g. for multiple saves)
     */
    public function save(UserInterface $user, bool $flushNow = true)
    {
        $this->dm->persist($user);

        if($flushNow) {
            $this->dm->flush();
        }
    }

    /**
     * Removes the user from the database
     * @param UserInterface $user
     * @param bool $flushNow Set to false to defer transaction (e.g. for multiple removes)
     */
    public function remove(UserInterface $user, bool $flushNow = true)
    {
        $this->dm->remove($user);

        if($flushNow) {
            $this->dm->flush();
        }
    }

    public function find($id): ?UserInterface
    {
        return $this->dm->createQueryBuilder($this->class)
            ->field('id')->equals($id)
            ->getQuery()->getSingleResult();
    }

    public function allCount(): int
    {
        return $this->dm->createQueryBuilder($this->class)->count()->getQuery()->execute();
    }
}