<?php
namespace App\Common\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController as BaseController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Form\Form;

class AbstractController extends BaseController
{
    public function response($data = null, int $status = 200, array $headers = []): Response
    {
        $class = $this->getParameter('app.common.response_class');
        /** @var Response $response */
        $response = new $class($data, $status, $headers);
        return $response;
    }

    protected function getFormErrors(Form $form)
    {
        $errors = [];
        
        foreach ($form->all() as $field) {
            if ($field->getErrors()->count() > 0) {
                $fieldName = $field->getName();
                $errors[$fieldName] = [];
                foreach ($field->getErrors() as $error) {
                    $errors[$fieldName][] = $error->getMessage();
                }
            }
        }

        return $errors;
    }
}