<p align="center"><a href="https://github.com/wavevision"><img alt="Wavevision s.r.o." src="https://wavevision.com/images/wavevision-logo.png" width="120" /></a></p>
<h1 align="center">DIService Annotation</h1>

[![Build Status](https://travis-ci.org/wavevision/di-service-annotation.svg?branch=master)](https://travis-ci.org/wavevision/di-service-annotation)
[![Coverage Status](https://coveralls.io/repos/github/wavevision/di-service-annotation/badge.svg?branch=master)](https://coveralls.io/github/wavevision/di-service-annotation?branch=master)
[![PHPStan](https://img.shields.io/badge/style-level%20max-brightgreen.svg?label=phpstan)](https://github.com/phpstan/phpstan)

Helper for registering Nette DI services via Doctrine Annotations, factory generator and inject generator.

## Install
```
composer require --dev wavevision/di-service-annotation
```

> **Note:** Install [phpstan-nette](https://github.com/phpstan/phpstan-nette) if you need support for strict return types.

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
- `generateComponent: bool` – will generate `<className>Component` trait, with factory and `createComponent<ClassName>` implemented
- `generateFactory: bool` – will generate `<ClassName>Factory` interface with `create` function
- `generateInject: bool` – will generate `Inject<ClassName>` trait with property `$<className>` and `inject<ClassName>` function implemented
- `params: string[]` – list of DI parameters to be passed to service constructor
- `tags: string[]` – list of tags to be used with the service in generated config

For configuration options see [Configuration properties](src/DIServiceAnnotation/Configuration.php#L7).

### Configuration option

#### Required

* `sourceDirectory: string` – location of services
* `outputFile: string` – output file for registered services

#### Optional

* `setMask: string` – mask for file locator - default `*.php`
* `setFileMapping: array` – map for splitting configs by namespace

```php
$configuration->setFileMapping([
    'RootNamespace\Namespace1' => 'config1.neon',
    'RootNamespace\Namespace2' => 'config2.neon',
]);
```
* `setInjectGenerator: Inject` – set custom generator for injects
* `setFactoryGenerator: Factory` – set custom generator for factories
* `setComponentFactory: Component` – set custom generator for components
* `setRegenerate: bool` – regenerate all generated code each run - default `false`
* `enableFileValidation` – check each file for fatal errors before reading annotation, skip this file on error
    * `autoloadFile: string` – file for class autoloading - e.g. `vendor/autoload.php`
    * `tempDir: string` – enable cache, directory for cache file - only changed files are validated

