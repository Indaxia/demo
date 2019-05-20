<?php
namespace App\Common\Services;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use App\Common\Services\InitialData\Event\CollectEvent;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Symfony\Component\DependencyInjection\ContainerInterface;

class ServerConfigProvider implements EventSubscriberInterface, ContainerAwareInterface
{
    use ContainerAwareTrait;

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
        $event->getCollector()->addData('Common', [
            'env' => $this->container->getParameter('APP_ENV'),
            'productName' => $this->container->getParameter('PRODUCT_NAME'),
            'mailerEmailFrom' => $this->container->getParameter('MAILER_EMAIL_FROM')
        ]);
    }
}