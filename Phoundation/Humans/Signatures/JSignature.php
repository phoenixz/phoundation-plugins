<?php

/**
 * Class JSignature
 *
 * This class manages human signatures using jSignature
 *
 * @see       https://willowsystems.github.io/jSignature/#/about/
 * @author    Sven Olaf Oostenbrink <so.oostenbrink@gmail.com>
 * @license   http://opensource.org/licenses/GPL-2.0 GNU Public License, Version 2
 * @copyright Copyright © 2025 Sven Olaf Oostenbrink <so.oostenbrink@gmail.com>
 * @package   Plugins\Phoundation\Human
 */


declare(strict_types=1);

namespace Plugins\Phoundation\Humans\Signatures;


use Phoundation\Data\Traits\TraitMethodHasRendered;


class JSignature extends Signature
{
    use TraitMethodHasRendered;


    /**
     * Renders the signature HTML code
     *
     * @return string|null
     */
    public function render(): ?string
    {

    }
}
