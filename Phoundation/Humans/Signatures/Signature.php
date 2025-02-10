<?php

/**
 * Class Signature
 *
 * This class manages human signatures
 *
 * @author    Sven Olaf Oostenbrink <so.oostenbrink@gmail.com>
 * @license http://opensource.org/licenses/GPL-2.0 GNU Public License, Version 2
 * @copyright Copyright © 2025 Sven Olaf Oostenbrink <so.oostenbrink@gmail.com>
 * @package Plugins\Phoundation\Human
 */


declare(strict_types=1);

namespace Plugins\Phoundation\Humans\Signatures;

use Phoundation\Data\DataEntries\DataEntry;
use Phoundation\Web\Html\Components\Input\Interfaces\RenderInterface;


abstract class Signature extends DataEntry implements RenderInterface
{
    /**
     * Renders the signature HTML code
     *
     * @return string|null
     */
    abstract function render(): ?string;
}
