# Berry Alpine.js

[Alpine.js](https://alpinejs.dev/) extension for [berry/html](https://github.com/atomicptr/berry)

## Usage

Install via composer

```sh
$ composer req berry/alpinejs
```

```php
<?php

use Berry\Element;

use function Berry\Html\button;
use function Berry\Html\div;
use function Berry\Html\span;

function renderCounter(): Element
{
    return div()
        ->xData(['count' => 0])
        ->children(
            button()->xOnClick('count++')->text('Increment'),
            span()->xText('count'),
        );
}
```

```php
<?php

use Berry\Element;

use function Berry\Html\button;
use function Berry\Html\div;

function renderDropdown(): Element
{
    return div()
        ->xData(['open' => false])
        ->children(
            button()->xOnClick('open = ! open')->text('Toggle'),
            div()->xShow('open')->xOnClickOutside('open = false')->text('Contents...'),
        );
}
```

Most Alpine directives are available as methods, for example `x-data` becomes `xData(...)`,
`x-model` becomes `xModel(...)`, and `x-on:click` becomes `xOnClick(...)`.

### PHPStan

If you're using [phpstan](https://phpstan.org/) (you should), you will also need to install
[phpstan/extension-installer](https://packagist.org/packages/phpstan/extension-installer) so that
the extension methods are automatically detected.

## License

MIT
