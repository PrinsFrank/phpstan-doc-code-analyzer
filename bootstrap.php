<?php declare(strict_types=1);

use PHPStan\DependencyInjection\Container;
use PrinsFrank\PHPStanDocCodeAnalyzer\DocCodeExtractor;

if (file_exists($installedPackagePath = dirname(__DIR__, 2) . '/autoload.php')) {
    require_once $installedPackagePath;
} else {
    require_once __DIR__ . '/vendor/autoload.php';
}

/** @var Container $container */
$container->getByType(DocCodeExtractor::class)
    ->extract(
        $container->getParameter('currentWorkingDirectory')
    );
