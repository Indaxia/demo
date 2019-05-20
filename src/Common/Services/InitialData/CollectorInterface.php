<?php
namespace App\Common\Services\InitialData;

interface CollectorInterface {
    /**
     * Adds data to the Collector. Must be called by a provider.
     * Merges data with the previous when called multiple times with the same domain.
     * The data must be transformed to the primitive or array of primitives.
     */
    public function addData(string $dataDomain, array $data);

    /**
     * Returns all the collected data.
     * @return array
     */ 
    public function getData(): array;

    /**
     * Emits the collect event to let the providers know when to add the data using addData()
     * Must be called by the host (receiver)
     * @return static
     */
    public function collect();
}