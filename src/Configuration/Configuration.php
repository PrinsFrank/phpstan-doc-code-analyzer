<?php declare(strict_types=1);

namespace PrinsFrank\PHPStanDocCodeAnalyzer\Configuration;

readonly class Configuration {
    /** @param list<string> $globPatterns */
    public function __construct(
        public string $outputDir,
        public bool   $prependOpenTagWhenMissing,
        public bool   $setStrictTypesWhenOpenTagMissing,
        public array  $globPatterns,
    ) {
    }
}
