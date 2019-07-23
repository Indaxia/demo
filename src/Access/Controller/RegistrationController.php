<?php
namespace App\Access\Controller;

use App\Common\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use App\Access\Factory\UserFactoryInterface;
use App\Common\Helper\FormTransformer;

/**
 * @Route("/api/access/registration")
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
        $form->submit($request->request->all());
        
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $user = $this->userFactory->create($data['identity'], $data['authenticity']);

            // TODO
            return $this->response($data);
        }
        
        return $this->response(['errors' => (new FormTransformer($form))->getFirstError()], 409);
    }
}