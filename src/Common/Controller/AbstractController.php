<?php
namespace App\Common\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController as BaseController;
use Symfony\Component\HttpFoundation\Response;

class AbstractController extends BaseController
{
    /**
     * Returns prepared response class instance from 'app.common.response_class' config parameter
     *
     * @param mixed|null $data
     * @param int $status
     * @param array $headers
     * @param string $responseClass
     * @return \Symfony\Component\HttpFoundation\Response
     * @see \App\Common\Helper\FormTransformer
     */
    public function response($data = null, int $status = 200, array $headers = []): Response
    {
        $class = $this->getParameter('app.common.response_class');
        /** @var Response $response */
        $response = new $class($data, $status, $headers);
        return $response;
    }
}