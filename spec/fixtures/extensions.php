<?php

return function ($object) {
    $object::addDynamicMethod('barfoo', function () {
        return 'foobar';
    });
    $object::addDynamicMethod('barbaz', function () {
        return 'bazbar';
    });
};
