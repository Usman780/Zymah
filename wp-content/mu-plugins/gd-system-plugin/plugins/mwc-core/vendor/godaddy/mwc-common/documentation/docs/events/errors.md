---
id: errors
title: Errors
---

The `BaseExceptionHandler` is a basic handler to manage `Exception` and `ExceptionError` events.

While this component itself extends an `Exception` object, it is really intended to be used as an instantiable handler rather than a throwable item, which in turn can be passed to some of its methods. As such, the `BaseExceptionHandler` is responsible for handling errors and exception event reporting to a defined logger.

## Base class

Child implementations can extend the base class to provide alternative loggers and handle more event types.

### Callback

The base handler does not provide additional handling for exceptions other than [reporting](errors#report-events) them. Child implementation can override the basic empty `callback` to include additional handling when an `Exception` is to be [reported](errors#report-events).

```php
use GoDaddy\WordPress\MWC\Common\Exceptions\BaseExceptionHandler;

class MyErrorEventHandler extends BaseExceptionHandler {

    protected function callback(Throwable $exception)
    {
        // do something
    }   
}
```

### Context

The `context` internal method is intended to provide additional system configuration data when an `Exception` event is logged.

Children implementations can extend this method to provide additional data, if necessary.

```php
use GoDaddy\WordPress\MWC\Common\Exceptions\BaseExceptionHandler;

class MyErrorEventHandler extends BaseExceptionHandler {

    protected function context() : array
    {
        $context = parent::context();
        $context['myKey'] = 'myValue';
        return $context;
    }   
}
```

### Error Handling

When the `BaseExceptionHandler` is instantiated, it will automatically convert PHP errors to `ErrorException` exceptions, via `handleError` method.

### Event Handling

When the `BaseExceptionHandler` is instantiated, it will automatically handle `Exception` events to be [reported](errors#report-events), via `handleException` method.

### Ignore events

The `ignore` method can be used to define exceptions that should be ignored by the handler.

```php
use GoDaddy\WordPress\MWC\Common\Exceptions\BaseExceptionHandler;

$handler = new BaseExceptionHandler('event');
$handler->ignore('MyCustomException');

```

### Report events

The base `report` method will check if the current exception event is [ignored](#ignore-events) and if not, it will invoke a [callback](#callback) and then [log](#alternative-loggers) the event.

```php
use GoDaddy\WordPress\MWC\Common\Exceptions\BaseExceptionHandler;

$handler = new BaseExceptionHandler('event');
$handler->report(new \Exception());
```

### Alternative loggers

The `getLogger` method can be overridden by child implementations to define an alternative [logger](/components/logger) for custom handling of exception reporting.

```php
use GoDaddy\WordPress\MWC\Common\Exceptions\BaseExceptionHandler;
use GoDaddy\WordPress\MWC\Common\Loggers\Logger;

class MyErrorEventHandler extends BaseExceptionHandler {

    protected function getLogger() : Logger
    {
        return ( new class extends Logger { 
            // Logger implementation instance
        } );
    }   
}
```