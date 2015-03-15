File Downloader
===============

File downloader utility. This package is a part of the Hups Framework.  

Features
--------

-   Large file download support
-   Resumable downloads
-   Download speed limitation
-   Low memory usage
-   Costumizable headers

Installation
------------

https://packagist.org/packages/shakahl/hups-util-filedownloader

Add `shakahl/hups-util-filedownloader` as a requirement to `composer.json`:

```javascript
{
    "require": {
        "shakahl/hups-util-filedownloader": "dev-master"
    }
}
```

Update your packages with `composer update` or install with `composer install`.

You can also add the package using `composer require shakahl/hups-util-filedownloader` and later specifying the version you want (for now, `dev-master` is your best bet).

Usage example
-------------

~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
<?php  
\Hups\Util\FileDownloader::download('/path/to/large_file_to_download');
?>
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

Unit testing
------------

### Under Windows

~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
$ composer update
$ vendor/bin/phpunit​.bat
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
 

### Under Linux

~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
$ composer update
$ vendor/bin/phpunit​
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
