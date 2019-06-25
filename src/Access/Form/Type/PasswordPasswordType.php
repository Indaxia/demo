<?php
namespace App\Access\Form\Type;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Validator\Constraints\Length;
use App\Common\Form\Type\ContainerAwareType;

class PasswordPasswordType extends ContainerAwareType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('authenticity', RepeatedType::class, [
                'type' => PasswordType::class,
                'constraints' => [
                    new Length([
                        'min' => $this->container->getParameter('app.access.input')['password_length_min'], 
                        'max' => $this->container->getParameter('app.access.input')['password_length_max']
                    ])
                ],
                'first_name' => 'authenticity',
                'second_name' => 'authenticity_repeat',
                'invalid_message' => 'The entered passwords don\'t match.',
            ])
        ;
    }
}