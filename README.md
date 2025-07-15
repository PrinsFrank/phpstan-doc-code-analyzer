# PHPStan Doc Code Analyzer

When you have an extensive README.md or a bunch of documentation files, it is sometimes hard to keep all documentation up to date with the actual code. Why not also analyze code blocks in those files with PHPStan?

## Getting started

```bash
composer require --dev prinsfrank/phpstan-doc-code-analyzer
```

## Configuration

### Auto prepend open tag when missing

When using PHP snippets in your documentation, you might not have all snippets start with the `<?php` open tag. So Instead of:

```
```php
<?php
```

You might start your code blocks with that open tag:

```
```php
```

In that case this extension will automatically prepend that opening tag. If you don't want that, you can disable this:

```neon
parameters:
    docCodeAnalyzer:
        prependOpenTagWhenMissing: false
```

### Set strict_types to true when adding missing opening tag

When the opening tag is not present, Besides automatically adding the tag, it will also enable strict_types. If you just want the automatic open tag without strict types, you can disable this:

```neon
parameters:
    docCodeAnalyzer:
        setStrictTypesWhenOpenTagMissing: false
```
