<?php

declare(strict_types=1);

namespace Tests\Architecture;

arch('CODE_STYLE: declare(strict_types=1); is enforced within the application')
    ->expect('App')
    ->toUseStrictTypes();

arch('CODE_STYLE: no debugging functions present in the application')
    ->expect('App')
    ->not->toUse(['var_dump', 'dump']);


arch('CODE_STYLE: DTOs are immutable and have dto suffix')
    ->expect('App\Shared\DTO')
    ->toBeReadonly()
    ->toHaveSuffix('DTO');

arch('CODE_STYLE: The facades folder has only interfaces')
    ->expect('App\Shared\Facade')
    ->toBeInterfaces()
    ->toHaveSuffix('FacadeInterface');
