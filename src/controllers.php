<?php

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Main && default route
 */
$app->get('/', function (Request $request) use ($app) {
    if (!$request->hasPreviousSession()) {
        $app['locale'] = $app['default_locales'];
    }

    if ($lang = $app['session']->get('_locale')) {
        $app['locale'] = $lang;
    }

    if ($lang = $request->attributes->get('_locale')) {
        $request->getSession()->set('_locale', $lang);
    } else {
        // if no explicit locale has been set on this request, use one from the session
        $request->setLocale($request->getSession()->get('_locale', $app['default_locales']));
    }

    return $app->redirect($app['url_generator']->generate('welcome'));
})->bind('index');

/**
 * We trap 'em if they think it's *really* ASP.NET
 */
$app->get('/Default.aspx', function () use ($app) {
    return $app->redirect($app['url_generator']->generate('index'));
});


/***********/

/**
 * Homepage
 */
$app->get('/prima-pagina.aspx', function () use ($app) {
    return $app['twig']->render('homepage/welcome.html.twig', []);
})
    ->bind('welcome');

/**
 * about-us
 */
$app->get('/despre-noi.aspx', function () use ($app) {
    return $app['twig']->render('homepage/about_us.html.twig', []);
})
    ->bind('about_us');

/**
 * gallery
 */
$app->get('/galerie.aspx', function () use ($app) {
    return $app['twig']->render('homepage/gallery.html.twig', []);
})
    ->bind('gallery');

/**
 * contact-us
 */
$app->get('/detalii-contact.aspx', function (Request $request) use ($app) {
    $form = $app['form.factory']->createBuilder(FormType::class)
        ->add('full_name', TextType::class, [
            'constraints' => [
                new Assert\NotBlank(),
                new Assert\Length([
                    'min' => 4,
                    'max' => 24,
                ])
            ]
        ])
        ->add('email', EmailType::class, [
            'constraints' => [
                new Assert\Email(),
                new Assert\NotBlank()
            ]
        ])
        ->add('cod_de_siguranta', TextareaType::class, [
            'constraints' => [
                new Assert\NotBlank(),
                new Assert\Length([
                    'min' => 7,
                    'max' => 7
                ])
            ]
        ])
        ->getForm();

    $form->handleRequest($request);

    if ($form->isValid()) {
        $data = $form->getData();

    }

    return $app['twig']->render('homepage/contact_us.html.twig', [
        'form' => $form->createView(),
        'info' => [
            'address', 'email', 'phone', 'fax', 'ceo',
        ],
    ]);
})
    ->bind('contact_us');

/**
 * documente
 */
$app->get('/documente.aspx', function () use ($app) {
    return $app['twig']->render('homepage/documents.html.twig', []);
})
    ->bind('documents');


/**
 * oferte
 */
$app->get('/oferte.aspx', function () use ($app) {
    return $app['twig']->render('homepage/offers.html.twig', []);
})
    ->bind('offers');

/**
 * realizari
 */
$app->get('/realizari.aspx', function () use ($app) {
    return $app['twig']->render('homepage/achievements.html.twig', []);
})
->bind('achievements');

/**
 * language-switcher
 */
$app->get('/language/{language}', function (Request $request, $language) use ($app) {
    if (!in_array($language, $app['allowed_locales'])) {
        $app['locale'] = $app['default_locales'];
    }

    $app['session']->set('_locale', $language);
    $app['locale'] = $language;

    /** FIXME this is dirty */
    #$referer = $request->headers->get('referer');
    $referer = null;
    if (is_null($referer)) {
        #$request->getSession()->set('_locale', $language);
        $referer = $app['url_generator']->generate('locale', ['_locale' => $language]);
    }

    return $app->redirect($referer);
})
->bind('lang_switch');

$app->error(function (\Exception $e, Request $request, $code) use ($app) {
    if ($app['debug']) {
        return;
    }

    // 404.html, or 40x.html, or 4xx.html, or error.html
    $templates = array(
        'errors/' . $code . '.html.twig',
        'errors/' . substr($code, 0, 2) . 'x.html.twig',
        'errors/' . substr($code, 0, 1) . 'xx.html.twig',
        'errors/default.html.twig',
    );

    return new Response($app['twig']->resolveTemplate($templates)->render(array('code' => $code)), $code);
});
