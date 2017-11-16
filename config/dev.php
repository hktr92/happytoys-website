<?php

use Silex\Provider\MonologServiceProvider;
use Silex\Provider\WebProfilerServiceProvider;
use Silex\Provider\VarDumperServiceProvider;

// include the prod configuration
require __DIR__ . '/prod.php';

// enable the debug mode
$app['debug'] = true;

$app->register(new MonologServiceProvider(), [
    'monolog.logfile' => app_constant('backend_var') . '/logs/silex_dev.log',
]);

$app->register(new WebProfilerServiceProvider(), [
    'profiler.cache_dir' => app_constant('backend_var') . '/cache/profiler',
]);

$app->register(new VarDumperServiceProvider());
