<?php

test('dump, dd, ray, sleep')
    ->expect(['dump', 'dd', 'ray', 'sleep'])
    ->not->toBeUsed();
