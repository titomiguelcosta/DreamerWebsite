<?php

namespace TitoMiguelCosta\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use TitoMiguelCosta\Form\EventListener\ReCaptchaValidationListener;

class Contact extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, array('constraints' => array(new Assert\NotBlank())))
            ->add('email', EmailType::class, array('constraints' => array(new Assert\NotBlank(), new Assert\Email())))
            ->add('message', TextareaType::class, array('constraints' => array(new Assert\NotBlank())))
            ->add('captcha', ReCaptchaType::class, ['type' => 'checkbox']); // invisible or checkbox
    }

    public function getName()
    {
        return 'contact';
    }
}
