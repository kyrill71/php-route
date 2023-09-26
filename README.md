# php routing system

## Installation

```bash
   composer require "kyrill/php-route" -dev
```

## Usage

On the index.php put the following code:
```php
<?php
use Kyril\PhpRoute\Router; //use this line if you want to define te routes in the index.php file
require 'vendor/autoload.php';

$route = $_SERVER['REQUEST_URI'];
$method = $_SERVER['REQUEST_METHOD'];

```
If you want to use the router without a route file, you can define use the following code in the index.php file:
```php
$router = new Router()
//add you routes here
$router->resolveRoute($route, $method);
```
#### Using a separate route file
If you want to use a separate file for routes, you must include the following code in the index.php file:
```php
require 'filepathOfTheRouteFile.php';
$router->resolveRoute($route, $method);

```
The route file should look like this:
```php
<?php
use Kyril\PhpRoute\Router;
$router = new Router()
//add you routes here
```


### Basic usage
You add a route to a controller like this:
```php
$route->addRoute('GET', '/home', [Controller::class, 'home']);
```

You can also use functions:
```php
$router->addRoute('GET','/routename', 'nameFunction')
});

public function nameFunction(){
    echo 'Hello function!';
}
```
Or anonymous functions:
```php
$router->addRoute('GET','/anonymousfunction', function () {
    echo 'Hello anonymous function!';
});
```
In these examples we use the GET method, but you can use any method you want.