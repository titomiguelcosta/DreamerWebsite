<?php

use Symfony\Component\HttpFoundation\Request;

$app->get('/', function () use ($app)
        {
            return $app['twig']->render('home.twig', array(
                    ));
        })->bind('homepage');

$app->match('/contacts', function (Request $request) use ($app)
        {
            $submit = false;
            $data = array(
                'name' => 'Your name',
                'email' => 'Your email',
            );

            $form = $app['form.factory']->createBuilder('form', $data)
                    ->add('name', 'text')
                    ->add('email', 'text')
                    ->add('gender', 'choice', array(
                        'choices' => array(1 => 'male', 2 => 'female'),
                        'expanded' => true,
                    ))
                    ->getForm();

            if ('POST' == $request->getMethod())
            {
                $form->bind($request);

                if ($form->isValid())
                {
                    $data = $form->getData();
                    $submit = true;

                    return $app->redirect('/contact');
                }
            }
            return $app['twig']->render('contact.twig', array(
                        'form' => $form->createView()
                    ));
        })->bind('contact');