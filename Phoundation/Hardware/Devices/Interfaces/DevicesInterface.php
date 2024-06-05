<?php

declare(strict_types=1);

namespace Plugins\Phoundation\Hardware\Devices\Interfaces;

use Phoundation\Data\DataEntry\Interfaces\DataListInterface;


/**
 * Interface DevicesInterface
 *
 *
 *
 * @author Sven Olaf Oostenbrink <so.oostenbrink@gmail.com>
 * @license http://opensource.org/licenses/GPL-2.0 GNU Public License, Version 2
 * @copyright Copyright (c) 2024 Sven Olaf Oostenbrink <so.oostenbrink@gmail.com>
 * @package Plugins\Phoundation\Hardware
 */
interface DevicesInterface extends DataListInterface
{
    /**
     * Scans for known hardware devices and registers them in the database
     *
     * @param bool $update_options
     * @return $this
     */
    public function search(bool $update_options): static;
}
