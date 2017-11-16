<?php

/**
 * @package cms
 * @author hacktor
 * @date 04.08.2016 20:18
 */

$base = __DIR__;

$directory_map = [
    'root'      => $base,

    'web'       => [
        'root'          => sprintf('%s',                    $base),
        'assets'        => sprintf('%s/assets',             $base),
    ],

    'backend'   => [
        'root'          => sprintf('%s',             $base),
        'bin'           => sprintf('%s/bin',         $base),
        'config'        => sprintf('%s/config',      $base),
        'application'   => sprintf('%s/src',         $base),
        'templates'     => sprintf('%s/templates',   $base),
        'var'           => sprintf('%s/var',                    $base),
    ],

    'libraries' => [
        'composer'      => sprintf('%s/composer',           $base),
        'bower'         => sprintf('%s/bower',              $base),
        'npm'           => sprintf('%s/npm',                $base),
    ]
];

/**
 * Constants format
 *
 * Here you can define constant's format.
 */
$constant_format = '__%s__';

/**
 * Here we convert the directory map into a set of global constants
 *
 * The general form of the constant is:
 *      ___%s___, if:
 *         |- the map key is 'root' or it's value is a string;
 *         |- the map key is an array and it contains 'root' key in it;
 *         \- the map key is 'libraries'. in this case, %s = dependency manager
 *            name (e.g.: npm)
 *
 *      ___%s1_%s2___, where %s1 = directory parentand %s2 = directory name;
 *
 */
foreach ($directory_map as $directory => $subdirectories) {
    /**
     * Here we check if
     *      $directory_map[$directory] is a string, and
     *      $directory is a string
     *
     * If so, we define the ___root___, which is the root directory of the
     * application
     */
    if (is_string($subdirectories) && $directory == 'root') {
        $constant = sprintf($constant_format, $directory);
        define ($constant, $subdirectories);

    /**
     * Here we check if $directory_map[$directory] is an array.
     *
     * In this case, we do another loop through the directory map and:
     *      if $directory is 'libraries', then we define ___%s___, where
     *          %s = [npm, composer, bower]
     *      if $name is 'root', we define ___%s___, where %s = parent directory,
     *          e.g.: backend
     *      else we define ___%s1_%s2___, where %s1 = parent dir and
     *          %s2 = child dir
     */
    } else if (is_array($subdirectories)) {
        foreach ($subdirectories as $name => $path) {
            if ($directory == 'libraries') {
                $constant = sprintf($constant_format, $name);
                define ($constant, $path);
            } else if ($name == 'root') {
                $constant = sprintf($constant_format, $directory);
                define($constant, $path);
            } else {
                $constant = sprintf($constant_format,
                                    sprintf('%s_%s', $directory, $name)
                );

                define($constant, $path);
            }
        }
    /**
     * If everything fails, we trigger the error.
     *
     * I cannot see another way to dump the error, so we do it this way.
     */
    } else {
        trigger_error(
            sprintf(
                'Application Error: You must provide a map of subdirectories to %s node, %s given.',
                $directory,
                gettype($directory)
            ),
            E_USER_ERROR
        );
    }
}

/**
 * This function is an enhancement of PHP's default constant() function
 *
 * It accepts only one parameter, $constant_name, as the main function.
 * Then, it calls constant() by adding the $constant_format.
 *
 * @param string $constant_name     The constant name
 * @return mixed
 */
function app_constant($constant_name)
{
    global $constant_format;

    return constant(sprintf($constant_format, $constant_name));
}

/**
 * Here, we load Composer's autoload script.
 */
require_once app_constant('composer') . '/autoload.php';
