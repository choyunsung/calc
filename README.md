# calc
php float point library

### Installation

This library is installable via [Composer](https://getcomposer.org/).
Just define the following requirement in your `composer.json` file:

```json
{
    "require": {
        "steven/calc": "*"
    }
}
```

### Overview

The constructors of the classes are not public, you must use a factory method to obtain an instance.
Example:

```php
include '/vendor/autoload.php';

echo "+:    ".steven\calc('111111111111111.10987654321 + 111111.22',8)."<br>";
echo "/:    ".steven\calc('111111111.1111111111 / 111111111.22',8)."<br>";
echo "*:    ".steven\calc('111111.1111111111 * 1111111.22',8)."<br>";
echo "-:    ".steven\calc('111111111.1111111111 - 111111111.22',8)."<br>";

```
