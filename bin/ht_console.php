#!/usr/bin/env php
<?php

require_once dirname(dirname(__DIR__)) . '/public_html/async2.php';

set_time_limit(0);

use Symfony\Component\Console\Input\ArgvInput;

$input = new ArgvInput();
$env = $input->getParameterOption(array('--env', '-e'), getenv('SYMFONY_ENV') ?: 'dev');

$app = require app_constant('backend_application') . '/app.php';
require app_constant('backend_config') . '/' . $env . '.php';
$console = require app_constant('backend_application') . '/console.php';

$console->run();
