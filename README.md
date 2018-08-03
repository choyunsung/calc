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
include 'vender/autoload.php';
use steven\calc;

echo calc('1111.111 + 1111.111');

```
