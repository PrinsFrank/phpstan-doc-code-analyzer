<?php declare(strict_types=1);

namespace PrinsFrank\PHPStanDocCodeAnalyzer\Configuration;

class Configuration {
    /** @param list<string> $globPatterns */
    public function __construct(
        public readonly string $outputDir,
        public readonly bool   $prependOpenTagWhenMissing,
        public readonly bool   $setStrictTypesWhenOpenTagMissing,
        public readonly array  $globPatterns,
    ) {
    }
}