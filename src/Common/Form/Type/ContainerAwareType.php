<?php
namespace App\Common\Form\Type;

use Symfony\Component\Form\AbstractType as BaseType;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;

class ContainerAwareType extends BaseType implements ContainerAwareInterface
{
    use ContainerAwareTrait;

    public function __construct(Container $container)
    {
        $this->setContainer($container);
    }
}