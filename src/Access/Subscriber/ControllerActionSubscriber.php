<?php
namespace App\Access\Subscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use App\Access\Security\InitialDataProvider;
use App\Common\Form\Type\SecureSessionType;

class ControllerActionSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents()
    {
        return array(
            KernelEvents::CONTROLLER => ['appendCsrfField', -1000],
        );
    }
    public function appendCsrfField(FilterControllerEvent $event)
    {
        $request = $event->getRequest();

        $csrfTokenValue = $request->headers->get(InitialDataProvider::CSRF_HEADER_NAME);
        $request->request->set(SecureSessionType::CSRF_FIELD_NAME, $csrfTokenValue ?: '');        
    }
}