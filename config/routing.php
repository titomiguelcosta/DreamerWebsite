<?php

use Symfony\Component\HttpFoundation\Request;

$app->get('/', function () use ($app) {
    return $app['twig']->render('home.twig', array(
    ));
})->bind('homepage');

$app->get('/contacts', function (Request $request) use ($app) {
    $submit = false;
    $data = array(
        'name' => 'Your name',
        'email' => 'Your email',
    );

    $form = $app['form.factory']->createBuilder('form', $data)
        ->add('name')
        ->add('email')
        ->add('gender', 'choice', array(
            'choices' => array(1 => 'male', 2 => 'female'),
            'expanded' => true,
        ))
        ->getForm();

    if ('POST' == $request->getMethod()) {
        $form->bindRequest($request);

        if ($form->isValid()) {
            $data = $form->getData();
            $submit = true;
            // redirect somewhere
            return $app->redirect('homepage');
        }
    }
    return $app['twig']->render('contacts.twig', array(
        'form' => $form->createView()
    ));
})->bind('contacts');
