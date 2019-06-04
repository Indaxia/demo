<?php
namespace App\Access\Factory\UserIdentity;

use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Symfony\Component\DependencyInjection\ContainerInterface;
use App\Access\Model\UserIdentityInterface;

/**
 * Generates authenticity depending on the UserIdentityInterface class (declared in config)
 */
abstract class GenericUserIdentityFactory implements ContainerAwareInterface {
    use ContainerAwareTrait;

    public function __construct(ContainerInterface $container)
    {
        $this->setContainer($container);
    }

    protected function create(string $identifier): UserIdentityInterface
    {
        $class = $this->container->getParameter('app.access.user_identity_class');
        return new $class($identifier);
    }
}