<?php declare(strict_types=1);

namespace PrinsFrank\PHPStanDocCodeAnalyzer\FileSystem;

use FilesystemIterator;
use PrinsFrank\PHPStanDocCodeAnalyzer\Exception\InvalidArgumentException;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;

class FileHandler {
    public function createDirectory(string $directory): void {
        if (!is_dir($directory) && mkdir($directory, 0777, true) && !is_dir($directory)) {
            throw new InvalidArgumentException(sprintf('Unable to create directory "%s"', $directory));
        }
    }

    public function cleanDirectory(string $directory): void {
        if (!is_dir($directory)) {
            return;
        }

        foreach (new RecursiveIteratorIterator(new RecursiveDirectoryIterator($directory, FilesystemIterator::SKIP_DOTS), RecursiveIteratorIterator::CHILD_FIRST) as $item) {
            if ($item->isDir()) {
                rmdir($item->getPathname());
            } else {
                unlink($item->getPathname());
            }
        }

        rmdir($directory);
    }

    /**
     * @param list<string> $globPatterns
     * @return list<string>
     */
    public function matchGlobs(string $directory, array $globPatterns): array {
        $files = [];
        foreach ($globPatterns as $path) {
            $matchingFiles = glob($directory . DIRECTORY_SEPARATOR . $path);
            if ($matchingFiles === false || $matchingFiles === []) {
                throw new InvalidArgumentException(sprintf('No files found for glob pattern "%s" in directory "%s"', $path, $directory));
            }

            $files = [...$files, ...$matchingFiles];
        }

        return $files;
    }

    public function write(string $outputFilePath, string $content): void {
        if (!is_dir(dirname($outputFilePath))) {
            $this->createDirectory(dirname($outputFilePath));
        }

        file_put_contents($outputFilePath, $content);
    }
}
