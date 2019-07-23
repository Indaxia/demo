<?php 
namespace App\Common\Services\InitialData;

use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use App\Common\Services\InitialData\Exception\CollectorException;
use App\Common\Services\InitialData\Event\CollectEvent;

/**
 * Collects data for initial client request
 */
class Collector implements CollectorInterface
{
    /** @var array */
    private $data = [];

    /** @var \Symfony\Component\EventDispatcher\EventDispatcherInterface */
    private $eventDispatcher;

    public function __construct(EventDispatcherInterface $eventDispatcher)
    {
        $this->eventDispatcher = $eventDispatcher;
    }

    /**
     * Adds data to the Collector. Merges data with the previous when emitted multiple times.
     * The data must be transformed to the primitive or array of primitives.
     */
    public function addData(string $dataDomain, array $data)
    {
        if(empty($dataDomain)) {
            throw new CollectorException('$dataDomain cannot be empty');
        }
        if(isset($this->data[$dataDomain])) {
            $this->data[$dataDomain] = array_merge_recursive($this->data[$dataDomain], $data);
        } else {
            $this->data[$dataDomain] = $data;
        }
    }

    /**
     * Returns all the collected data
     * @return array
     */ 
    public function getData(): array
    {
        return $this->data;
    }

    /**
     * Emits the collect event to let the providers know when to add the data using addData()
     * @return static
     */
    public function collect()
    {
        $this->eventDispatcher->dispatch(CollectEvent::class, new CollectEvent($this));

        return $this;
    }
}