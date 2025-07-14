<?php declare(strict_types=1);

namespace PrinsFrank\PHPStanDocCodeAnalyzer;

use PrinsFrank\PHPStanDocCodeAnalyzer\Exception\InvalidArgumentException;

class FileCollector {
    public function __construct(
        private readonly Configuration $configuration,
    ) {
    }

    /** @return list<string> */
    public function allInCWD(string $cwd): array {
        $files = [];
        foreach ($this->configuration->globPatterns as $path) {
            $matchingFiles = glob($cwd . DIRECTORY_SEPARATOR . $path);
            if ($matchingFiles === false || $matchingFiles === []) {
                throw new InvalidArgumentException(sprintf('No files found for glob pattern "%s" in directory "%s"', $path, $cwd));
            }

            $files = [...$files, ...$matchingFiles];
        }

        return $files;
    }
}