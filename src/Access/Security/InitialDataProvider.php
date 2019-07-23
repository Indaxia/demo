<?php
namespace App\Access\Security;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Symfony\Component\DependencyInjection\ContainerInterface;
use App\Common\Services\InitialData\Event\CollectEvent;

/**
 * Sends initial security data when a session initializes
 */
class InitialDataProvider implements EventSubscriberInterface, ContainerAwareInterface
{
    use ContainerAwareTrait;

    const CSRF_HEADER_NAME = 'X-CSRF-TOKEN';

    public function __construct(ContainerInterface $container)
    {
        $this->setContainer($container);
    }

    public static function getSubscribedEvents()
    {
        return [
            CollectEvent::class => 'onCollect'
        ];
    }

    public function onCollect(CollectEvent $event)
    {
        /** @var \Symfony\Component\HttpFoundation\Session\SessionInterface $session */
        $session = $this->container->get('session');
        $session->start(); // if not started
        $sessionId = $session->getId();

        $csrfToken = $this->getCsrfToken($sessionId);

        $event->getCollector()->addData('Access', [
            'sessionId' => $sessionId,
            'csrf' => [
                'protection' => $csrfToken !== null,
                'token' => $csrfToken ?? '',
                'headerName' => static::CSRF_HEADER_NAME
            ]
        ]);
    }

    /**
     * @param string|null $sessionId
     * @return string|null Returns null if CSRF protection is not enabled in your application. Enable it with the "csrf_protection" key
     */
    protected function getCsrfToken(string $sessionId): ?string
    {
        if($this->container->has('security.csrf.token_manager')) {
            /** @var \Symfony\Component\Security\Csrf\CsrfTokenManagerInterface $csrfTokenProvider */
            $csrfTokenProvider = $this->container->get('security.csrf.token_manager');
            return $csrfTokenProvider->getToken($sessionId)->getValue();
        }
        return null;

    }
}