<?php
namespace App\Access\Factory\UserAuthenticity;

use App\Access\Document\UserAuthenticity;
use App\Access\Model\UserAuthenticityInterface;
use App\Access\Factory\UserAuthenticityFactoryInterface;
use App\Access\Exception\UserAuthenticityFactoryException;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;
use Symfony\Component\Security\Core\Encoder\PasswordEncoderInterface;

class UserAuthenticityPasswordFactory implements UserAuthenticityFactoryInterface {
    /**
     * @var PasswordEncoderInterface
     */
    protected $encoder;

    public function __construct(ContainerInterface $container, EncoderFactoryInterface $encoderFactory)
    {
        $userClass = $container->getParameter('app.access.user_class');
        $this->encoder = $encoderFactory->getEncoder($userClass);
    }

    /**
     * @param array|string|int|float $rawIdentifier Can be a string or array in ['password' => 'abc', 'salt' => 'def'] format
     * @return UserIdentityInterface
     * @throws UserIdentityFactoryException
     */
    public function create($rawAuthenticator): UserAuthenticityInterface
    {
        $salt = null;
        if(is_array($rawAuthenticator) && isset($rawAuthenticator['salt']) && isset($rawAuthenticator['password'])) {
            $salt = $rawAuthenticator['salt'];
            $rawAuthenticator = $rawAuthenticator['password'];
        }
        
        if(!is_string($rawAuthenticator)) {
            throw new UserAuthenticityFactoryException('Authenticator expected to be a string or special array, got ' . gettype($rawAuthenticator));
        }

        return UserAuthenticity::createWithPassword(
            $this->encoder->encodePassword($rawAuthenticator, $salt), 
            $salt
        );
    }
}