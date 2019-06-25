<?php
namespace App\Access\Factory;

use App\Access\Model\UserInterface;
use App\Access\Model\UserContactsInterface;
use App\Access\Model\UserSettingsInterface;
use App\Access\Factory\UserFactoryInterface;
use App\Access\Exception\UserFactoryException;
use App\Access\Factory\UserIdentityFactoryInterface;
use App\Access\Factory\UserAuthenticityFactoryInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;

class UserFactory implements UserFactoryInterface, ContainerAwareInterface 
{
    use ContainerAwareTrait;

    /**
     * @var UserIdentityFactoryInterface
     */
    protected $identityFactory;

    /**
     * @var UserAuthenticityFactoryInterface
     */
    protected $authenticityFactory;

    public function __construct(
        ContainerInterface $container,
        UserIdentityFactoryInterface $identityFactory,
        UserAuthenticityFactoryInterface $authenticityFactory
    ) {
        $this->setContainer($container);
        $this->identityFactory = $identityFactory;
        $this->authenticityFactory = $authenticityFactory;
    }

    /**
     * @inheritdoc
     */
    public function create(
        string $rawIdentity = null,
        string $rawAuthenticity = null
    ): UserInterface {
        $userClass = $this->container->getParameter('app.access.user_class');
        $userContactsClass = $this->container->getParameter('app.access.user_contacts_class');
        $userSettingsClass = $this->container->getParameter('app.access.user_settings_class');
        /**
         * @var UserInterface $user
         */
        $user = new $userClass();

        if(! ($user instanceof UserInterface)) {
            throw new UserFactoryException('Class "' . $userClass . '" is not an instance of UserInterface');
        }

        if($rawIdentity) {
            $user->setIdentity($this->identityFactory->create($rawIdentity));
        }
        if($rawAuthenticity) {
            $user->setAuthenticity($this->authenticityFactory->create($rawAuthenticity));
        }

        /**
         * @var UserContactsInterface $contacts
         */
        $contacts = new $userContactsClass();

        /**
         * @var UserSettingsInterface $settings
         */
        $settings = new $userSettingsClass();
        
        $user->setContacts($contacts)->setSettings($settings);

        return $user;
    }
}