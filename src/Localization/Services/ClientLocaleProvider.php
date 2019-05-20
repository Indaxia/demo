<?php
namespace App\Localization\Services;

use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use App\Common\Services\InitialData\Event\CollectEvent;

class ClientLocaleProvider implements EventSubscriberInterface
{
    const DEFAULT_LOCALE = 'en';

    /** @var \Symfony\Component\HttpFoundation\RequestStack */
    private $requestStack;

    public function __construct(RequestStack $requestStack)
    {
        $this->requestStack = $requestStack;
    }

    public static function getSubscribedEvents()
    {
        return [
            CollectEvent::class => 'onCollect'
        ];
    }

    public function getClientLocale(): string
    {
        if(empty($_SERVER['HTTP_ACCEPT_LANGUAGE'])) {
            if($r = $this->requestStack->getCurrentRequest()) {
                return $r->getLocale();
            } else {
                return static::DEFAULT_LOCALE;
            }
        }
        return strtolower(str_split($_SERVER['HTTP_ACCEPT_LANGUAGE'], 2)[0]);
    }

    public function onCollect(CollectEvent $event)
    {
        $event->getCollector()->addData('Localization', [
            'clientLocale' => $this->getClientLocale()
        ]);
    }
}