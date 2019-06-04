<?php
namespace App\Access\Factory\UserAuthenticity;

use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Symfony\Component\DependencyInjection\ContainerInterface;
use App\Access\Model\UserAuthenticityInterface;
use App\Access\Security\User\AuthenticatorInterface;
use App\Access\Factory\UserAuthenticityFactoryInterface;

/**
 * Generates authenticity depending on the AuthenticatorInterface implementation (declared in config)
 */
class GenericUserAuthenticityFactory implements ContainerAwareInterface, UserAuthenticityFactoryInterface {
    use ContainerAwareTrait;

    /**
     * @var AuthenticatorInterface
     */
    private $authenticator;

    public function __construct(ContainerInterface $container, AuthenticatorInterface $authenticator)
    {
        $this->setContainer($container); 
        $this->authenticator = $authenticator;
    }

    /**
     * @inheritdoc
     */
    public function create(string $rawAuthenticity, bool $alreadyEncoded = false): UserAuthenticityInterface
    {
        $class = $this->container->getParameter('app.access.user_authenticity_class');
        $encodedAuthenticityData = $alreadyEncoded ? $rawAuthenticity : $this->authenticator->generate($rawAuthenticity);
        return new $class($encodedAuthenticityData);
    }
}