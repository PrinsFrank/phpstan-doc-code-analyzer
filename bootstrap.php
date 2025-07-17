<?php declare(strict_types=1);

use PHPStan\DependencyInjection\Container;
use PrinsFrank\PHPStanDocCodeAnalyzer\DocCodeExtractor;

if (! class_exists(Container::class)) {
    require __DIR__ . '/vendor/autoload.php';
}

/** @var Container $container */
$container->getByType(DocCodeExtractor::class)
    ->extract(
        $container->getParameter('currentWorkingDirectory')
    );
