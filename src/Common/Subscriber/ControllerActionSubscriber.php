<?php
namespace App\Common\Subscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\KernelEvents;

class ControllerActionSubscriber
{
    public static function getSubscribedEvents()
    {
        return array(
            KernelEvents::CONTROLLER => 'convertAjaxStringToArray',
        );
    }
    public function convertAjaxStringToArray(FilterControllerEvent $event)
    {
        $request = $event->getRequest();

        switch($request->getContentType()) {
            case 'json':
            case 'jsonld':
                $data = json_decode($request->getContent(), true);
                if (json_last_error() !== JSON_ERROR_NONE) {
                    throw new BadRequestHttpException(json_last_error_msg());
                }
                $request->request->replace(is_array($data) ? $data : []);
                break;
            case 'xml':
            case 'atom':
            case 'rss':
                libxml_use_internal_errors(true);
                $xml = simplexml_load_string($request->getContent(), "SimpleXMLElement", LIBXML_NOCDATA);
                if($xml === false) {
                    $errors = libxml_get_errors();
                    throw new BadRequestHttpException(reset($errors));
                }
                $json = json_encode($xml);
                $data = json_decode($json,TRUE);
                $request->request->replace(is_array($data) ? $data : []);
                break;
        }
        
    }
}