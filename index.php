<?php

/**
 * Error reporting
 *
 * Here you shall turn off error reporting or displaying them.
 * Or, if you're using a Debug environment, you shall report everything.
 *
 * Use `$development` variable as a switch for it ;)
 */
$development = false;
$god = false;
$allowedDomainsForDev = [
    'localhost',
    'dev.happytoys.srl',
];

require_once __DIR__ . '/async2.php';

if (($development && in_array($_SERVER['HTTP_HOST'], $allowedDomainsForDev)) || $god)
{
    /**
     * An array of whitelisted IPs in order to access the Debug mode of the front-end.
     *
     * Feel free to add more IPs at the end of the array.
     */
    $ip_whitelist = [
        # Default IPs
        '127.0.0.1',
        'fe80::1',
        '::1',
    ];

    /**
     * This check prevents access to debug mode of the front-end if it's deployed accidentally with `$development` set
     * to true.
     *
     * This part was extended from the basic, raw form.
     */
    if (isset($_SERVER['HTTP_CLIENT_IP'])
        || isset($_SERVER['HTTP_X_FORWARDED_FOR'])
        || !in_array(@$_SERVER['REMOTE_ADDR'], $ip_whitelist)
    ) {
        header('HTTP/1.0 403 Forbidden');
        exit('Momentan, site-ul web se afla sub mentenanta neasteptata. Reveniti!');
    }

    /**
     * Loading the Debug component of Symfony
     */
    \Symfony\Component\Debug\Debug::enable();

    /**
     * Setting this variable to 'dev' in order to load the development configuration file.
     */
    $configuration = 'dev';
} else {
    /**
     * We hide all the errors that are thrown by the application.
     */
    ini_set('display_errors', 0);

    /**
     * Here we use the production configuration file
     */
    $configuration = 'prod';
}

$app = require app_constant('backend_application') . '/app.php';

require sprintf('%s/%s.php', app_constant('backend_config'), $configuration);
require app_constant('backend_application') . '/controllers.php';

$app->run();
