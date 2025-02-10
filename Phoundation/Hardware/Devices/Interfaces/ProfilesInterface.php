<?php

declare(strict_types=1);

namespace Plugins\Phoundation\Hardware\Devices\Interfaces;

use Phoundation\Data\DataEntries\Interfaces\DataIteratorInterface;
use ReturnTypeWillChange;
use Stringable;

interface ProfilesInterface extends DataIteratorInterface
{
    #[ReturnTypeWillChange] public function get(float|Stringable|int|string $key, bool $exception = true): ?ProfileInterface;
}
