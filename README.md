# Wavevision DI service annotation

Helper for registering nette DI services via Doctrine Annotations, factory generator and inject generator.

## Usage

Running 

```php
use Wavevision\DIServiceAnnotation\Configuration;
use Wavevision\DIServiceAnnotation\ExtractServices;

(new ExtractServices(new Configuration('sourceDirectory', 'outputFile')))->run()
```
will generate from [class](tests/DIServiceAnnotationTests/Services/Nested/ExampleService.php) following:
- [factory](tests/DIServiceAnnotationTests/expected/Services/Nested/ExampleServiceFactory.php)
- [inject](tests/DIServiceAnnotationTests/expected/Services/Nested/InjectExampleServiceFactory.php) 
- [neon config](tests/DIServiceAnnotationTests/expected/nested.neon#L5)

For configuration options see [Configuration properties](src/DIServiceAnnotation/Configuration.php#L7)
