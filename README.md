# Wavevision DI service annotation

[![Build Status](https://travis-ci.org/wavevision/di-service-annotation.svg?branch=master)](https://travis-ci.org/wavevision/di-service-annotation)
[![Coverage Status](https://coveralls.io/repos/github/wavevision/di-service-annotation/badge.svg?branch=master)](https://coveralls.io/github/wavevision/di-service-annotation?branch=master)
[![PHPStan](https://img.shields.io/badge/style-level%20max-brightgreen.svg?label=phpstan)](https://github.com/phpstan/phpstan)

Helper for registering Nette DI services via Doctrine Annotations, factory generator and inject generator.

## Install
```
composer require --dev wavevision/di-service-annotation
```

## Usage

### Annotate your service

```php
use Wavevision\DIServiceAnnotation\DIService;

/**
 * @DIService(params={"%wwwDir%"}, generateInject=true, generateFactory=true)
 */
class ExampleService
{

}
```

### Create runner script

For example `bin/extract-services.php`

```php
use Wavevision\DIServiceAnnotation\Configuration;
use Wavevision\DIServiceAnnotation\Runner;

Runner::run(new Configuration('sourceDirectory', 'services.neon'));
```

Running this script with `php bin/extract-services.php`

will generate from [class](tests/DIServiceAnnotationTests/Services/Nested/ExampleService.php) following:
- [factory](tests/DIServiceAnnotationTests/expected/Services/Nested/ExampleServiceFactory.php)
- [inject](tests/DIServiceAnnotationTests/expected/Services/Nested/InjectExampleServiceFactory.php) 
- [neon config](tests/DIServiceAnnotationTests/expected/nested.neon#L5)

### Annotation options

- `enableInject: bool` – will add `inject: on` to generated service config (default `true`)
- `generateComponent: bool` – will generate `<className>Component` trait, with factory and `createComponent` implemented
- `generateFactory: bool` – will generate `<className>Factory` interface with `create` function
- `generateInject: bool` – will generate `Inject<className>` trait with property `$<className>` and `inject<ClassName>` function implemented
- `params: string[]` – list of DI parameters to be passed to service constructor
- `tags: string[]` – list of tags to be used with the service in generated config

For configuration options see [Configuration properties](src/DIServiceAnnotation/Configuration.php#L7).
