<?php declare(strict_types=1);

namespace PrinsFrank\PHPStanDocCodeAnalyzer;

use PrinsFrank\PHPStanDocCodeAnalyzer\Exception\DocCodeAnalyzerException;
use PrinsFrank\PHPStanDocCodeAnalyzer\Exception\RuntimeException;

class DocCodeExtractor {
    public function __construct(
        private readonly Configuration $configuration,
        private readonly FileHandler   $fileHandler,
    ) {
    }

    /** @throws DocCodeAnalyzerException */
    public function extract(string $cwd): void {
        $this->fileHandler->createDirectory($this->configuration->outputDir);
        $this->fileHandler->cleanDirectory($this->configuration->outputDir);
        foreach ($this->fileHandler->matchGlobs($cwd, $this->configuration->globPatterns) as $file) {
            if (($fileContents = file_get_contents($file)) === false) {
                throw new RuntimeException(sprintf('Unable to read file "%s"', $file));
            }

            if (preg_match_all('/```php\s(?<content>([\s\S]*?))```/m', $fileContents, $matches, PREG_OFFSET_CAPTURE | PREG_SET_ORDER) === false) {
                throw new RuntimeException('An error occurred while parsing the file.');
            }

            foreach ($matches as $match) {
                $outputFilePath = sprintf('%s/%s:%d.php', $this->configuration->outputDir, ltrim(str_replace($cwd, '', $file), '/'), substr_count(substr($fileContents, 0, (int) $match['content'][1]), "\n") + 1);
                if (!str_starts_with($content = $match['content'][0], '<?php') && $this->configuration->prependOpenTagWhenMissing) {
                    $content = $this->configuration->setStrictTypesWhenOpenTagMissing
                        ? '<?php declare(strict_types=1);' . PHP_EOL . $content
                        : '<?php' . PHP_EOL . $content;
                }

                $this->fileHandler->write($outputFilePath, $content);
            }
        }
    }
}
