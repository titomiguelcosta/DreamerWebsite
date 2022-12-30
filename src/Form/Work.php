<?php

namespace TitoMiguelCosta\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class Work extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', 'text')
            ->add('email', 'email')
            ->add('phone', 'text', array('required' => false))
            ->add('project', 'text')
            ->add('description', 'textarea');
    }
    public function getName()
    {
        return 'work';
    }
}
