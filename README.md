# URL library

A PHP 7 library to manipulates URLs. This library is compatible with [PSR-7](https://www.php-fig.org/psr/psr-7/) [`UriInterface`](https://www.php-fig.org/psr/psr-7/#35-psrhttpmessageuriinterface) through the [`Psr7Url`](src/Psr7Url.php) and [`Psr7ServerUrl`] classes.

## Usage

```php
<?php
use CodeInc\Url\Url;

// parsing a URL
$url = Url::fromString("https://www.google.com/?q=A+great+search");
if (isset($url->getQueryAsArray()["p"])) {
	echo $url->getQueryAsArray()["p"];
}

// building a URL
$url = (new Url())
    ->withHost("www.google.com")
    ->withoutScheme("https")
    ->withQuery(["q", "A great search"]);
echo $url;

// getting the current URL
$currentUrl = Url::fromGlobals();

## Tests

A unit test is available for the [`Url`](src/Url.php) class in the [`UrlTest`](tests/UrlTest.php) class. 

To run the tests using [PHPUnit](https://phpunit.de/):

```bash
./vendor/bin/phpunit tests/UrlTest.php
```


## Installation
This library is available through [Packagist](https://packagist.org/packages/codeinc/url) and can be installed using [Composer](https://getcomposer.org/): 

```bash
composer require codeinc/url
```

## License

The library is published under the MIT license (see [`LICENSE`](LICENSE) file).