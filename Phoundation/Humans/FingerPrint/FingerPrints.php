<?php

/**
 * Class FingerPrint
 *
 * This class manages finger print access using the Fprint class
 *
 * @author    Sven Olaf Oostenbrink <so.oostenbrink@gmail.com>
 * @license http://opensource.org/licenses/GPL-2.0 GNU Public License, Version 2
 * @copyright Copyright © 2025 Sven Olaf Oostenbrink <so.oostenbrink@gmail.com>
 * @package Plugins\Phoundation\Humans
 */


declare(strict_types=1);

namespace Plugins\Phoundation\Humans\FingerPrint;

use Phoundation\Data\DataEntry\DataIterator;
use Plugins\Phoundation\Humans\FingerPrint\Interfaces\FingerPrintsInterface;


class FingerPrints extends DataIterator implements FingerPrintsInterface
{

    /**
     * @inheritDoc
     */
    public static function getTable(): ?string
    {
        return 'fingerprints';
    }

    /**
     * @inheritDoc
     */
    public static function getDefaultContentDataType(): ?string
    {
        return FingerPrint::class;
    }

    /**
     * @inheritDoc
     */
    public static function getUniqueColumn(): ?string
    {
        return 'users_id';
    }
}
