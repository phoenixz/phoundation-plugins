<?php

declare(strict_types=1);

namespace Plugins\Phoundation\Hardware\Devices\Interfaces;

use Phoundation\Data\DataEntry\Interfaces\DataIteratorInterface;

interface DevicesInterface extends DataIteratorInterface
{
    /**
     * Scans for known hardware devices and registers them in the database
     *
     * @param bool $update_options
     * @return $this
     */
    public function search(bool $update_options): static;
}
