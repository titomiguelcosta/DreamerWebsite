<?php

use Symfony\Component\HttpFoundation\Request;

/**
 * homepage
 */
$app->get('/', function () use ($app)
        {
            return $app['twig']->render('home.twig', array(
                    ));
        })->bind('homepage');

/**
 * contacts
 */
$app->match('/contacts', function (Request $request) use ($app)
        {

            $submit = false;
            $data = array(
                'name' => '',
                'email' => '',
            );

            $form = $app['form.factory']->createBuilder('form', $data)
                    ->add('name', 'text')
                    ->add('email', 'text')
                    ->add('message', 'textarea')
                    ->getForm();

            if ('POST' == $request->getMethod())
            {
                $form->bind($request);

                if ($form->isValid())
                {
                    $app->register(new Silex\Provider\SwiftmailerServiceProvider());
                    
                    $data = $form->getData();
                    $submit = true;

                    return $app->redirect('/contact');
                }
            }
            return $app['twig']->render('contact.twig', array(
                        'form' => $form->createView()
                    ));
        })->bind('contact');