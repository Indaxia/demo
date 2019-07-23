<?php
namespace App\Access\Form\Type;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\Regex;

class FullNameEmailPasswordPairType extends EmailPasswordPairType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $min = $this->container->getParameter('app.access.input')['fullname_length_min'];
        $max = $this->container->getParameter('app.access.input')['fullname_length_max'];
        $builder
            ->add('fullName', TextType::class, [
                'constraints' => [
                    new NotBlank(),
                    new Length([
                        'min' => $min, 
                        'max' => $max
                    ]),
                    new Regex([
                        'pattern' => '/^(?:[\p{L}\p{Mn}\p{Pd}\x{2019}]+\s?)+$/u'
                    ])
                ],
                'invalid_message' => 'This value must be from '.$min.' to '.$max.' character(s) long and contain one or more words separated by spaces'
            ])
        ;
        parent::buildForm($builder, $options);
    }
}