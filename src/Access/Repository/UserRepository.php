<?php

namespace App\Access\Repository;

use Doctrine\ODM\MongoDB\DocumentManager;
use App\Access\Model\UserInterface;
use App\Access\Model\UserIdentityInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use App\Common\Repository\RepositoryAsStorageInterface;
use App\Common\Repository\RepositoryAsStorage;

/** 
 * @method findBy(array $fields, int $index, int $limit = null, string|array $orderBy = null, bool $inverseOrder = false, bool $getCountInstead = false): UserInterface[]|\Doctrine\MongoDB\CursorInterface|int
 * @method findOneBy(array $fields): UserInterface|null
 * @method save(UserInterface $document, bool $flushNow = true)
 * @method remove(UserInterface $document, bool $flushNow = true)
 */
class UserRepository implements RepositoryAsStorageInterface
{
    use RepositoryAsStorage;

    public function __construct(DocumentManager $dm, ContainerInterface $container)
    {
        $this->using($dm, $container->getParameter('app.access.user_class'));
    }

    public function findByIdentity(UserIdentityInterface $identity): ?UserInterface
    {
        return $this->findOneBy(['identity.identifier' => $identity->getIdentifier()]);
    }
}