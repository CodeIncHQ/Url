# Code Inc.'s URL library

A PHP 7 library to manipulates URLs. This library is compatible with [PSR7](https://www.php-fig.org/psr/psr-7/) [`UriInterface`](https://www.php-fig.org/psr/psr-7/#35-psrhttpmessageuriinterface) through the `Psr7Url` and `Psr7ServerUrl` classes.

## Install

This library is available through [Packagist](https://packagist.org/packages/codeinchq/lib-url) and can be installed using [Composer](https://getcomposer.org/): 

```bash
composer require codeinchq/lib-url
```


## Usage

### Parsing a URL:
```php
<?php
use CodeInc\Url\Url;

$url = new Url("https://www.google.com/?q=A+great+search");
if ($url->hasQueryParameter("p")) {
	echo $url->getQueryParameter("p");
}
```

### Building a URL:
```php
<?php 
use CodeInc\Url\Url;

$url = new Url();
$url->setHost("www.google.com");
$url->setScheme($url::SCHEME_HTTPS);
$url->setQueryParameter("q", "A great search");
echo $url->getUrl();
```

### Getting the current URL:
```php
<?php 
use CodeInc\Url\ServerUrl;

$url = new ServerUrl();
echo $url->getUrl();
```


## Tests

A unit test is available for the `Url` class in the `UrlTest` class. 

To run the tests using [PHPUnit](https://phpunit.de/):

```bash
./vendor/bin/phpunit tests/UrlTest.php
```
