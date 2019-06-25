<?php
namespace App\Access\Controller;

use App\Common\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use App\Access\Factory\UserFactoryInterface;

/**
 * @Route("/access/registration")
 */
class RegistrationController extends AbstractController 
{
    /**
     * @var UserFactoryInterface
     */
    protected $userFactory;

    public function __construct(UserFactoryInterface $userFactory)
    {
        $this->userFactory = $userFactory;
    }

    /**
     * @Route("/register")
     * @Method("POST")
     *
     * @param Request $request
     */
    public function register(Request $request)
    {
        $form = $this->createForm($this->getParameter('app.access.input')['form_type_registration']);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $user = $this->userFactory->create($data['identity'], $data['authenticity']);

            // TODO
        }

        return $this->response(['errors' => $this->getFormErrors($form)], 409);
    }
}