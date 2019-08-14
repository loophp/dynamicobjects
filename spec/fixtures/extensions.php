<?php

declare(strict_types=1);

return static function ($object) {
    $object::addDynamicMethod('barfoo', static function () {
        return 'foobar';
    });
    $object::addDynamicMethod('barbaz', static function () {
        return 'bazbar';
    });
};
