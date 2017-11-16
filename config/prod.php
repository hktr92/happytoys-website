<?php

/**
 * Configurația aplicației pentru producție.
 *
 * Această configurație este în special pentru producție.
 */

$app['request'] = \Symfony\Component\HttpFoundation\Request::createFromGlobals();
$app['universe.current_hostname'] = $app['request']->getUri();

$app['default_locales'] = 'ro';
$app['allowed_locales'] = ['ro', 'en'];
$app['locale_fallbacks'] = ['ro'];
$app['google_api_key'] = 'AIzaSyAoiY86Rclc6O7f_vZKwf_thgWm-dTfM_I';
$app['locale'] = $app['default_locales'];
$app['translator.domains'] = require_once __DIR__ . '/language.php';

$app['twig.path'] = [
    app_constant('backend_templates')
];

$app['twig.options'] = [
    'cache' => app_constant('backend_var') . '/cache/twig'
];

$app['twig.form.templates'] = ['bootstrap_3_layout.html.twig'];

$app['session.storage.options'] = [
    'name' => 'happytoys',
    'cookie_domain' => $app['universe.current_hostname'],
    'cookie_httponly' => true,
];