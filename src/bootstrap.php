<?php

declare(strict_types=1);

use Berry\AlpineJs\BerryAlpineJs;

(function () {
    static $initialized = false;

    if ($initialized) {
        return;
    }

    BerryAlpineJs::install();

    $initialized = true;
})();
