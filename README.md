# Code Inc.'s URL library

Simple PHP library to manipulates URLs. 

## Usage

Parsing a URL:
```php
<?php
use CodeInc\Url\Url;

$url = new Url("https://www.google.com/?q=A+great+search");
if ($url->hasQueryParameter("p")) {
	echo $url->getQueryParameter("p");
}
```

Building a URL:
```php
<?php 
use CodeInc\Url\Url;

$url = new Url();
$url->setHost("www.google.com");
$url->setScheme($url::SCHEME_HTTPS);
$url->setQueryParameter("q", "A great search");
echo $url->getUrl();
```