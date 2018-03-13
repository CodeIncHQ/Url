# URL library

A PHP 7 library to manipulates URLs. This library is compatible with [PSR-7](https://www.php-fig.org/psr/psr-7/) [`UriInterface`](https://www.php-fig.org/psr/psr-7/#35-psrhttpmessageuriinterface) through the [`Psr7Url`](src/Psr7Url.php) and [`Psr7ServerUrl`] classes.

## Usage

```php
<?php
use CodeInc\Url\Url;
use CodeInc\Url\ImmutableUrl;
use CodeInc\Url\UrlGlobals;

// parsing a URL
$url = new Url("https://www.google.com/?q=A+great+search");
if ($url->hasQueryParameter("p")) {
	echo $url->getQueryParameter("p");
}

// building a URL
$url = new Url();
$url->setHost("www.google.com");
$url->setScheme("https");
$url->setQueryParameter("q", "A great search");
echo $url->getUrl();

// getting the current URL
$currentUrl = Url::fromGlobals();

// getting a immuable URL
$immuableUrl = new ImmutableUrl("https://www.google.com/?q=A+great+search");
$newImmuableUrl = $immuableUrl->withHost("www.google.fr");

// getting infos about the current URL
echo UrlGlobals::getCurrentHost().":".UrlGlobals::getCurrentPort();
```


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