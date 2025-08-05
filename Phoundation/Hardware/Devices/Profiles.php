<?php

/**
 * Class Profiles
 *
 *
 *
 * @author    Sven Olaf Oostenbrink <so.oostenbrink@gmail.com>
 * @license   http://opensource.org/licenses/GPL-2.0 GNU Public License, Version 2
 * @copyright Copyright © 2025 Sven Olaf Oostenbrink <so.oostenbrink@gmail.com>
 * @package   Plugins\Phoundation\Hardware
 */


declare(strict_types=1);

namespace Plugins\Phoundation\Hardware\Devices;

use Phoundation\Data\DataEntries\DataIterator;
use Plugins\Phoundation\Hardware\Devices\Interfaces\ProfileInterface;
use Plugins\Phoundation\Hardware\Devices\Interfaces\ProfilesInterface;
use ReturnTypeWillChange;
use Stringable;


class Profiles extends DataIterator implements ProfilesInterface
{
    /**
     * Devices class constructor
     */
    public function __construct()
    {
        $this->keys_are_unique_column = true;
        parent::__construct();
    }


    /**
     * @inheritDoc
     */
    public static function getTable(): ?string
    {
        return 'hardware_profiles';
    }


    /**
     * @inheritDoc
     */
    public static function getDefaultContentDataType(): ?string
    {
        return Profile::class;
    }


    /**
     * @inheritDoc
     */
    public static function getUniqueColumn(): ?string
    {
        return 'name';
    }


    /**
     * Returns the specified profile
     *
     * @param float|Stringable|int|string $key
     * @param bool $exception
     * @return ProfileInterface|null
     */
    #[ReturnTypeWillChange] public function get(float|Stringable|int|string $key, bool $exception = true): ?ProfileInterface
    {
        return parent::get($key, $exception);
    }
}
