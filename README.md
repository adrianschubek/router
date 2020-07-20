# Router
[![Latest Stable Version](https://poser.pugx.org/adrianschubek/router/v)](//packagist.org/packages/adrianschubek/router)
[![License](https://poser.pugx.org/adrianschubek/router/license)](//packagist.org/packages/adrianschubek/router)

## Features
  - Easy to use
  - Supports GET, POST, PUT, PATCH, DELETE & OPTIONS verbs
  - Supports Route Parameters (Regex support)
  - Middleware
  - Route Groups
  - Reverse Routing (Generate URL from Route Name)
  - Subdirectory Routing
  - Error Routes
  - Implement Custom Resolver & Dispatcher
## Installation
```
composer require adrianschubek/router
```
## Example
```php
use adrianschubek\Routing\Route;
use adrianschubek\Routing\Router;

$r = new Router();

$r->get("/", function () {
    echo "Hello stranger!";
});

$r->get("/[a]/[b]", function ($a, $b) {
    echo $a + $b;
})->where("([0-9]+)");

$r->dispatch();
```
