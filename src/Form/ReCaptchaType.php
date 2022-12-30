<?php

namespace TitoMiguelCosta\Form;

use TitoMiguelCosta\Form\EventListener\ReCaptchaValidationListener;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ReCaptchaType extends AbstractType
{
    /**
     * @inheritDoc
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $subscriber = new ReCaptchaValidationListener();
        $subscriber->setInvalidMessage($options['invalid_message']);
        $builder->addEventSubscriber($subscriber);
    }

    /**
     * @inheritDoc
     */
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $view->vars['type'] = $options['type'];
    }

    /**
     * @inheritDoc
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver
            ->setDefault('type', 'invisible')
            ->setAllowedValues('type', ['checkbox', 'invisible']);

        $resolver
            ->setDefault('invalid_message', 'The captcha is invalid. Please try again.');
    }
}
