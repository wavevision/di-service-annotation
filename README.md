# Wavevision DI service annotation

[![Build Status](https://travis-ci.org/wavevision/di-service-annotation.svg?branch=master)](https://travis-ci.org/wavevision/di-service-annotation)
[![Coverage Status](https://coveralls.io/repos/github/wavevision/di-service-annotation/badge.svg?branch=master)](https://coveralls.io/github/wavevision/di-service-annotation?branch=master)
[![PHPStan](https://img.shields.io/badge/style-level%20max-brightgreen.svg?label=phpstan)](https://github.com/phpstan/phpstan)

Helper for registering Nette DI services via Doctrine Annotations, factory generator and inject generator.

## Usage

Running 

```php
use Wavevision\DIServiceAnnotation\Configuration;
use Wavevision\DIServiceAnnotation\ExtractServices;

(new ExtractServices(new Configuration('sourceDirectory', 'services.neon')))->run()
```
will generate from [class](tests/DIServiceAnnotationTests/Services/Nested/ExampleService.php) following:
- [factory](tests/DIServiceAnnotationTests/expected/Services/Nested/ExampleServiceFactory.php)
- [inject](tests/DIServiceAnnotationTests/expected/Services/Nested/InjectExampleServiceFactory.php) 
- [neon config](tests/DIServiceAnnotationTests/expected/nested.neon#L5)

For configuration options see [Configuration properties](src/DIServiceAnnotation/Configuration.php#L7)
