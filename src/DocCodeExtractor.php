<?php declare(strict_types=1);

namespace PrinsFrank\PHPStanDocCodeAnalyzer;

use PrinsFrank\PHPStanDocCodeAnalyzer\Exception\DocCodeAnalyzerException;
use PrinsFrank\PHPStanDocCodeAnalyzer\Exception\RuntimeException;

class DocCodeExtractor {
    public function __construct(
        private readonly Configuration $configuration,
        private readonly FileCollector $docFileCollector,
    ) {
    }

    /** @throws DocCodeAnalyzerException */
    public function extract(string $cwd): void {
        if (!is_dir($this->configuration->outputDir) && mkdir($this->configuration->outputDir, 0777, true) && !is_dir($this->configuration->outputDir)) {
            throw new RuntimeException(sprintf('Unable to create output directory "%s"' , $this->configuration->outputDir));
        }

        foreach ($this->docFileCollector->allInCWD($cwd) as $file) {
            $fileContents = file_get_contents($file);
            if ($fileContents === false) {
                throw new RuntimeException(sprintf('Unable to read file "%s"', $file));
            }

            if (preg_match_all('/```php\s(?<content>([\s\S]*?))```/m', $fileContents, $matches, PREG_OFFSET_CAPTURE | PREG_SET_ORDER) === false) {
                throw new RuntimeException('An error occurred while parsing the file.');
            }

            foreach ($matches as $match) {
                $outputFilePath = sprintf('%s/%s:%d', $this->configuration->outputDir, ltrim(str_replace($cwd, '', $file), '/'), substr_count(substr($fileContents, 0, (int) $match['content'][1]), "\n") + 1);
                if (!file_exists($outputDir = dirname($outputFilePath)) && mkdir($outputDir, 0777, true) && !file_exists($outputDir)) {
                    throw new RuntimeException(sprintf('Unable to create output directory "%s"', $outputDir));
                }

                $content = $match['content'][0];
                if (!str_starts_with($content, '<?php') && $this->configuration->prependOpenTagWhenMissing) {
                    $content = $this->configuration->setStrictTypesWhenOpenTagMissing
                        ? '<?php declare(strict_types=1);' . PHP_EOL . $content
                        : '<?php' . PHP_EOL . $content;
                }

                file_put_contents($outputFilePath, $content);
            }
        }
    }
}
