<?php

declare(strict_types=1);

namespace Plugins\Phoundation\Hardware\Devices\Interfaces;

use Phoundation\Data\DataEntry\Interfaces\DataIteratorInterface;
use Stringable;

interface ProfilesInterface extends DataIteratorInterface
{
    public function get(float|Stringable|int|string $key, bool $exception = true): ?ProfileInterface;
}
