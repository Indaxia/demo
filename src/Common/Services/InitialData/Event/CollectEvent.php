<?php
namespace App\Common\Services\InitialData\Event;
use Symfony\Component\EventDispatcher\Event;
use App\Common\Services\InitialData\CollectorInterface;

class CollectEvent extends Event
{
    /** @var \App\Common\Services\InitialData\CollectorInterface */
    private $collector;

    public function __construct(CollectorInterface $collector)
    {
        $this->collector = $collector;        
    }

    public function getCollector(): CollectorInterface
    {
        return $this->collector;
    }
}