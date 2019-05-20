<?php
    
namespace App\Common\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Common\Services\InitialData\Collector;

class InitialController extends AbstractController
{
    /** @var \App\Common\Services\InitialData\Collector */
    private $collector;

    public function __construct(Collector $collector)
    {
        $this->collector = $collector;
    }

    /**
     * @Route("/")
     */
    public function index()
    {
        return $this->render('@Common/index.html.twig');
    }
    
    /**
     * Frontend HTML5 history mode support
     * @see https://developer.mozilla.org/en-US/docs/Web/API/History_API#Adding_and_modifying_history_entries
     *
     * @Route("/{frontendPath}", requirements={"frontendPath"="^(?!api).+$"})
     */
    public function frontendHistoryModeSupport()
    {
        return $this->index();
    }
    
    /**
     * Provides initial data for the frontend
     * @Route("/api/initial")
     */
    public function initial()
    {
        return new JsonResponse($this->collector->collect()->getData());
    }
}