<?php

use Symfony\Component\Console\Application;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

$console = new Application('Metin2Universe CMS', '1.0-dev');
$console->getDefinition()
        ->addOption(new InputOption('--env', '-e', InputOption::VALUE_REQUIRED, 'The Environment name.', 'dev'))
;
$console->setDispatcher($app['dispatcher']);

$console
    ->register('cache:clear')
    ->setDescription('This command will purge the cache.')
    ->setCode(function (InputInterface $input, OutputInterface $output) use ($app) {

        $output->writeln('Clearing the cache...');
        $fs = new \Symfony\Component\Filesystem\Filesystem();

        $cache_dir = app_constant('backend_var') . '/cache';

        if ($fs->exists($cache_dir)) {
            $fs->remove($cache_dir);
            $fs->mkdir([$cache_dir, $cache_dir . '/twig']);
            $fs->chmod($cache_dir, 0777, 000, true);
        }

        $output->writeln('Cache cleared.');
    })
;

$console
    ->register('asset:install')
    ->setDescription('This command will copy or symlink the assets into the public directory.')
    ->setCode(function (InputInterface $input, OutputInterface $output) use ($app) {

        $output->writeln('Getting dependencies list...');
        $dependencies = json_decode(file_get_contents(app_constant('backend') . '/lib/dependencies.json'));
        $fs = new \Symfony\Component\Filesystem\Filesystem();

        $directories = [
            'css' => app_constant('web') . '/css',
            'js' => app_constant('web') . '/js',
        ];

        $output->writeln('Checking if assets directory exists...');
        if (!$fs->exists($directories)) {
            $output->writeln('Creating assets directory with public permissions...');
            $fs->mkdir($directories, 0777);
        }

        $output->writeln('Sweeping throught dependencies...');
        foreach ($dependencies as $dependency) {
            if (is_dir(app_constant('bower') . '/' . $dependency)) {
                $dirname = pathinfo(app_constant('bower') . '/' . $dependency, PATHINFO_BASENAME);
                $output->writeln(sprintf("\t[%-6s] %s/", 'mirror', $dirname));
                $fs->mirror(app_constant('bower') . '/' . $dependency, app_constant('web') . '/' . $dirname);
                $fs->chmod(app_constant('web') . '/' . $dirname, 0777, 0000, true);
            } else {
                $extension = pathinfo(app_constant('bower') . $dependency, PATHINFO_EXTENSION);
                $depname = pathinfo($dependency, PATHINFO_BASENAME);

                $output->writeln(sprintf("\t[%-6s] %s", 'copy', $depname));
                $fs->copy(app_constant('bower') . '/' . $dependency, $directories[$extension] . '/' . $depname, true);
                $fs->chmod($directories[$extension] . '/' . $depname, 0777);
            }
        }

        $output->writeln('Operation finished.');
    })
;

return $console;
