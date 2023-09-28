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
### Using the index.php file
In your index.php file, include the following code to set up the routing system:
```php
<?php
use Kyrill\PhpRoute\Router; //use this line if you want to define te routes in the index.php file
require 'vendor/autoload.php';


```
If you prefer defining your routes directly in the index.php file:
```php
$router = new Router();
//add you routes here
$router->resolveRoute();
```
The resolveRoute() method will return true if a route is found and false if no route is found. You can use this to display a 404 page if no route is found:

#### Using a separate route file
To use a separate file for route definitions, include the following code in your index.php file:]
```php
require 'filepathOfTheRouteFile.php';
$router->resolveRoute();

```
The route file should resemble this:
```php
<?php
use Kyrill\PhpRoute\Router;
$router = new Router();
//add you routes here
```


### Basic usage
You can add routes to a controller like this:
```php
$route->addRoute('GET', '/home', [Controller::class, 'home']);
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

## license
This project is licensed under the MIT License - see the [LICENSE.md](LICENSE.md) file for details
