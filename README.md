# php-route

The **PHP-route** is a lightweight PHP library that simplifies routing in web applications. It allows you to define routes and associate them with controllers, functions, or anonymous functions for handling HTTP requests.

## Installation

You can easily install the PHP Routing System using [Composer](https://getcomposer.org/):
```bash
   composer require "kyrill/php-route"
```

## Usage
### Basic setup
Ensure your ``composer.json file has the minimum-stability set to "dev":
```json
"minimum-stability": "dev"
```

In your index.php file, include the following code is required for the package to work:
```php
<?php
require 'vendor/autoload.php';

```
If you prefer defining your routes directly in the index.php file:
```php
$router = new Router();
//add you routes here
$router->resolveRoute();
```

#### In the file where you want to use the routing
To use the file for route definitions, include the following code in your routering file:
```php
<?php
use Kyrill\PhpRoute\Router;
$router = new Router(); // add an instance of the Router

$router->resolveRoute();// resolves all the requests

```
The resolveRoute() method will return true if a route is found and false if no route is found. You can use this to display a 404 page if no route is found:


### Basic usage
You can add routes to a controller like this:
```php
$router->addRoute('GET', '/home', [Controller::class, 'home']);
```
You can also use functions as route handlers:
```php
$router->addRoute('GET','/routename', 'nameFunction')
});

public function nameFunction(){
    echo 'Hello function!';
}
```
Additionally, anonymous functions can be used as route handlers:
```php
$router->addRoute('GET','/anonymousfunction', function () {
    echo 'Hello anonymous function!';
});
```
In these examples, we use the GET method, but you can use any HTTP method you need for your routes.

You can also use parameters in your routes, if you dont specify a expression the default is ([0-9]+):
```php
$router->addRoute('GET','/user/{id}', [Controller::class, 'home']);
```
You also can use regular expressions in your routes:
```php
$router->addRoute('GET','/user/{id:[0-9]+}', [Controller::class, 'home']);
```
Currently only class methods are supported for parameterized routes.
## license
This project is licensed under the MIT License - see the [LICENSE.md](LICENSE.md) file for details
