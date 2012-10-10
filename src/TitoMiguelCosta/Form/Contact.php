<?php

namespace TitoMiguelCosta\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class Contact extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('name', 'text', array('constraints' => array(new Assert\NotBlank())))
                ->add('email', 'email', array('constraints' => array(new Assert\NotBlank(), new Assert\Email())))
                ->add('message', 'textarea', array('constraints' => array(new Assert\NotBlank())));
    }

    public function getName()
    {
        return 'contact';
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {

    }

}