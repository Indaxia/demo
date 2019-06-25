<?php
namespace App\Access\Form\Type;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\Regex;

class FullNameEmailPasswordPasswordType extends PasswordPasswordType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('fullName', TextType::class, [
                'constraints' => [
                    new NotBlank(),
                    new Length(['min' => 2, 'max' => 48]),
                    new Regex([
                        'pattern' => '/^(?:[\p{L}\p{Mn}\p{Pd}\x{2019}]+\s?)+$/u'
                    ])
                ],
                'invalid_message' => 'This value must be from 2 to 48 characters long and contain one or more words separated by spaces'
            ])
        ;
        parent::buildForm($builder, $options);
    }
}