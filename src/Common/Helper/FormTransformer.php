<?php
namespace App\Common\Helper;

use Symfony\Component\Form\FormInterface;

class FormTransformer
{
    private $form = null;

    public function __construct(FormInterface $form)
    {
        $this->form = $form;
    }

    /**
     * Returns errors of the form as an array
     *
     * @param bool $deep Whether to include errors of child forms as well
     * @return array 2d array where the key is the field name and the value is an array of errors
     */
    public function getErrorsAsArray(bool $deep = true): array
    {
        $errors = [];
        
        foreach ($this->form->all() as $field) {
            $fieldName = $field->getName();
            $fieldErrors = $field->getErrors($deep);
            if(count($fieldErrors) > 0) {
                $errors[$fieldName] = [];
                foreach($fieldErrors as $error) {
                    $errors[$fieldName][] = $error->getMessage();
                }
            }
        }

        return $errors;
    }

    /**
     * Returns first error of the form or null if no errors
     *
     * @param bool $deepCheck Whether to include errors of child forms as well
     * @return string|null
     */
    public function getFirstError(bool $deepCheck = true): ?string
    {
        $errors = $this->form->getErrors($deepCheck);
        return count($errors) > 0 ? $errors->current()->getMessage() : null;
    }
}