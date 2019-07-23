<?php
namespace App\Common\Form\Type;

use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides CSRF protection functions on per-session level
 */
class SecureSessionType extends ContainerAwareType
{
    const CSRF_FIELD_NAME = '__CSRF_Token';

    private $sessionId = null;

    public function __construct(ContainerInterface $container, SessionInterface $session)
    {
        parent::__constrict($container);
        $session->start(); // if not started
        $this->sessionId = $session->getId();
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'csrf_token_id' => $this->sessionId,
            'csrf_field_name' => static::CSRF_FIELD_NAME
        ]);
    }
}